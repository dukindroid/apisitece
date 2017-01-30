<?php
namespace Sitece16\Entidades;
/**
 * Sistema de Control de Acceso
 * 
 * Recibe un Rol y devuelve todos sus permisos asignados. Habilita a un Usuario
 * para saber que puede y no puede hacer. Las tablas SQL correspondientes a esta
 * clase se encuentran en ./../database/rbac_.sql
 * 
 * @todo Implementar un módulo que permita editar los roles, los permisos, y que
 *       rol tiene asignado cada usuario
 */
class Permisos
{
	/** @var array Acciones que puede llevar a cabo el usuario */
	private $permisos = array();
	/** @var array Tipo de acceso a Modulos para este usuario */
	private $accesos = array(); 
	
	/**
	 * Constructor de la clase Permisos
	 * @param  Rol    $rol Recibe el tipo de usuario
	 * @return Permisos      Devuelve la instancia de este objeto
	 */
	public function __construc(Rol $rol) {
		if (!count($datos)) {
			die("Nueva instancia de Permisos sin inicializar en ".__FILE__);
		}
	}

	/**
	 * Devuelve los modulos asignados a este usuario
	 * @return string Array separado por comas de los distintos modulos
	 */
	public function accesos() {
		// Devuelve SELECT DISCTINCT moduloID FROM rbac_permisos LEFT JOIN 
		// rbac_modulos ON moduloID = moduloID
		// O sea, los diferentes módulos que puede ver
		 
		return $this->accesos;
	}

	/**
	 * Devuelve las acciones que puede realizar este usuario
	 * @return string 	Array separado por comas de las acciones que puede hacer
	 */
	public function permisos() {
		// Devuelve las acciones que puede realizar
		return $this->permisos;
	}

	/**
	 * Devuelve la información completa contenida en el objeto como string
	 * @return string Permisos y accesos
	 */
	public function __toString() {
		$buf = "Accesos:" . explode(', ', $this->accesos) . PHP_EOL;
		$buf .= "Permisos:" . explode(', ', $this->permisos) . PHP_EOL;

		return $buf;
	}
}