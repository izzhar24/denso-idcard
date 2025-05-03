<?php

$router->get('/', 'HomeController@index');
$router->get('/card', 'HomeController@card');
$router->get('/photo', 'HomeController@photo');
$router->get('/choose-background', 'HomeController@chooseBackground');
$router->get('/print-preview', 'HomeController@printPreview');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/dashboard', 'AuthController@dashboard');
$router->get('/logout', 'AuthController@logout');
$router->post('/get-card', 'HomeController@getCard');
$router->post('/set-employee', 'HomeController@setEmployee');
$router->post('/set-photo', 'HomeController@setPhoto');
$router->post('/set-background', 'HomeController@setBackground');
