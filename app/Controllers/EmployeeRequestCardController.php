<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use App\Models\RequestEmployeeCard;

class EmployeeRequestCardController extends Controller
{
    public function index()
    {
        $perPage = 5;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $requestEmployeeCards = RequestEmployeeCard::table()->limit($perPage)->offset($offset)
            ->with('employee_card.employee')
            ->get();
        var_dump($requestEmployeeCards[0]);
        // $employeeCard = $requestEmployeeCards->employee_card();
        $total = RequestEmployeeCard::table()->count();
        $totalPages = ceil($total / $perPage);
        return view('admin.request-employee-cards.index', [
            'requestEmployeeCards' => $requestEmployeeCards,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages
        ]);
    }
    public function approve($id)
    {
        RequestEmployeeCard::table()->where('id', $id)
            ->update($id, [
                'status' => 'APPROVED'
            ]);
        $_SESSION['success'] = 'Data berhasil di approve dan di cetak';
        redirect('/employee-request-cards');
    }

    public function reject($id)
    {
        RequestEmployeeCard::table()->where('id', $id)
            ->update($id, [
                'status' => 'REJECTED'
            ]);
        $_SESSION['success'] = 'Data berhasil di reject';
        redirect('/employee-request-cards');
    }
}
