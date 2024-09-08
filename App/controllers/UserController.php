<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the login view.
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Show the registration view.
     *
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    public function store()
    {


        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];
        $state = $_POST['state'];

        $errors = [];

        // Validation

        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter valid email.';
        }

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters.';
        }


        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be min 6 characters';
        }

        if (!Validation::match($password, $password_confirmation)) {
            $errors['password_conformation'] = 'Passwords do not match';
        }


        if (!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        }

        // Check if email already exists

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'Email already exists';

            loadView('users/create', [
                'errors' => $errors
            ]);
            exit;
        }

        // Insert user into database

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        // get new user id

        $userId = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
        ]);

        redirect('/');

        exit;

    }


    public function logout()
    {
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);
        redirect('/');
    }

    public function authenticate() {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        if(!Validation::email($email)) {
            $errors['email'] = "Please enter valid email";
        }
        

        if(!Validation::string($password, 6, 50)) {
            $errors['password'] = "Password must be at least 6 chars";
        }

        if(!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);

            exit;
        }

        // Check if email exists

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if(!$user) {
            $errors['email'] = "Invalid credentials";

            loadView('users/login', [
                'errors' => $errors
            ]);

            exit;
        }

        // check if password is correct

        if(!password_verify($password, $user->password)) {
            $errors['password'] = "Invalid credentials";
        }

        if(!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);

            exit;
        }

        // Login user

        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
        ]);
        
        redirect('/');
        

    }
}