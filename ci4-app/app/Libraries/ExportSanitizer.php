<?php
namespace App\Libraries;
class ExportSanitizer
{
    public static function cell(mixed $value): mixed
    {
        if(!is_string($value))return $value;
        return preg_match('/^[=+\-@\t\r]/',$value)===1?"'".$value:$value;
    }
    public static function row(array $row): array{return array_map([self::class,'cell'],$row);}
}
