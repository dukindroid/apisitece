<?php
namespace Sitece16\Entidades\PlanDeGastos;
/**
 * Un PlanAnual, que contiene un arreglo de seis PlanBimestral
 * 
 * Los inicios y términos de semana son calculados en base a la norma ISO-8601
 */

class PlanAnual {
	protected $anio;
	protected $sucursal;

	public function __construct() {
		// Instanciamos seis PlanBimestral
		for ($i=0; $i < 6; $i++) { 
			// Primer día del bimestre
			$inicioBim = $anio . '-' . ((2*$i)+1) . '-01'; 
			$timeInicio = strtotime('');
			// Primer dia del segundo mes del bimestre
			$finBim = $anio . '-' . (2*($i+1)) . '-01';
			// Último dia del segundo mes del bimestre
			$finBim = date("Y-m-t", strtotime($finBim));
			$planAnual[] = new PlanBimestral(date('F',555));
		}
	}
}
?>

