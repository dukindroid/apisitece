<?php
namespace Sitece16\Entidades;
/**
 * Servicio de carga de templates html o texto plano.
 * 
 * Descripcion
 * 
 * @author Javier Gutiérrez Herrera
 * 
 * @var Permisos $permisos Objeto de la clases Permisos instanciada por el 
 *      Usuario
 * @deprecated Mejor siempre si vamos a usar el twig con vue
 */

/**
 * Carga las representaciones HTML de un objeto.
 * 
 * La clase Vistas es la unica que cuenta con metodos echo, printf, etc. Recibe
 * el nombre de una vista o un objeto y una instancia de usuario para mediante 
 * sus permisos saber que acceso a datps puede darle. Adapta la información del
 * objeto en un template y devuelve la respuesta HTML. Este archivo es parte de 
 * la libreria Sitece/Sitece16.
 * 
 * @author     Javier Gutierrez Herrera
 */
class Vistas
{
	// Recibe un nombre de archivo html para usar como template, un arreglo con
	// variables para cargar al template y una referencia a los permisos que 
	// tiene el usuario que requiere la vista
	
	private $permisos;
	/**
	 * Constructor del servicio de vistas
	 * @param Usuario|null $usr Puede iniciarse con los permisos de un usuario o
	 *                          vacio para formatos por default
	 */
	public function __construct(Usuario $usr = null) {
		$this->permisos = $perm;
	}

	/**
	 * Funcion render de HTML
	 * @param  file $template Archivo de template
	 * @param  array $vars     Arreglo asociativo con variables del template
	 * @return bool           true si se cargo correctamente
	 */
	public function vista($template, $vars) {
		echo "<pre>Me enviaste a cargar " . $template . " con las variables ";
		echo $vars . "pero todavia no he sido implementada. </pre>";
	}
} 