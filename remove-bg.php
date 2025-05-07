<?php
// Specify the path to the input and output images
$imagePath = 'image.jpeg';
$api_key = 'fbgwLt2MGvVAxaDwQjZBfaJ1';
$url = 'https://api.remove.bg/v1.0/removebg';

$client = new GuzzleHttp\Client();
$res = $client->post('https://api.remove.bg/v1.0/removebg', [
    'multipart' => [
        [
            'name'     => 'image_file',
            'contents' => fopen($imagePath, 'r')
        ],
        [
            'name'     => 'size',
            'contents' => 'auto'
        ]
    ],
    'headers' => [
        'X-Api-Key' => 'INSERT_YOUR_API_KEY_HERE'
    ]
]);

$fp = fopen("no-bg.png", "wb");
fwrite($fp, $res->getBody());
fclose($fp);
fclose($fp);
?>