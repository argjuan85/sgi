<?php
	class Componentes_model extends CI_Model
	{
		
		//dado un id de insumo devuelve true si se han realizado entregas al mismo
			public function verificar_componentes($cod)
		{   
			$this->load->database();
			$sql = 'select id from componentes where id_insumo= '.$cod;
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