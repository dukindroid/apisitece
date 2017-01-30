<?php
namespace Sitece16\Entidades\AntiguedadColegiatura;

use Sitece16\Entidades\Database;

class RepoCarreras {
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

 	function cargar($N_Sucursal) {
 	    \ORM::set_db($this->db);
	    $consultaSucs = \ORM::get_db()
	        ->query("select id, nombre 
	        	from carrerasactivas 
	        	where n_sucursal = $N_Sucursal");
	    $temporal = $consultaSucs
	        ->fetchAll(\PDO::FETCH_KEY_PAIR);
	    $repoRegs = new RepoRegistro($this->db);

	    foreach ($temporal as $key => $value) {
	        $infoRegistro = $repoRegs->cargar($key);
	        $_datos[$key] = Array('nombre' => $value, 
	        	'registros' => new Registro($infoRegistro));
	        // Ya que andamos por aquí, hay que procesar los registros
	        $_datos[$key]['registros']->procesar();
	    }
	    return $_datos;
 	}
}
/*
	public function load($N_Sucursal) {
	    $consulta = \ORM::get_db()->query(
	    	"select * from carrerasactivas where n_sucursal = $N_Sucursal"
	    );
		return $consulta->fetchAll(\PDO::FETCH_ENUM);
	}
*/