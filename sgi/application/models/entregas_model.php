<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Entregas_model extends CI_Model
	{
		
		public function fillAdvertisements()
                
{
	$this->load->database();
	$query = $this->db->get_where('advertisement');
	return $query->result();
}
		
		/*
		 select SUM(e.cantidad) as cantidad, i.id, i.codigo_insumo from insumos i inner join entregas e on e.id_insumo=i.id where e.id_equipo='1' and fecha_entrega>='2015-11-18 00:02:54	' and fecha_entrega<='2015-11-20 22:18:06' GROUP BY i.id
		
		
		*/
		
		
				//dado un id de equipo devuelve true si se han realizado entregas al mismo
		public function verificar_entregas($cod)
		{   
			$this->load->database();
			$sql = 'select id from Entregas where id_equipo= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
				//dado un id de insumo devuelve true si se han realizado entregas al mismo
			public function verificar_entregas2($cod)
		{   
			$this->load->database();
			$sql = 'select id from Entregas where id_insumo= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
		
						//dado un id de sector devuelve true si se han realizado entregas al mismo
			public function verificar_entregas3($cod)
		{   
			$this->load->database();
			$sql = 'select id from Entregas where id_sector= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
	
		
		
		//dado un rango de fechas obtiene las entregas realizadas para un Equipos
	
		public function obtener_consumo_equipo($cod,$desde,$hasta)
		{   
			$this->load->database();
			$sql = 'select SUM(e.cantidad) as cantidad, i.id, i.codigo_insumo from insumos i inner join entregas e on e.id_insumo=i.id where e.id_equipo='.$cod.' and fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' GROUP BY i.id';
			//$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}
		
		//dado un rango de fechas obtiene las entregas realizadas para un Equipos
	
		public function obtener_consumo_insumos($desde,$hasta, $sede)
		{   
			$this->load->database();
			$sql = 'select SUM(e.cantidad) as cantidad, i.id, i.codigo_insumo from insumos i inner join entregas e on e.id_insumo=i.id where e.id_sede='.$sede.' and fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' GROUP BY i.id';
			
			//$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}
		
			//dado un rango de fechas obtiene las entregas realizadas para un sector
	
		public function obtener_consumo_sectores($desde,$hasta,$sector)
		{   
			$this->load->database();
			$sql = 'select SUM(e.cantidad) as cantidad, i.id, i.codigo_insumo from insumos i inner join entregas e on e.id_insumo=i.id where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and e.id_sector='.$sector.' GROUP BY i.id';
			
			//$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}
		
		
		//dado un rango de fechas, obtiene el contador primer entrega realizada para un equipo determinado
		public function obtener_primer_entrega($cod_e,$cod_i,$desde,$hasta)
		{   
			$this->load->database();
			 $sql = 'select * from Entregas where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and id_equipo= '.$cod_e.' and id_insumo= '.$cod_i.'  order by fecha_entrega asc limit 1';
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->contador;
		}
		
		//dado un rango de fechas, obtiene el contador de la ultima entrega realizada para un equipo e insumo determinado
		public function obtener_ultima_entrega($cod_e,$cod_i,$desde,$hasta)
		{   
			$this->load->database();
		    $sql = 'select * from Entregas where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and id_equipo= '.$cod_e.' and id_insumo= '.$cod_i.'  order by fecha_entrega desc limit 1';
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->contador;
		}	
		
		//calcula el rendimiento de los insumos consumidos por un equipo en particular dentro de un rango de fechas
			public function obtener_rendimiento($cod_e,$cod_i,$desde,$hasta)
		{   
			
			
			$rendimiento = $this->obtener_ultima_entrega($cod_e,$cod_i,$desde,$hasta) - $this->obtener_primer_entrega($cod_e,$cod_i,$desde,$hasta);
			return $rendimiento;
		}
		
		//cuenta la cantidad de insumos rechazados para un equipo y un tipo de insumo en particular en un rango de fechas
			public function calcula_rechazados($cod_e,$cod_i,$desde,$hasta)
		{   
			$this->load->database();
		    $sql = 'select * from Entregas where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and id_equipo= '.$cod_e.' and id_insumo= '.$cod_i.' and estado= "Entregada"' ;
			$consulta=$this->db->query($sql);
			return $consulta->num_rows();
		}	
		
		//REVISAR ID ESTADO, NO VA QUEDAR ASI DEFINITIVAMENTE
			//cuenta la cantidad de insumos rechazados para un equipo y un tipo de insumo en particular en un rango de fechas
			public function calcula_rechazado_insumo($cod_i,$desde,$hasta)
		{   
			$this->load->database();
		    $sql = 'select * from Entregas where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and id_insumo= '.$cod_i.' and estado= "Rechazada"' ;
			$consulta=$this->db->query($sql);
			return $consulta->num_rows();
		}	
		
		
				public function calcula_rechazados_entrefechas($desde,$hasta,$sede)
		{   
			$this->load->database();
			$sql = 'select SUM(e.cantidad) as cantidad, i.id, i.codigo_insumo from insumos i inner join entregas e on e.id_insumo=i.id where fecha_entrega>='.$desde.' and fecha_entrega<='.$hasta.' and e.estado=  "Rechazada" and e.id_sede='.$sede.' GROUP BY i.id';
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=$consulta->result_array();
			}
			else
			{$nombresx = "-1";}
			return $nombresx;
		}	
		
		//dado un id de insumo devuelve las equipos asociadas
		public function obtener_equipos($cod)
		{   
			$this->load->database();
			$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}

		//dado un id de insumo devuelve las equipos asociadas
		public function obtener_equipos_validos($cod)
		{   
			$this->load->database();
			$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod.' and estado<>'.$this->parametros_model->obtener_id_parametro("estado equipo","deshabilitado");
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}

		
				//dado un id de equipo devuelve los insumos asociados
		public function obtener_insumos($cod)
		{   
			$this->load->database();
			$sql = 'select i.id, i.codigo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where e.id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
		
		//dado un id de equipo devuelve el sector
		public function obtener_sector($cod)
		{   
			$this->load->database();
			$sql = 'select id_sector from equipos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->id_sector;
			
		}
		
		//dado un id de entrega obtiene el sector afectado
		public function obtener_sector_entrega($cod)
		{   
			$this->load->database();
			$sql = 'select id_sector from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->id_sector;
			
		}
		//dado un id de entrega obtiene el id_insumo entregado
		public function obtener_idinsumo($cod)
		{   
			$this->load->database();
			$sql = 'select id_insumo from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->id_insumo;
			
		}
		
			//dado un id de entrega obtiene el equipo afectado
		public function obtener_equipo($cod)
		{   
			$this->load->database();
			$sql = 'select id_equipo from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->id_equipo;
			
		}
		
				//dado un id de entrega obtiene la cantidad entregada
		public function obtener_cantidad($cod)
		{   
			$this->load->database();
			$sql = 'select cantidad from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->cantidad;
			
		}
		
		  	//dado un id de entrega obtiene el insumo entregado
		public function obtener_insumo($cod)
		{   
			$this->load->database();
			$sql = 'select id_insumo from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->id_insumo;
			
		}
		
		
				//dado un id de entrega obtiene nombre de la equipo afectada
		/*public function obtener_codigoinsumo($cod)
		{   
			$this->load->database();
			$sql = 'select id_equipo from entregas where id= '.$cod;
			$consulta=$this->db->query($sql);
			$sector=$consulta->row();
			return $sector->codigo;
			
		}*/
		
	}
?>