<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->view('login');
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = (new User())->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            $this->redirect('/dashboard');
        } else {
            $this->view('login', ['error' => 'Invalid username or password']);
        }
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $this->view('dashboard', ['user' => $_SESSION['user']]);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        $this->redirect('/login');
    }
}
