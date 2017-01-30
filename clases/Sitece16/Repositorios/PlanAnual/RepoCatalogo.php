<?php
namespace Sitece16\Repositorios\PlanDeGastos;

use Sitece16\Entidades\Repositorio;
use Sitece16\Entidades\PlanDeGastos\Partida;

/**
 * Mapeador de Datos entre el Objeto Partida y sus propiedades correspondientes
 * en la tabla 'catalogo'.
 */
class RepoCatalogo extends Repositorio
{
	/** 
	 * Constructor: Inicializa la conexion de datos 
	 * */
	public function __construct() {
		parent::__construct();
	}

	
	public function catalogoActivo() {
		$buf = \ORM::for_table('catalogo')
			->where('status','1')
			->find_many();
		return $buf;
	}

	/**
	 * Busca y devuelve una sola Partida del Catálogo por medio de Código 
	 * @param  string $cual  Código de la Partida que buscamos. Ej.: CU-AD14
	 * @return Partida|false Objeto Partida o false si no se encuentra 
	 */
	public function encontrarUno($cual) {
		//echo "Se instanció RepoCatalogo </br>";
		//printf("<pre>%s</pre>",print_r($buf, true));
		$buf = \ORM::for_table('catalogo')
			->where('codigo',$cual)
			->find_one();
		return new Partida($buf->as_array());
	}
}

	//printf("<pre>%s</pre>",print_r($buf, true));
	/*
		private $_datos = [];
		foreach (ORM::for_table('catalogo')->find_result_set() as $unaPartida) {
		  	$this->_datos[$unaPartida->codigo] = new Partida()
		  }  
		  ORM::forTable('catalogo')
	*/