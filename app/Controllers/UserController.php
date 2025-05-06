<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
class UserController extends Controller
{
    public function index()
    {
        $users = User::table()->get();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        $data = $_POST;
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        User::table()->create($data);

        header('Location: /users');
        exit;
    }

    public function edit($id)
    {
        $user = User::table()->find($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update($id)
    {
        $data = $_POST;
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        User::table()->update($id, $data);

        header('Location: /users');
        exit;
    }

    public function destroy($id)
    {
        User::table()->delete($id);
        header('Location: /users');
        exit;
    }
}
