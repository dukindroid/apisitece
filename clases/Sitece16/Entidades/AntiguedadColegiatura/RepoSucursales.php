<?php
namespace Sitece16\Entidades\AntiguedadColegiatura;

use Sitece16\Entidades\Database;

class RepoSucursales {
	/** @var Database Instancia de la conexión a la base de datos */
	protected $db;
	/**
	 * Carga los datos de este objeto Sucursales. Una sola sucursal Lleva:
	 * $_datos = Array(
	 * 		$n_sucursal => Array ( 'nombre' => string $nombre ,
	 *  						   'carreras' => new Carreras($n_sucursal) 
	 *  						 ) 
	 * 
	 * 
	 * @todo validar que si se haya cargado datos
	 */
	function __construct(Database $db) {
		$this->db = $db;
		\ORM::set_db($this->db);
	}

 	function cargar($listaSucs) {
 		$repo = new RepoCarreras($this->db);
    	$_datos = Array();
    	foreach ($listaSucs as $N_Sucursal => $nombre) {
    		$_datos[$N_Sucursal] = Array( 'nombre' => $nombre, 
    			'carreras' => new Carreras($repo->cargar($N_Sucursal)));
    	}

    	return $_datos;
 	}
}