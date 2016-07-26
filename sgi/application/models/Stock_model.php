<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Stock_model extends CI_Model
	{
		
				//dado un id de insumo y sede  devuelve la ant en stock 
		public function obtener_stock($cod,$sede)
		{   
			$this->load->database();
			$sql = 'select * from stock where id_insumo='.$cod.' and id_sede='.$sede;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->stock_real;
			
				
			
		}
		
		//crea un registro en la tabla stock, para los casos que no estaba previamente cargado
		public function crea_stock($id, $sede)
		{   
			$this->load->database();
			$sql = "INSERT INTO stock (id_insumo, stock_real, habilitado, id_sede) VALUES (".$id.", '0', '1', ".$sede." )";
			$this->db->query($sql);
			
		}
		
			//trae todos los insumos que estan con stock por debajo del minimo
		public function obtener_cantstockminimo($id_sede = "1")
		{
			if (isset($id_sede))
			{$this->load->database();
			$consulta=$this->db->query('select * from Insumos i inner join stock s on s.id_insumo=i.id where stock_real < stock_minimo and id_sede= '.$id_sede);
			$nombresx=$consulta->num_rows();
			return $nombresx;
			}
			else 
			return "-1";
		}
			
					//actualiza stock de insumo dado cod , sede y cantidad nueva
		public function actualiza_stock($cod, $sede, $cantidad)
		{   
			$this->load->database();
			$sql = 'update stock set stock_real='.$cantidad.' where id_insumo='.$cod.' and id_sede='.$sede;
			$consulta=$this->db->query($sql);
		
		}
			
		//verifica si hay stock de un insumo en determinada sede
		public function verifica_stock($cod, $sede)
		{   
			$this->load->database();
			$sql = 'select stock_real from stock where id_insumo= '.$cod.' and id_sede= '.$sede;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			if ($nombre == NULL)
			{
				return false;
			}
			elseif ($nombre->stock_real > "0")
			{
				return true;
				}
				
			else
			{
						 
				return false;
			}
			
		}
		
			//verifica si hay registro de stock generado en la tabla
		public function verifica_stock1($cod, $sede)
		{   
			$this->load->database();
			$sql = 'select stock_real from stock where id_insumo= '.$cod.' and id_sede= '.$sede;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			if ($nombre == NULL)
			{
				return true;
			}
			else
			{
				return false;
			}
				
			
			
		}
		
		//dado un pedido , realiza el descuento del stock para una sede de todo el detalle
	public function descuenta_stock($cod_pedido, $sede)
		{   	
		$this->load->database();
		
		$sql = 'select s.id, d.id_pedido, d.id_insumo, i.codigo_insumo, d.cantidad_pedida, s.stock_real from detalle_pedido d inner join insumos i on i.id=d.id_insumo inner join stock s on s.id_insumo=i.id where id_pedido='.$cod_pedido.' and s.id_sede='.$sede;
		
		
			$query=$this->db->query($sql);
			
			foreach($query->result() as $fila)
		{
			$nuevo_stock = $fila->stock_real -  $fila->cantidad_pedida;
			$data[] = array(			
				'id'		=>		$fila->id,
				'stock_real'		=>		$nuevo_stock,
					
				);
		}
		
		if (isset($data))
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			{$this->db->update_batch('stock', $data, 'id');  }
			
	   }
	   
	 //dado un codigo y una sede incremente la cantidad   		
	public function incrementar_stockx1($cod, $sede, $cantidad)
		{   	
		$stockprevio = $this->obtener_stock($cod, $sede);
		$nuevo_stock = $stockprevio +  $cantidad;
		$this->actualiza_stock($cod, $sede, $nuevo_stock);
			
		}
		
		 //dado un codigo y una sede incremente la cantidad   		
	public function decrementar_stockx1($cod, $sede, $cantidad)
		{   	
		$stockprevio = $this->obtener_stock($cod, $sede);
		$nuevo_stock = $stockprevio -  $cantidad;
		$this->actualiza_stock($cod, $sede, $nuevo_stock);
			
		}
			
	   		
		//dado un pedido , realiza el incremento del stock para una sede de todo el detalle
		public function incrementa_stock($cod_pedido, $sede)
		{   	
		$this->load->database();
		
		$sql = 'select s.id, d.id_pedido, d.id_insumo, i.codigo_insumo, d.cantidad_pedida, s.stock_real from detalle_pedido d inner join insumos i on i.id=d.id_insumo inner join stock s on s.id_insumo=i.id where id_pedido='.$cod_pedido.' and s.id_sede='.$sede;
		
		
			$query=$this->db->query($sql);
			
			foreach($query->result() as $fila)
		{
			$nuevo_stock = $fila->stock_real +  $fila->cantidad_pedida;
			$data[] = array(			
				'id'		=>		$fila->id,
				'stock_real'		=>		$nuevo_stock,
					
				);
		}
		
		if (isset($data))
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			{$this->db->update_batch('stock', $data, 'id');  }
			
	   }
		
	}
?>