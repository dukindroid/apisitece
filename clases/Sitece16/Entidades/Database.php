<?php
namespace Sitece16\Entidades;
/**
 * Conexion preconfigurada a servicio PDO
 * 
 * Devuelve una conexion a una base de datos MySQL en base a un archivo conf.ini
 * el cual contiene dos configuraciones diferentes a usar dependiendo si se 
 * esta ejecutando en modo desarrollo o en produccion. El archivo conf.ini debe
 * guardarse fuera de las carpetas accesibles mediante servicios web. Este 
 * archivo es parte de la libreria Sitece16.
 * 
 * @todo Establecer la ruta en parse_ini_file (linea 23) como constante estatica
 *       de la clase para facilitar el acceso al archivo .ini
 * 
 * @author Javier Gutiérrez Herrera 
 * 
 * @todo Establecer parametros para que la clase distinga entre ejecución modo
 * desarrollo y producción.
 * 
 * uses       \PDO Devuelve una conexion/objeto PDO 
 * return     PDO Regresa un objeto PDO inicializado
 */
use \PDO;

class Database extends PDO
{	
	/**
	 * Constructor por default de la clase. Instancia una conexion de datos
	 * PDO iniicializada por los parametros del archivo conf.ini. 
	 */
	function __construct() {
		//echo "Se instanció y se devuelve Database.";
		$conf_ini = parse_ini_file("dbconf.ini", true);
		//printf("<pre>%s</pre>",print_r($conf_ini['desarrollo'], true));
		extract($conf_ini['desarrollo'], EXTR_PREFIX_ALL, "DB");
		parent::__construct("mysql:host=$DB_host; dbname=$DB_name", $DB_user, $DB_pass);
	}
}

/*
	try {
		$this->conexion = new PDO("mysql:host={$DB_host};
								   dbname={$DB_name}",
								   $DB_user,
								   $DB_pass);
		$modo = (isset($_SERVER['WINDIR'])) ? 'desarrollo' : 'produccion';
		if ($modo == 'desarrollo')
		 	$this->conexion->setAttribute(PDO::ATTR_ERRMODE, 
		 		PDO::ERRMODE_WARNING);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
*/