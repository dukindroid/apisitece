<?php
namespace Sitece16\Repositorios\PlanDeGastos;
use Sitece16\Entidades\Repositorio; 
/**
 * Repositorio de Gastos Proyectados
 * 
 * Implementa:
 * 
 * Select gasto, semana_8 from  planbimestral 
 * where desde = '36' and hasta ='43' and ano ='2016' and status = 1 and sucursal = '30'
 * order by gasto desc
 */
class RepoGastosProyectados extends Repositorio
{
	/** 
	 * Constructor: Inicializa la conexion de datos 
	 * */
	public function __construct() {
		parent::__construct();
	}

	public function menuDePlanes($sucursal) {
		/*
		Select desde,hasta,ano from planbimestral where sucursal = $txtsucursal group by  desde, hasta, ano order by id asc ",$id_conexion*/
		$buf = \ORM::for_table('planbimestral') 
			->select_many('desde', 'hasta', 'ano')
			->where('sucursal', $sucursal)
			->group_by('desde')
			->group_by('hasta')
			->group_by('ano')
			->order_by_asc('id')
			->find_many();
		return $buf;
	}


	public function datosDeProyecto($sucursal, $desde, $hasta, $anio) {
		//echo "sucursal: " . $sucursal . " desde: " . $desde . " hasta: " . $hasta . " año: " . $anio . "\n"; 
		$buf = \ORM::for_table('planbimestral') 
		->select_many('gasto', 'semana_8')
		->where( array (
				'desde' => $desde,
				'hasta' => $hasta,
				'ano'   => $anio,
				'sucursal' => $sucursal
			))
		->order_by_desc('gasto')
		->find_many();
		/*
			->raw_query("Select * from planbimestral where desde='36' and hasta='43' and ano='2016' and sucursal='30' order by gasto desc");
		\test::d($gastoProy);
		$buf = $gastoProy->as_array();
		\test::d($buf);
		\test::d($gastoProy);
		*/
	
		return $buf;
	}
}