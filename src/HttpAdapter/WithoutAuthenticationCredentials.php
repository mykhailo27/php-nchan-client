<?php

declare(strict_types=1);

namespace Marein\Nchan\HttpAdapter;

use Marein\Nchan\Http\Request;

class WithoutAuthenticationCredentials implements Credentials
{
    public function authenticate(Request $request): Request
    {
        return $request;
    }
}
