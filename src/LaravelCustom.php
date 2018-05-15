<?php

namespace SmallRuralDog\LaravelCustom;


use Exception;
use SmallRuralDog\LaravelCustom\Api\ExceptionReport;

class LaravelCustom
{

    public static function ExceptionReport(Exception $exception)
    {
        $reporter = ExceptionReport::make($exception);
        if ($reporter->shouldReturn()) {
            return $reporter->report();
        }else{
            return false;
        }
    }

}