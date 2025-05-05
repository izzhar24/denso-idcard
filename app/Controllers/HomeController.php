<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Employee;
use App\Models\EmployeeCard;
use App\Models\RequestEmployeeCard;
use App\Models\Template;
use GuzzleHttp\Client;

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
        return view('photo');
    }

    public function chooseBackground()
    {
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
        $employee = Employee::table()->select(['id', 'npk', 'name'])->where('npk', $id)->first();
        if (!$employee) json_error('Data karyawan tidak ditemukan', 404);
        return json($employee);
    }

    // Set EmployeeID
    public function setEmployee()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $employee = $input['employee'] ?? null;

        $employeeCardExist = EmployeeCard::table()->where('employee_id', $employee['id'])->first();
        $requestEmployeeCardExist = RequestEmployeeCard::table()->where('employee_Card_id', $employeeCardExist['id'])->where('status', '!=','APPROVED')->first();

        $data = [
            "employee_id" => $employee['id'],
            "exist" => false,
        ];
        if ($employeeCardExist) json([
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
        // $this->removeBackground();
    }

    protected function removeBackground()
    {
        $photo = $_SESSION['idcard']['photo']['original'];
        $url = $_ENV['REMOVE_BG_URL'] ?? null;
        $key = $_ENV['REMOVE_BG_KEY'] ?? null;

        if (!$url || !$key) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Remove.bg API URL or key is not set']);
            return;
        }
        // Prosesnya dijadikan async
        $client = new Client();
        $promise = $client->postAsync($url, [
            'multipart' => [
                [
                    'name'     => 'image_file',
                    'contents' => fopen($photo, 'r')
                ],
                [
                    'name'     => 'size',
                    'contents' => 'auto'
                ]
            ],
            'headers' => [
                'X-Api-Key' => $key
            ]
        ]);

        $promise->then(
            function ($res) {
                $fileNamePath = 'employee/remove-bg/' . $_SESSION['idcard']['employee']['npk'] . ".png";
                $fp = fopen($fileNamePath, "wb");
                fwrite($fp, $res->getBody());
                fclose($fp);

                $_SESSION['idcard']['photo']['remove-bg'] = $fileNamePath;
                return json(["photo" => $fileNamePath]);
            },
            function ($e) {
                return json(['error' => 'Failed to process image: ' . $e->getMessage()]);
            }
        );

        $promise->wait();
    }

    // Set Background
    public function setBackground()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $image = $input['image'] ?? null;

        $_SESSION['idcard']['background'] = $image;

        header('Content-Type: application/json');
        return json(["image" => $image]);
    }

    public function printPreview()
    {
        return view('print-preview', [
            "employee" => $_SESSION['idcard']['employee'],
            "photo" => $_SESSION['idcard']['photo']['original'],
            "background" => $_SESSION['idcard']['background']
        ]);
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
