<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Pedidos_model extends CI_Model
	{
		
	
	
		
		public function consulta_estado_pedido($row)
		{   $id_pedido = $row->estado_pedido;
			$this->load->database();
			$consulta=$this->db->query('select * from Pedidos where id= '.$id_pedido);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
	
	
		
		//obtiene los datos de un pedido
		public function obtener_datos_pedido($id_pedido)
		{  
			$this->load->database();
			$sql = 'select * from pedidos where id= '.$id_pedido;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
		
		//dado un id devuelve el estado del Pedidos
			public function obtener_estado($cod)
		{   
			$this->load->database();
			$sql = 'select estado_pedido from pedidos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->estado_pedido;
			
		}
		
		//dado un id devuelve la sede del Pedido
			public function obtener_sede_pedido($cod)
		{   
			$this->load->database();
			$sql = 'select id_sede from pedidos where id='.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->id_sede;
			
		}
		
		//cambia de estado a un pedido dado un id
		public function genera_pedido($row)
		{   
			$this->load->database();
			$sql = 'update pedidos set estado_pedido= "Generado" where id='.$row;
			$consulta=$this->db->query($sql);
		
		}
		//cambia de estado a un pedido dado un id
		public function confirma_pedido($row)
		{   
			$this->load->database();
			$sql = 'update pedidos set estado_pedido= "En Proceso" where id='.$row;
			$consulta=$this->db->query($sql);
		
		}
			//cambia de estado a un pedido dado un id
		public function cierra_pedido($row)
		{   
			$this->load->database();
			$sql = 'update pedidos set estado_pedido= "Cerrado" where id='.$row;
			$consulta=$this->db->query($sql);
		
		}
			//cambia de estado a un pedido dado un id
		public function colocar_pendiente($row)
		{   
			$this->load->database();
			$sql = 'update pedidos set estado_pedido= "Pendiente" where id='.$row;
			$consulta=$this->db->query($sql);
		
		}
		 function obtener_numero_rows() {
        $query = $this->db->get('pedidos');
        return $query->last_row();
    	}
    	
    	function redirect_before_insert(){
        $row = obtener_numero_rows();
        return ($row->id);
    	}
		
				//dado un id de pedido devuelve el proveedor
		public function consulta_proveedor($cod_pedido)
		{
			$this->load->database();
			$sql = 'select id_proveedor from pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->id_proveedor;
		}
		
				//dado un id de pedido devuelve el proveedor
		public function consulta_tipo($cod_pedido)
		{
			$this->load->database();
			$sql = 'select tipo_pedido from pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->tipo_pedido;
		}
		
					//dado un id de pedido devuelve el numero
		public function obtener_nro($cod_pedido)
		{
			$this->load->database();
			$sql = 'select nro_pedido from pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->nro_pedido;
		}
		
					//dado un id de pedido devuelve la sede
		public function consulta_sede($cod_pedido)
		{
			$this->load->database();
			$sql = 'select id_sede from pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->id_sede;
		}
		
		//dado un id de proveedor devuelve los pedidos realizados entre fechas
			
		public function obtener_pedidos_proveedor($proveedor,$desde,$hasta, $sede)
		{
			$this->load->database();
			$sql = 'select nro_pedido, estado_pedido, nro_tk, fecha_pedido, observaciones, id_proveedor from pedidos where id_proveedor='.$proveedor.' and fecha_pedido>='.$desde.' and fecha_pedido<='.$hasta.' and (tipo_pedido= "E" or tipo_pedido= "N") and id_sede= '.$sede;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}
		
					//dado un id de pedido devuelve true si se hizo a alguno a proveedor
			public function verificar_pedidos($cod)
		{   
			$this->load->database();
			$sql = 'select id from Pedidos where id_proveedor= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
	
					//ultimo id insertado
			public function ultima_insercion()
		{   
			$this->load->database();
			$sql = 'select id from pedidos';
			$query=$this->db->query($sql);
        	$resu= $query->last_row();
        	
        	return $resu->id;
        	}
		
	}
?>