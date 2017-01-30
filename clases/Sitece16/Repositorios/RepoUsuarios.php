<?php
namespace Sitece16\Repositorios;

class repoUsuarios extends Repositorio {
	public static function encontrarVarios(Array $cuales){
		// TODO: Escribir el query para encontrar varios alumnos
	}

	public static function encuentraUno($cual) {
		$tabla = ( mb_strlen($cual) == 4 ) ? 'exp_admin' : 'exp_alumnos';
		ORM::for_table($tabla)
			->where('usr', $cual)
			->find_one();

		// Cargar control de acceso para este usuario
		return new Usuario($info);
	}
}