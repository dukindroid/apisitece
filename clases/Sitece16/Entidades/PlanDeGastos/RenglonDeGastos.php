<?php
namespace Sitece16\Entidades\PlanDeGastos;

/**
 * Clase que corresponde a los datos contenidos en un renglón del plan
 * bimestral. Todo el renglón corresponde a un solo concepto del catálogo y 
 * cada avance de columna es representado por un GastoSemanal, en el cual los 
 * valores de getSemana() deben ser consecutivos. Implementa
 * 
 * Select sum(importe) as total,codigo,cheque from gastos 
 * where sucursal = '$txtsucursal' 
 * and semana ='$x' 
 * and ano ='$ano' 
 * group by codigo 
 * order by id  asc"
 */
class RenglonDeGastos implements ArrayAccess, Iterator {
	private $semanaDeInicio;
	private $semanaFinal;
	private $anio;
	private $sucursal;

	public function __constructor() {
		
	}
}