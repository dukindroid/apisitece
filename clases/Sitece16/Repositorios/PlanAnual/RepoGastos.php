<?php
namespace Sitece16\Repositorios\PlanDeGastos;
/**
 * Repositorio de Gastos 
 * 
 * Esta clase trabaja con la tabla Gastos. Puede devolver objetos GastoSemanal 
 * individuales o conceptos completos por un gasto en especifico. En general, 
 * implementa la consulta encontrada en planbimestral.php siguiente:
 * 
 * Select sum(importe) as total,codigo,cheque from gastos 
 * where sucursal = '$txtsucursal' 
 * and semana ='$x' 
 * and ano ='$ano' 
 * group by codigo 
 * order by id  asc
 * 
 * Con valores:
 * Select sum(importe) as total,codigo,cheque from gastos 
 * where sucursal = '30'and semana between 36 and 43 and ano ='2016' 
 * group by codigo order by id  asc;
 */
class repoGastos extends Repositorio
{
	/** 
	 * Constructor: Inicializa la conexion de datos 
	 * */
	public function __construct() {
		parent::__construct();
	}


}