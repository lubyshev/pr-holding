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

    /**
     * Фабрика для создания яблок.
     *
     * @param string                  $color
     * @param \DateTimeImmutable|null $createdAt
     *
     * @return \backend\interfaces\AppleInterface
     * @throws \backend\exceptions\AppleException
     */
    public static function create(string $color, ?\DateTimeImmutable $createdAt = null): AppleInterface
    {
        if (!in_array($color, [
            self::COLOR_GREEN,
            self::COLOR_YELLOW,
            self::COLOR_RED,
        ])) {
            throw new AppleException("Невозможно создать AppleEntity цвета `{$color}`.");
        }
        if ($createdAt && $createdAt > new \DateTimeImmutable()) {
            throw new AppleException("Дата создания не может быть больше текущей.");
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
     * @throws \backend\exceptions\AppleException
     */
    public function checkForRotten(): self
    {
        if ($this->isOnGround()) {
            $diff = (new \DateTimeImmutable())->diff($this->fallAt());
            if (
                $diff->y || $diff->m || $diff->d
                || 5 <= $diff->h
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
        return (float)$this->model->size;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(date('Y-m-d H:i:s', $this->model->created_at));
    }

    public function fallAt(): ?\DateTimeImmutable
    {
        return
            $this->model->fall_at
                ? new \DateTimeImmutable(date('Y-m-d H:i:s', $this->model->fall_at))
                : null;
    }

    public function isOnTree(): bool
    {
        return self::STATE_ON_TREE === $this->model->state;
    }

    public function fallOnGround(?\DateTimeImmutable $fallAt = null): self
    {
        if (!$this->isOnTree()) {
            throw new AppleException(
                "Невозможен переход из `{$this->model->state}` в `".self::STATE_ON_GROUND."`."
            );
        }
        if ($fallAt) {
            if ($fallAt <= $this->createdAt()) {
                throw new AppleException(
                    "Время падения не может быть меньше времени создания."
                );
            }
            $fallTime = $fallAt->getTimestamp();
        } else {
            $fallTime = time();
        }
        $this->model->fall_at = $fallTime;
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
