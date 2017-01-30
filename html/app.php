<?php
/**
 * Para debug con sublime hay que agregar: ?XDEBUG_SESSION_START=sublime.xdebug
 */
require '../src/idiorm.php';                               // ORM
require '../vendor/autoload.php'; 					// Librerias/Composer

$config = require __DIR__ . '/../src/config.php'; 	// configuración
$app = new \Slim\App($config); 						// Crear instancia

require __DIR__ . '/../src/dependencias.php'; 		// dependencias 
require __DIR__ . '/../src/middleware.php'; 		// middleware
require __DIR__ . '/../src/rutas.php'; 				// rutas

$app->run(); 										// Se acabó