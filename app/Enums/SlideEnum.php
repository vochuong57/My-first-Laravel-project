<?php
namespace App\Enums;

enum SlideEnum: string{
    const BANNER = 'banner';
    const MAIN = 'main-slide';

    public static function toArray(): array
    {
        return [
            self::BANNER => 'banner',
            self::MAIN => 'main-slide',
        ];
    }
}