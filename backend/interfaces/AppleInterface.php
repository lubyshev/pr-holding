<?php
declare(strict_types=1);

namespace backend\interfaces;

use backend\models\AppleModel;

/**
 * Интерфейс яблока.
 *
 * @package backend\interfaces
 */
interface AppleInterface
{
    public const COLOR_GREEN  = 'green';
    public const COLOR_RED    = 'red';
    public const COLOR_YELLOW = 'yellow';

    public const STATE_ON_TREE   = 'on_tree';
    public const STATE_ON_GROUND = 'on_ground';
    public const STATE_ROTTEN    = 'rotten';

    public static function create(string $color, ?\DateTimeImmutable $createdAt): AppleInterface;

    public static function findAll(): ?array;

    public static function findById(int $id): ?AppleInterface;

    public function save(): void;

    public function delete(): void;

    public function isDeleted(): bool;

    public function getId(): int;

    public function getColor(): string;

    public function eat(int $percent): self;

    public function getSize(): float;

    public function createdAt(): \DateTimeImmutable;

    public function setFallAt(\DateTimeImmutable $value): self;

    public function fallAt(): \DateTimeImmutable;

    public function isOnOnTree(): bool;

    public function fallOnGround(): self;

    public function isOnOnGround(): bool;

    public function markAsRotten(): self;

    public function isRotten(): bool;

    public function checkForRotten(): self;

}
