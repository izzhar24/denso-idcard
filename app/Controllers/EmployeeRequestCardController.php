<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use App\Models\EmployeeCard;
use App\Models\RequestEmployeeCard;
use App\Models\Template;

class EmployeeRequestCardController extends Controller
{
    public function index()
    {
        $perPage = 5;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $requestEmployeeCards = RequestEmployeeCard::table()->limit($perPage)->offset($offset)
            ->get();

        foreach ($requestEmployeeCards as $key => $requestEmployeeCard) {
            $employeeCard = EmployeeCard::table()
                ->where('id', $requestEmployeeCard['employee_card_id'])
                ->first();
            $requestEmployeeCards[$key]['employee'] = Employee::table()->where('id', $employeeCard['employee_id'])->first();
            $requestEmployeeCards[$key]['template'] = Template::table()->where('id', $employeeCard['template_id'])->first();
            $requestEmployeeCards[$key]['employee_card']  = $employeeCard;
        }
        $total = RequestEmployeeCard::table()->count();
        $totalPages = ceil($total / $perPage);
        return view('admin.request-employee-cards.index', [
            'requestEmployeeCards' => $requestEmployeeCards,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages
        ]);
    }
    public function approve()
    {
        $id = $_POST['id'] ?? null;
        $data = RequestEmployeeCard::table()
            ->update($id, [
                'admin_id' => $_SESSION['user']['id'],
                'status' => 'APPROVED'
            ]);
        return json(['message' => 'Data berhasil di approve dan di cetak', "data"=> $data]);
    }

    public function reject($id)
    {
        RequestEmployeeCard::table()
            ->update($id, [
                'admin_id' => $_SESSION['user']['id'],
                'status' => 'REJECTED'
            ]);
        $_SESSION['success'] = 'Data berhasil di reject';
        redirect('/employee-request-cards');
    }
}
