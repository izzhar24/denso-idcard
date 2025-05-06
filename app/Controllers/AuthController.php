<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{

    public function showLogin()
    {
        $this->view('auth.login', [], 'auth');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::table()->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email atau password salah.';
            return redirect('/login');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        $_SESSION['error'] = null;

        return redirect('/admin');
    }

    public function admin()
    {
        $this->view('admin.index', ['user' => $_SESSION['user']], 'app');
    }

    public function logout()
    {
        session_destroy();
        redirect('/login');
    }
}
