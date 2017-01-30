<?php

use Sitece16\Entidades\Database; // Acceso a la base de datos
use Sitece16\Entidades\Acl; // Lista de Control de Acceso
use JeremyKendall\Password\PasswordValidator; // Validación de hash de password
use JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter; // Adaptador de persistencia de lib. de aut.
use JeremyKendall\Slim\Auth\Exception\HttpForbiddenException; 
use JeremyKendall\Slim\Auth\Exception\HttpUnauthorizedException;
use Zend\Authentication\Storage\Session as SessionStorage; // Persistencia de sesión por default
use Zend\Session\Config\SessionConfig; // Conf. de la sesión
use Zend\Session\SessionManager; // Controlador de sesiones

// contenedor injector de clases
$contenedor = $app->getContainer();
    
// Instancia de acceso a base de datos
// $contenedor['db'] = new Database();


// Instancia de adaptador para Slim de plantillas twig
$contenedor['vistas'] = function ($c) {
    $vista = new \Slim\Views\Twig(
        array('../clases/Sitece16/Vistas/partials',
            '../clases/Sitece16/Vistas/leafs'),
             ['debug' => true,]
        //, ['cache' => '../vendor/twig/twig/lib/Twig/Cache']
        );
    $basePath = rtrim(str_ireplace('index.php', '', 
    $c['request']->getUri()->getBasePath()), '/');
    $vista->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $vista->addExtension(new Twig_Extension_Escaper('html'));
    $vista->addExtension(new Twig_Extension_Debug());

    return $vista;
};

// Registro de bitácora
$contenedor['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// Detector de tipo de dispositivo del cliente navegador
$contenedor['dispositivo'] = new Mobile_Detect;

// Servicio de adaptador PDO utilizado por la librería de autentificación
$contenedor['authAdapter'] = function ($c) {
    return new PdoAdapter(
        new Database(), // $c->db(), 
        'seguridad', 
        'USR', 
        'hash',
        new PasswordValidator()
    ); // Adaptador de conexión pdo 
};

// Instancia de lista de control de acceso
$contenedor['acl'] = function ($c) { return new Acl(); };

// Instancia de servicio de autentificación
$contenedor->register(new \JeremyKendall\Slim\Auth\ServiceProvider\SlimAuthProvider());

$contenedor['usuario'] = $contenedor->auth->getIdentity();


/** poop handler */ 
$contenedor['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $Directory = opendir('/var/www/html/img/poop/');
        while (false !== ($poop = readdir($Directory))) {
            $shitload[] = $poop; 
        }
        closedir($Directory);

        $response = $c['response']->withStatus(500)
                    ->withHeader('Content-Type', 'text/html' );
        $poopSent = $shitload[rand(2,17)];
        if ($poopSent == '.' || $poopSent == '..')
            $poopSent = 'poop2.gif';

        $response = $c->vistas->render($response, "http/500.twig",
        [
            'poop' => $poopSent,
            'debug' => array(
                    //'shitload' => $shitload,
                    'usuario' => $c->auth->getIdentity(),
                    'rol' => $c->slimAuthRedirectMiddleware,
                    'ruta' => $request->getAttribute('route')->getName(),
                    'exception' => $exception,
                )
        ]);
    return $response;
    };
};


/**
 * Configuramos el contenedor para mostrar errores más detallados
$contenedor['errorHandler'] = function (\Exception $e) use ($app) {
    if ($e instanceof HttpForbiddenException) {
        return $app->render('403.twig', array('e' => $e), 403);
    }

    if ($e instanceof HttpUnauthorizedException) {
        return $app->redirectTo('login');
    }

    // You should handle other exceptions here, not throw them
    throw $e;
};

$contenedor['flash'] = function () {
    return new \Slim\Flash\Messages();
};
 */
