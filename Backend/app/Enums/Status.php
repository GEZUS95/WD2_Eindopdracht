<?php

namespace Enums;

enum Status implements \JsonSerializable
{
    case Closed;
    case Open;
    case Reopened;
    case Unassigned;

    public function toString(): string
    {
        return match ($this) {
            Status::Closed => "Closed",
            Status::Open => "Open",
            Status::Reopened => "Reopened",
            Status::Unassigned => "Unassigned"
        };
    }

    public static function from($value): Status
    {
        return match ($value) {
            'Closed' => self::Closed,
            'Open' => self::Open,
            'Reopened' => self::Reopened,
            'Unassigned' => self::Unassigned,
            default => throw new \InvalidArgumentException("Invalid status: $value"),
        };
    }

    public function jsonSerialize(): string
    {
        return match ($this) {
            self::Closed => "Closed",
            self::Open => "Open",
            self::Reopened => "Reopened",
            self::Unassigned => "Unassigned"
        };
    }
}
