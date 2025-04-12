<?php

namespace App\Enums;

enum StatusOrderEnum: string
{
    case REQUESTED = 'REQUESTED';
    case APPROVED  = 'APPROVED';
    case CANCELED  = 'CANCELED';

    public static function label(self $value): string {
        return match ($value) {
            StatusOrderEnum::REQUESTED => 'Solicitado',
            StatusOrderEnum::APPROVED => 'Aprovado',
            StatusOrderEnum::CANCELED => 'Cancelado',
        };
    }
}
