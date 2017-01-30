<?php
namespace Sitece16\Entidades\PlanDeGastos;

/**
 * Clase Subtotal de Gasto Semanal
 * 
 * Es una clase mutable sencilla con dos atributos, es el bloque 
 * fundamental con el que se estructura el Plan Anual de Gastos completo. Guarda
 * solamente dos datos: el subtotal de los gastos de un mismo concepto que se 
 * hayan realizado en una semana, en una sucursal y la semana a la que se 
 * refiere este gasto. 
 * 
 */
class GastoSemanal 
{
	private $_datos = array( );

	/**
	 * Constructor de la clase Gasto Semanal
	 * 
	 * Genera un nuevo objeto de la clase, ya sea con parametros de 
	 * inicializacion o vacio, dependiendo de los argumentos. Si los argumentos 
	 * son incorrectos se establece semana actual para semana y 0 para gasto.
	 * 
	 * @throws InvalidArgumentException  Parametros invalidos
	 * @throws OutOfBoundsException		 Valores fuera de rango
	 * 
	 * @param int|null	      $semana A que semana corresponde
	 * @param string|null $gasto  Cantidad de dinero que se ejercio en esta
	 *                            semana, representada como $N,NNN.DD
	 */
	public function __construct( $semana = null, $gasto = null ) {
		if (!isset($semana) || !self::esSemanaValida($semana)) {
      $semana = idate('W', time());
    }
    if (!self::esSemanaValida($semana)) {
			
		}
		$this->_datos['semana'] = $semana;
		if (!isset($gasto) || !self::esDineroValido($gasto)) {
      $gasto = "0";
		}
    $this->_datos['semana'] = $semana;
		$this->_datos['gasto'] = $gasto;
		return $this;
	}

	/**
	 * Devuelve el valor de Semana almacenado
	 * @return int Numero entero entre 1 y 53
	 */
	public function getSemana() {
		return $this->_datos['semana'];
	}

	/**
	 * Recibe y almacena un entero correspondiente a valor de Semana
	 * @param int $semana Numero entero entre 1 y 53
	 */
	public function setSemana(int $semana) {
		if (esSemanaValida($semana)) {
			$this->_datos['semana'] = $semana;
      return $this;
    } 
    throw new InvalidArgumentException("Tipo de dato incorrecto en semana", 1);
    return false;
	}

	/**
	 * Devuelve el valor de Gasto almacenado para esta Semana
	 * @return string Entero a dos decimales mayor que cero, codificado como
	 *                cadena de texto
	 */
	public function getGasto(){
		return $this->_datos['gasto'];
	}

	/**
	 * Recibe y almacena una cadena de texto representativa de un Gasto.
	 * 
	 * @param string $gasto Cantidad de dinero válida
	 */
	public function setGasto(string $gasto) {
		if (esDineroValido($gasto)) {
      $this->_datos['gasto'] = $gasto;
      return $this;
    }
	}

  /**
   * Se usa cuando por ejemplo escribimos:
   * 
   * $unGasto = new Gasto(45,2100.5);
   * echo "Gasto cargado:" . $unGasto;
   * 
   * @return string [description]
   */
	public function __toString () {
		return "Sem.: " . $this->_datos['semana'] .", $".$this->_datos['gasto'];
	}

  /**
   * Funcion para agregar varios gastos de una misma semana:
   * 
   * $gastoTotal = $unGasto->mas(otroGasto->mas($todaviaOtroGasto));
   * 
   * @param  GastoSemanal $otroGasto El objeto de clase GastoSemanal que 
   *                                 queremos agregar.
   * @return GastoSemanal|false      Regresa un objeto de tipo GastoSemanal ya
   *                                 con el campo gasto modificado, o false si
   *                                 los argumentos son incorrectos.
   */
	public function mas(GastoSemanal $otroGasto) {
    if ($this->_datos['semana'] != $otroGasto->getSemana()) {
      throw new InvalidArgumentException ("Semana incorrecta en mas()");
      return false;
    }
		$this->_datos['gasto'] += $otroGasto->getGasto();
		return $this;
	}

  /**
   * Función que evalúa un número válido de semana. Debe ser un entero entre 1 y
   * 53
   * 
   * @param  int $prueba Entero a validar
   * @return boolean         true si es valido
   */
	private function esSemanaValida($prueba) {
		if (!is_integer($prueba)) {
			throw new \InvalidArgumentException("Tipo de dato incorrecto en semana", 1);
			return false;
		}
		if ($prueba > 53 || $prueba < 0 ) {
			throw new \OutOfBoundsException("Número inválido de prueba.", 1);
			return false;
		}
		return true;
	}

  /**
   * Función que evalúa una cantidad válida de dinero. Debe ser un float 
   * redondeado a dos décimas mayor que cero.
   * 
   * @param  [type] $prueba [description]
   * @return [type]         [description]
   */
	private function esDineroValido($prueba) {
		if (is_numeric($prueba) && $prueba < 0) {
		  throw new \InvalidArgumentException("No es una cantida de dinero válida.", 1);
      return false;
    } 
		return true;
	} 	
}

// TODO: Validar y formatear rigurosamente las cantidades representativas de
// dinero:
/*
function money_format($format, $number) 
{ 
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
    if (setlocale(LC_MONETARY, 0) == 'C') { 
        setlocale(LC_MONETARY, ''); 
    } 
    $locale = localeconv(); 
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
    foreach ($matches as $fmatch) { 
        $value = floatval($number); 
        $flags = array( 
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
                           $match[1] : ' ', 
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
                           $match[0] : '+', 
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
        ); 
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
        $conversion = $fmatch[5]; 

        $positive = true; 
        if ($value < 0) { 
            $positive = false; 
            $value  *= -1; 
        } 
        $letter = $positive ? 'p' : 'n'; 

        $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
        switch (true) { 
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
                $prefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
                $suffix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
                $cprefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
                $csuffix = $signal; 
                break; 
            case $flags['usesignal'] == '(': 
            case $locale["{$letter}_sign_posn"] == 0: 
                $prefix = '('; 
                $suffix = ')'; 
                break; 
        } 
        if (!$flags['nosimbol']) { 
            $currency = $cprefix . 
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
                        $csuffix; 
        } else { 
            $currency = ''; 
        } 
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

        $value = number_format($value, $right, $locale['mon_decimal_point'], 
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
        $value = @explode($locale['mon_decimal_point'], $value); 

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) { 
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
        } 
        $value = implode($locale['mon_decimal_point'], $value); 
        if ($locale["{$letter}_cs_precedes"]) { 
            $value = $prefix . $currency . $space . $value . $suffix;
        } else { 
            $value = $prefix . $value . $space . $currency . $suffix;
        } 
        if ($width > 0) { 
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
                     STR_PAD_RIGHT : STR_PAD_LEFT); 
        } 

        $format = str_replace($fmatch[0], $value, $format); 
    } 
    return $format; 
} 
*/