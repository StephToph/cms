<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionExpireFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Initialize the session
        $session = session();

        // Check if the user is logged in (modify as per your logic)
        if (!$session->has('isLoggedIn')) {
            // User is not logged in, redirect to the login page
            return redirect()->to('/auth/login');
        }

        // Check if the session has expired based on the 'last_activity'
        $sessionExpiration = config('App')->sessionExpiration; // Set this in Config/App.php or use a default value

        // Check if the session has expired
        if (time() - $session->get('last_activity') > $sessionExpiration) {
            // Destroy the session
            $session->destroy();

            // Redirect to the login page with a flash message about session expiration
            return redirect()->to('/auth/logout')->with('error', 'Your session has expired. Please log in again.');
        }

        // Update last activity timestamp to prevent session from expiring during active usage
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No after-action needed for this filter
    }
}
