<?php
namespace Sitece16\Entidades\AntiguedadColegiatura;

use Exception as BaseException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use \Countable;
use \IteratorAggregate;

/**
 * Clase que guarda los registros que hay en la vista de registros para una
 * carrera.
 */
class Registro implements \IteratorAggregate, Countable {
	/** @var Array Arreglo con los datos de los registrus cargados */
	private $_datos = Array();
	/** @var Integer Guarda el Id de la carrera que va a cargar */
	private $carreraId;
	/** @var integer Último valor de la colegiatura para esta carrera */
	private $colegiatura;
	/** @var integer Valor anterior al actual para colegiatura */
	private $colegiaturaAnterior = null;
	/** @var Fecha Fecha en que se realizó el primer registro de esta carrera */
	private $fechaPrimerRegistro;
	/** @var Fecha Fecha en que se realizó el último registro de esta carrera */
	private $fechaUltimoRegistro;
	/** @var Fecha Fecha en que se cambió por última vez la colegiatura */
	private $fechaCambio;
	/** indice cambio: renglón en que se encontró el cambio */
	private $indiceCambio;
	/** bandera que guarda si se encontró o no el cambio de colegiatura */
	private $tieneCambio = FALSE;
	function __construct(Array $datosIniciales) {
		// Cargar los datos iniciales que vienen de la consulta
		$this->_datos = $datosIniciales;
	}

	/**
	 * Regresa la 'cantidad' de registros o los que se haya recorrido hasta 
	 * encontrar el cambio de precio.
	 * @param  integer $cantidad Opcional, permite especificar un límite de 
	 *                           registros a retornar
	 * @return array           arreglo que contiene n registros con la infor-
	 *                         mación: Array('colegiatura' => $valor, 
	 *                                        'fecha' => $fecha)
	 */
	public function dump($cantidad = null) {
		$offset = $this->tieneCambio() ? $this->indiceCambio : 10;
		return array_slice ( $this->_datos, $offset - 10, 20);
	}

	/**
	 * Regresa la cantidad de registros en este objeto
	 */
	public function count() {
		return sizeof($this->_datos);
	}

	public function tieneCambio() {
		return $this->tieneCambio;
	}

	/**
	 * alias para count()
	 */
	public function total() {
		return $this->count();
	}

	/**
	 * Encuentra el último cambio en la colegiatura
	 * 
	 * @param      integer cantidadSeguidos Cantidad de registros diferentes a 
	 *                                      la primera colegiatura que deben
	 *                                      ocurrir para considerarse cambio
	 * @param      integer cantidadRegistros Cantidad de registros que deben de
	 *                                       existir en el objeto para que se 
	 *                                       considere válido correr el método
	 * @return     integer Fecha en que se encontró el cambio
	 * 
	 */
	public function procesar($maximoSeguidos = 5) {
		// Guardamos la última colegiatura que se ha cobrado como actual
		if ($this->_datos == null)
			return;

	    $this->colegiatura = (int)$this->_datos[0]['colegiatura'];
	    // Inicializamos fecha inicial y final de la carrera
	    $this->fechaPrimerRegistro = $this->_datos[0]['fecha'];
	    $this->fechaUltimoRegistro = $this->_datos[$this->count()-1]['fecha'];

	    $cambiosSeguidos = 0; // Guarda cantidad de colegs. cambiadas seguidas
	    $colegCambiada = 0; // Guarda el valor de la colegiatura anterior
		$i;           // Guarda el renglón donde se encontró el cambio
	    foreach ($this->_datos as $key => $value) {
	        $valor = (int)$value['colegiatura']; // <= UltimoPrecio, Fecha
	        
	        if ($valor == $this->colegiatura)
	            $cambiosSeguidos = 0;

	        if ($valor != $this->colegiatura)
	            $cambiosSeguidos++;

	        if($cambiosSeguidos == 1) {
			    $this->fechaCambio = $value['fecha'];
			    $this->colegiaturaAnterior = $value['colegiatura'];
			    $this->indiceCambio = $key;
			}

	        if ($cambiosSeguidos == $maximoSeguidos){
	        	$this->tieneCambio = TRUE;
	            break;
	        }
	    }
		return TRUE;
	}

	/**
	 * Regresa la colegiatura actual encontrada en el mas reciente de los 
	 * registros
	 * 
	 * @return     integer Valor de la colegiatura
	 */
	public function getColegiatura() {
		return $this->colegiatura;
	}

	public function getFechaCambio() {
		return $this->fechaCambio;
	}

	public function getFechaPrimerRegistro() {
		return $this->fechaPrimerRegistro;
	}

	public function getFechaUltimoRegistro() {
		return $this->fechaUltimoRegistro;
	}

	public function getIndice() {
		return $this->indiceCambio;
	}

	/**
	 * Regresa el valor de la primera colegiatura en que hubo cambio
	 * 
	 * @return     integer Valor de la siguiente colegiatura
	 */
	public function getColegiaturaAnterior() {
		return $this->colegiaturaAnterior;
	}

	public function getIterator() {
		return new \ArrayIterator($this->_datos);
	}
}
/* tests: 
	#select * from sucursales_activas order by nombre asc
	# la primera es 32>Cass. Bach. Col.

	#select * from carrerasactivas where n_sucursal = 32
	# la unica carrera que tiene es 327>bach. tec. gastr.

	#select * from coleg_carr_por_fecha where `CarreraID` = 327

	# 199 registros # nunca ha cambiado

	 ----- segundo ejemplo -----
	#select * from sucursales_activas order by nombre asc
	# la segunda es 25>Cass. Col.

	#select * from carrerasactivas where n_sucursal = 25
	# tres carreras, 62,548,538 tec. gastr, petit chef, repost.

	# select ultimoprecio,fecha from coleg_carr_por_fecha where `CarreraID` = 62

	# 62>1489 registros cambio en el registro 1041 fecha 2010-03-26
	# nunca ha cambiado
*/