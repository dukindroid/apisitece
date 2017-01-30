<?php
namespace Sitece16\Entidades\PlanDeGastos;

/**
 * Clase que guarda los datos referentes a una sola partida
 * 
 * Las columnas correspondientes en la tabla que requerimos para nuestras
 * propiedades son:
 * 	
 * id
 * gasto  		// Código
 * gastotxt		// Descripción
 * semana_8		// Gasto Proyectado
 * status		// 1 - Activa, 0 - Inactiva
 * comentario	// Hora de captura
 */
class Partida
{
	private $id;
	private $tipo;
	private $codigo;
	private $valor;
	private $status;
	private $seccion;
	private $comenta;
	private $otros;
	private $ayuda;
	private $misPropiedades = [];
	// const CURSO = 'Gasto de Curso';
	// const CARRERA = 'Gasto de Carrera';

	/**
	 * Constructor de la clase Partida. 
	 * 
	 * Recibe un arreglo con los datos de la partida, por default con las mismas
	 * llaves que los nombres de columna de la tabla 'catalogo'
	 * 
	 * @var Array  Arreglo con los datos de la partida
	 * @return Partida Un objeto Partida inicializado o vacío
	 */
	public function __construct( Array $datos = null) {
		// Inicializa arreglo con propiedades del objeto
		foreach ($this as $key => $value) {
			$this->misPropiedades[] = $key;
		}
		array_pop($this->misPropiedades);
		
		// Recibe y almacena los parametros de inicialización
		if (isset($datos)) {
			foreach ($datos as $key => $value) {
				if(in_array($key,$this->misPropiedades))
					$this->{$key} = $value;
			}
		}
	}

	/**
	 * Función por default cuando se trata de usar el objeto como string. 
	 * 
	 * @return string Devuelve una lista "parámetro = valor"
	 */
	public function __toString() {
		$buffer = "";
		foreach ($this->misPropiedades as $key => $value) {
			if ($key != 'misPropiedades' || !isset($this->{$value})) {
				$buffer .= $value . " = " . $this->{$value} . "\n";
			} 
		}
		return $buffer;
	}

	/**
	 * Función por default cuando se llama un método inexistente. Recibe el
	 * comando de método ->get"campo"().
	 * 
	 * @todo Recibir el comando ->set"campo"()
	 *  
	 * @param  string $funcion    String contenido en $this->misPropiedades
	 * @param  void $argumentos No se usa
	 * @return string             Valor de la propiedad referida en $funcion
	 */
	public function __call($funcion, $argumentos) {
		$comando = substr($funcion, 0,3);
		$propiedad = strtolower( substr($funcion,3));
		if(in_array( $propiedad, $this->misPropiedades)) {
			return $this->{$propiedad};
		}
		throw new \BadFunctionCallException("Partida no tiene esa funcion", 1);
		return false;
	}

	/**
	 * Devuelve una cadena con los campos o nombres de columna de este objeto
	 * @return string Propiedades del objeto separado por comas
	 */
	public function campos() {
		return implode(",", $this->misPropiedades);
	}
}