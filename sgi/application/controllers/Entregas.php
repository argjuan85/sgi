<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregas extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		/* Standard Libraries */
		$this->load->database();
		$this->load->helper('url');
		/* ------------------ */	
		  $this->load->library('session');
		$this->load->library('grocery_CRUD');	
		$this->load->model('entregas_model');
		$this->load->model('sedes_model');
	    $this->load->model('sectores_model');
		$this->load->model('stock_model');
		$this->load->model('general_model');
		$this->load->model('insumos_model');
		$this->load->model('equipos_model');
		$this->load->model('parametros_model');
		$this->load->model('proveedores_model');
		$this->load->helper('array');
			//aca va la sede del user logueado
			//$this->session->set_userdata('sede', "15");
			$this->session->set_userdata('usuario', "jarganaraz");
	
	}
	
function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 
	
	function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	
	
	//no se usa de momento
	function alistar()
	{
		$data['equipo'] = $this->equipos_model->equipos();
		$data['insumo'] = $this->equipos_model->insumos1();
		//$data['test'] =  $this->insumos_model->decrementa_stock('12'); 
		
		
		//cargo vista para realizar recepciones
        $data['content_view']='entregas_view';
     	//template diferente para vistas que no incluyen grocery 
    	$this->load->view('template2',$data);
		
		}
		
	
	
		public function llena_insumos()
	{
		$options = "";
		if($this->input->post('equipo'))
		{
			$equipo = $this->input->post('equipo');
			$insumos = $this->equipos_model->insumos($equipo);
			
			foreach($insumos as $fila)
			{?>
			
    	 <option value="<?$fila['id']?>"><?echo $fila['codigo_insumo']?></option>
  
			
			<?}						
		
		}
		
	
			
		
	}
	
			public function llena_equipos()
	{
		$options = "";
		if($this->input->post('insumo1'))
		{
			$insumos = $this->input->post('insumo1');
			$equipos = $this->equipos_model->equipos1($insumos);
			
			
			foreach($equipos as $fila)
			{?>
			
    	 <option value="<?$fila['id']?>"><?echo $fila['codigo_equipo']?></option>
  
			
			<?}						
		
		}
		
	
			
		
	}
	
	/*
	* CRUD ESTANDAR, de aca se hacen las consultas y edit. los  se redireccionan segun criterio a listar 1 y 2, de momento solo se hace add por listar1 y lo demas por listar, si hay tiempo se deja el listar 2
	*/
	function listar()
	{
		
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
		
		
			//$permiso_sede = $this->session->userdata('sede');
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();
			$crud->set_table('entregas');
			$crud->set_theme('Datatables');
			$aux=$this->session->userdata('sede_filtro');
		if ($this->session->userdata('sede_filtro'))
        {$crud->where('entregas.id_sede',$this->session->userdata('sede_filtro'));}
	  	else
      	{$crud->where('entregas.id_sede',$sede_consulta);}
			//$crud->where('entregas.id_sede',$permiso_sede);
		
			$crud->set_language('spanish');
			$crud->unset_delete();
			
			//$crud->set_relation('id_proveedor','equipos','id_proveedor');		
			
			$crud->columns('id_equipo','id_insumo','id_sector','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->add_fields('id_sede','id_sector','estado','id_equipo','id_insumo','fecha_entrega','observaciones','usuario_entrega', 'cantidad');
			
			$crud->edit_fields('id_sede','id_sector','estado','id_equipo','id_insumo','fecha_entrega','observaciones','usuario_entrega', 'cantidad');
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_equipo','Equipo')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Entrega');
			
			   $crud->set_relation('id_insumo','Insumos','codigo_insumo','habilitado="1"');
          	
           $crud->set_relation('id_equipo','Equipos','codigo_equipo');
      
			
			//la validacion del estado debe estar presente en todas las instancias
		    $crud->set_rules('estado', 'estado','required');
		    
			
			
			if ($crud->getState() != 'add') 
{
	        $crud->set_relation('estado','parametros','valor','nombre_parametro="estado_entrega" and habilitado="1"');
	      
	
}
			
			//por algun no olcutaba el campo preguntando si el estado era add y era a causa del relation...  y como la necestio en list y edit lo deje asi

// idem estado pero con sector
			if (($crud->getState() != 'add') and ($crud->getState() != 'edit'))
{
	       
	        $crud->set_relation('id_sector','sectores','nombre_sector');
	
}

			if ($crud->getState() == 'add') 
{
	//trabajo el add por listar1 el 2 no se usa.
	redirect('/entregas/listar1/add');
	$crud->change_field_type('id_sector','invisible');
	$crud->change_field_type('estado','invisible');
     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
     $crud->change_field_type('cantidad','invisible');
     //coloco las validaciones aca para que no las haga innecesariamente en el edit
 $crud->set_relation('id_equipo','equipos','codigo_equipo', 'estado_equipo='.$this->parametros_model->obtener_id_parametro("estado equipo","Operativa"));
 	$crud->set_relation('id_insumo','Insumos','codigo_insumo','habilitado="1"');
 	
			$crud->set_rules('id_equipo', 'Equipo','trim|required');
		   
		   
		        //pongo validacion aca si no me chequea el stock al modificar
      $crud->set_rules('id_insumo', 'id_insumo','trim|required|callback_insumo_check');
			
}
			if ($this->grocery_crud->getState() == 'edit') 
{
	/*$a = $this->uri->segment(4);
	$b = '/entregas/listar1/edit/'.$a;
     redirect($b);*/
     	   $crud->change_field_type('id_sede','invisible');
      	   $crud->change_field_type('usuario_entrega','invisible');
           $crud->change_field_type('fecha_entrega','invisible');
           $crud->change_field_type('id_sector','invisible');
           $crud->change_field_type('cantidad','invisible');
           //$crud->set_relation('id_insumo','Insumos','codigo_insumo','habilitado="1"');
           $crud->field_type('id_insumo','readonly'); 	
          // $crud->set_relation('id_equipo','Equipos','codigo_equipo');
           $crud->field_type('id_equipo','readonly');
    
}
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
		
		    $crud->callback_add_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			//$crud->callback_edit_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			$crud->callback_after_insert(array($this, 'after_insert1'));
			$crud->callback_before_insert(array($this,'before_insert1')); 	
			$crud->callback_before_update(array($this,'before_update1'));
			$crud->callback_after_update(array($this, 'after_update1'));
	
				
			$output = $crud->render();
			
			
					
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_equipo','id_insumo'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			
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
		 
		
		$data['equipos']  = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro'));		
		//cargo vista para realizar recepciones
          $data['content_view']='entregas/reporte.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}
     	
     	// Solicito datos para generar el informe 
     	 function generar_reporte2($cod = '', $error = ''){
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
		
		//cargo vista para realizar recepciones
          $data['content_view']='entregas/reporte2.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}
 
     	// Solicito datos para generar el informe 
     	 function generar_reporte3($cod = '', $error = ''){
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
		 
		$data['sectores']  = $this->sectores_model->obtener_sectores();		
		//cargo vista para realizar recepciones
          $data['content_view']='entregas/reporte3.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}
  
     	// Solicito datos para generar el informe 
     	 function generar_reporte4($cod = '', $error = ''){
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
          $data['content_view']='entregas/reporte4.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template2',$data);
      
     
     	}
     	
function tester (){
	$data['error'] = "aaa";
     						
		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
		 
		$data['equipos']  = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro'));		
		
 $this->load->view('entregas/reporte1.php',$data);
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
     	  $this->form_validation->set_rules("codigo", "equipo", "required|is_natural");// valido que seleccione una opcion con id positivo "seleccione" tiene asignado -1
          $this->form_validation->set_message('is_natural', 'Por favor seleccione una opción');

     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails
                 		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "";
     	      $data['content_view']='entregas/reporte.php';
     	      $data['equipos'] = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro')); 
    		  $this->load->view('template',$data);
          }
          else
          {
     	  /* capturo fechas seleccionadas */
        $equipo =  $this->input->post('codigo');
        $data['equipo']  = $this->equipos_model->obtener_codigo($equipo);
     	$data['codigo_equipo'] = $equipo;
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
     	$data['query_ad'] = $this->entregas_model->obtener_consumo_equipo($equipo ,$desde , $hasta );

     	If($data['query_ad'] == "-1")// consulta vacia
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='entregas/reporte.php';
     	      $data['equipos'] = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro')); 
    		  $this->load->view('template',$data);
     		
     	}
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de consumos";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='entregas/reporte_consumo_equipos';
     $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
		     
		} 	}  	}


// valido datos para generar el informe y lo genero
function reporte2($error = ''){
		//si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
   
     	//set validations
         
          $this->form_validation->set_rules("from", "Desde", "trim|required");
          $this->form_validation->set_rules("to", "Hasta", "trim|required");
     	  
     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails, cargo vista nuevamente
               //cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "";
     	      $data['content_view']='entregas/reporte2.php';
     	      //$data['equipos'] = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro')); 
    		  $this->load->view('template',$data);
	}
	 else
          {
     /* capturo echas seleccionadas */
        //$equipo =  $this->input->post('codigo');
        //$data['equipo']  = $this->equipos_model->obtener_codigo($equipo);
     	//$data['codigo_equipo'] = $equipo;
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
     	$data['query_ad'] = $this->entregas_model->obtener_consumo_insumos($desde , $hasta, $this->session->userdata('sede_filtro') );

     	If($data['query_ad'] == "-1")
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='entregas/reporte2.php';
     	      //$data['equipos'] = $this->equipos_model->obtener_equipos_sede($this->session->userdata('sede_filtro')); 
    		  $this->load->view('template',$data);
     		
     	}
     	
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de consumos por Código";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='entregas/reporte_consumo_equipos2';
      $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
				
     
		}
         	  
     	}
     	}

// valido datos para generar el informe y lo genero
function reporte3($error = ''){
			  	 //si hubo cambio de sede actualizo permisos y filtro sede (lo hago aca en las vistas que no incluyen cruds)
      	 	  if(isset($_POST['insumo']))
    	{
        $sede_consulta = $this->input->post('insumo');//sede nueva
        $this->auth_model->cambio_sede($sede_consulta);
    
    	}
   
     	//set validations
         
          $this->form_validation->set_rules("from", "Desde", "trim|required");
          $this->form_validation->set_rules("to", "Hasta", "trim|required");
     	  $this->form_validation->set_rules("sector", "sector", "required|is_natural");// valido que seleccione una opcion con id positivo "seleccione" tiene asignado -1
          $this->form_validation->set_message('is_natural', 'Por favor seleccione una opción');

     	 if ($this->form_validation->run() == FALSE)// validacion campos
          {
               //validation fails
                 		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     			     
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		 $data['sectores']  = $this->sectores_model->obtener_sectores();	
     	  	  $data['error'] = "";
     	      $data['content_view']='entregas/reporte3.php';
     	      $data['sectores'] = $this->sectores_model->obtener_sectores(); 
    		  $this->load->view('template',$data);
     			
     			  }
          else
          {
     			
     /* capturo echas seleccionadas */
        $sector =  $this->input->post('sector');
        $data['sector']  = $this->sectores_model->obtener_nombre($sector);
     	//$data['codigo_equipo'] = $equipo;
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
     	$data['query_ad'] = $this->entregas_model->obtener_consumo_sectores($desde , $hasta, $sector );

     	If($data['query_ad'] == "-1")
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		 $data['sectores']  = $this->sectores_model->obtener_sectores();	
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='entregas/reporte3.php';
     	      $data['sectores'] = $this->sectores_model->obtener_sectores(); 
    		  $this->load->view('template',$data);
     		
     	}
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de consumos por Sector";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='entregas/reporte_consumo_equipos3';
      $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
	 
		}	  
     	}
}
// valido datos para generar el informe y lo genero
function reporte4($error = ''){
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
     	      $data['content_view']='entregas/reporte4.php';
     	      $data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      $this->load->view('template',$data);
     	

		 }
          else
          {
	
     /* capturo echas seleccionadas */
        $proveedor =  $this->input->post('proveedor');
        $data['proveedor']  = $this->proveedores_model->obtener_nombre($proveedor);
     	//$data['codigo_equipo'] = $equipo;
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
     	$data['query_ad'] = $this->entregas_model->calcula_rechazados_entrefechas($desde , $hasta, $this->session->userdata('sede_filtro'));

     	If($data['query_ad'] == "-1")
     	{
     		//cargo vista para cargar rengo de fechas nuevamente
     			//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/datepicker/js/jquery-1.7.2.min.js','subariable2'=>base_url().'assets/datepicker/js/jquery-ui-1.8.20.custom.min.js');
		$data['css_files']=array('subariable2'=>base_url().'assets/datepicker/css/ui-lightness/jquery-ui-1.8.20.custom.css', 'subariable3'=>base_url().'assets/css/rangos_fechas.css');		
     		     		
     	  	  $data['error'] = "vacio";
     	      $data['content_view']='entregas/reporte4.php';
     	      $data['proveedores']  = $this->proveedores_model->obtener_proveedores();
     	      
    		  $this->load->view('template',$data);
     		
     	}
     	else
     	{
			
		     	$data['js_files']=array('subvariable1'=> base_url().'assets/DataTables/media/js/jquery.js','subariable2'=>base_url().'assets/DataTables/media/js/jquery.dataTables.js','subariable3'=>base_url().'assets/TableTools/js/dataTables.tableTools.js','subariable4'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.js','subariable5'=>base_url().'assets/DataTables/1.10.2/jquery.dataTables.min.js');

   $data['css_files']=array('subariable2'=>base_url().'assets/DataTables/media/css/jquery.dataTables.css','subariable3'=>base_url().'assets/TableTools/css/dataTables.tableTools.css','subariable4'=>base_url().'assets/TableTools/2.2.2/css/dataTables.tableTools.css','subariable5'=>base_url().'assets/css/reportes.css');
   	 $data['css_files2']=array('subariable2'=>base_url().'assets/css/reportes.css');	
   	 $data['titulo_reporte'] = "Informe de consumos por Código";
   	 $data['sede'] =  $this->sedes_model->obtener_nombre($this->session->userdata('sede_filtro'));
     $data['content_view']='entregas/reporte_consumo_equipos4';
      $data['menu_sede_oculto']="1";
	 $this->load->view('template',$data);
				
     
		}
         	  
     	}
     	}

	/*
	* CRUD ESTANDAR, PARA ENTREGAS A PARTIR DE CODIGO DE EQUIPOS
	*/
	function listar1()
	{
			//GROCERY CRUD SETUP
			
			
			// aca tomo la sede:
			$permiso_sede = $this->session->userdata('sede');
			$crud = new grocery_CRUD();
			$crud->set_table('entregas');
			$crud->set_language('spanish');
			$crud->where('entregas.id_sede',$permiso_sede);
			$crud->unset_delete();
		
			//agregar habilitado
			$crud->set_relation('id_equipo','equipos','codigo_equipo','id_sede= "'.$this->session->userdata('sede_filtro').'" and estado_equipo="'.$this->parametros_model->obtener_id_parametro("estado equipo","Operativa").'"');
			
			$crud->columns('id_insumo','id_equipo','id_sector','nro_ticket','fecha_entrega','estado','observaciones','usuario_entrega');
			//$crud->add_fields('id_sede','id_sector','estado','id_equipo','id_insumo','fecha_entrega','observaciones','nro_ticket','usuario_entrega', 'cantidad', 'contador');
			
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_equipo','Equipo')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Entrega');
					//anulo todas las acciones salvo agregar para usar solo el crud definido en listar
			  $crud->unset_list(); 
			  $crud->unset_delete();
        	  $crud->unset_read();
              $crud->unset_edit();
              $crud->unset_export();
              $crud->unset_print();
			
			$crud->set_relation('id_insumo','Insumos','codigo_insumo', 'habilitado="1"');
			
			//por algun no olcutaba el campo preguntando si el estado era add y era a causa del relation...  y como la necestio en list y edit lo deje asi

			if ($crud->getState() != 'add') 
{
	        $crud->set_relation('estado','parametros','valor','nombre_parametro="estado_entrega" and habilitado="1"');
	      
	
}
// idem estado pero con sector
			if (($crud->getState() != 'add') and ($crud->getState() != 'edit'))
{
	       
	        $crud->set_relation('id_sector','sectores','nombre_sector');
	
}

			if ($crud->getState() == 'add') 
{

$crud->change_field_type('id_sector','invisible');
$crud->change_field_type('estado','invisible');
     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
     $crud->change_field_type('cantidad','invisible');
     
}
			if ($this->grocery_crud->getState() == 'edit') 
{
/*$uri =  $this->uri->segment(3);
    $this->session->set_userdata('insumo_actual', $uri);*/
     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
           $crud->change_field_type('fecha_entrega','invisible');
           $crud->change_field_type('id_sector','invisible');
           $crud->change_field_type('cantidad','invisible');
           	
    
}
		/* validaciones */
		
		$crud->set_rules('id_equipo', 'Equipo','trim|required');
		 $crud->set_rules('id_insumo', 'Insumo','trim|required|callback_insumo_check');
		//$crud->set_rules('observaciones', 'observaciones','trim|required');
		//$crud->set_rules('id_equipo','id_equipo','required');
		//$crud->set_rules('nro_ticket','nro_ticket','numeric');
		//$crud->set_rules('id_insumo', 'Insumo', 'callback_insumo_check');
		//$crud->required_fields('id_equipo','id_insumo');
		
		
		
		
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
		
		    $crud->callback_add_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			$crud->callback_edit_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			$crud->callback_after_insert(array($this, 'after_insert1'));
			
			$crud->callback_before_insert(array($this,'before_insert1')); 	
			$crud->callback_before_update(array($this,'before_update1'));	
			try {
            
            $output = $crud->render(); //this will raise an exception when the action is list

            //$this->template->load('template', 'default_view', $output);
            
        } catch (Exception $e) {

            if ($e->getCode() == 14) {  //The 14 is the code of the error on grocery CRUD (don't have permission).
                //redirect using your user id
                redirect(strtolower(__CLASS__) . '/listar');
                
            } else {
                show_error($e->getMessage());
                return false;
            }
			
			
			}
			
								
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_equipo','id_insumo'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			$output->content_view='crud_content_view';
			$this->_example_output($output);
	}	

	/*
	* reporte de consumo por impresora
	*/
	function reporte000()
	{
			//GROCERY CRUD SETUP
			
			
			// aca tomo la sede:
			$permiso_sede = $this->session->userdata('sede');
			$crud = new grocery_CRUD();
			$crud->set_table('entregas');
			$crud->set_language('spanish');
			$crud->where('entregas.id_sede',$permiso_sede);
			$crud->set_relation('id_equipo','equipos','codigo_equipo','id_sede= "'.$this->session->userdata('sede_filtro').'" and estado_equipo="'.$this->parametros_model->obtener_id_parametro("estado equipo","deshabilitado").'"');
			$crud->columns('id_insumo','id_equipo','id_sector','nro_ticket','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->add_fields('id_sede','id_sector','estado','id_equipo','id_insumo','fecha_entrega','observaciones','nro_ticket','usuario_entrega', 'cantidad', 'contador');
			
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_equipo','Equipo')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Entrega');
					//anulo todas las acciones salvo agregar para usar solo el crud definido en listar
			  $crud->unset_list(); 
			  $crud->unset_delete();
        	  $crud->unset_read();
              $crud->unset_edit();
              $crud->unset_export();
              $crud->unset_print();
			
			$crud->set_relation('id_insumo','Insumos','codigo_insumo');
			
			//por algun no olcutaba el campo preguntando si el estado era add y era a causa del relation...  y como la necestio en list y edit lo deje asi

			if ($crud->getState() != 'add') 
{
	        $crud->set_relation('estado','parametros','valor','nombre_parametro="estado_entrega"');
	      
	
}
// idem estado pero con sector
			if (($crud->getState() != 'add') and ($crud->getState() != 'edit'))
{
	       
	        $crud->set_relation('id_sector','sectores','nombre_sector');
	
}

			if ($crud->getState() == 'add') 
{

$crud->change_field_type('id_sector','invisible');
$crud->change_field_type('estado','invisible');
     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
     $crud->change_field_type('cantidad','invisible');
     
}
			if ($this->grocery_crud->getState() == 'edit') 
{
/*$uri =  $this->uri->segment(3);
    $this->session->set_userdata('insumo_actual', $uri);*/
     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
           $crud->change_field_type('fecha_entrega','invisible');
           $crud->change_field_type('id_sector','invisible');
           $crud->change_field_type('cantidad','invisible');
           	
    
}
		/* validaciones */
		
		$crud->set_rules('id_equipo', 'Equipo','trim|required');
		 $crud->set_rules('id_insumo', 'id_insumo','trim|required|callback_insumo_check');
		//$crud->set_rules('observaciones', 'observaciones','trim|required');
		//$crud->set_rules('id_equipo','id_equipo','required');
		//$crud->set_rules('nro_ticket','nro_ticket','numeric');
		//$crud->set_rules('id_insumo', 'Insumo', 'callback_insumo_check');
		//$crud->required_fields('id_equipo','id_insumo');
		
		
		
		
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
		
		    $crud->callback_add_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			$crud->callback_edit_field('id_insumo', array($this, 'empty_state_dropdown_select'));
			$crud->callback_after_insert(array($this, 'after_insert1'));
			
			$crud->callback_before_insert(array($this,'before_insert1')); 	
			$crud->callback_before_update(array($this,'before_update1'));	
			try {
            
            $output = $crud->render(); //this will raise an exception when the action is list

            //$this->template->load('template', 'default_view', $output);
            
        } catch (Exception $e) {

            if ($e->getCode() == 14) {  //The 14 is the code of the error on grocery CRUD (don't have permission).
                //redirect using your user id
                redirect(strtolower(__CLASS__) . '/listar');
                
            } else {
                show_error($e->getMessage());
                return false;
            }
			
			
			}
			
								
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_equipo','id_insumo'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			$output->content_view='crud_content_view';
			$this->_example_output($output);
	}	


/*
	* CRUD ESTANDAR, PARA ENTREGAS A PARTIR DE CODIGO DE INSUMOS ( de momento no se usa)
	*/
	function listar2()
	{
		
			$permiso_sede = $this->session->userdata('sede');
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();
			$crud->set_table('entregas');
			$crud->where('entregas.id_sede',$permiso_sede);
			$crud->set_language('spanish');
			$crud->set_relation('id_equipo','equipos','codigo_equipo');
					
			$crud->unset_delete();
			$crud->columns('id_insumo','id_equipo','id_sector','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->add_fields('id_sede','id_sector','estado','id_insumo','id_equipo','fecha_entrega','observaciones','nro_ticket','usuario_entrega', 'cantidad');
			
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_equipo','Equipo')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Entrega');
			
			$crud->set_relation('id_insumo','Insumos','codigo_insumo');
			
			//anulo todas las acciones salvo agregar para usar solo el crud definido en listar
			  $crud->unset_list(); 
			  $crud->unset_delete();
        	  $crud->unset_read();
              $crud->unset_edit();
              $crud->unset_export();
              $crud->unset_print();
			
			//por algun no olcutaba el campo preguntando si el estado era add y era a causa del relation...  y como la necestio en list y edit lo deje asi
			if ($crud->getState() != 'add') 
{
	        $crud->set_relation('estado','parametros','valor','nombre_parametro="estado_entrega"');
	      
	
}
// idem estado pero con sector
			if (($crud->getState() != 'add') and ($crud->getState() != 'edit'))
{
	       
	        $crud->set_relation('id_sector','sectores','nombre_sector');
	
}

			if ($crud->getState() == 'add') 
{

$crud->change_field_type('id_sector','invisible');
$crud->change_field_type('estado','invisible');
     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
     $crud->change_field_type('cantidad','invisible');
     
}
			if ($this->grocery_crud->getState() == 'edit') 
{
	$uri =  $this->uri->segment(3);
    $this->session->set_userdata('insumo_actual', $uri);
     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
           $crud->change_field_type('fecha_entrega','invisible');
           $crud->change_field_type('id_sector','invisible');
           $crud->change_field_type('cantidad','invisible');
           	
    
}
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
		
		    $crud->callback_add_field('id_equipo', array($this, 'empty_state_dropdown_select1'));
		    //si no viene del abm insumos la variable de arriba trae el valor "add" caso contrario trae id de insumo y ahi llamo al callback
		    if ($this->uri->segment(3) != "add")
		    {$crud->callback_add_field('id_insumo', array($this, 'carga_insumo1'));}
			
			$crud->callback_edit_field('id_equipo', array($this, 'empty_state_dropdown_select1'));
			$crud->callback_after_insert(array($this, 'after_insert1'));
			$crud->callback_before_update(array($this,'before_update1'));
			$crud->callback_before_insert(array($this,'before_insert1')); 	
				
					
			try {
            
            $output = $crud->render(); //this will raise an exception when the action is list

            //$this->template->load('template', 'default_view', $output);
            
        } catch (Exception $e) {

            if ($e->getCode() == 14) {  //The 14 is the code of the error on grocery CRUD (don't have permission).
                //redirect using your user id
                redirect(strtolower(__CLASS__) . '/listar');
                
            } else {
                show_error($e->getMessage());
                return false;
            }
			
			
			}
			
			
			
			
			
			
			
								
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_insumo','id_equipo'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states1/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			$output->content_view='crud_content_view';
			$this->_example_output($output);
	}	

	
	
	
	 function crear(){
     	
     	 $this->load->view('entregas/crear.php'); 
     	
		}
	//CALLBACK FUNCTIONS ver si se usan si no borrar
	
	
	/**
el editar si se cambia el estado y el insumo implicado no tiene stock, va saltar la validacion rever mas adelante o bien anular la posiblidad de cambiar el insumo
	*/
	        public function insumo_check($str)
        {
             
       //$insumo = $this->insumos_model->obtener_codigo($this->session->userdata('insumo_actual'));
       $stock = $this->stock_model->verifica_stock($str,$this->session->userdata('sede_filtro'));
               
               if (!$stock)
                {
               
                $this->form_validation->set_message('insumo_check', 'No hay suficiente stock del insumo para realizar la entrega');
                        
                        return FALSE;
                }
                else
                {
                        return TRUE;
                }
        }
	
	
	function carga_insumo($value, $row)
{
  $data = $this->entregas_model->obtener_equipos($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la equipo para mostrar el sector)
//$this->session->set_userdata('equipo', $filas['id']);
$html = "<select id='field-id_equipo'  name='id_equipo' class='chosen-select' data-placeholder='Seleccionar equipo' style='width:300px'>";
	 $html = $html."<option value=''></option>";
	foreach ($data as $filas)
	{
         $html = $html."<option value='".$filas['id']."' >".$filas['codigo'].'</option>';
     }
     $html=$html.'</select>';
  	 return $html;
}

	function carga_insumo1($value, $primary_key)
{
	
	/* lineas en prueba */
	
	$id_insumo = $this->uri->segment(3);
	
//id_insumo = $this->entregas_model->obtener_idinsumo($primary_key);
 $data = $this->insumos_model->obtener_insumos_general();
 /* fin prueba */
 // $data = $this->entregas_model->obtener_equipos($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la equipo para mostrar el sector)
//$this->session->set_userdata('equipo', $filas['id']);
$html = "<select id='field-id_insumo'  name='id_insumo' class='chosen-select' data-placeholder='Seleccionar Insumo' style='width:300px'>";
	 $html = $html."<option value=''></option>";
	foreach ($data as $filas)
	{
         if ( $filas['id'] == $id_insumo)
         {
		$html = $html."<option value='".$filas['id']."' selected>".$filas['codigo_insumo'].'</option>'; 	
		 
		 }
		 else
		 {
		 
         $html = $html."<option value='".$filas['id']."' >".$filas['codigo_insumo'].'</option>';
    	}
     }
     $html=$html.'</select>';
  	 return $html;
  	//return $id_insumo;
}



function carga_insumo2($value, $row)
{

  $data = $this->insumos_model->obtener_codigo($this->session->userdata('insumo'));

  	 return $data;
}



	
	
	function empty_state_dropdown_select()
	{
		//creo el select
				// para que no se pueda elegir colocar "diabled" despues de <select
		$empty_select = '<select  name="id_insumo" class="chosen-select" data-placeholder="insumo" style="width: 300px; display: none;" >';
		$empty_select_closed = '</select>';
	
		//id de entrega
		$id_entrega = $this->uri->segment(4);
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//verifico id entrega y estado
		
		if(isset($id_entrega) && $state == "edit") {
			
			
			//GET THE STORED STATE ID
			$this->db->select('*')
					 ->from('entregas')
					 ->where('id', $id_entrega);
			$db = $this->db->get();
			$row = $db->row(0);
			//insumo y equipo asocido a la entrega, lo obtengo para despues saber cual estaba seleccionado al generar el select
			$id_insumo = $row->id_insumo;
			$id_equipo = $row->id_equipo;
			
			//traigo los insumos asociados al equipo
				$this->db->select('insumos.id , insumos.codigo_insumo, insumos.habilitado')
				 ->from('componentes')
				 ->join('insumos', 'insumos.id = componentes.id_insumo', 'inner')
				 ->where('id_equipo', $id_equipo)
				 ->where('habilitado', "1");
			
				$db = $this->db->get();
			
		
			
			//armo el combo
			foreach($db->result() as $row):
		
					
				if($row->id == $id_insumo) {
			
					$empty_select .= '<option value="'.$row->id.'" selected="selected">'.$row->codigo_insumo.'</option>';
				} 
				else
				{
					$empty_select .= '<option value="'.$row->id.'" >'.$row->codigo_insumo.'</option>';	
				}
				
				
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
			//return $id_insumo;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
			//return $id_insumo;
		}
	}
	



function empty_state_dropdown_select1()
	{
		//creo el select
				// para que no se pueda elegir colocar "diabled" despues de <select
		$empty_select = '<select  name="id_equipo" class="chosen-select" data-placeholder="insumo" style="width: 300px; display: none;" >';
		$empty_select_closed = '</select>';
	
		//id de entrega
		$id_entrega = $this->uri->segment(4);
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//verifico id entrega y estado
		
		if(isset($id_entrega) && $state == "edit") {
			
			
			//GET THE STORED STATE ID
			$this->db->select('*')
					 ->from('entregas')
					 ->where('id', $id_entrega);
			$db = $this->db->get();
			$row = $db->row(0);
			//insumo y equipo asocido a la entrega, lo obtengo para despues saber cual estaba seleccionado al generar el select
			$id_insumo = $row->id_insumo;
			$id_equipo = $row->id_equipo;
			
			//traigo los equipos asociados al insumo
			
			   $this->db->select('equipos.id , equipos.codigo_equipo')
				 ->from('componentes')
				 ->join('equipos', 'equipos.id = componentes.id_equipo', 'inner')
				 ->where('id_insumo', $id_insumo)
				 ->where('estado_equipo', $this->parametros_model->obtener_id_parametro("estado equipo","deshabilitado")); 
				$db = $this->db->get();
		
			
			//armo el combo
			foreach($db->result() as $row):
				if($row->id == $id_equipo) {
			
					$empty_select .= '<option value="'.$row->id.'" selected="selected">'.$row->codigo_equipo.'</option>';
				} 
				else
				{
					$empty_select .= '<option value="'.$row->id.'" >'.$row->codigo_equipo.'</option>';	
				}
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
			//return $id_insumo;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
			//return $id_insumo;
		}
	}
	


	
		function before_insert1($post_array) {

$post_array['estado'] = $this->parametros_model->obtener_id_parametro("estado_entrega","Entregada");
$post_array['cantidad'] = "1";
$post_array['id_sede'] = $this->equipos_model->obtener_sede_equipo($post_array['id_equipo']);
$post_array['id_sector'] =  $this->entregas_model->obtener_sector($post_array['id_equipo']);
$post_array['usuario_entrega'] = $this->session->userdata('usuario');

return $post_array;
} 

		function after_insert1($post_array,$primary_key)
{
$this->insumos_model->decrementa_stock($post_array['id_insumo'], $post_array['cantidad'], $this->equipos_model->obtener_sede_equipo($post_array['id_equipo']));   
 return;
 }

	function before_update1($post_array, $primary_key) {

$post_array['cantidad'] = "1";
$post_array['usuario_entrega'] = $this->session->userdata('usuario');
//$insumo_actual = $this->entregas_model->obtener_insumo($primary_key);
/*$estado_entrega_cancelada = "Cancelada";

if (($post_array['estado'] != $estado_entrega_cancelada) && ($insumo_actual != $post_array['id_insumo']))
{
$this->session->set_userdata('actualiza_inventario', "1");	
$this->session->set_userdata('insumo_previo', $insumo_actual );
}*/


return $post_array;
} 

		function after_update1($post_array,$primary_key)
{
	$d = $primary_key;
	
	$estado_entrega_cancelada = $this->parametros_model->obtener_id_parametro("estado_entrega","Cancelada");;// test revisar arreglar aca hay q obtener el id del parametro correspondiente al estado cancelar de las entregas
	$id_equipo = $this->entregas_model->obtener_equipo($d);
	$id_insumo = $this->entregas_model->obtener_insumo($d);
	$c= $this->equipos_model->obtener_sede_equipo($id_equipo);

	//recupero la cantidad original de la entrega ya que del formulario puede venir otro valor
		$cantidad = $this->entregas_model->obtener_cantidad($primary_key);
	//si el estado es cancelada debo incrementar stock del Insumo
	if ($post_array['estado'] == $estado_entrega_cancelada)
	{
		
		
		$this->insumos_model->incrementa_stock($id_insumo, $cantidad, $c);  
 	}
 	
 
	
	}

 

			
	//GET JSON OF STATES
	//esto llena el dropdowwn inicial de equipos.
	function get_states()
	{
		$id_equipo = $this->uri->segment(3);
		$this->db->select('insumos.id , insumos.codigo_insumo')
				 ->from('componentes')
				 ->join('insumos', 'insumos.id = componentes.id_insumo', 'inner')
				 ->where('id_equipo', $id_equipo)
				 ->where('habilitado', "1");
				$db = $this->db->get();
		
		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->id, "property" => $row->codigo_insumo);
		endforeach;
		//$array[] = array("value" => "5", "property" => "hola");
		echo json_encode($array);
		
		exit;
	}
	
	function get_states1()
	{
		$id_insumo = $this->uri->segment(3);
		
		$this->db->select('equipos.id , equipos.codigo_equipo')
				 ->from('componentes')
				 ->join('equipos', 'equipos.id = componentes.id_equipo', 'inner')
				 ->where('id_insumo', $id_insumo)
				 ->where('estado_equipo', $this->parametros_model->obtener_id_parametro("estado equipo","deshabilitado")) ;
				$db = $this->db->get();
		
		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->id, "property" => $row->codigo_equipo);
		endforeach;
		//$array[] = array("value" => "5", "property" => "hola");
		echo json_encode($array);
		
		exit;
	}
	


}