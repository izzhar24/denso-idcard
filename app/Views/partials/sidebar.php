<?php

$guestMenu = [
    [
        "title" => "Halaman Utama",
        "icon" => "bx bx-home",
        "url" => "/"
    ],
    [
        "title" => "Baca Kartu",
        "icon" => "bx bx-server",
        "url" => "/card"
    ],
    [
        "title" => "Photo / Selfie",
        "icon" => "bx bx-user",
        "url" => "/photo"
    ],
    [
        "title" => "Pilih Background",
        "icon" => "bx bx-book-content",
        "url" => "/choose-background"
    ],
    [
        "title" => "Preview / Cetak",
        "icon" => "bx bx-file-blank",
        "url" => "/print-preview"
    ]
];

$adminMenu = [
    [
        "title" => "Halaman Utama",
        "icon" => "bx bx-home",
        "url" => "/admin"
    ],
    [
        "title" => "Users",
        "icon" => "bx bx-user",
        "url" => "/users"
    ],
    [
        "title" => "Karyawan",
        "icon" => "bx bx-list-ul",
        "url" => "/employees"
    ],
    [
        "title" => "Request Print ID Card",
        "icon" => "bx bx-book-content",
        "url" => "/employee-request-cards"
    ],
    [
        "title" => "Keluar",
        "icon" => "bx bx-log-out",
        "url" => "/logout"
    ]
];

// Mendapatkan URL saat ini
$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Fungsi untuk memeriksa apakah URL saat ini cocok dengan link
function isActive($url, $currentUrl)
{
    return $url === $currentUrl ? 'active' : '';
}

$menus = (isset($_SESSION['user'])) ? $adminMenu : $guestMenu;

echo '<nav class="nav-menu">';
foreach ($menus as $menu) {
    $active = isActive($menu['url'], $currentUrl);
    echo '<li class="' . $active . '">
            <a href="' . $menu['url'] . '">
                <i class="' . $menu['icon'] . '"></i> 
                <span>' . $menu['title'] . '</span>
            </a>
          </li>';
}
echo '</nav>';
