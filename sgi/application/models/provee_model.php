<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Provee_model extends CI_Model
	{
		
		
					//dado un id de pedido devuelve true si se hizo a alguno a proveedor
			public function verificar_provee($cod)
		{   
			$this->load->database();
			$sql = 'select id from Provee where id_proveedor= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
		
		
	}
?>