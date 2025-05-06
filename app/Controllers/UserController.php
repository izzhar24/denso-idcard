<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $users = User::table()->limit($perPage)->offset($offset)->get();
        $total = User::table()->count();
        $totalPages = ceil($total / $perPage);
        return view('admin.users.index', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$name || !$email || !$role || !$password) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            return redirect('/users/create');
        }

        if (User::exists('email', $email)) {
            $_SESSION['error'] = 'Email sudah digunakan';
            return redirect('/users/create');
        }

        User::table()->create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $_SESSION['success'] = 'Data berhasil disimpan';
        return redirect('/users');
    }

    public function edit($id)
    {
        $user = User::table()->find($id);
        view('admin.users.form', compact('user'));
    }

    public function update($id)
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';

        if (!$name || !$email || !$role) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            return redirect("/users/$id/edit");
        }

        User::table()->where('id', $id)
        ->update($id,[
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);
        $_SESSION['success'] = 'Data berhasil diubah';
        return redirect('/users');
    }

    public function destroy($id)
    {
        User::table()->delete($id);
        $_SESSION['success'] = 'Data berhasil dihapus';
        redirect('/users');
    }
}
