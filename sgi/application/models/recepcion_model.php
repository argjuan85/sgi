<?php


	class Recepcion_model extends CI_Model
	{
		
		//dado un id de detalle de insumo devuelve las recepciones asociadas
		public function obtener_recepciones($cod)
		{   
			$this->load->database();
			$sql = 'select r.id, r.cantidad_recepcion, r.id_detalle_pedido, r.numero  from detalle_pedido d inner join recepciones r on r.id_detalle_pedido=d.id where d.id= '.$cod;
			$consulta=$this->db->query($sql);
			if ($consulta->num_rows() > 0)
			{
			$nombresx=$consulta->result_array();
			return $nombresx;
			}
			else
			return false;
     	}
     	
     	
     			//dado un id de detalle de insumo devuelve las recepciones asociadas
		public function obtener_recepciones2($cod)
		{   
			$this->load->database();
			$sql = 'select * from recepciones where id_detalle_pedido= '.$cod;
			$consulta=$this->db->query($sql);
			if ($consulta->num_rows() > 0)
			{
			$nombresx=$consulta->result_array();
			return $nombresx;
			}
			else
			return false;
     	}

//dado un id de detalle_pedido obtiene la cantidad recibida hasta el momento
function obtener_recepcion($detalleid)
{
    $CI =& get_instance();

    $query = $CI->db->query("SELECT cantidad_recepcion FROM recepciones WHERE id_detalle_pedido = $detalleid");
    $cantidad_recibida = "0";
    if($query->num_rows() > 0)
    {
    	$nombresx=$query->result_array();
    	foreach ($nombresx as $filas)
		{
    	$cantidad_recibida = $cantidad_recibida + $filas['cantidad_recepcion'];
    	}
        return $cantidad_recibida;
    }else
    {
        return "0"; 
    }
}


//dado un id de detalle_pedido obtiene el numero cantidad recibida hasta el momento
function obtener_nrecepcion($iddeta, $tipo_r, $nro_r)
{
	$this->load->database();
		
		$sql = 'select numero from recepciones where tipo_recepcion="'.$tipo_r.'" and numero="'.$nro_r.'" and id_detalle_pedido='.$iddeta;
			$query = $this->db->query($sql);
    		$codigo=$query->row();
			return $codigo->numero;
}

//dado un id de detalle_pedido obtiene el id de recepcion
function obtener_idrecepcion($iddeta, $tipo_r, $nro_r)
{
	$this->load->database();
		
		$sql = 'select id from recepciones where tipo_recepcion="'.$tipo_r.'" and numero="'.$nro_r.'" and id_detalle_pedido='.$iddeta;
			$query = $this->db->query($sql);
    		$codigo=$query->row();
			return $codigo->id;
}


     	//verifica si existe aguna recepcion asociada al detalle de Pedidos
     		public function verifica_recepciones($cod)
		{   
			$this->load->database();
			$sql = 'select id  from  recepciones where id_detalle_pedido= '.$cod;
			$query=$this->db->query($sql);
			$codigo=$query->row();			
			if ($query->num_rows() > 0)
			{
			return true;
			}
			else
			return false;
     	}
     	
	//busca una recepcion para un detalle de pedido datos los datos de una recep en particular	
function consulta_recepcion($iddeta,$tipo_r,$nro_r)
{

		 //identifico la recepcion a eliminar con la clave (iddetalle ,nro de remito/traza, y tipo recepcion ya que no deberia haber dos registros con esto mismo
		$sql = 'select id from recepciones where id_detalle_pedido="'.$iddeta.'" and tipo_recepcion= "'.$tipo_r.'" and numero= "'.$nro_r.'"';
		
    $query = $this->db->query($sql);
    $codigo=$query->row();
		if ($query->num_rows() > 0)
		{	
		return $codigo->id;
		}
		return "-1";
}		
		
function eliminar_recepcion($iddeta,$tipo_r,$nro_r)
{

		 //identifico la recepcion a eliminar con la clave (iddetalle ,nro de remito/traza, y tipo recepcion ya que no deberia haber dos registros con esto mismo
		$sql = 'select id from recepciones where tipo_recepcion="'.$tipo_r.'" and numero="'.$nro_r.'" and id_detalle_pedido='.$iddeta;
		
		
    $query = $this->db->query($sql);
    $codigo=$query->row();
		if ($query->num_rows() > 0)
		{	
		//borro la recepcion
		$sql = 'delete from recepciones where id= "'. $codigo->id.'"';
		$consulta=$this->db->query($sql);
			
		}
	$a= $codigo->id;
}

	//dado un nro de traza o remito devuelve pedidos asociados
function busca_recepcion($tipo_r,$nro_r)
{

		 //identifico la recepcion a eliminar con la clave (iddetalle ,nro de remito/traza, y tipo recepcion ya que no deberia haber dos registros con esto mismo
		$sql = 'select distinct p.nro_pedido, p.estado_pedido, p.nro_tk, p.fecha_pedido, p.observaciones, p.id_proveedor from recepciones r inner join detalle_pedido d on d.id=r.id_detalle_pedido inner join pedidos p on p.id=d.id_pedido where r.tipo_recepcion= "'.$tipo_r.'" and r.numero="'.$nro_r.'"';
		    
    $consulta=$this->db->query($sql);
		
		if ($consulta->num_rows() > 0)
		{	
			$nombresx=$consulta->result_array();
			return $nombresx;
		}
		return "-1";
}	

//dado un id de recepcion obtiene la cantidad
	
			public function obtener_cantidad_recepcion($iddeta,$tipo_r,$nro_r)
		{   
			$this->load->database();
		
		$sql = 'select cantidad_recepcion from recepciones where tipo_recepcion="'.$tipo_r.'" and numero="'.$nro_r.'" and id_detalle_pedido='.$iddeta;
			$query = $this->db->query($sql);
    		$codigo=$query->row();
			$a= $codigo->cantidad_recepcion;
			return $codigo->cantidad_recepcion;
			
		}

}


?>