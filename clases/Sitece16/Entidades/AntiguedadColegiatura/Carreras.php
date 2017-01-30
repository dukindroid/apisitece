<?php 
namespace Sitece16\Entidades\AntiguedadColegiatura;

use Sitece16\Entidades\Database;
use Sitece16\Entidades\AntiguedadColegiatura\RepoRegistro;
use Sitece16\Entidades\AntiguedadColegiatura\Registro;
use \IteratorAggregate;

/**
 * Una carrera tiene los siguientes datos
 * carreraid> llave de la carrera unique(int)
 * Registros> Objeto
 * UltimaColeg> Ultima colegiatura que se ha cobrado
 */
 
 class Carreras implements \IteratorAggregate{
 	/** @var Array Arreglo que almacena los registros  */
 	protected $_datos = Array();

 	function __construct(array $otrosDatos)
 	{
 		$this->_datos = $otrosDatos;
 	}

 	public function getIterator() {
		return new \ArrayIterator($this->_datos);
	}
 }