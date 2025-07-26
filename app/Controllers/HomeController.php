<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use App\Models\EmployeeCard;
use App\Models\RequestEmployeeCard;
use App\Models\Template;

class HomeController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['idcard'])) {
            $_SESSION['idcard'] = [];
        }
    }

    public function index()
    {
        return $this->view('home');
    }

    public function card()
    {
        return $this->view('card');
    }


    public function photo()
    {
        if(!$_SESSION['idcard']['employee']){
            $_SESSION['error'] = "Anda belum scan id card anda";
            return redirect('/card');
        }
        return view('photo');
    }

    public function chooseBackground()
    {
        if(!$_SESSION['idcard']['photo']){
            $_SESSION['error'] = "Anda belum melakukan foto selfie";
            return redirect('/photo');
        }
        $templates = Template::table()->get();
        return view('choose-background', [
            "templates" => $templates
        ]);
    }


    public function getCard()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) json(['error' => 'ID tidak ditemukan.']);

        // Logika untuk mengambil data berdasarkan ID
        $cardData = $this->fetchCardData($id);

        // Kembalikan data card dalam format JSON
        echo json_encode($cardData);
    }

    private function fetchCardData($id)
    {
        $employee = Employee::table()->select(['id', 'npk', 'name','nickname'])
        ->whereLike('npk', $id)->first();
        if (!$employee) json_error('Data karyawan tidak ditemukan', 404);
        return json($employee);
    }

    // Set EmployeeID
    public function setEmployee()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $employee = $input['employee'] ?? null;

        $employeeCardExist = EmployeeCard::table()->where('employee_id', $employee['id'])->first();
        if($employeeCardExist) $requestEmployeeCardExist = RequestEmployeeCard::table()->where('employee_card_id', $employeeCardExist['id'])->where('status', '!=','APPROVED')->first();

        $data = [
            "employee_id" => $employee['id'],
        ];
        if (!empty($employeeCardExist)) json([
            ...$data,
            "exist" => true,
            "message" => "Anda sudah print ID Card",
            "status_request" => $requestEmployeeCardExist ? true: false
        ]);
        $_SESSION['idcard']['employee'] = $employee;

        return json([
            ...$data,
            "message" => "Set Employee Successfully"
        ]);
    }

    protected function saveImage($base64Image)
    {
        list($type, $data) = explode(';', $base64Image);
        list(, $data) = explode(',', $data);

        $imageData = base64_decode($data);

        // Tentukan ekstensi file dari tipe MIME
        $ext = explode('/', $type)[1];

        // Simpan file
        $dir = 'employee/original/';
        if (!file_exists($dir)) mkdir($dir, 0777, true);

        $fileName = $dir . $_SESSION['idcard']['employee']['npk'] . '.' . $ext;
        file_put_contents($fileName, $imageData);
        $_SESSION['idcard']['photo']['original'] = $fileName;
    }

    // Set Photo
    public function setPhoto()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $base64Image = $input['photo'] ?? null;
        $this->saveImage($base64Image);
        return json([
            "message" => "Set Photo Successfully"
        ]);
    }


    // Set Background
    public function setBackground()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $imageId = $input['selectedImageId'] ?? null;

        $_SESSION['idcard']['background'] = $imageId;

        return json(["imageId" => $imageId]);
    }

    public function printPreview()
    {
        if(!$_SESSION['idcard']['background']){
            $_SESSION['error'] = "Anda belum memilih background";
            return redirect('/choose-background');
        }

        $background = $_SESSION['idcard']['background'];
        $template = Template::table()->where('id', $background)->first();
        // var_dump($template['image_path']); die;
        return view('print-preview', [
            "employee" => $_SESSION['idcard']['employee'],
            "photo" => $_SESSION['idcard']['photo']['original'],
            "background" => $template['image_path']
        ]);
    }

    
    // Store Id Card
    public function storeIdcard()
    {
        $employeeId = $_SESSION['idcard']['employee']['id'];
        $photo = $_SESSION['idcard']['photo']['original'];
        $background = $_SESSION['idcard']['background'];

        $employeeCard = EmployeeCard::table()->where('employee_id', $employeeId)->first();
        if (!$employeeCard) {
            EmployeeCard::table()->create([
                "employee_id" => (int)$employeeId,
                "selected_photo_path" => $photo,
                "template_id" => (int)$background
            ]);
            // clear session idcard
            unset($_SESSION['idcard']);
            $_SESSION['success'] = "ID Card Berhasil Di Cetak";
            return json(["message" => "Id Card has been created"]);
        }
        return json(["message" => "Id Card is already exists"]);
    }
    // Request REPrint Id Card
    public function requestPrintIdcard()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $employeeId = $input['id'] ?? null;
        $reason = $input['reason'] ?? null;

        $employeeCard = EmployeeCard::table()->where('employee_id', $employeeId)->first();
        RequestEmployeeCard::table()->create([
            "employee_card_id" => $employeeCard['id'],
            "reason" => $reason
        ]);
        return json(["message" => "Send request print successfully"]);
    }
}
