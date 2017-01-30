<?php
namespace Sitece16\Modulos;

class WebHook
{
	private $_cont;
	private $usuario; // sesion guardada en api.sitece.mx
	private $_USR; // sesion de usr de sitece.mx
	private $rutas;
	private $_uri; // uri en que se encuentra el iframe 
	private $_umod; // mod de sitece.mx

	public function __construct(\Slim\Container $contenedor) {
		$this->_cont = $contenedor;
		$this->usuario = $this->_cont->usuario;

		$Directory = new \RecursiveDirectoryIterator('/var/www/clases/Sitece16/Vistas/leafs/webhook/');
		$Iterator = new \RecursiveIteratorIterator($Directory);
		$Regex = new \RegexIterator($Iterator, '/^.+\.twig$/i', \RecursiveRegexIterator::GET_MATCH);

		foreach($Regex as $name => $object) {
			$directorio = explode("/", $name, 8);
			$this->rutas[] = $directorio[7];
		}
	}

	public function logout($request, $response) {
		$this->authenticator->logout();
		return $res->withStatus(302)->withHeader('Location', '/');  
	}

	public function login($request, $response) {
	    $params = $request->getParsedBody();

	    if ($request->isPost()) {
	        $username = $params['username'];
	        $password = $params['password'];

	        $result = $this->_cont->get('authenticator')->
	        			authenticate($username, $password);

	        if ($result->isValid()) {
	            return $response->withStatus(302)
	            	->withHeader('Location', 
	            		'https://api.sistematece.mx/webhook/redirect/success');

	        }
	        return $response->withStatus(302)->
	            	withHeader('Location', 'https://api.sistematece.mx/webhook/redirect/failure');
	    }

	    $response = $this->_cont->vistas->render($response, "/webhook/login.twig",
	            [ 
	                // 'debug' => array( 
	                //      'args' => $args, 
	                //      'usuario' => $this->usuario 
	                //  ),
	                //'USR' => $_SESSION['USR'],
	            ]);
	    return $response;
	}

	public function menu($request, $response, $args) {

		$response = $this->_cont->vistas->
			fetch($response, "webhook/menu/guest.twig", ['USR' => $this->usuario]);
		return $response; 
	}

	public function answer($request, $response, $args) {
		$uri = 'https://api.sistematece.mx/webhook/login';
		if ($request->isPost()) {
		    $json_args = $request->getParsedBody();
		    //var_dump($json_args);
			$this->_uri = $json_args['uri'];
			/*
		    json_decode($json_args, true);
			$this->_mod = $json_args['mod'];
			$this->_USR = $json_args['USR'];
			*/
		}

		$rol = $this->_cont->usuario['role'];
	    $html_menu = $this->_cont->vistas->fetch('/webhook/menu' . $rol . '.twig', 
	    	[
	    		'debug' => [
	    			'sesion' => $_SESSION,
	    			'json' => $json_args,
	    		],
	    	]);
	    
	    $html_iframe = $this->_cont->vistas->fetch( '/webhook/iframe.twig', ['uri' => $uri]);
	    //echo $html_content;
	    $buf = array( 
					'html_menu' => $html_menu,
	                'html_iframe' => $html_iframe,
	                 );
	    return json_encode($buf, JSON_PRETTY_PRINT);	
	}

	public function __invoke($request, $response, $args) {
		// Si es una función ya definida en la clase...
		if (isset($args['params']) && in_array($args['params'], get_class_methods($this))) {
			return call_user_func_array( array( $this, $args['params'] ), array($request, $response, $args));
		}

		// Y si no pues cargar la ruta que corresponda directamente:
		$response = $this->_cont->vistas->
			render($response, "/webhook/" .
				( 
					isset( $args['params'] ) ? $args['params'] : "webhook" 
				) . ".twig",
			[ 
				// 'debug' => array( 
				// 		'args' => $args, 
				// 		'usuario' => $this->usuario 
				// 	),
				'usuario' => $this->usuario,
			]);

		return $response;
	}
}

/*
	function (Req $req, Res $res) { 
		$usr = $contenedor->usuario;
		$html_menu = $app->vistas->fetch('/partials/webhook/' . $usr->role . '.menu.twig', ['usuario' => $usr]);

		$html_iframe = $app->vistas->fetch('/partials/webhook/'. $usr->role . '.iframe.twig', ['usuario' => $usr]);
		//echo $html_content;
		$buf = array('menu' => $html_menu,
		'iframe' => $html_iframe );
		echo json_encode($buf, JSON_PRETTY_PRINT);
	})
*/