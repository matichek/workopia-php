<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize {


    // check if user is authenticated

    public function isAuthenticated() {
        return Session::get('user') ? true : false;
    }
 

    public function handle($role) {
      
        if($role === 'guest' && $this->isAuthenticated()) {
            redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticated()) {
            redirect('/auth/login');
        } 

    }




}


