<?php
class Detalle_pedido extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->library('session');
                $this->load->library('calendar');
                $this->load->database();
                $this->load->model('detalle_pedido_model');
                $this->load->model('pedidos_model');
                $this->load->model('sedes_model');
                $this->load->model('stock_model');
                $this->load->model('insumos_model');
                $this->load->model('recepcion_model');
                $this->load->model('proveedores_model');
                 $this->load->model('general_model');
                $this->load->helper('form');
				$this->load->helper('url'); 
				$this->load->helper('html');
                $this->load->helper('recepcion_helper');
                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/detalle_pedido/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('detalle_pedido/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

     function genera($cod = ''){
     	$cod= $this->pedidos_model->ultima_insercion();
     	$aux = $this->session->userdata('tipo');
     	$array_sesiones = array('liid' => '', 'tipo' => '');
     	$this->session->unset_userdata($array_sesiones);
     	//pierdo todas las variables de sesion por eso comento, solo desactivo las de arriba
		//$this->session->sess_destroy();
     	if ($aux == "1")
     	{
     	//genero Detalle_pedido para pedido $cod
     	$this->detalle_pedido_model->generarpedido_stockminimo($this->session->userdata('sede'),$cod);
     	}
     	elseif ($aux == "2")
     	{/**
		  * codigo que genera por consumo entre rangos
		  */
		  // .site_url('detalle_pedido/recibir'.('/').$row->id)."
			redirect('detalle_pedido/generapedido_consumo'.'/'.$cod);
		}
		elseif ($aux == "3")
     	{/**
		  * cargo vista para ingresos
		  */
		$data['id_pedido'] = $cod;
     	$this->pedidos_model->genera_pedido($cod);
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	$data['tipo_pedido'] = $aux;
     	//cargo vista con el detalle del pedido ya generado y editable 
     		$data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');
     	$this->load->view('detalle_pedido/detalle_pedido.php', $data);
		}
		
			
		
     	$data['id_pedido'] = $cod;
     	$this->pedidos_model->genera_pedido($cod);
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	//cargo vista con el detalle del pedido ya generado y editable 
     		$data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');
     	$this->load->view('detalle_pedido/detalle_pedido.php', $data);
     	
				
     	//cargo vista con el detalle del pedido ya generado y editable 
     	//$this->load->view('detalle_pedido/detalle_pedido_manual.php', $data);
          	redirect('detalle_pedido/ver'.'/'.$cod);
     	}
     	
     	
     	function genera_envio($cod = ''){
        $aux = $this->session->userdata('tipo');
     	$array_sesiones = array('liid' => '', 'tipo' => '');
     	$this->session->unset_userdata($array_sesiones);
     	//pierdo todas las variables de sesion por eso comento, solo desactivo las de arriba
		//$this->session->sess_destroy();
     	if ($aux == "1")
     	{
     	//genero Detalle_pedido para pedido $cod
     	//$this->detalle_pedido_model->generarpedido_stockminimo($this->session->userdata('sede'),$cod);
     	}
     	elseif ($aux == "2")
     	{/**
		  * codigo que genera por consumo entre rangos
		  */
		  // .site_url('detalle_pedido/recibir'.('/').$row->id)."
			redirect('detalle_pedido/generapedido_consumo'.'/'.$cod);
		}
		elseif ($aux == "3")
     	{/**
		  * cargo vista para ingresos
		  */
		$data['id_pedido'] = $cod;
     	$this->pedidos_model->genera_pedido($cod);
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	$data['tipo_pedido'] = $aux;
     	//cargo vista con el detalle del pedido ya generado y editable 
     	$data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');
     	$this->load->view('detalle_pedido/detalle_pedido.php', $data);
		}
		
  		$data['id_pedido'] = $cod;
     	$this->pedidos_model->genera_pedido($cod);
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	//cargo vista con el detalle del pedido ya generado y editable 
     	//$this->load->view('detalle_pedido/detalle_pedido.php', $data);
     					
     	//cargo vista con el detalle del pedido ya generado y editable 
    
     	redirect('detalle_pedido/ver2'.'/'.$cod);
     	}
     	
     	//una vez cargados los datos de la recepcion
     	function genera_ingreso($cod = ''){
    
  		$a=$this->input->post('numero');
     	$b=$this->input->post('tipo_recepcion');
     	if (isset($a)and isset($b))	
     	{$_SESSION['numero']= $this->input->post('numero') ;
     	$_SESSION['tipo_recepcion']= $this->input->post('tipo_recepcion');
     	}
     
     	$this->detalle_pedido_model->genera_ingreso($cod, $b, $a);
     	
    					
     	//ok
    // cierro el pedido
    $this->session->set_flashdata('message', 'Successfully Added.');
    $this->session->set_flashdata('success_msg', 'success');
    $a = $this->session->flashdata('message');

     	redirect('pedidos/cerrar'.'/'.$cod);
     	}
     	
     	
     	//procesa las modificaciones en las grillas de pedidos
     	function editable(){
      	 //$this->load->view('detalle_pedido/fancy'); 
		$a=$this->input->post('value');
		$b=$this->input->post('row_id');
		  $this->form_validation->set_rules("value", "value", "trim|required|is_natural_no_zero|max_length[3]");
       
     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
          	echo "Ingrese un Valor Positivo de a lo sumo 3 dígitos";
		}
		else
		{$c=$this->detalle_pedido_model->actualizar_detalle($a,$b);
		echo $c;
		}
		}
     	
     	//procesa las modificaciones en las grillas de pedidos
     	function elimina(){
      	 //$this->load->view('detalle_pedido/fancy'); 
		$a=$this->input->post('xid');
		$b=$this->input->post('xped');
		$c=$this->detalle_pedido_model->eliminar_detalle($a,$b);
		$tipo= $this->pedidos_model->consulta_tipo($b);
			redirect('detalle_pedido/ver2'.'/'.$b);

		}
		
		
		   	//procesa las modificaciones en las grillas de recepciones
     	function modifica_recepcion(){
      	$value=$this->input->post('value');
		$column=$this->input->post('column');
		$row_id=trim($this->input->post('row_id'));
		$numero=$this->input->post('numero');
		$tipo_recepcion=$this->input->post('tipo_recepcion');
		
		$this->form_validation->set_rules("value", "value", "trim|required|is_natural_no_zero|max_length[3]");
       
     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
          	echo "Ingrese un Valor Positivo de a lo sumo 3 dígitos";
		}
		else
		{
	
			//es probable que este de mas ya que siempre deberia venir 4 pero la dejo por las dudas. no tuve tiempo de hacerle un seguimiento
			if ($column == '4')
	        {
		
			$band="0";
        	/* verifico que no existe la recepcion... en caso de existir la reemplazo */
        	$id_recep = verifica_recepcion($row_id,$numero,$tipo_recepcion);
        	if ($id_recep != "-1")
        	{
				//pongo una bandera para saber como recalcular el stock ya que no es lo mismo si modifico una linea de recepcion ya cargada 
				$band="1";
				$valor_lineavieja= obtener_valor_linea($id_recep);
				$sql = "update recepciones set tipo_recepcion= ". "'" .$tipo_recepcion."', numero= ". "'" .$numero."', cantidad_recepcion=". "'" .$value."' ,id_detalle_pedido= ". "'" .trim($row_id)."' ";
	   			$sql = $sql . " where id= " ."'".$id_recep."'";
	    				
			}
				else
				{
	
   	 $sql = 'INSERT INTO recepciones (id_detalle_pedido, cantidad_recepcion, tipo_recepcion, numero) VALUES ( "'.$row_id.'", "'.$value.'", "'.$tipo_recepcion.'", "'.$numero.'")';
	   			}
	   			
        $rtdo=$this->db->query($sql);
         
     		 if(!$rtdo)
 				{
       			echo "hubo un error";
       			}
       			else
       			{ 
       			  //actualizo stock
       			  	
       			  	//obengo  id del insumo del detalle
       			  	
       			  	/**
						 * 
						 * testear
						 * 
						 */
       			  	
       			  
       			    $id_pedido = consulta_idpedido($row_id);
       			    
       			    $tipo_pedido = obtener_tipopedido ($id_pedido);
       			
       			   	$id_sede = consulta_sedepedido($id_pedido);
       			  
       			    $id_insumo = consulta_idinsumo($id_sede,$row_id);
       			    
       			    //si no existe stock del insumo crea el registro
       			    if (verifica_insumo_sede($id_insumo, $id_sede) != "-1")
       			      			   { 
       			      			  $stock_insumo = obtener_stock($id_sede,$id_insumo);
       			      			   
       			      			   //sumo el stock ( cuando cargo recepcion por primera vez)
       			      			   if(!$band)
       			      			    {
       			    				$nuevo_stock = $stock_insumo + $value;
       			    				 actualizar_stockinsumo($id_insumo, $nuevo_stock, $id_sede );
       			    			   if ($tipo_pedido == "I")
        							{
        							$sede_envio = consulta_sedepedidointerno($id_pedido);
        							$stock_sede_envio = obtener_stock($sede_envio,$id_insumo);
        							$nuevo_stock_sede_envio = $stock_sede_envio - $value;
        							actualizar_stockinsumo($id_insumo,$nuevo_stock_sede_envio,$sede_envio);}
       			    				 
       			    				}
       			    				//actualizo el stock (modifico una linea cargada)
       			    				else
       			    				{
       			    					if($valor_lineavieja != "-1")
       			    				 {
       			    				 	  $nuevo_stock = $stock_insumo - $valor_lineavieja + $value;     			    				 
       			    				 	actualizar_stockinsumo($id_insumo, $nuevo_stock, $id_sede);
       			    				 	 if ($tipo_pedido == "I")
        							{
        							$sede_envio = consulta_sedepedidointerno($id_pedido);
        							$stock_sede_envio = obtener_stock($sede_envio,$id_insumo);
      $nuevo_stock_sede_envio = $stock_sede_envio + $valor_lineavieja - $value;
        							actualizar_stockinsumo($id_insumo,$nuevo_stock_sede_envio,$sede_envio);}
        							
       			    				 }
       			    				 else
       			    				 {
       			    				 	echo "error";
									 	}
									 }
       			      			 
       			      			 	}
       			      			    else
       			      			   { generar_stock_insumo($id_insumo, $value, $id_sede);
       			      			    //descuento de la sede que envia en el caso de los envios internos
   									  if ($tipo_pedido == "I")
        							{
        							$sede_envio = consulta_sedepedidointerno($id_pedido);
        							$stock_sede_envio = obtener_stock($sede_envio,$id_insumo);
        							$nuevo_stock_sede_envio = $stock_sede_envio - $value;
        							actualizar_stockinsumo($id_insumo,$nuevo_stock_sede_envio,$sede_envio);}
        							}
       			    /**
					   * 
					   * 
					   * 
					   */
       			     //devuelve el estado de un pedido dado el id
   
     
     //$estado = consulta_estadopedido($id_pedido);
     
     //si es la primera recepcion actualizo el estado del pedido
        			if (consulta_estadopedido($id_pedido)== "En Proceso")
       				{
       
                      	   colocar_pendiente($id_pedido);
					}
		
		       echo $value;
		      //$_SESSION['valor']=$_POST['value'];
		      
		     
        		}
        }
        
   
			

    }
    }


//procesa vista para pedidos automaticos por consumo (rango fechas y proveedor)
     	 function genera_consumo($cod = '', $error = ''){
     	 	
     	 	//set validations
         
          $this->form_validation->set_rules("from", "Desde", "trim|required");
          $this->form_validation->set_rules("to", "Hasta", "trim|required");
     	 $tipo= $this->pedidos_model->consulta_tipo($cod);
     	
     	 if ($tipo == "P")
     	 {
		 $this->form_validation->set_rules("proveedor", "proveedor", "required|is_natural");// valido que seleccione una opcion con id positivo "seleccione" tiene asignado -1
		 }
          $this->form_validation->set_message('is_natural', 'Por favor seleccione una opción');

     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails
                 		//cargo vista para cargar rengo de fechas nuevamente
     	$data['id_pedido'] = $cod;
     	$data['error'] = $error;
     	$data['tipo']  = $this->pedidos_model->consulta_tipo($cod);
     	if ($data['tipo'] == "P")
     	{$data['proveedores']  = $this->proveedores_model->obtener_proveedores();	
     	}
     	//$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);

				
		 	     		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');

		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');			
				
		//cargo vista para realizar recepciones
          $data['content_view']='detalle_pedido/rango_consumo.php';
     	 //template diferente para vistas que no incluyen grocery 
     	  $data['menu_sede_oculto']="1";
    	 $this->load->view('template2',$data);
     	
     	
     	}
     	else
     	{
     	 	
     	 	/* capturo echas seleccionadas */
     	$desde =  "'".$this->input->post('from');
     	$id_proveedor = $this->input->post('proveedor');
     	// agrego el horario para poder realizar la consulta
     	$desde = $desde. " 00:00:00'";
     	$hasta =  "'".$this->input->post('to');
     	$hasta = $hasta. " 23:59:59'";
     	//genero Detalle_pedido para pedido $cod con rango de fechas
     	$tipo_pedido = $this->pedidos_model->consulta_tipo($cod);
     	if($tipo_pedido == "P")
     	{
     	
		$resu = $this->detalle_pedido_model->generarpedido_consumo2($cod,$desde,$hasta, $id_proveedor);
     	}
     	else
     	{
		$resu = $this->detalle_pedido_model->generarpedido_consumo($cod,$desde,$hasta);	
		}
     	If($resu == "1")
     	{$this->pedidos_model->genera_pedido($cod);}
     	else
     	{
			//cargo vista para cargar rengo de fechas
			
			redirect('detalle_pedido/generapedido_consumo'.'/'.$cod.'/vacio');
     	//$this->load->view('detalle_pedido/rango_consumo.php', $data);
		}
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	//cargo vista con el detalle del pedido ya generado y editable 
     	$this->load->view('detalle_pedido/detalle_pedido.php', $data);
     	
				/* ver q onda este codigo...  no deberi hacer falta */
				
     	//cargo vista con el detalle del pedido ya generado y editable 
     	$this->load->view('detalle_pedido/detalle_pedido_manual.php', $data);
     	  	redirect('detalle_pedido/ver'.'/'.$cod);
     	}
     	}
     	     
 function ver($cod = ''){
     	unset($_SESSION['numero']);
	unset($_SESSION['tipo_recepcion']);
     //echo $cod;
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	$data['estado'] = $this->detalle_pedido_model->consulta_estadopedido($cod);
     	$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);
     	$data['id_sede'] = $this->pedidos_model->consulta_sede($cod);
     	$data['sede_log'] = $this->session->userdata('sede_filtro');
     	//obtengo los datos del Pedido
     	$data['datos_pedido']= $this->pedidos_model->obtener_datos_pedido($cod);
     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.jeditable.js','subariable3'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable4'=>base_url().'assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5');

$data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/demo_table.css','subariable3'=>base_url().'assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5');

$data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');
		if($this->detalle_pedido_model->consulta_estadopedido($cod) == "Generado")	
     	{//cargo vista con el detalle del pedido ya generado y editable 
     	  /*
     	  codigo en prueba
     	  */
     	  
     	  // cargo js y css de la vista a cargar
    
     	
		
     	      $data['content_view']='detalle_pedido/detalle_pedido.php';
     	      //template diferente para vistas que no incluyen grocery 
     	       $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
     	  
     	  //fin codigo Prueba
     	  
     	  //$this->load->view('detalle_pedido/detalle_pedido.php', $data); 
     	}
     	else
     	{
			
			 $data['content_view']='detalle_pedido/detalle_pedido_noedit.php';
     	      //template diferente para vistas que no incluyen grocery 
     	       $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
			
			
			//par aver con la interfaz vieja
			//$this->load->view('detalle_pedido/detalle_pedido_noedit.php', $data); 
		}
		}
		
		 function ver2($cod = ''){
     	
       	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	$data['estado'] = $this->detalle_pedido_model->consulta_estadopedido($cod);
     	$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);
     	$data['id_sede'] = $this->pedidos_model->consulta_sede($cod);
     	$data['id_proveedor'] = $this->pedidos_model->consulta_proveedor($cod);
     	$data['sede_log'] = $this->session->userdata('sede_filtro');
     	$data['solicitante'] = $this->pedidos_model->obtener_sede_pedido($cod);
     	//obtengo los datos del Pedido
     	$data['datos_pedido']= $this->pedidos_model->obtener_datos_pedido($cod);
     	
		if($this->detalle_pedido_model->consulta_estadopedido($cod) == "Generado")	
     	{//cargo vista con el detalle del pedido ya generado y editable 
     	
     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.jeditable.js','subariable3'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable4'=>base_url().'assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5');

$data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/demo_table.css','subariable3'=>base_url().'assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5');
     	
     	
     	//$data['variable3']=array('subvariable1'=>'value1-1','subariable2'=>'value1-2');
     	
     	  $data['content_view']='detalle_pedido/detalle_pedido2.php';
     	      //template diferente para vistas que no incluyen grocery 
     	      $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
     	}
     	else
     	{
     		
     		$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.jeditable.js','subariable3'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable4'=>base_url().'assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5');

$data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/demo_table.css','subariable3'=>base_url().'assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5');
     		
     			 $data['content_view']='detalle_pedido/detalle_pedido_noedit2.php';
     	      //template diferente para vistas que no incluyen grocery 
     	      $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
	
		}
		}

//vista para cargar datos de las recepciones
     	 function recibir($cod = ''){
     	   unset($_SESSION['numero']);
		unset($_SESSION['tipo_recepcion']);  
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);
		$data['id_sede'] = $this->pedidos_model->consulta_sede($cod);
			$data['sede_log'] = $this->session->userdata('sede_filtro');
     	//cargo vista para cargar recepciones
     	$a= $data['detalle_pedido'];

     	
     	if (	$a != NULL)
     	   {   
     	   
     	   	  	$data['css_files']=array('subariable3'=>base_url().'assets/css/rangos_fechas.css');	
     	   	  $data['content_view']='detalle_pedido/realizar_recepcion_pedido.php';
     	   	  $a = $this->pedidos_model->consulta_proveedor($cod);
     	   	  $b = $this->sedes_model->obtener_nombre($a);
     	   	  $data['proveedor_pedido']= $b;
     	      //template diferente para vistas que no incluyen grocery 
     	       $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
     	}
     	else
     	{
				  $this->session->set_flashdata('error', 'No hay detalles cargados en el pedido.');
				  redirect('detalle_pedido/ver'.'/'.$cod);
		}
     	
     
     	}

function recibir_total ($cod = '')
{
	// REVISAR , PENDIENTE , PROBAR , TEST  VALIDACION DE VAR DE SESION CON UN SET FLASH DEBERIA ALCANZAR
	$numero =	$_SESSION['numero'];
    $tipo_recepcion = 	$_SESSION['tipo_recepcion'];
     	$tipo_pedido = $this->pedidos_model->consulta_tipo($cod);
		 $this->session->set_flashdata('message', 'El envio: '.$this->pedidos_model->obtener_nro($cod).' se ha recepcionado completamente.');
	     
	     //si no esta creado el registro en la tabla stock lo creo
	
		  // incremento de stock
	     $this->detalle_pedido_model->recepcion_total($cod,$tipo_recepcion,$numero);
	     // debo redireccionar segun el tipo de pedido
	     if ($tipo_pedido == "P")
	     {
		 redirect('pedidos/listar2/', 'refresh');
		 }
	     elseif ($tipo_pedido == "I")
	     {
	     redirect('pedidos/listar3/', 'refresh');		 	
		 }
		 else
		 {
		 redirect('pedidos/listar/', 'refresh');		
		 }

}
     	
     	//genero las recepciones
     		 function recepcion($cod = ''){
     	  		 redirect('pedidos/cclistar/', 'refresh');
     
     	}
     	
     	 	
     	
     	//redirecciono para cargar rango de fechas
     	 function generapedido_consumo($cod = '', $error = ''){
     	     
     	$data['id_pedido'] = $cod;
     	$data['error'] = $error;
     	$data['tipo']  = $this->pedidos_model->consulta_tipo($cod);
     	if ($data['tipo'] == "P")
     	{$data['proveedores']  = $this->proveedores_model->obtener_proveedores();	
     	}
     	//$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);

				
		 	     		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');

		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');			
				
		//cargo vista para realizar recepciones
          $data['content_view']='detalle_pedido/rango_consumo.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}
     	
     	
     	//recepcion de pedidos tipo "e"
     	function recibir1($cod = ''){
     	  	//set validations
         
          $this->form_validation->set_rules("numero", "numero", "trim|required");
 
     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
          	unset($_SESSION['numero']);
		unset($_SESSION['tipo_recepcion']);  
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     
		$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);
		$data['id_sede'] = $this->pedidos_model->consulta_sede($cod);
			$data['sede_log'] = $this->session->userdata('sede_filtro');
     	//cargo vista para cargar recepciones
   
   $data['css_files']=array('subariable3'=>base_url().'assets/css/rangos_fechas.css');	
     	  $a = $this->pedidos_model->consulta_proveedor($cod);
     	   	  $b = $this->sedes_model->obtener_nombre($a);
     	   	  $data['proveedor_pedido']= $b;
     	      $data['content_view']='detalle_pedido/realizar_recepcion_pedido.php';
     	      //template diferente para vistas que no incluyen grocery 
     	       $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
     	
          	}
          	else{
				
			
     	
     	$a=$this->input->post('numero');
     	$b=$this->input->post('tipo_recepcion');
     	if (isset($a)and isset($b))	
     	{$_SESSION['numero']= $this->input->post('numero') ;
     	$_SESSION['tipo_recepcion']= $this->input->post('tipo_recepcion');
     	}
     	if (isset($_SESSION['numero']))
     	{$data['numero'] =  $_SESSION['numero'];}
     	if (isset($_SESSION['tipo_recepcion']))
     	{$data['tipo_recepcion'] =  $_SESSION['tipo_recepcion'];}
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	     	$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);	
     	     	$data['datos_pedido']= $this->pedidos_model->obtener_datos_pedido($cod);
     	     		
     	     		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.jeditable.js','subariable3'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable4'=>base_url().'assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5');

		$data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/demo_table.css','subariable3'=>base_url().'assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5');	
		
			
     	//cargo vista para realizar recepciones
         		  $a = $this->pedidos_model->consulta_proveedor($cod);
     	   	  $b = $this->sedes_model->obtener_nombre($a);
     	   	  $data['proveedor_pedido']= $b;
     	  $data['content_view']='detalle_pedido/recepcion_pedido.php';
     	      //template diferente para vistas que no incluyen grocery 
     	      $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
    		  }
     	 
     	}
     	  
     	  //editar, solo es copia de la 1
     	    function recibir2($cod = ''){
     	$a=$this->input->post('numero');
     	$b=$this->input->post('tipo_recepcion');
     	if (isset($a)and isset($b))	
     	{$_SESSION['numero']= $this->input->post('numero') ;
     	$_SESSION['tipo_recepcion']= $this->input->post('tipo_recepcion');
     	}
     	if (isset($_SESSION['numero']))
     	{$data['numero'] =  $_SESSION['numero'];}
     	if (isset($_SESSION['tipo_recepcion']))
     	{$data['tipo_recepcion'] =  $_SESSION['tipo_recepcion'];}
     	$data['id_pedido'] = $cod;
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($cod);
     	     		$data['tipo_pedido'] =	$this->pedidos_model->consulta_tipo($cod);
     	     		$data['datos_pedido']= $this->pedidos_model->obtener_datos_pedido($cod);
     	     		
     	     		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.jeditable.js','subariable3'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable4'=>base_url().'assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5');

		$data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/demo_table.css','subariable3'=>base_url().'assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5');	
		  	  $a = $this->pedidos_model->consulta_proveedor($cod);
     	   	  $b = $this->sedes_model->obtener_nombre($a);
     	   	  $data['proveedor_pedido']= $b;
			
     	//cargo vista para realizar recepciones
         	
     	  $data['content_view']='detalle_pedido/recepcion_pedido.php';
     	      //template diferente para vistas que no incluyen grocery 
     	      $data['menu_sede_oculto']="1";
    		  $this->load->view('template2',$data);
     	 
     	}
     	  
     	    
     	    	 function eliminar($cod = ''){ 
        //tomo los datos
     	$data['id'] =  $this->input->post('data[id]');
     	$data['id_pedido'] =  $this->input->post('data[id_pedido]');
     	$data['numero'] = $this->input->post('data[numero]');
     	$data['tipo_recepcion'] = $this->input->post('data[tipo_recepcion]');
     	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($data['id_pedido']);
     	
     	//veo si existe la Recepcion_model //ver por que estaba esta linea de abajo, no es necesaria en los ingresos manuales
     	$recep ="-1";
     	$recep = 	$this->recepcion_model->consulta_recepcion($data['id'],$data['tipo_recepcion'],$data['numero']);
     
     	//llamo a borrar
     	if ($recep != "-1")
     	{
     				// actualizo Stock
     $this->stock_model->decrementar_stockx1($this->detalle_pedido_model->consulta_idinsumo($data['id']),$this->pedidos_model->obtener_sede_pedido($data['id_pedido']), $this->recepcion_model->obtener_cantidad_recepcion($data['id'],$data['tipo_recepcion'],$data['numero']));
     		$this->recepcion_model->eliminar_recepcion($data['id'],$data['tipo_recepcion'],$data['numero']);
     	}
     
     	//redireciono
     		redirect('detalle_pedido/recibir2'.'/'.$data['id_pedido']);
     	//$this->load->view('detalle_pedido/recepcion_pedido.php', $data);
     						 
     	
     	  
     	}   
     	   
     	   
	//no se si se use
      function listar($cod = '', $fun= ''){
      	$this->session->set_userdata('pedido', $cod);
      	$this->session->set_userdata('act', $fun);
      	$this->session->set_userdata('proovedor', $this->pedidos_model->genera_pedido($cod));
      	$this->grocery_crud->set_language('spanish');
      	$this->grocery_crud->unset_back_to_list();
	    //s$this->grocery_crud->callback_add_field('id_pedido',array($this,'callback_add1'));
	  
		$this->grocery_crud->set_theme('datatables');
		$this->grocery_crud->set_table('detalle_pedido');
		$this->grocery_crud->set_language('spanish');
		$this->grocery_crud->add_fields('id_pedido','id_insumo','cantidad_pedida');
		$this->grocery_crud->display_as('id_insumo','Codigo insumo');
		$this->grocery_crud->display_as('cantidad_pedida','Cantidad');
		
		//validacion
 		//$this->grocery_crud->unique_fields('nombre_sector');
		$this->grocery_crud->set_rules('id_insumo', 'Codigo insumo','trim|required');
		$this->grocery_crud->set_rules('cantidad_pedida', 'Cantidad','trim|required|is_natural_no_zero');
		
		/*$this->grocery_crud->callback_before_delete(array($this,'before_delete'));
		$this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar el sector, el mismo posee registros asociados');
		$this->grocery_crud->set_lang_string('delete_success_message', 'El sector se ha eliminado correctamente');	*/
		
		
		$this->grocery_crud->callback_add_field('id_insumo',array($this,'add_field_callback_1'));
		if ($this->grocery_crud->getState() == 'add') 
			{
			$this->grocery_crud->change_field_type('id_pedido','invisible');
			
			
			}
		$this->grocery_crud->where('id_pedido',$cod);
		//$this->grocery_crud->change_field_type('id_pedido','invisible');
		$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));
		$this->grocery_crud->callback_after_insert(array($this, 'after_insert'));
		$output = $this->grocery_crud->render();
		$this->_example_output($output);
		}




function fancy(){
      	 $this->load->view('detalle_pedido/fancy'); 
		
		}
		
function test(){
			$a="93";
			$b="R";
			$c="qwerrty";
      	  $this->recepcion_model->eliminar_recepcion($a,$b,$c);
		}

function before_insert1($post_array) {

   
$post_array['id_pedido'] = $this->session->userdata('pedido');

  return $post_array;
} 

function after_insert($post_array) {

  
  return $post_array;
} 

function add_field_callback_1($value, $row)
{
$pedido= $this->uri->segment(3);
$prov= $this->pedidos_model->consulta_proveedor($pedido);
$tipo= $this->pedidos_model->consulta_tipo($pedido);

// cargo el combo de insumos en agregar detalle segun el tipo de pedido
if (($tipo == "E") || ($tipo == "N")) 
{
	//cargo el combo con insumos que tiene asociado el proveedor seleccionado
	$datos = $this->insumos_model->obtener_insumos($prov);}
elseif ($tipo == "I") 
{
	//cargo el combo con insumos en stock de la sede que realiza el envio interno
	$datos = $this->insumos_model->obtener_insumosenstock_sede($this->pedidos_model->consulta_proveedor($pedido));}
else
{
	//cargo el combo con insumos en gral, ya que la sede a la cual le estoy pidiendo puede no tenerlos al momento de la solicitud pero si en un posterior ingreso.
	$datos = $this->insumos_model->obtener_insumos_general();
}

$html = "<select id='field-id_insumo'  name='id_insumo' class='chosen-select' data-placeholder='Seleccionar Insumo' style='width:300px'>";
	 $html = $html."<option value=''></option>";
	foreach ($datos as $filas)
	{
         $html = $html."<option value='".$filas['id']."' >".$filas['codigo_insumo'].'</option>';
     }
     $html=$html.'</select>';
  	 return $html;
    

  
}
	


function _example_output($output = null){
$this->load->view('example',$output);
} 

    


}
?>