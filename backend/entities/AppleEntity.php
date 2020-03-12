<?php
declare(strict_types=1);

namespace backend\entities;

use backend\exceptions\AppleException;
use backend\interfaces\AppleInterface;
use backend\models\AppleModel;

class AppleEntity implements AppleInterface
{
    private AppleModel $model;

    private bool $deleted = false;

    private static bool $factoryInProgress = false;

    public function __construct(AppleModel $model)
    {
        if (!self::$factoryInProgress) {
            throw new AppleException("Создавать AppleEntity можно только через ::create().");
        }
        $this->model = $model;
    }

    public static function create(string $color, ?\DateTimeImmutable $createdAt): AppleInterface
    {

        if (!in_array($color, [
            self::COLOR_GREEN,
            self::COLOR_YELLOW,
            self::COLOR_RED,
        ])) {
            throw new AppleException("Невозможно создать AppleEntity цвета `{$color}`.");
        }

        $model             = new AppleModel();
        $model->color      = $color;
        $model->state      = self::STATE_ON_TREE;
        $model->size       = 1;
        $model->created_at = $createdAt ? $createdAt->getTimestamp() : time();

        self::$factoryInProgress = true;
        $result                  = new AppleEntity($model);
        self::$factoryInProgress = false;

        return $result;
    }

    /**
     * @return \backend\interfaces\AppleInterface[]|null
     */
    public static function findAll(): ?array
    {
        $result = null;
        $items  = AppleModel::find()->all();
        if ($items) {
            $result = [];
            foreach ($items as $item) {
                self::$factoryInProgress = true;
                $result[]                = (new AppleEntity($item))->checkForRotten();
                self::$factoryInProgress = false;
            }
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return \backend\interfaces\AppleInterface|null
     */
    public static function findById(int $id): ?AppleInterface
    {
        $result = null;
        $item   = AppleModel::findOne(['id' => $id]);
        if ($item) {
            self::$factoryInProgress = true;
            $result                  = (new AppleEntity($item))->checkForRotten();
            self::$factoryInProgress = false;
        }

        return $result;
    }

    /**
     * Проверка на загнивание
     *
     * @param \backend\entities\AppleEntity $entity
     *
     * @throws \backend\exceptions\AppleException
     */
    public function checkForRotten(): self
    {
        if ($this->isOnGround() && !$this->isRotten()) {
            $diff = (new \DateTimeImmutable())->diff($this->fallAt());
            if (
                $diff->y || $diff->m || $diff->d
                || ($diff->h && 5 <= $diff->h)
            ) {
                // Прошло больше 5-ти часов
                $this->markAsRotten()->save();
            }
        }

        return $this;
    }

    public function save(): void
    {
        if (!$this->isDeleted()) {
            $this->model->save();
        }
    }

    public function delete(): void
    {
        $this->model->delete();
        $this->deleted = true;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function getId(): int
    {
        return $this->model->id;
    }

    public function getColor(): string
    {
        return $this->model->color;
    }

    public function eat(int $percent): self
    {
        if (!$this->isOnGround()) {
            throw new AppleException(
                "Невозможно съесть когда не на земле."
            );
        }
        if (0 >= $percent || 100 < $percent) {
            throw new AppleException(
                "Неверно задан процент {$percent}."
            );
        }
        $float = $percent / 100;
        if ($this->model->size < $float) {
            throw new AppleException(
                "Остаток({$this->model->size}) меньше {$float}."
            );
        }
        $this->model->size -= $float;

        if ($this->model->size <= 0) {
            $this->model->delete();
            $this->deleted = true;
        }

        return $this;
    }

    public function getSize(): float
    {
        return $this->model->size;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(date('Y-m-d H:i:s', $this->model->created_at));
    }

    public function fallAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(date('Y-m-d H:i:s', $this->model->fall_at));
    }

    public function isOnTree(): bool
    {
        return self::STATE_ON_TREE === $this->model->state;
    }

    public function fallOnGround(?\DateTimeImmutable $fallAt): self
    {
        if (!$this->isOnTree()) {
            throw new AppleException(
                "Невозможен переход из `{$this->model->state}` в `".self::STATE_ON_GROUND."`."
            );
        }
        $this->model->fall_at =
            $fallAt
                ? $fallAt->getTimestamp()
                : time();
        $this->model->state   = self::STATE_ON_GROUND;

        return $this;
    }

    public function isOnGround(): bool
    {
        return self::STATE_ON_GROUND === $this->model->state;
    }

    public function markAsRotten(): self
    {
        if (!$this->isOnGround()) {
            throw new AppleException(
                "Невозможен переход из `{$this->model->state}` в `".self::STATE_ROTTEN."`."
            );
        }
        $this->model->state = self::STATE_ROTTEN;

        return $this;
    }

    public function isRotten(): bool
    {
        return self::STATE_ROTTEN === $this->model->state;
    }

}
