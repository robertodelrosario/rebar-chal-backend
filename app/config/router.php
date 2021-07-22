<?php

$router = $di->getRouter();

// Define your routes here
$router->add('/users/register', ['controller' => 'users', 'action' => 'register']);
$router->add('/users/login', ['controller' => 'users', 'action' => 'login']);
$router->add('/users/logout', ['controller' => 'users', 'action' => 'logout']);
$router->add('/users/profile', ['controller' => 'users', 'action' => 'profile']);
$router->add('/users/editprofile/{id}', ['controller' => 'users', 'action' => 'editprofile']);
$router->add('/users/addcontact', ['controller' => 'users', 'action' => 'addcontact']);
$router->add('/users/editcontact/{id}', ['controller' => 'users', 'action' => 'editcontact']);
$router->add('/users/deletecontact/{id}', ['controller' => 'users', 'action' => 'deletecontact']);
$router->add('/users/uploaddp/{id}', ['controller' => 'users', 'action' => 'uploaddp']);
$router->add('/users/removedp/{id}', ['controller' => 'users', 'action' => 'removedp']);

$router->handle($_SERVER['REQUEST_URI']);
