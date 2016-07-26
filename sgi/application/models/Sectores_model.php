<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Sectores_model extends CI_Model
	{
		
			
		//dado un id de insumo devuelve el codigo
		public function obtener_nombre($cod)
		{   
			$this->load->database();
			$sql = 'select nombre_sector from Sectores where id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			return $nombre->nombre_sector;
			
		}
		
					//devuelve un arreglo con los nombres de los sectores cargados en el sist
		public function obtener_sectores()
		{   
			$this->load->database();
			$sql = 'select id, nombre_sector from sectores';
			$query=$this->db->query($sql);
				$arrDatos['-1'] = 'Seleccione una opción';//asigno -1 por que en la validacion pregunto por id positivos en el dropdown
	
			 if ($query->num_rows() > 0) {
        // almacenamos en una matriz bidimensional
        foreach($query->result() as $row)
           $arrDatos[htmlspecialchars($row->id, ENT_QUOTES)] = 
htmlspecialchars($row->nombre_sector, ENT_QUOTES);

        $query->free_result();
       
		}
		 return $arrDatos;
		}
		
	}
?>