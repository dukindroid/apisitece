<?php
namespace Sitece16\Repositorios

/** 
 *  
 */
class repoPermisos extends Repositorio
{
	public function __construct(Contenedor $depend){
		$this->db = $depend->db();
		$this->repo = $depend->repositorio();
	}

	// Recibe el tipo de usuario y devuelve un arreglo
	// con sus permisos
	public function permDelRol() {
		// Query necesario:
		// SELECT p.titulo FROM rbac_permisos as p 
		// inner join rbac_permisos_rol as rl
		// on rl.permisoid = p.id 
		// where rl.rolid = $_SESSION['tipo_usuario']
		$this->repo->cargar('rbac_permisos')
			 ->columnas('titulo')
			 ->de('rbac_permisos')
			 ->union('rbac_permisos_rol')
			 ->en('rl.permisoid = p.id')
			 ->donde('rl.rolid =' . $_SESSION['tipo_usuario']);
		$info = $this->repo->ejecutar();
		return $info->fetch(PDO::FETCH_ASSOC);
	}
}
