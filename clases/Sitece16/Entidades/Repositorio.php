<?php
namespace Sitece16\Entidades;

use Sitece16\Entidades\Database;

/**
 * Clase abstracta Repositorio
 * 
 * Configura la clase Modelo de Objetos Relacionales para conectarse mediante
 * PDO a la base de datos MySQL.
 */
abstract class Repositorio {
	/**
	 * Constructor por default de la clase. Hace una llamada estática a ORM
	 * mediante la cual establece la conexión entre el Modelo y la base MySQL.
	 */
	public function __construct() {
		\ORM::set_db(new Database());
	}
}