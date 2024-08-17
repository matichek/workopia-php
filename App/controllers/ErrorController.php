<?php

namespace App\Controllers;


class ErrorController
{

    /**
     * 404 error page
     * @return void
     */

    public function notFound($message = 'Page not found')
    {

        http_response_code(404);

        
        loadView('error', [
            'status' => 404,
            'message' => 'Page not found'
        ]);
    }

    /**
     * 500 error page
     * @return void
     */

    public function serverError($message = 'Server error')
    {

        http_response_code(500);

        
        loadView('error', [
            'status' => 500,
            'message' => 'Server error'
        ]);
    }

    /**
     * 403 unauthorized page
     * @return void
     */

    public function unauthorized($message = 'Unauthorized')
    {

        http_response_code(403);

        
        loadView('error', [
            'status' => 403,
            'message' => $message
        ]);
    }

}