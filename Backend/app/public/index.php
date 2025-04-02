<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();
$controller = new Controllers\Controller();

//// check if the token is provided
//if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
//    $controller->respondWithError(401, "No token provided");
//    die();
//}

$router->setNamespace('Controllers');

$router->setBasePath('/api');

$router->get('/', function () {
    echo 'Welcome to the Ticketinator API';
});


// routes for the user endpoint
$router->get('/users', 'UserController@GetAll');
$router->get('/users/([a-z0-9-]+)', 'UserController@Get');

$router->post('/users', 'UserController@Create');
$router->put('/users/([a-z0-9-]+)', 'UserController@Update');
$router->delete('/users/([a-z0-9-]+)', 'UserController@Delete');

$router->post('/users/login', 'UserController@Login');
$router->post('/users/change-password', 'UserController@UpdatePassword');

// routes for the ticket endpoint
$router->get('/tickets', 'TicketController@GetAll');
$router->get('/tickets/([a-z0-9-]+)', 'TicketController@Get');

$router->post('/tickets', 'TicketController@Create');
$router->put('/tickets/([a-z0-9-]+)', 'TicketController@Update');
$router->delete('/tickets/([a-z0-9-]+)', 'TicketController@Delete');
$router->post('/tickets/([a-z0-9-]+)/resolved', 'TicketController@setResolved');

// messages
$router->post('/tickets/([a-z0-9-]+)/reply', 'TicketController@addMessage');
$router->get('/tickets/([a-z0-9-]+)/messages', 'TicketController@getMessages');


// Run it!
$router->run();
