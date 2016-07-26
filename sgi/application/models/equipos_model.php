<?php

//seria importante chequear que las consultas esten ok en alguna parte para evitar errores no amigables en el sitio
	class Equipos_model extends CI_Model
	{
		
		public function __construct()
	{
		parent::__construct();
	}
	
	public function equipos()
	{
		$this->db->order_by('codigo_equipo','asc');
		$equipo = $this->db->get('equipos');
		if($equipo->num_rows()>0)
		{
			return $equipo->result();
		}
	}
	
	public function insumos1()
	{
		$this->db->order_by('codigo_insumo','asc');
		$equipo = $this->db->get('insumos');
		if($equipo->num_rows()>0)
		{
			return $equipo->result();
		}
	}
	
	
	
	
	public function insumos($equipo)
	{   
	$this->load->database();
			$sql = 'select i.id, i.codigo_insumo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos im on im.id=c.id_equipo where im.id= '.$equipo;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
			
	
		}
		
		
			public function obtener_codigo($cod)
		{   
			$this->load->database();
			$sql = 'select codigo_equipo from equipos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombre=$consulta->row();
			return $nombre->codigo_equipo;
			
		}
		
		//dado un id de insumo devuelve las equipos asociadas
		public function equipos1($cod)
		{   
			$this->load->database();
			$sql = 'select e.id, e.codigo_equipo from Insumos i inner join componentes c on c.id_insumo=i.id inner join equipos e on e.id=c.id_equipo where i.id= '.$cod;
			$consulta=$this->db->query($sql);
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
	

		//dado un id de sede devuelve los equipos asociadas
		public function obtener_equipos_sede($cod)
		{   
			$this->load->database();
			$sql = 'select id, codigo_equipo from equipos where id_sede= '.$cod;
			$query=$this->db->query($sql);
		$arrDatos['-1'] = 'Seleccione una opción';//asigno -1 por que en la validacion pregunto por id positivos en el dropdown
		        
			 if ($query->num_rows() > 0) {
        // almacenamos en una matriz bidimensional
        foreach($query->result() as $row)
           $arrDatos[htmlspecialchars($row->id, ENT_QUOTES)] = 
htmlspecialchars($row->codigo_equipo, ENT_QUOTES);

        $query->free_result();
        
		}
		return $arrDatos;
		}

		//dado un id de insumo devuelve el codigo
		public function obtener_sector($cod)
		{   
			$this->load->database();
			$sql = 'select codigo_insumo from insumos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->codigo;
			
		}
		
			//dado un id de equipo devuelve la sede
		public function obtener_sede_equipo($cod)
		{   
			$this->load->database();
			$sql = 'select id_sede from equipos where id= '.$cod;
			$consulta=$this->db->query($sql);
			$codigo=$consulta->row();
			return $codigo->id_sede;
			
		}
		
			//dado un id de equipo devuelve true si se ha asignado un equipo al sector
			public function verificar_equipos($cod)
		{   
			$this->load->database();
			$sql = 'select id from equipos where id_sector= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
		

			//dado un id de equipo devuelve true si se ha asignado un proveedor al mismo
			public function verificar_equipos2($cod)
		{   
			$this->load->database();
			$sql = 'select id from equipos where id_proveedor= '.$cod;
			$consulta=$this->db->query($sql);
			 if ($consulta->num_rows() > 0) {
			$nombresx=true;
			}
			else
			{$nombresx = false;}
			return $nombresx;
		}
		
			//dado un id de equipo devuelve true si se ha asignado un equipo al sector
			public function verificar_modelo($cod)
		{   
			$this->load->database();
			$sql = 'select id from equipos where id_modelo= '.$cod;
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