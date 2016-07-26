<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class sedes_model extends CI_Model
	{
		
			
		//dado un id de insumo devuelve el codigo
		public function obtener_sedes()
		{   
			$this->load->database();
			$sql = 'select * from sedes';
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
			
		}
		
		public function obtener_nombre($cod)
		{   
			$this->load->database();
			$sql = 'select nombre_sede from sedes where id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			return $nombre->nombre_sede;
			
		}
		
			public function obtener_id_sede($cod)
		{   
			$this->load->database();
			$sql = 'select id from sedes where nombre_sede= '.'"'.$cod.'"';
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			return $nombre->id;
			
		}
		
		
		
			public function obtener_nivel_sede($cod)
		{   
			$this->load->database();
			$sql = 'select nivel from sedes where id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			return $nombre->nivel;
			
		}
		
					//dado un id de sede devuelve true si tiene alguna asociacion
			public function verificar_sede($cod)
		{   
			$this->load->database();
			$sql = 'select id from equipos where id_sede= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0)
			{
			$nombresx=true;
			}
			else
				{
				$sql = 'select id from pedidos where id_sede= '.$cod;
				$consulta=$this->db->query($sql);
			 	if ($consulta->num_rows() > 0) 
			 	{
				$nombresx=true;
				}
				else
				{
					$sql = 'select id from sectores where id_sede= '.$cod;
					$consulta=$this->db->query($sql);
			 		if ($consulta->num_rows() > 0) 
			 		{
					$nombresx=true;
					}
					else
					{
					$nombresx = false;
					}
				}
				
				}
				return $nombresx;
		}
		
		
		
	}
?>