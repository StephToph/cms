<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionTimeoutFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Set the timeout duration in seconds (e.g., 600 seconds for 10 minutes)
        $timeout = 7200; 

        // Check if the session variable 'lastActivity' is set
        if ($session->has('lastActivity')) {
            // Calculate the time difference
            $lastActivity = $session->get('lastActivity');
            $timeDifference = time() - $lastActivity;

            // If time difference is greater than the timeout, destroy the session
            if ($timeDifference > $timeout) {
                $session->destroy();
                return redirect()->to('/auth/login')->with('message', 'Session expired due to inactivity.');
            }
        }

        // Update the 'lastActivity' time
        $session->set('lastActivity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request in this case
    }
}
