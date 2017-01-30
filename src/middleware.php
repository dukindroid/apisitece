<?php
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;
use \Psr\Http\Message\ServerRequestInterface as Req;
use \Psr\Http\Message\ResponseInterface as Res;

// Middleware para errores fresones 
$app->add(new WhoopsMiddleware);

// Funcion middleware que remueve el '/' final del URI 
$app->add(function (Req $req, Res $res, callable $next) {
    $uri = $req->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        $uri = $uri->withPath(substr($path, 0, -1));        
        if($req->getMethod() == 'GET') {
            return $res->withRedirect((string)$uri, 301);
        } else {
            return $next($req->withUri($uri), $res);
        }
    }
    return $next($req, $res);
});

// Funcion middleware que registra en el log todas las peticiones HTTP 
$app->add(function (Req $req, Res $res, callable $next) {
    $this->logger->addInfo("Nuevo acceso: " . $req->getUri() );
    return $next($req, $res);
});

// middleware autorizador 
$app->add($app->getContainer()->get('slimAuthRedirectMiddleware'));

// TODO: por el momento autentificar directo con el USR que viene desde el CRM
// $app->get('authenticator')->authenticate($username, $password);
// 


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
