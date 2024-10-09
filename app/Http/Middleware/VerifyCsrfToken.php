<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/5917377959:AAHiHqxvdY8vFufMNm0auq3GLvEWF8BPGDU/webhook',
        'api/website-monitoring/dashboard/store-multiple-monitor',
    ];
}
