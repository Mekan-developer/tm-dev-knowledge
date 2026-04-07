<?php

namespace App\Enums;

/**
 * Роль пользователя: администратор или контрибьютор.
 */
enum UserRole: string
{
    case Admin = 'admin';
    case Contributor = 'contributor';
}
