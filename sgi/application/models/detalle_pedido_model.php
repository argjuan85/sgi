<?php
	class Detalle_pedido_model extends CI_Model
	{
		
		//trae todos los insumos que estan con stock por debajo del minimo
		public function consulta_stockminimo($id_sede = "1")
		{
			$this->load->database();
			$consulta=$this->db->query('select * from Insumos i inner join stock s on s.id_insumo=i.id where stock_real < stock_minimo and id_sede= '.$id_sede);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
		
	//dado un id de proveeodr devuelve los insumoscon stock por debajo del minimo
		public function obtener_insumo_stockminimo($cod)
		{   
			$this->load->database();
			$sql = 'select i.id, i.codigo_insumo from insumos i inner join provee p on p.id_insumo=i.id inner join stock s on s.id_insumo=i.id where i.stock_real < i.stock_minimo and p.id_proveedor= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->result_array();
			return $codigo;
		}	
			
		// genera el detalle automatico para un pedido en particular en base a los insumos en stock minimo, las cantidades solicitadas son las especificadas en reorden
		public function generarpedido_stockminimo($id_sede = "1", $cod_pedido )
		{
	
	//insert_batch codeigniter
	// del pedido obtengo el proveedor 
	$this->load->database();
	$sql = 'SELECT p.id, p.nombre_proveedor  FROM proveedores p inner join  pedidos s on s.id_proveedor=p.id where s.id='.$cod_pedido;
	$query = $this->db->query($sql);
	//$resu = $query->result_array();
		foreach($query->result() as $fila)
		{
			//MODIFICAR!!!
			
			/* codigo viejo */
		//recogemos todos los stock minimos
		$sql = 'select i.id, s.cant_reorden from insumos i inner join provee p on p.id_insumo=i.id inner join stock s on s.id_insumo= i.id where s.stock_real < s.stock_minimo and p.id_proveedor= '.$fila->id;
		
	
		$query = $this->db->query($sql);
	
	/*codigo viejo*/
	//$query = obtener_insumo_stockminimo($fila->id)
		}
		//los recorremos y los guardamos en un array 
		foreach($query->result() as $fila)
		{
			$data[] = array(			
				'id_pedido'		=>		$cod_pedido,
				'id_insumo'		=>		$fila->id,
				'cantidad_pedida'			=>		$fila->cant_reorden,
				
				);
		}
		
		if (isset($data))
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			{$this->db->insert_batch('detalle_pedido', $data); }
			
	
	
		}
		
		/*  modificar ya que cambi el modelo */
		
			//trae el detalle de un pedido en particular
		public function consulta_detallepedido($cod_pedido)
		{
			$this->load->database();
			$sql = 'select d.id, d.id_pedido, d.id_insumo, i.codigo_insumo, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where id_pedido='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			/*if ($consulta->num_rows() == NULL)
			{
				return "-1";
			}
			else
			{*/
			return $nombresx;
			//}
		}

		public function genera_ingreso($cod_pedido, $tipo_recep, $numero_recep)
		{
		
		//obtento el detalle del pedido generado	
		$this->load->database();
		$sql = 'select d.id, d.id_pedido, d.id_insumo, i.codigo_insumo, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where id_pedido='.$cod_pedido;
			
		$query=$this->db->query($sql);
		
		
		//los recorremos y los guardamos en un array 
		foreach($query->result() as $fila)
		{
			$data[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$fila->cantidad_pedida,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
				//la fecha no se coloca ya que la toma automaticamente en la inserci´n a la base
				
				);
				//este array se crea para chequear la existencia o no del registro de stock en la sede
					$check[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$fila->cantidad_pedida,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
				
				
				);
		}
		
		foreach ($check as $fila)
		{
			$a=$this->consulta_idinsumo($fila['id_detalle_pedido']);
			$b=$this->session->userdata('sede_filtro');
			
			$c=$this->stock_model->verifica_stock1($a,$b);
			//si no hay registro de stock lo creo (previo a la actualizacion del stock)
			if ($c)
			{
				$this->stock_model->crea_stock($a,$b);
			}
			 // incremento de stock
	     $this->stock_model->incrementar_stockx1($a,$b,$fila['cantidad_recepcion']);
		}
		if (isset($data))
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			{$this->db->insert_batch('recepciones', $data); }
		
		
		
		
		
		
		}
		
		public function recepcion_total($cod_pedido, $tipo_recep, $numero_recep)
		{
		
		//obtento el detalle del pedido generado	
		$this->load->database();
		$sql = 'select d.id, d.id_pedido, d.id_insumo, i.codigo_insumo, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where id_pedido='.$cod_pedido;
			
		$query=$this->db->query($sql);
		
		
		//los recorremos y los guardamos en un array 
		foreach($query->result() as $fila)
		{
			
			//si ya hay una linea de recepcion cargada, no cargo la cantidad pedida en el detalle si no la resta entre lo cargado hasta el momento (recordar que puede haber mas de una recepcion parcial) y la recepcion 
			if($this->recepcion_model->verifica_recepciones($fila->id))
			{
				//obtengo todas las recepciones cargadas para un detalle de pedido
				$recepciones_previas=$this->recepcion_model->obtener_recepciones2($fila->id);
				$cantidad_cargada = "0";
				$bandera = false;
				foreach($recepciones_previas as $aux)
		{
			//calculo cantidad recibida de momento
			$parcial = $aux['cantidad_recepcion'];
			$cantidad_cargada = $cantidad_cargada + $parcial;
			//esto controla que no este haciendo una recepcion total luego de haber echo una carga bajo el mismo numero de recepcion. ( es decir la linea de recepcion generada al final debe ser modificada en lugar de insertar una nueva)
			if ($aux['numero'] == $numero_recep)
			{$bandera =	true;
			$id_recepcion = $aux['id'];
			//en este caso al reemplazar una de las lineas no puedo restar la sumatoria ya que no es una linea nueva
			$cantidad_cargada = $cantidad_cargada - $parcial;
			}
		}
		
			$cantidadfaltante = $fila->cantidad_pedida - $cantidad_cargada;
			// si cantidad faltante es 0 o menos quiere decir que se recepciono todo por ende no cargo registro
			if($cantidadfaltante > "0")
			{
				
				if ($bandera)
				{
				$data2[] = array(
				'id'		=>		$id_recepcion,//obtengo id para el update batch			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$cantidadfaltante,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
				//la fecha no se coloca ya que la toma automaticamente en la insercion a la base
				
				);
					//este array se crea para chequear la existencia o no del registro de stock en la sede
					$check[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$cantidad_cargada,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
					);	
			
				}
				else
				//en este caso hay una recepcion parcial previa pero con un numero de recepcion distinto
				{
									
				$data[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$cantidadfaltante,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
				//la fecha no se coloca ya que la toma automaticamente en la inserci´n a la base
				
				);
				
					//este array se crea para chequear la existencia o no del registro de stock en la sede
					$check[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$cantidadfaltante,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
					);	
				}
			
					
			}
			
				
			}
			else //no hay linea de recepcion
			{$data[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$fila->cantidad_pedida,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
				//la fecha no se coloca ya que la toma automaticamente en la inserci´n a la base
				
				);
				//este array se crea para chequear la existencia o no del registro de stock en la sede
					$check[] = array(			
				'id_detalle_pedido'		=>		$fila->id,
				'cantidad_recepcion'		=>		$fila->cantidad_pedida,
				'tipo_recepcion'			=>		$tipo_recep,
				'numero'		=>		$numero_recep,
			
				
				);
				}
		}
		// si el array $check y $data2 no esta seteado es por que no hay nada que devolver (ya que si no al menos hay una linea nueva o por modificar)
		If ((isset($check)) or (isset($data2)))
		{foreach ($check as $fila)
		{
			$a=$this->consulta_idinsumo($fila['id_detalle_pedido']);
			$b=$this->session->userdata('sede_filtro');
			
			$c=$this->stock_model->verifica_stock1($a,$b);
			//si no hay registro de stock lo creo (previo a la actualizacion del stock)
			if ($c)
			{
				$this->stock_model->crea_stock($a,$b);
			}
			 // incremento de stock
	     $this->stock_model->incrementar_stockx1($a,$b,$fila['cantidad_recepcion']);
		}
		if (isset($data))
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			{$this->db->insert_batch('recepciones', $data); }
		if (isset($data2))
			//si corresponde modifico lineas previamente cargadas
			{$this->db->update_batch('recepciones', $data2, 'id'); }
		
		}
				
		
		}
		
				//devuelve el estado de un pedido dado el id
		public function consulta_estadopedido($cod_pedido)
		{
			$this->load->database();
			$sql = 'select estado_pedido from pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->estado_pedido;
		}
		
					//devuelve el id de insumo asociado a un id de pedido
		public function consulta_idinsumo($cod_pedido)
		{
			$this->load->database();
			$sql = 'select id_insumo from detalle_pedido where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->id_insumo;
		}
		
		
		//devuelve el id de un pedido dato un id de Detalle_pedido
				
		public function consulta_idpedido($id_sede = "1",$cod_pedido)
		{
			$this->load->database();
			$sql = 'select id_pedido from detalle_pedidos where id='.$cod_pedido;
			$consulta=$this->db->query($sql);
			$estado=$consulta->row();
			return $estado->id_pedido;
		}
		
		// genera el detalle automatico para un pedido en particular en base a los consumos realizados en un rango de fechas
		public function generarpedido_consumo($cod_pedido, $desde, $hasta )
		{
	
	
	// del pedido obtengo el proveedor 
	$this->load->database();
	$sql = 'SELECT p.id, p.nombre_proveedor  FROM proveedores p inner join  pedidos s on s.id_proveedor=p.id where s.id='.$cod_pedido;
	$query = $this->db->query($sql);
	//$resu = $query->result_array();
		foreach($query->result() as $fila)
		{
			
		//recogemos todos los insumos del proveedor
		$sql = 'select i.id from insumos i inner join provee p on p.id_insumo=i.id where  p.id_proveedor= '.$fila->id;
		$query = $this->db->query($sql);

		}
		//los recorremos y los guardamos en un array 
		foreach($query->result() as $fila)
		{
			//selecciona las entregas de cada  insumo
			$filtro = $this->session->userdata('sede_filtro');
			
			$sql = 'select SUM(e.cantidad) as cantidad, i.id from insumos i inner join entregas e on e.id_insumo=i.id where e.id_sede='.$filtro.' and i.id='.$fila->id.' and fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' GROUP BY i.id';
		$query = $this->db->query($sql);
			
			foreach($query->result() as $fila1)
		{
			
			$data[] = array(			
				'id_pedido'		=>		$cod_pedido,
				'id_insumo'		=>		$fila1->id,
				'cantidad_pedida'			=>		$fila1->cantidad,
				
				);
				}
		}
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			if (isset($data))
			{$this->db->insert_batch('detalle_pedido', $data); 
			return "1";}
			else
			{
				return "-1";
			}
	
	
		}
		
	
		// genera el detalle automatico para un pedido (interno) en particular en base a los consumos realizados en un rango de fechas
		// test revisar check 
		public function generarpedido_consumo2($cod_pedido, $desde, $hasta, $id_proveedor )
		{
		
	
	$this->load->database();

			
		//recogemos todos los insumos del proveedor
		if ($id_proveedor == "0")
		{
		$sql = 'select i.id from insumos i inner join provee p on p.id_insumo=i.id';
		}
		else
		{
		$sql = 'select i.id from insumos i inner join provee p on p.id_insumo=i.id where  p.id_proveedor= '.$id_proveedor;
		}
		$query = $this->db->query($sql);

		
		//los recorremos y los guardamos en un array 
		foreach($query->result() as $fila)
		{
			//selecciona las entregas de cada  insumo
			$filtro = $this->session->userdata('sede_filtro');
			
			$sql = 'select SUM(e.cantidad) as cantidad, i.id from insumos i inner join entregas e on e.id_insumo=i.id where e.id_sede='.$filtro.' and i.id='.$fila->id.' and fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' GROUP BY i.id';
		$query = $this->db->query($sql);
			
			foreach($query->result() as $fila1)
		{
			
			$data[] = array(			
				'id_pedido'		=>		$cod_pedido,
				'id_insumo'		=>		$fila1->id,
				'cantidad_pedida'			=>		$fila1->cantidad,
				
				);
				}
		}
			//fuera del bucle hacemos la insercción de los datos con insert_batch	
			if (isset($data))
			{$this->db->insert_batch('detalle_pedido', $data); 
			return "1";}
			else
			{
				return "-1";
			}
	
	
		}
	
	
				//dado un id de insumo devuelve true si hay algun detalle generado con el mismo
							
			public function verificar_detalle($cod)
		{   
			$this->load->database();
			$sql = 'select id from detalle_pedido where id_insumo= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
		
				//cambia cantidad de un detalle de pedido
		public function actualizar_detalle($value,$row)
		{   
			$this->load->database();
			$sql = "update detalle_pedido set cantidad_pedida= ". "'" .$value."' ";
	        $sql = $sql . " where id= " ."'".$row."'";
			$consulta=$this->db->query($sql);
			if (!$consulta)
			return "-1";
			else
			return $value;
		
		}
		
					//cambia cantidad de un detalle de pedido
		public function eliminar_detalle($row)
		{   
			$this->load->database();
			$sql = "delete from detalle_pedido where id="."'".$row."'";
			$consulta=$this->db->query($sql);
			if (!$consulta)
			return "-1";
			else
			return "1";
		
		}
		
		
		
	}
?>