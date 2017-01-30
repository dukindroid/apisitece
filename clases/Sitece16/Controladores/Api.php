<?php
namespace Sitece16\Modulos;

class Api
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

		$Directory = new \RecursiveDirectoryIterator('/var/www/clases/Sitece16/Vistas/leafs/api/');
		$Iterator = new \RecursiveIteratorIterator($Directory);
		$Regex = new \RegexIterator($Iterator, '/^.+\.twig$/i', \RecursiveRegexIterator::GET_MATCH);

		foreach($Regex as $name => $object) {
			$directorio = explode("/", $name, 8);
			$this->rutas[] = $directorio[7];
		}
	}

	public function user($request, $response, $args) {
		$rol = $this->_cont->usuario['role'];
		$html_menu = $this->_cont->vistas->fetch('/webhook/menu' . $rol . '.twig');
		
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