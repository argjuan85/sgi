<?php // test_helper.php
if(!defined('BASEPATH')) exit('No direct script access allowed');
//dado un id de detalle_pedido obtiene la cantidad recibida hasta el momento
function obtener_recepciones($detalleid)
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


 function consulta_estadopedido($cod_pedido)
		{
			$CI =& get_instance();
			
			$sql = 'select estado_pedido from pedidos where id='.$cod_pedido;
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->estado_pedido;
		}
		
		//obtiene la sede dado el id de pedido
		 function consulta_sedepedido($cod_pedido)
		{
			$CI =& get_instance();
			
			$sql = 'select id_sede from pedidos where id='.$cod_pedido;
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->id_sede;
		}
		
		//obtiene la sede dado el id de pedido interno
		 function consulta_sedepedidointerno($cod_pedido)
		{
			$CI =& get_instance();
			
			$sql = 'select id_proveedor from pedidos where id='.$cod_pedido;
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->id_proveedor;
		}
		
		
		//devuelve el id de un pedido dato un id de Detalle_pedido
				
		 function consulta_idpedido($id_detalle)
		{
			$CI =& get_instance();
			
			$sql = 'select id_pedido from detalle_pedido where id="'.$id_detalle.'"';
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->id_pedido;
		}
		
			//devuelve el id de un pedido dato un id de Detalle_pedido
				
		 function consulta_idinsumo($id_sede,$id_detalle)
		{
			$CI =& get_instance();
			
			$sql = 'select id_insumo from detalle_pedido where id="'.$id_detalle.'"';
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->id_insumo;
		}
		
		//dado un id de insumo obtiene su stock
			 function obtener_stock($id_sede,$id_insumo)
		{
			$CI =& get_instance();
			
			$sql = 'select stock_real from stock where id_insumo="'.$id_insumo.'" and id_sede="'.$id_sede.'"';
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->stock_real;
		}
		
		//dado un id de pedido obtiene el tipo
			 function obtener_tipopedido($id_insumo)
		{
			$CI =& get_instance();
			
			$sql = 'select tipo_pedido from pedidos where id="'.$id_insumo.'"';
			$rtdo=$CI->db->query($sql);
			if($rtdo->num_rows()==0)
 				{
       			echo "Hubo un error ";
       			}
       			else
       			{ 
			$resul= $rtdo->row();
				}
			return $resul->tipo_pedido;
		}
		
		
		// incrementa el stock de un insumo 
			 function actualizar_stockinsumo($id, $nuevo_stock, $sede)
		{   
			$CI =& get_instance();
			
			$sql = 'update stock set stock_real= "'.$nuevo_stock.'" where id_insumo="'.$id.'" and id_sede="'.$sede.'"';
			$rtdo=$CI->db->query($sql);
			//en un update si la fila no recibe modificacion arroja afected rows 0... por ende valido con la variable de la query q en caso de error queda en false
			if(!$rtdo)
 				{
       			echo "Hubo un error ".$sql;
       			}
		
		}
		
			 function colocar_pendiente($row)
		{   
			$CI =& get_instance();
			
			$sql = 'update pedidos set estado_pedido= "Pendiente" where id="'.$row.'"';
			$rtdo=$CI->db->query($sql);
			if(!$rtdo)
 				{
       			echo "Hubo un error ";
       			}
		
		}

//dado un id de insumo obtiene su stock
			 function verifica_recepcion($deta,$numero,$recep)
		{
			
			$CI =& get_instance();

     
		
			$sql = 'select id from recepciones where id_detalle_pedido="'.$deta.'" and numero="'.$numero.'" and tipo_recepcion= "'.$recep.'" ';
			
			
			$rtdo=$CI->db->query($sql);
			$nm=$rtdo->num_rows();
			if($nm == "0")
 				{
       			return "-1";
       			}
       			else
       			{ 
       			$resul= $rtdo->row();
				return $resul->id;
			
				}
			
		}
		
			 function obtener_valor_linea($id_linea)
		{
			$CI =& get_instance();
			
			$sql = 'select cantidad_recepcion from recepciones where id="'.$id_linea.'" ';
			$rtdo=$CI->db->query($sql);
			$nm=$rtdo->num_rows();
			if($nm == "0")
 				{
       			return "-1";
       			}
       			else
       			{ 
       			$resul= $rtdo->row();
				return $resul->cantidad_recepcion;
			
				}
			
		}

function verifica_insumo_sede($id_insumo,$id_sede)
	{
			$CI =& get_instance();
			
			$sql = 'select id from stock where id_insumo="'.$id_insumo.'" and id_sede="'.$id_sede.'" ';
			
			$rtdo=$CI->db->query($sql);
			$nm=$rtdo->num_rows();
			if($nm == "0")
 				{
       			return "-1";
       			}
       			else
       			{ 
       			$resul= $rtdo->row();
				return $resul->id;
			
				}
			
		}

function generar_stock_insumo($id_insumo, $nuevo_stock, $id_sede)
{
	$CI =& get_instance();
	
	$sql = 'INSERT INTO stock (id_insumo, stock_real, habilitado, id_sede) VALUES ( "'.$id_insumo.'", "'.$nuevo_stock.'", "'.$id_sede.'", "'.$id_sede.'")';
	$rtdo=$CI->db->query($sql);
         
     		 if(!$rtdo)
 				{
       			echo "Hubo un error en la carga";
       			}
}


?>