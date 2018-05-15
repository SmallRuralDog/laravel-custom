<?php
return [
    //允许跨域域名
    'Allow-Origin' => [

    ],
    'do-report' => [
        \SmallRuralDog\LaravelCustom\Exceptions\ApiExceptions::class => [false, 400, 'error'],
        \Illuminate\Auth\AuthenticationException::class => ['用户未授权', 400, 'no-login']
    ]
];