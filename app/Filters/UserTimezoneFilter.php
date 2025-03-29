<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserTimezoneFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $timezone = session()->get('user_timezone');

        if (!empty($timezone) && in_array($timezone, timezone_identifiers_list())) {
            date_default_timezone_set($timezone);
        } else {
            // Fallback to server default timezone
            date_default_timezone_set(date_default_timezone_get());
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
