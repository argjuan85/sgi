<?php
class General_model extends CI_Model
	{
		
		public function __construct()
	{
		parent::__construct();
	}
	
	
		// valido si el usuer logueado tiene permiso para acceder a la pagina, recibo el id de la pagina y lo comparo con el permiso del user
	public function validapermiso ( $nivelpagina    )
	{ 
		$permiso_user = $this->session->userdata('permisosede');
		$a = $nivelpagina;
		IF ( ($nivelpagina & $permiso_user) == $nivelpagina )
		{
		return true;
		}
		else 
		{	
			$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No tiene permisos para acceder a esta página</div>');
			redirect('auth/inicio');
		return false;
		}
	}
	
	
	
		//para mostrar los tipos de pedido
		public function label_pedido($tipo)
		{  
		if ( ($tipo == "I") ||  ($tipo == "P") )
		return "Interno";
		else 
		return "Externo";
		}
    //////////////////////////////////////////////////// 
//Convierte fecha de 2010-10-08 a 08-10-2010
//////////////////////////////////////////////////// 

function cambia_sql_normal($fecha){ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    return $lafecha; 
}

function ou_sede_id ($ou)
{
		switch ($ou)
	{
		
		
		case "OU SJ":{
			$id = $this->sedes_model->obtener_id_sede("San Juan");
			break;
			}
		case "OU BA":{
			$id = $this->sedes_model->obtener_id_sede("Buenos Aires");
			break;
			}	
			
	}
	
	 return $id;
}


}
?>