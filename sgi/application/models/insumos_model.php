<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Insumos_model extends CI_Model
	{
		
			
		//dado un id de insumo devuelve el codigo
		public function obtener_codigo($cod)
		{   
			$this->load->database();
			$sql = 'select codigo_insumo from insumos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->codigo;
			
		}
		
			//dado un id de insumo devuelve el stock actual
		public function obtener_stock($sede ,$cod )
		{   
			$this->load->database();
			$sql = 'select stock_real from stock where id_insumo= '.$cod.' and id_sede= '.$sede;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->stock_real;
			
		}
	

		//dado un id de proveeodr devuelve los insumos
		public function obtener_insumos($cod)
		{   
			$this->load->database();
			$sql = 'select i.id, i.codigo_insumo from insumos i inner join provee p on p.id_insumo=i.id where p.id_proveedor= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->result_array();
			return $codigo;
			
		}
		
		//dado un id de sede devuelve los insumos
		public function obtener_insumos_sede($cod)
		{   
			$this->load->database();
			$sql = 'select i.id, i.codigo_insumo from insumos i inner join stock s on s.id_insumo=i.id where id_sede= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->result_array();
			return $codigo;
			
		}
		
		//dado un id de sede devuelve los insumos que tienen stock
		public function obtener_insumosenstock_sede($cod)
		{   
			$this->load->database();
			$sql = 'select i.id, i.codigo_insumo from insumos i inner join stock s on s.id_insumo=i.id where stock_real> "0" and id_sede= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->result_array();
			return $codigo;
			
		}
			
		
		// devuelve todos los insumos
		public function obtener_insumos_general()
		{   
			$this->load->database();
			$sql = 'select * from insumos';
			$consulta=$this->db->query($sql);
			$codigo=$consulta->result_array();
			return $codigo;
			
		}		
		//decrementa stock de un insumo
			public function decrementa_stock($id_insumo, $cantidad, $sede)
		{   
			$this->load->database();
			$stock_nuevo= $this->obtener_stock($sede, $id_insumo) - $cantidad;
			
		$sql = 'update stock set stock_real='.$stock_nuevo.' where id_insumo='.$id_insumo.' and id_sede='.$sede;
			$consulta=$this->db->query($sql);
		//$a= $sql;
		return;
		
		}
		
			//incrementa stock de un insumo
			public function incrementa_stock($id_insumo, $cantidad, $sede)
		{   
			$this->load->database();
			$stock_nuevo= $this->obtener_stock($sede, $id_insumo) + $cantidad;
			$sql = 'update stock set stock_real='.$stock_nuevo.' where id_insumo='.$id_insumo.' and id_sede='.$sede;
			$consulta=$this->db->query($sql);
			
		
		}
		
		
	}
?>