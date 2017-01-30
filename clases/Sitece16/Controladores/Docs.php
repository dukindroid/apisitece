<?php
namespace Sitece16\Controladores;

class Docs
{
	protected $contenedor;
	protected $usuario;
	protected $rutas;

	/**
	  *  Constructor, genera un arreglo con todos los directorios a partir de
	  * /clases/Sitece16/Vistas/leafs/docs/
	  * Asi si agregamos un archivo twig en este directorio se genera autom[ati-
	  * camente su menu. O para algo asi lo queria me parece recordar...]
	  */

	public function __construct(\Slim\Container $contenedor) {
		$this->contenedor = $contenedor;
		$this->usuario = $this->contenedor->usuario;

		$Directory = new \RecursiveDirectoryIterator('/var/www/clases/Sitece16/Vistas/leafs/docs/');
		$Iterator = new \RecursiveIteratorIterator($Directory);
		$Regex = new \RegexIterator($Iterator, '/^.+\.twig$/i', \RecursiveRegexIterator::GET_MATCH);

		foreach($Regex as $name => $object) {
			$directorio = explode("/", $name, 8);
			$this->rutas[] = $directorio[7];
		}
	}

	public function __invoke($request, $response, $args) {
		$response = $this->contenedor->vistas->
			render($response, "/docs/" . (isset($args['params'])? $args['params'] : "static" ) . ".twig",
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
