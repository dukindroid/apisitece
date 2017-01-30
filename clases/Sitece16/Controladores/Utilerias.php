<?php
namespace Sitece16\Modulos;

use Sitece16\Entidades\Repositorio;
/**
 * Funciones y rutinas que solo es necesario ejecutar una vez.
 */

 class Utilerias extends Repositorio {
 	public function __construct() {
		parent::__construct();
	}

 	public function generaHashes() {
 		\ORM::for_table('seguridad')->find_result_set()
		->set('hash', password_hash('Password', PASSWORD_DEFAULT))
		->save();
 	}
 }