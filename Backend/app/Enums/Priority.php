<?php

namespace Enums;

use JsonSerializable;

enum Priority implements JsonSerializable
{
    case Low;
    case Medium;
    case High;
    case Urgent;

    public static function from(mixed $priority): Priority
    {
        return match ($priority) {
            'Low' => self::Low,
            'Medium' => self::Medium,
            'High' => self::High,
            'Urgent' => self::Urgent,
            default => throw new \InvalidArgumentException("Invalid priority value: $priority"),
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Urgent => 'Urgent',
        };

    }

    public function jsonSerialize(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Urgent => 'Urgent',
        };
    }
}
