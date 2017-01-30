<?php
namespace Sitece16\Repositorios;
use Sitece16\Entidades\Repositorio; 
use \PDO;
class RepoCrudo extends Repositorio
{
	public function __construct() {
		parent::__construct();
	}

	public function consulta() {

		$buf = \ORM::for_table('carreras') 
			->select_many('carreras.id', 'carreras.nombre')
			->inner_join('sucursales', array('carreras.zona', '=', 'sucursales.N_Sucursal'))
			->where('carreras.status', 'A')
			->where('sucursales.status', 'A')
			->where_not_equal('carreras.zona', '1')
			->order_by_desc('carreras.zona')
			->find_result_set();
		return $buf;
	}

}
