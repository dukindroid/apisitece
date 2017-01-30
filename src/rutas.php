<?php
use \Psr\Http\Message\ServerRequestInterface as Req;
use \Psr\Http\Message\ResponseInterface as Res;
use Sitece16\Controladores\API;
use Sitece16\Controladores\Docs;
use Sitece16\Controladores\WebHook;


/** Indice principal API */

$app->get('/', function (Req $req, Res $res) use ($app, $contenedor) {
    $res = $this->vistas->render($res, "index.twig",
        [ 
            //'debug' => 1,
            'usuario' => $this->auth->getIdentity(),
            'rol' => $this->slimAuthRedirectMiddleware,
        ]);
    return $res;
});

/** logout */
$app->get('/logout', function (Req $req, Res $res) use ($app) {
    $this->authenticator->logout();
    return $res->withStatus(302)->withHeader('Location', '/');    
});

/** login simplón, redireccióna a la raiz de api.sistematece.mx (portada de la API) */
$app->map(['GET', 'POST'], '/login', function (Req $req, Res $res, $args) use ($app) {
    $params = $req->getParsedBody();

    if ($req->isPost()) {
        $username = $params['username'];
        $password = $params['password'];

        $result = $this->get('authenticator')->authenticate($username, $password);

        if ($result->isValid()) {
            return $res->withStatus(302)->withHeader('Location', '/');
        }
    }

    $res = $this->vistas->render($res, "ui/login.twig",
        [ 'titulo' => 'Bienvenido!' ]);
    return $res;
});

/** Páginas estáticas, documentación */
$app->get('/docs[/{params:.*}]', Docs::class);

/** Comunicación externa con CRM via webhook json */
$app->map(['GET','POST'],'/webhook[/{params:.*}]', WebHook::class);

/** Api sitece */
$app->map(['GET','POST'],'/api/v1[/{params:.*}]', Api::class);

