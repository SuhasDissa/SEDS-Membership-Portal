<?php

namespace App\Enums;

enum UserRole: int
{
    case MEMBER = 100;
    case BOARD_MEMBER = 200;
    case ADMIN = 300;

    /**
     * Get the human-readable label for the role
     */
    public function label(): string
    {
        return match($this) {
            self::MEMBER => 'Member',
            self::BOARD_MEMBER => 'Board Member',
            self::ADMIN => 'Admin',
        };
    }

    /**
     * Get UserRole from integer value
     */
    public static function fromValue(int $value): ?self
    {
        return match($value) {
            100 => self::MEMBER,
            200 => self::BOARD_MEMBER,
            300 => self::ADMIN,
            default => null,
        };
    }

    /**
     * Get all roles as an array
     */
    public static function all(): array
    {
        return [
            self::MEMBER,
            self::BOARD_MEMBER,
            self::ADMIN,
        ];
    }

    /**
     * Get all roles with their labels
     */
    public static function options(): array
    {
        return [
            self::MEMBER->value => self::MEMBER->label(),
            self::BOARD_MEMBER->value => self::BOARD_MEMBER->label(),
            self::ADMIN->value => self::ADMIN->label(),
        ];
    }
}
