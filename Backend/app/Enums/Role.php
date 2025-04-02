<?php

namespace Enums;

enum Role implements \JsonSerializable
{
    case Admin;
    case User;
    case Support_Agent;

    public static function from(mixed $value): Role
    {
        return match ($value) {
            'Admin' => self::Admin,
            'User' => self::User,
            'Support_Agent' => self::Support_Agent,
            default => throw new \InvalidArgumentException("Invalid role: $value"),
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::User => 'User',
            self::Support_Agent => 'Support_Agent',
        };
    }


    public function jsonSerialize(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::User => 'User',
            self::Support_Agent => 'Support_Agent',
        };
    }

}
