<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class proveedores_model extends CI_Model
	{
		
				//dado un id de proveedor devuelve el nombre
		public function obtener_nombre($id)
		{
			$this->load->database();
			$sql = 'select nombre_proveedor from proveedores where id='.$id;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->nombre_proveedor;
		}
		
		
		
			//devuelve un arreglo con los nombres de los proveedores cargados en el sist
		public function obtener_proveedores()
		{   
			$this->load->database();
			$sql = 'select id, nombre_proveedor from proveedores';
			$query=$this->db->query($sql);
		$arrDatos['-1'] = 'Seleccione una opción';//asigno -1 por que en la validacion pregunto por id positivos en el dropdown
			 $arrDatos['0'] = 'Todos';// asigno 0 a todos para las consultas que generan el pedido
			 if ($query->num_rows() > 0) {
        // almacenamos en una matriz bidimensional
        foreach($query->result() as $row)
           $arrDatos[htmlspecialchars($row->id, ENT_QUOTES)] = 
htmlspecialchars($row->nombre_proveedor, ENT_QUOTES);

        $query->free_result();
       
		}
		 return $arrDatos;
		}
		
		
	}
?>