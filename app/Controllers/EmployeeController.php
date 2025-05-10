<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $perPage = 5;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $employees = Employee::table()->limit($perPage)->offset($offset)->get();
        $total = Employee::table()->count();
        $totalPages = ceil($total / $perPage);
        return view('admin.employees.index', [
            'employees' => $employees,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        return view('admin.employees.form');
    }

    public function store()
    {
        $npk = $_POST['npk'];
        $name = $_POST['name'];
        $nickname = $_POST['nickname'];
        $company = $_POST['company'];
        $plant = $_POST['plant'];
        $kd_bu = $_POST['kd_bu'];
        $nm_bu = $_POST['nm_bu'];
        $status_karyawan = $_POST['status_karyawan'];

        if (empty($npk) || empty($name) || empty($nickname) || empty($company) || empty($plant) || empty($kd_bu) || empty($nm_bu) || empty($status_karyawan)) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            redirect('/employees/create');
        }

        if (strlen($nickname) > 17) {
            $_SESSION['error'] = 'Nickname hanya boleh 17 karakter';
            redirect('/employees/create');
        }

        if (Employee::exists('npk', $npk)) {
            $_SESSION['error'] = 'NPK sudah digunakan';
            return redirect('/employees/create');
        }


        Employee::table()->create([
            'name' => $name,
            'nickname' => $nickname,
            'npk' => $npk,
            'company' => $company,
            'plant' => $plant,
            'kd_bu' => $kd_bu,
            'nm_bu' => $nm_bu,
            'status_karyawan' => $status_karyawan
        ]);

        $_SESSION['success'] = 'Data berhasil disimpan';
        return redirect('/employees');
    }

    public function edit($id)
    {
        $employee = Employee::table()->find($id);
        view('admin.employees.form', compact('employee'));
    }

    public function update($id)
    {
        $npk = $_POST['npk'];
        $nickname = $_POST['nickname'];
        $name = $_POST['name'];
        $company = $_POST['company'];
        $plant = $_POST['plant'];
        $kd_bu = $_POST['kd_bu'];
        $nm_bu = $_POST['nm_bu'];
        $status_karyawan = $_POST['status_karyawan'];

        if (empty($npk) || empty($name) || empty($nickname) || empty($company) || empty($plant) || empty($kd_bu) || empty($nm_bu) || empty($status_karyawan)) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            return redirect('/employees/' . $id . '/edit');
        }

        if (strlen($nickname) > 17) {
            $_SESSION['error'] = 'Nickname hanya boleh 17 karakter';
            return redirect('/employees/' . $id . '/edit');
        }

        if (Employee::table()->where('id', '!=', $id)->where('npk', $npk)->first()) {
            $_SESSION['error'] = 'NPK sudah digunakan';
            return redirect('/employees/' . $id . '/edit');
        }

        Employee::table()->update($id, [
            'name' => $name,
            'nickname' => $nickname,
            'npk' => $npk,
            'company' => $company,
            'plant' => $plant,
            'kd_bu' => $kd_bu,
            'nm_bu' => $nm_bu,
            'status_karyawan' => $status_karyawan
        ]);

        $_SESSION['success'] = 'Data berhasil diubah';
        return redirect('/employees');
    }

    public function destroy($id)
    {
        Employee::table()->delete($id);
        $_SESSION['success'] = 'Data berhasil dihapus';
        redirect('/users');
    }
}
