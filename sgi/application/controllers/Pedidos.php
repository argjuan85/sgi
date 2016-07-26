<?php
class Pedidos extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->model('pedidos_model');
                $this->load->model('parametros_model');
                $this->load->model('stock_model');
                $this->load->model('sedes_model');
                $this->load->model('proveedores_model');
                $this->load->model('detalle_pedido_model');
                $this->load->model('recepcion_model');
                $this->load->model('general_model');
                $this->load->library('session');
                $this->load->database();
				$this->load->helper('url'); 
				$this->load->helper('date');
				$this->load->helper('html');
				//$this->session->set_userdata('sede', "15");
                // Your own constructor code
        }


        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/pedidos/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('pedidos/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }
function notificar ($id_pedido = ''){
		 	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($id_pedido);
	 	$a= $data['detalle_pedido'];
	 	   	if (	$a == NULL)
     	   {  
     	     $this->session->set_flashdata('error', 'No hay detalles cargados en el pedido.');
				  redirect('detalle_pedido/ver2'.'/'.$id_pedido);
     	}
     	else
     	{
		 $this->session->set_flashdata('message', 'El pedido interno N°: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha generado correctamente.');
	     //envio de notificacion a sede proovedora
	    
	    //redirecciono al crud
	     redirect('pedidos/listar2/', 'refresh');
	     }
	}
function confirmar($id_pedido = ''){
	$data['detalle_pedido'] = $this->detalle_pedido_model->consulta_detallepedido($id_pedido);
	 	$a= $data['detalle_pedido'];
	 	   	if (	$a == NULL)
     	   {  
     	     $this->session->set_flashdata('error', 'No hay detalles cargados en el pedido.');
				  redirect('detalle_pedido/ver'.'/'.$id_pedido);
     	}
     	else
     	{
				
		

	
	if (($this->pedidos_model->consulta_tipo($id_pedido) == "E" ) || ($this->pedidos_model->consulta_tipo($id_pedido) == "N"))
	{
			$this->pedidos_model->confirma_pedido($id_pedido);
		$this->session->set_flashdata('message', 'El pedido numero: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha confirmado correctamente.');
	     redirect('pedidos/listar/', 'refresh');
	     }
	     elseif ($this->pedidos_model->consulta_tipo($id_pedido) == "I" ) 
	     {$aux = False;
	     
	     // chequeo stock antes de realizar el descuento si no deberan acomodar el detalle antes de confirmar
	     $data = $this->detalle_pedido_model->consulta_detallepedido($id_pedido);
	     	foreach($data as $fila)
		{
			$a= $fila['cantidad_pedida'];
			$b = $this->stock_model->obtener_stock($fila['id_insumo'],$this->session->userdata('sede_filtro')); 
			    if($b < $a)
			    {
					$aux = True;
					$this->session->set_flashdata('error', 'No hay stock suficiente del insumo:'.$fila['codigo_insumo']);
					redirect('detalle_pedido/ver2'.'/'.$id_pedido, 'refresh');
				}
	    }
		 if (!$aux)
		 {
		 			$this->pedidos_model->confirma_pedido($id_pedido); 
		 $this->session->set_flashdata('message', 'El envio: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha confirmado correctamente.');
	     // descuento de stock
	     $this->stock_model->descuenta_stock($id_pedido,$this->session->userdata('sede_filtro'));
	     redirect('pedidos/listar3/', 'refresh');
	     }
	     }
	     else{
	     	//los pedidos internos no los confirma el solicitante, solo se crean y se notifica a la sede proveedora.
		 	 $this->session->set_flashdata('message', 'El pedido interno N°: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha confirmado correctamente.');
	     //envio de notificacion a sede proovedora
	    
	    $this->stock_model->descuenta_stock($id_pedido,$this->session->userdata('sede_filtro'));
	     //redirecciono al crud
	     redirect('pedidos/listar2/', 'refresh');
		 }
		 }
}
function cerrar($id_pedido = ''){
	unset($_SESSION['numero']);
	unset($_SESSION['tipo_recepcion']);
	if (($this->pedidos_model->consulta_tipo($id_pedido) == "I" ) && ($this->pedidos_model->obtener_estado($id_pedido) == "En Proceso"))
	{
		// si es un envio entre sedes que esta confirmado, al cerrarlo debo reintegrar el Stock
		//devolver stock
		$this->stock_model->incrementa_stock($id_pedido,$this->session->userdata('sede_filtro'));
	}
	$this->pedidos_model->cierra_pedido($id_pedido);
	
	
	if (($this->pedidos_model->consulta_tipo($id_pedido) == "E" ) || ($this->pedidos_model->consulta_tipo($id_pedido) == "N"))
	{
	
		//creo el mensaje de confirmacion a mostrar luego de la redireccion
	    $this->session->set_flashdata('message', 'El pedido numero: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha cerrado correctamente.');
    
	     redirect('pedidos/listar/', 'refresh');
	     
	     }
	     else
	     {
	     	//creo el mensaje de confirmacion a mostrar luego de la redireccion
	    $this->session->set_flashdata('message', 'El envio numero: '.$this->pedidos_model->obtener_nro($id_pedido).' se ha cerrado correctamente.');
    
         if ($this->pedidos_model->consulta_tipo($id_pedido) == "I" ) 
	     {redirect('pedidos/listar3/', 'refresh');}
	     else
	     {
		 redirect('pedidos/listar2/', 'refresh');	
		 }		 	
		 }
}
function colocar_pendiente($id_pedido = ''){
	$this->pedidos_model->colocar_pendiente($id_pedido);
	
	
	if (($this->pedidos_model->consulta_tipo($id_pedido) == "E" ) || ($this->pedidos_model->consulta_tipo($id_pedido) == "N"))
	{
	     redirect('pedidos/listar/', 'refresh');
	     }
	     else
	     {
		 	redirect('pedidos/listar2/', 'refresh');
		 }
}

//crud para pedidos del tipo externos (pedidos e ingresos)
      function listar(){
      	
       	 //si hubo cambio de sede actualizo permisos y filtro sede, lo tengo que hacer aca debido q el crud carga primero antes que la plantilla
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
    	else
    	{
			$sede_consulta= $this->general_model->ou_sede_id($this->session->userdata('sede'));
		}
		
      	$this->grocery_crud->set_theme('Datatables');
      	$this->grocery_crud->set_language('spanish');
      	$this->grocery_crud->set_table('pedidos');
      	
      	 $where = "id_sede='".$this->session->userdata('sede_filtro')."' AND (tipo_pedido='E' OR tipo_pedido='N')";
      	 //$where = "(tipo_pedido='E' OR tipo_pedido='N') AND id_sede='".$this->session->userdata('sede_filtro')."')";
	  	$this->grocery_crud->or_where($where); 
	  	
	  	
	  	 
     
        /*$this->grocery_crud->where('id_sede',$this->session->userdata('sede_filtro'));
	   	$this->grocery_crud->where('tipo_pedido','E');
	   	$this->grocery_crud->or_where('tipo_pedido','N');*/
		$this->grocery_crud->set_js("assets/js/custom.js");
		//$this->grocery_crud->set_js(base_url() . "custom.js");
		$this->grocery_crud->columns('fecha_pedido', 'estado_pedido', 'observaciones','nro_pedido', 'nro_tk', 'id_proveedor', 'Acciones');
		$this->grocery_crud->display_as('id_proveedor','Proveedor');
		$this->grocery_crud->add_fields('tipo','fecha_pedido','estado_pedido','observaciones', 'nro_pedido', 'nro_tk', 'id_sede', 'id_proveedor','tipo_pedido');
		$this->grocery_crud->edit_fields('observaciones','nro_tk', 'id_proveedor' );
		$this->grocery_crud->set_relation('id_proveedor','proveedores','nombre_proveedor'); 
		
		//set validations
       
          $this->grocery_crud->set_rules('id_proveedor', 'Proveedor','trim|required');
          //$this->grocery_crud->callback_before_delete(array($this,'before_delete'));
		 // $this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar la sede, la misma posee registros asociados');
		 // $this->grocery_crud->set_lang_string('delete_success_message', 'La sede se ha eliminado correctamente');	

		/**
		* 
		* @var /creo dos campos fecha que no estan
		* 
		*/
		//el campo no existe en la bd, con el callback solo lo hago para capturar el rango de fechas q va por parametro a la funcion que va generar el detalle del pedido

		
		$this->grocery_crud->unset_edit();
		$this->grocery_crud->unset_delete();
		if ($this->grocery_crud->getState() == 'add') 
			{
			$this->grocery_crud->change_field_type('estado_pedido','invisible');
			$this->grocery_crud->change_field_type('fecha_pedido','invisible');
    		$this->grocery_crud->change_field_type('id_sede','invisible');
    		$this->grocery_crud->change_field_type('nro_pedido','invisible');
    		$this->grocery_crud->change_field_type('tipo_pedido','invisible');
      
			}

$this->grocery_crud->unset_read();//delete/x
$this->grocery_crud->callback_column('Acciones',array($this,'callback_webpage_url2'));
$this->grocery_crud->callback_add_field('tipo',array($this,'edit_field_callback_1'));
//$this->grocery_crud->callback_add_field('prueba',array($this,'add_field_callback_2'));


$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));               
          
$this->grocery_crud->callback_after_insert(array($this,'after_insert1'));
$this->grocery_crud->set_lang_string('insert_success_message',
            'Generando el pedido...<script type="text/javascript">
            window.location = "'.site_url('detalle_pedido/genera'.'/'.$this->session->userdata('liid')).'";
            </script><div style="display:none">');
   
  
$output = $this->grocery_crud->render();
$output->content_view='crud_content_view';
  $state = $this->grocery_crud->getState();

    if($state == 'add')
    {
        $output->css_files[] = base_url().'assets/css/pedidos.css';
       
    }


$this->_example_output($output);
}

//DESDE ESTE CRUD CARGO PEDIDO INTERNOS ENTRE Sedes

      function listar2(){
      	
      	
     	 //si hubo cambio de sede actualizo permisos y filtro sede
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
    	else
    	{
			$sede_consulta= $this->general_model->ou_sede_id($this->session->userdata('sede'));
		}
			$this->grocery_crud->set_theme('Datatables');
      	$this->grocery_crud->set_language('spanish');
      	$this->grocery_crud->set_table('pedidos');
      	
      /*   
	  filtro por proveedor y sede, por que en los envios internos debo ver mis pedidos y envios (valido de otra forma las acciones posibles sobre cada uno)
	  */
     
        $where = "tipo_pedido='P' AND (id_sede='".$this->session->userdata('sede_filtro')."' OR id_proveedor='".$this->session->userdata('sede_filtro')."')";
	  	$this->grocery_crud->or_where($where);  
     
       /*	$this->grocery_crud->where('id_sede',$this->session->userdata('sede_filtro'));  
      	//muestro los dos tipos de pedidos interno
      	$this->grocery_crud->where('tipo_pedido','P');*/
		
      	
      	$this->grocery_crud->set_js(base_url() . "assets/js/custom.js");
		$this->grocery_crud->columns('fecha_pedido', 'estado_pedido', 'observaciones','nro_pedido', 'nro_tk','id_proveedor', 'id_sede', 'Acciones');
		$this->grocery_crud->add_fields('tipo','tipo_pedido','fecha_pedido','estado_pedido','observaciones', 'nro_pedido', 'nro_tk', 'id_sede', 'id_proveedor');
		$this->grocery_crud->display_as('fecha_pedido','Fecha Envio');
		$this->grocery_crud->display_as('estado_pedido','Estado Envio');
		$this->grocery_crud->display_as('nro_pedido','Nro Envio');
		$this->grocery_crud->display_as('id_proveedor','Proveedor');
		$this->grocery_crud->display_as('id_sede','Solicitante');
		$this->grocery_crud->edit_fields('observaciones','nro_tk', 'id_proveedor' );
		//$this->grocery_crud->set_relation('id_proveedor','sedes','nombre_sede'); 
		$this->grocery_crud->set_relation('id_sede','sedes','nombre_sede'); 
		$this->grocery_crud->set_relation('id_proveedor','sedes','nombre_sede', 'nombre_sede <> "'.$this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro')).'"');
	 
			
		$this->grocery_crud->unset_edit();
		$this->grocery_crud->unset_delete();
		//set validations
       
          $this->grocery_crud->set_rules('id_proveedor', 'Proveedor','trim|required');
		if ($this->grocery_crud->getState() == 'add') 
			{
			$this->grocery_crud->change_field_type('estado_pedido','invisible');
			$this->grocery_crud->change_field_type('fecha_pedido','invisible');
    		$this->grocery_crud->change_field_type('nro_pedido','invisible');
      		$this->grocery_crud->change_field_type('tipo_pedido','invisible');
      		//$this->grocery_crud->change_field_type('id_proveedor','invisible');
			}

$this->grocery_crud->unset_read();//delete/x
$this->grocery_crud->callback_column('Acciones',array($this,'callback_webpage_url2'));
$this->grocery_crud->callback_before_insert(array($this,'before_insert2'));               
$this->grocery_crud->callback_add_field('tipo',array($this,'edit_field_callback_3'));
$this->grocery_crud->callback_add_field('id_sede',array($this,'add_field_callback_4'));
$this->grocery_crud->callback_after_insert(array($this,'after_insert2'));
$this->grocery_crud->set_lang_string('insert_success_message',
            'El pedido/envio se esta generando...<script type="text/javascript">
            window.location = "'.site_url('detalle_pedido/genera_envio'.'/'.$this->session->userdata('liid')).'";
            </script>');
            

   
  
$output = $this->grocery_crud->render();

  $state = $this->grocery_crud->getState();

    if($state == 'add')
    {
        $output->css_files[] = base_url().'assets/css/pedidos.css';
       
    }

$output->content_view='crud_content_view';
$this->_example_output($output);
}

// para envios internos entre sedes


      function listar3(){
      	
      	
      	 	 //si hubo cambio de sede actualizo permisos y filtro sede
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
    	else
    	{
			$sede_consulta= $this->general_model->ou_sede_id($this->session->userdata('sede'));
		}
			$this->grocery_crud->set_theme('Datatables');
      	$this->grocery_crud->set_language('spanish');
      	$this->grocery_crud->set_table('pedidos');
      	
      /*   
	  filtro por proveedor y sede, por que en los envios internos debo ver mis pedidos y envios (valido de otra forma las acciones posibles sobre cada uno)
	  */
	  $where = "tipo_pedido='I' AND (id_sede='1' OR id_proveedor='1')";
	  	$this->grocery_crud->or_where($where);  
     
      	
      	$this->grocery_crud->set_js(base_url() . "assets/js/custom.js");
		$this->grocery_crud->columns('fecha_pedido', 'estado_pedido', 'observaciones','nro_pedido', 'nro_tk', 'id_proveedor' ,'id_sede', 'Acciones');
		$this->grocery_crud->add_fields('tipo','tipo_pedido','fecha_pedido','estado_pedido','observaciones', 'nro_pedido', 'nro_tk', 'id_sede', 'id_proveedor');
		$this->grocery_crud->display_as('fecha_pedido','Fecha Envio');
		$this->grocery_crud->display_as('estado_pedido','Estado Envio');
		$this->grocery_crud->display_as('nro_pedido','Nro Envio');
		$this->grocery_crud->display_as('id_proveedor','Proveedor');
		$this->grocery_crud->display_as('id_sede','Solicitante');
		$this->grocery_crud->edit_fields('observaciones','nro_tk', 'id_proveedor' );
		$this->grocery_crud->set_relation('id_proveedor','sedes','nombre_sede'); 
		$this->grocery_crud->set_relation('id_sede','sedes','nombre_sede', 'nombre_sede <> "'.$this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro')).'"');
	 
			
		$this->grocery_crud->unset_edit();
		$this->grocery_crud->unset_delete();
		if ($this->grocery_crud->getState() == 'add') 
			{
			$this->grocery_crud->change_field_type('estado_pedido','invisible');
			$this->grocery_crud->change_field_type('fecha_pedido','invisible');
    		$this->grocery_crud->change_field_type('nro_pedido','invisible');
      		$this->grocery_crud->change_field_type('tipo_pedido','invisible');
      		$this->grocery_crud->change_field_type('id_proveedor','invisible');
      		$this->grocery_crud->change_field_type('tipo','invisible');
			}

$this->grocery_crud->unset_read();//delete/x
$this->grocery_crud->callback_column('Acciones',array($this,'callback_webpage_url2'));

$this->grocery_crud->callback_add_field('id_proveedor',array($this,'add_field_callback_5'));
$this->grocery_crud->callback_before_insert(array($this,'before_insert3'));               
//$this->grocery_crud->callback_add_field('tipo',array($this,'edit_field_callback_2'));
$this->grocery_crud->callback_after_insert(array($this,'after_insert2'));
$this->grocery_crud->set_lang_string('insert_success_message',
            'El pedido/envio se esta generando...<script type="text/javascript">
            window.location = "'.site_url('detalle_pedido/genera_envio'.'/'.$this->session->userdata('liid')).'";
            </script>');
   
  
$output = $this->grocery_crud->render();

  $state = $this->grocery_crud->getState();

    if($state == 'add')
    {
        $output->css_files[] = base_url().'assets/css/pedidos.css';
       
    }

$output->content_view='crud_content_view';
$this->_example_output($output);
}


// Solicito datos para generar el informe 
     	 function generar_reporte($cod = '', $error = ''){
     	     
     	  	 //si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
     	$data['error'] = $error;
     				
		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
		 
		$data['proveedores']  = $this->proveedores_model->obtener_proveedores();		
		//cargo vista para realizar recepciones
          $data['content_view']='pedidos/reporte.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}

// Solicito datos para generar el informe 
     	 function generar_reporte2($error = ''){
     	       	 //si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
     	
     	$data['error'] = $error;
     				
		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
		 
		//$data['proveedores']  = $this->proveedores_model->obtener_proveedores();		
		//cargo vista para realizar recepciones
          $data['content_view']='pedidos/reporte2.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}

// valido datos para generar el informe y lo genero
function reporte($error = ''){
      	 //si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
   
     	//set validations
         
          $this->form_validation->set_rules("from", "Desde", "trim|required");
          $this->form_validation->set_rules("to", "Hasta", "trim|required");
     	  $this->form_validation->set_rules("proveedor", "proveedor", "required|is_natural");// valido que seleccione una opcion con id positivo "seleccione" tiene asignado -1
          $this->form_validation->set_message('is_natural', 'Por favor seleccione una opción');

     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails
                 		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     				$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "";
     	      $data['content_view']='pedidos/reporte.php';
     	      	$data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      	
    		  $this->load->view('template',$data);
     	}
     	else
     	{
	
		
	    
     /* capturo fechas seleccionadas y proveedor */
        $proveedor =  $this->input->post('proveedor');
        $data['proveedor']  = $this->proveedores_model->obtener_nombre($proveedor);
     	$data['nombre_proveedor'] = $proveedor;
     	$desde =  $this->input->post('from');
     	// agrego el horario para poder realizar la consulta
     	$data['desde']  = $this->general_model->cambia_sql_normal($desde);
     	$desde  = "'".$desde. " 00:00:00'";
     	$data['fecha_desde'] = $desde;
     	$hasta =  $this->input->post('to');
     	$data['hasta'] = $this->general_model->cambia_sql_normal($hasta);
     	$hasta = "'".$hasta. " 23:59:59'";
     	$data['fecha_hasta'] = $hasta;
     	//genero consulta para enviar
     	$data['query_ad'] = $this->pedidos_model->obtener_pedidos_proveedor($proveedor ,$desde , $hasta, $this->session->userdata('sede_filtro') );

     	If($data['query_ad'] == "-1")
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='pedidos/reporte.php';
     	      	$data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      	
    		  $this->load->view('template',$data);
     		
     	}
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de pedidos";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='pedidos/reporte_pedidos_proveedor';
      $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
			
		
     
		}
     	}
     	  
		
     	  
     	}

// valido datos para generar el informe y lo genero
function reporte2($error = ''){
	//si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
   
     	//set validations
         
          $this->form_validation->set_rules("numero", "Recepción", "trim|required");
          $this->form_validation->set_rules("tipo_recepcion", "tipo de recpción", "required|alpha_numeric");// valido que seleccione una opcion con id positivo "seleccione" tiene asignado -1
          $this->form_validation->set_message('alpha_numeric', 'Por favor seleccione una opción');

     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails
                 		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "";
     	      $data['content_view']='pedidos/reporte2.php';
     	      	//$data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      
    		  $this->load->view('template',$data);
	
	}
	else
	{
		
	
	
     /* capturo fechas seleccionadas y proveedor */
        $tipo =  $this->input->post('tipo_recepcion');
        $nro =  $this->input->post('numero');
        $data['tipo'] = $tipo;
        $data['numero'] = $nro;
     	//genero consulta para enviar
     	$data['query_ad'] = $this->recepcion_model->busca_recepcion($tipo ,$nro );

     	If($data['query_ad'] == "-1")
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='pedidos/reporte2.php';
     	      	//$data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      
    		  $this->load->view('template',$data);
     		
     	}
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de pedidos";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='pedidos/reporte_pedidos_recepcion';
      $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
			
		
     
		}
     	
     	  
		
     	}  
     	}

function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 



  //insert listar 1
function before_insert1($post_array) {
 

if($this->parametros_model->obtener_id_parametro_nombre($this->session->userdata('sede_filtro'),"nro_pedido_e") != NULL)
{
$post_array['estado_pedido'] = "Generado";
$datestring = date("d-m-Y");
$post_array['fecha_pedido'] = $datestring;


if ($post_array['tipo'] == "3")
{
$post_array['tipo_pedido'] = "N";

}
else{
$post_array['tipo_pedido'] = "E";
 
}	
$id_param = $this->parametros_model->obtener_id_parametro_nombre($this->session->userdata('sede_filtro'),"nro_pedido_e");
$valor_param = $this->parametros_model->obtener_parametro($id_param);
$post_array['nro_pedido'] = $valor_param; // ver si esto se parametriza o se define un nombre que no cambie para que no traiga inconvenientes
$post_array['id_sede'] = $this->session->userdata('sede_filtro');
$nuevo_valor = $valor_param + 1;
$this->parametros_model->modifica_parametro($id_param,$nuevo_valor);
$this->session->set_userdata('tipo', $post_array['tipo']);
unset($post_array['tipo']);
return $post_array;
}
else
{return false;}

}   


function before_insert2($post_array) {

if($this->parametros_model->obtener_id_parametro_nombre("0","nro_pedido_i") != NULL)
{
$post_array['estado_pedido'] = "Generado";
$datestring = date("d-m-Y");
$post_array['fecha_pedido'] = $datestring;
//el numero de pedidos interno sera el mismo para todas las sedes 
$id_param = $this->parametros_model->obtener_id_parametro_nombre("0","nro_pedido_i"); 
$valor_param = $this->parametros_model->obtener_parametro($id_param);
$post_array['nro_pedido'] = $valor_param; // ver si esto se parametriza o se define un nombre que no cambie para que no traiga inconvenientes
	$post_array['tipo_pedido'] = "P";
	//$post_array['id_proveedor'] = $post_array['id_sede'];
 	$post_array['id_sede'] =  $this->session->userdata('sede_filtro');

$nuevo_valor = $valor_param + 1;
$this->parametros_model->modifica_parametro($id_param,$nuevo_valor);
$this->session->set_userdata('tipo', $post_array['tipo']);
unset($post_array['tipo']);
return $post_array;
}
else
{return false;}
}
//listar 3
//before insert para envio interno entre sedes
function before_insert3($post_array) {

if($this->parametros_model->obtener_id_parametro_nombre("0","nro_pedido_i") != NULL)
{
$post_array['estado_pedido'] = "Generado";
$datestring = date("d-m-Y");
$post_array['fecha_pedido'] = $datestring;
//el numero de pedidos interno sera el mismo para todas las sedes
$id_param = $this->parametros_model->obtener_id_parametro_nombre("0","nro_pedido_i"); 
$valor_param = $this->parametros_model->obtener_parametro($id_param);
$post_array['nro_pedido'] = $valor_param; // ver si esto se parametriza o se define un nombre que no cambie para que no traiga inconvenientes
$post_array['tipo_pedido'] = "I";
$post_array['id_proveedor'] =  $this->session->userdata('sede_filtro');
$nuevo_valor = $valor_param + 1;
$this->parametros_model->modifica_parametro($id_param,$nuevo_valor);
$this->session->set_userdata('tipo', "-1");//no selecciono tipo en el caso de los envios. debo setear algun valor para que no tome el anterior en la redireccion
unset($post_array['tipo']);
return $post_array;
}
else
{return false;}
}

 

function after_insert1($post_array, $primary_key) {


$this->session->set_userdata('liid', $primary_key);

}

function after_insert2($post_array, $primary_key) {

$this->session->set_userdata('liid', $primary_key);

}

function callback_webpage_url2($value, $row)
{
	if (($this->pedidos_model->consulta_tipo($row->id) == "E" ) || ($this->pedidos_model->consulta_tipo($row->id) == "N" ))
	{$vistaver = 'detalle_pedido/ver';
	$vistarec = 'detalle_pedido/recibir';
	}
	else
	{$vistaver = 'detalle_pedido/ver2';
	$vistarec = 'detalle_pedido/recibir';
	}



switch ($row->estado_pedido) {
    case 'Generado':
    return " 
    <a href='".site_url($vistaver.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Editar</span>
    </a>";
            break;
    case 'Cerrado':
      return " 
    <a href='".site_url($vistaver.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Ver</span>
    </a>";
        break;
    case 'En Proceso':
    
    if ($row->id_sede == $this->session->userdata('sede_filtro'))
    {
	
         return " 
    <a href='".site_url($vistaver.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Ver</span>
    </a>
    
    <a href='".site_url($vistarec.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Recibir</span>
    </a>
    
    <a href='".site_url('pedidos/cerrar'.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Cerrar</span>
    </a>";	
    }
    else
    {
    	     return " 
    <a href='".site_url($vistaver.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Ver</span>
    </a>
    
        
    <a href='".site_url('pedidos/cerrar'.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Cerrar</span>
    </a>";
		
	}
        break;
        
         case 'Pendiente':
         return " 
    <a href='".site_url($vistaver.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Ver</span>
    </a><a href='".site_url($vistarec.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Recibir</span>
    </a><a href='".site_url('pedidos/cerrar'.('/').$row->id)."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'>Cerrar</span>
    </a>";	
        break;
}
    
    
  
}
// esto lo hago para quitar el boton grabar del formulario add ya que el mismo me traia problemas al redireccionar, no asi el guardar y volver a lista
function edit_field_callback_1($value, $row)
{

   return ' <select id="tipo" name="tipo">
        <option value="2" selected>Automatica (Consumo)</option>
        <option value="1">Automatica (Stock Minimo)</option>
        <option value="0">Manual</option>
        <option value="3">Ingreso</option>
      </select>';
    

  
}
	
	function edit_field_callback_2($value, $row)
{

   return ' <select id="tipo" name="tipo">
        <option value="2" selected>Automatica (Consumo)</option>
        <option value="1">Automatica (Stock Minimo)</option>
        <option value="0">Manual</option>
        <option value="3">Envio</option>
      </select>';
    

  
}
//para listar 2
	function edit_field_callback_3($value, $row)
{

   return ' <select id="tipo" name="tipo">
        <option value="2" selected>Automatica (Consumo)</option>
        <option value="1">Automatica (Stock Minimo)</option>
        <option value="0">Manual</option>
             </select>';
    
 
  
}

//para listar 2
	function add_field_callback_4($value, $row)
{
$sede= $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
   return $sede;
    

  
}

//para listar 3
	function add_field_callback_5($value, $row)
{
$sede= $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
   return $sede;
    

  
}


}
?>