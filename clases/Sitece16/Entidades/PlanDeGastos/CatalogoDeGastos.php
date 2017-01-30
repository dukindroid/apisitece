<?php
namespace Sitece16\Entidades\PlanDeGastos;

use Sitece16\Repositorios\PlanDeGastos\RepoCatalogo as Repo;

/**
 * Objeto que instancia un arreglo con todas las Partidas válidas del Plan de
 * Gastos. 
 * 
 * ATENCIÓN!!!: Los datos en la base están encodificados de manera incorrecta y 
 * al presentarse deben decodificarse antes con la función  mb_decode_mimeheader
 * 
 * @todo Guardar el catálogo en caché mientras no presente cambios
 */
class CatalogoDeGastos //implements ArrayAccess , Iterator
{
	/** @var array Arreglo de Partidas en este Catálogo */
	private $catalogo;
	/** @var RepoCatalogo Repositorio de la clase */
	private $repositorio;
	/** @var array Arreglo con todas las Partidas del catálogo activo */
	private $partidas = [];

	/** Constructor de la clase, carga el catálogo activo y sus códigos */
	public function __construct() {
		// Instancia de repositorio de la clase
		$this->repositorio = new Repo;
		// Carga todos los elementos activos del catálogo 
		$this->catalogo = $this->repositorio->catalogoActivo();
		// Carga de los códigos en objetos Partida en un arreglo separado
		foreach ($this->catalogo as $elemento) {
			$initPartida = $elemento->as_array();
			$this->partidas[$elemento->get('codigo')] = new Partida($initPartida);
		}
		return $this;
	}

	/**
	 * Devuelve una partida del Catálogo
	 * @throws UnexpectedValueException Si no existe el código
	 * @param  string $cual Cadena correspondiente a un código de partida valido
	 * @return Partida       Objeto Partida correspondiente
	 */
	public function partida($cual) {
		if (!array_key_exists($cual, $this->partidas)) {
			throw new UnexpectedValueException("Partida inexistente", 1);
		}
		return $this->partidas[$cual];
	}
	/**
	 * Función por default cuando se declara un Catalogo como cadena de texto
	 * @return string Cadena con todos los códigos cargados, separado por \n
	 */
	public function __toString() {
		$buf = "";
		foreach ($this->partidas as $partida) {
			$buf .= $partida->getCodigo() . "\n";
		}
		return $buf;
	}
}