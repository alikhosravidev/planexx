<?php

declare(strict_types=1);

namespace App\Core\BPMS\Enums;

enum TaskAction: string
{
    case EDIT     = 'edit';
    case ADD_NOTE = 'add_note';
    case FORWARD  = 'forward';

    public function isEdit(): bool
    {
        return $this === self::EDIT;
    }

    public function isAddNote(): bool
    {
        return $this === self::ADD_NOTE;
    }

    public function isForward(): bool
    {
        return $this === self::FORWARD;
    }

    public static function fromString(string $action): self
    {
        return match ($action) {
            'edit'     => self::EDIT,
            'add_note' => self::ADD_NOTE,
            'forward'  => self::FORWARD,
            default    => self::EDIT,
        };
    }

    public function getSuccessMessage(): string
    {
        return match ($this) {
            self::EDIT     => 'کار مورد نظر بروزرسانی شد.',
            self::ADD_NOTE => 'یادداشت با موفقیت ثبت شد.',
            self::FORWARD  => 'کار با موفقیت ارجاع شد.',
        };
    }
}
