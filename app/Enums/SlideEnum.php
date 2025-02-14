<?php
namespace App\Enums;

enum SlideEnum: string{
    const BANNER = 'banner';
    const MAIN = 'main-slide';
    const BANNER_2 = 'banner-2';
    const BANNER_3 = 'banner-3';

    public static function toArray(): array
    {
        return [
            self::BANNER => 'banner',
            self::MAIN => 'main-slide',
            self::BANNER_2 => 'banner-2',
            self::BANNER_3 => 'banner-3',
        ];
    }
}