<?php

namespace App\Enum;

/**
 * Cette classe permet de définir des constantes de rôles utilisateurs
 * qui seront affichés dans le crud controller User d'EasyAdmin.
 */
enum UserRoleEnum
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public static function getValues(): array
    {
        return [
            'User' => self::ROLE_USER,
            'Admin' => self::ROLE_ADMIN,
        ];
    }
}
