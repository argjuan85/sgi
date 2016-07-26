<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Parametros_model extends CI_Model
	{
		
			
		//dado un nombre  y valor  de param devuelve el id
		public function obtener_id_parametro($nombre,$valor)
		{   
			$this->load->database();
		$sql= 	'select id from Parametros where nombre_parametro= '.'"'.$nombre.'"'.' and valor= '.'"'.$valor.'"';
		$query = $this->db->query($sql);
			
			$nombre=$query->row();
			return $nombre->id;
			
		}



		
				//dado un nombre  de param devuelve el id
		public function obtener_id_parametro_nombre($sede,$nombre)
		{   
			$this->load->database();
			
		$query = $this->db->query('select id from Parametros where nombre_parametro= '.'"'.$nombre.'"'.' and id_sede= '.'"'.$sede.'"');
			
			$nombre=$query->row();
			return $nombre->id;
			
		}
		
			//dado un id de param devuelve el valor
		public function obtener_parametro($id)
		{   
			$this->load->database();
			
		$query = $this->db->query('select valor from Parametros where id= '.'"'.$id.'"');
			
			$nombre=$query->row();
			return $nombre->valor;
			
		}
		
		
		//modifica valor parametro dado un id
				
		public function modifica_parametro($id,$valor)
		{   
			$this->load->database();
			$sql = "update Parametros set valor= ". "'" .$valor."' ";
			$sql = $sql . " where id= " ."'".$id."'";
			$query = $this->db->query($sql);
			
			/*$nombre=$query->row();
			return $nombre->valor;*/
			
		}
		
		//obtiene los grupos de ad para el sistema
			public function obtener_grupos()
		{   
			$this->load->database();
			$sql = 'select nombre_parametro, valor from Parametros where nombre_parametro="grupo_ad"';
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}
		
		//dado el identificador de la sede en un grupo devuelve el nombre completp
				public function sigla_sede($sede)
		{   
			switch ($sede)
			{
				case "APSJ":{
					$nombresx= "San Juan";
					break;
				}
			}
			return $nombresx;
		}
		
			//dado el OU de la sede en un grupo devuelve el nombre completp
				public function ou_sede($sede)
		{   
			switch ($sede)
			{
				case "OU SJ":{
					$nombresx= "San Juan";
					break;
				}
			}
			return $nombresx;
		}
		
		//dado el nombre de la sede en un grupo devuelve el nombre del identif
				public function sede_sigla($sede)
		{   
			switch ($sede)
			{
				case "San Juan":{
					$nombresx= "APSJ";
					break;
				}
			}
			return $nombresx;
		}
		
		//obtiene los grupos de ad para una sede 
			public function obtener_grupos_sede($sede)
		{   
			$grupos = $this->obtener_grupos();//ok
			foreach ($grupos as $grupo)
			{
				//$borrar = $this->auth_model->obtener_sede_grupo($grupo['valor']);
				if ($this->auth_model->obtener_sede_grupo($grupo['valor']) == $sede)	
				//if ( "3" == $sede)
				{
					$xgrupo[] = $grupo['valor'];//armo array
				}
				
			}
			
			return $xgrupo;
		}
		
	
		
	
		
					//dado un nombre  de grupo de ad devuelve el nivel de permiso asociado
		/*public function obtener_nivel_grupo($grupo)
		{   
			$this->load->database();
			$sql = 'select valor from Parametros where nombre_parametro= '.'"'.$grupo.'"';
			$query = $this->db->query($sql);
			$nombre=$query->row();
			return $nombre->valor;
			
		}*/
		
		
	}
?>