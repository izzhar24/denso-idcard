<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    public function card()
    {
        return $this->view('card');
    }

    public function getCard()
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            // Jika ID tidak ditemukan, kembalikan response error
            echo json_encode(['error' => 'ID tidak ditemukan.']);
            return;
        }

        // Logika untuk mengambil data berdasarkan ID
        $cardData = $this->fetchCardData($id);

        // Kembalikan data card dalam format JSON
        echo json_encode($cardData);
    }

    private function fetchCardData($id)
    {
        // Misalnya, panggil model untuk mengambil data kartu dari database
        // Di sini, hanya contoh data statis
        return [
            'id' => $id,
            'nama' => 'John Doe',
            'alamat' => 'Jl. Raya No. 123',
            'foto' => 'path/to/photo.jpg'
        ];
    }

    public function photo() 
    {
        return view('photo');    
    }

    public function chooseBackground() 
    {
        return view('choose-background');    
    }
    public function printPreview() 
    {
        return view('print-preview');    
    }
}