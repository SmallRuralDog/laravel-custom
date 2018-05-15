<?php

namespace SmallRuralDog\LaravelCustom;


use Exception;
use SmallRuralDog\LaravelCustom\Api\ExceptionReport;

class LaravelCustom
{
    public function printRunning()
    {
        echo 'running';
    }


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