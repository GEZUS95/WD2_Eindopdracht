<?php

namespace Enums;

enum Status
{
    case Closed;
    case Open;
    case Reopened;
    case Unassigned;

    public function toString()
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
}
