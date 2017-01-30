<?php
namespace Sitece16\Entidades
/**
 * Representacion objeto de un usuario.
 * 
 * Cada que alguien acceda al sistema ser+a por medio de una cuenta y contraseña
 * previamente registrada que guarda una relacion con un expediente que mantiene
 * la institución. Esta clase carga algunos de esos datos así como también los 
 * permisos y la sucursal a la que pertenece el usuario. Este archivo es parte 
 * de la libreria Sitece/Sitece16.
 * 
 * @author Javier Gutiérrez Herrera
 */
class Usuario 
{
	private $usr;
	private $nombre;
	private $ap_paterno;
	private $ap_materno;
	private $sucursal;
	private $tipo_usuario;
	private $id_rol;
	private $tipo_usuario;
	private $permisos;

	/**
	 * Constructor de la clase Usuario
	 * @param array $info Arreglo de variables de inicialiacion de datos
	 */
	public function __construct($info = array()) {
		$this->usr = $info['usr'];
		$this->nombre = $info['nombre'];
		$this->ap_paterno = $info['ap_paterno'];
		$this->ap_materno = $info['ap_materno'];
		$this->sucursal = $info['sucursal'];
		$this->$seguridad = new Permisos($info['tipo_usuario'];
	}

	/**
	 * Determina si un Usuario tiene acceso a alguna funcion del sistema
	 * @param  Permisos::ConstantesAcceso $que Permiso o acceso que se pregunta
	 * @return bool      True si tiene acceso
	 */	
	public function puede($que) {
		return $this->seguridad->tieneAcceso($que);
	}

	/**
	 * Devuelve los permisos y accesos con que cuenta el usuario
	 * @return string Permisos y accesos separados por coma
	 */
	public function permisos() {
		return $this->seguridad;
	}

	/**
	 * Funcion llamada cuando se trata de usar un objeto usuario como string
	 * @return string Datos principales 
	 */
	public function __toString() {
		$infoUsuario = $this->usr.": ";
		$infoUsuario .= $this->nombre." ";
		$infoUsuario .= $this->ap_paterno." ";
		$infoUsuario .= $this->ap_materno."<br />".PHP_EOL;
		$infoUsuario .= "Sucursal: ". $this->sucursal;
		$infoUsuario .= " Tipo: " . $this->tipo_usuario;
		$infoUsuario .= " Seguridad: " . $this->permisos();

		return $infoUsuario;
	}
}
