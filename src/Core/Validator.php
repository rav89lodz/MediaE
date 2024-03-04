<?php

namespace MediaExpert\Backend\Core;

class Validator
{
    public static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function greaterThan(int $value, int $greaterThan): bool
    {
        return $value > $greaterThan;
    }

    public static function isInt($value) : bool
    {
        return is_numeric($value);
    }

    public static function getValue($value)
    {
        return isset($value) ? $value : null;
    }

    public static function validateId($value)
    {
        if(self::greaterThan($value, 0) == false || self::isInt($value) == false)
        {
            return false;
        }
        return true;
    }

    public static function isValidDate($value)
    {
        if(isset($value))
        {
            if(!is_string($value))
            {
                return false;
            }
            if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value))
            {
                return false;
            }
            $date = explode('-', $value);
            if(count($date) != 3)
            {
                return false;
            }
            if(!checkdate($date[1], $date[2], $date[0]))
            {
                return false;
            }
        }
        return true;
    }
}