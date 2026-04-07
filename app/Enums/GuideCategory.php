<?php

namespace App\Enums;

/**
 * Категория гайда (совпадает с enum в БД).
 */
enum GuideCategory: string
{
    case Docker = 'Docker';
    case Git = 'Git';
    case Linux = 'Linux';
    case Python = 'Python';
    case JsTs = 'JS/TS';
    case DevOps = 'DevOps';
    case Database = 'Database';
    case Other = 'Other';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
