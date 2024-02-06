<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'success',
        'failed',
        'payment/*',
        'admin/sort-payment-methods',
        '*branch-employee-list',
        '*get-role-user-info',
        '*get-parcel-unit-service',
        '*get-parcel-unit-service',
        '*get-parcel-type-unit',
        '*get-package-variant',
		'*captcha*'
    ];
}
