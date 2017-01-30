<?php
namespace Sitece16\Entidades\AntiguedadColegiatura;

use Sitece16\Entidades\Database;

class RepoRegistro {
	/** @var Database Instancia de la conexión a la base de datos */
	protected $db;
	/**
	 * Carga los datos de este objeto Registros
	 * 
	 * @todo validar que si se haya cargado datos
	 */
	function __construct(Database $db) {
		$this->db = $db;
		\ORM::set_db($this->db);
	}

	public function cargar($CarreraId) {
	    $consulta = \ORM::get_db()->query(
	    	"select ultimoprecio as colegiatura, Fecha as fecha
	    	from coleg_carr_por_fecha where `CarreraId` = $CarreraId"
	    );
		return $consulta->fetchAll(\PDO::FETCH_ASSOC);
	}


}