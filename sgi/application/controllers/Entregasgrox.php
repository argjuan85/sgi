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
			$this->load->model('insumos_model');
			$this->load->model('impresoras_model');
	}
	
	function _example_output($output = null)
	{
		$this->load->view('example.php', $output);	
	}
	
	function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	
	function alistar()
	{
		$data['impresora'] = $this->impresoras_model->impresoras();
		$this->load->view('entregas_view',$data);

		}
		
	
	
		public function llena_insumos()
	{
		$options = "";
		if($this->input->post('impresora'))
		{
			$impresora = $this->input->post('impresora');
			$insumos = $this->impresoras_model->insumos($impresora);
			
			foreach($insumos as $fila)
			{?>
			
    	 <option value="<?$fila['id']?>"><?echo $fila['codigo']?></option>
  
			
			<?}						
		
		}
		
	
			
		
	}
	
	/*
	* ESTE SOLO REDIRECCIONA, NO AGREGA REGISTROS EN BASE A ID DE IMPRESORA NI INSUMO, ES GENERICO
	*/
	function listar()
	{
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();
			$crud->set_table('entregas');
			$crud->set_language('spanish');
			$crud->set_relation('id_impresora','impresoras','codigo');
			$crud->set_relation('id_sector','sectores','nombre');
			$crud->columns('id_insumo','id_impresora','id_sector','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_impresora','Impresora')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Customer');
			$crud->set_relation('id_insumo','Insumos','codigo');
			if ($this->grocery_crud->getState() == 'add') 
{

     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('estado','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
}
			if ($this->grocery_crud->getState() == 'edit') 
{

     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
    
}
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
			$crud->callback_add_field('id_sector', array($this, 'empty_state_dropdown_select'));
			$crud->callback_edit_field('id_sector', array($this, 'empty_state_dropdown_select'));
									
			$output = $crud->render();
			
			/*if ($crud->getState() == 'add')
			{
				redirect('entregas/crear/', 'refresh');
			
			}*/
			
					
			
			
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_impresora','id_sector'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			
			$this->_example_output($output);
	}	
	/* Agrega registros a partir de un id de insumo */
	function listar1($id_insumo = '')
	{
			
			
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();
			$crud->set_js(base_url() . "assets/js/test.js");
				$this->session->set_userdata('insumo', $id_insumo);
			$crud->set_table('entregas');
			$crud->set_language('spanish');
			$crud->set_relation('id_impresora','impresoras','codigo');
			$crud->set_relation('id_sector','sectores','nombre');
			$crud->columns('id_insumo','id_impresora','id_sector','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_impresora','Impresora')
				 ->display_as('id_sector','Sector');
			//$crud->set_subject('Customer');
			//$crud->set_relation('id_insumo','Insumos','codigo');
			if ($this->grocery_crud->getState() == 'add') 
{

     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('id_insumo','invisible');
     $crud->change_field_type('estado','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
}
			if ($this->grocery_crud->getState() == 'edit') 
{
$a = "3";//$this->uri->segment(4);
     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','readonly');
     $crud->set_relation('id_insumo','Insumos','codigo', 'id='.$a );
     $crud->set_js(base_url() . "assets/js/test.js");
    
  
    
}
			$crud->callback_add_field('id_insumo', array($this, 'carga_insumo2'));
			$crud->callback_add_field('id_impresora', array($this, 'carga_insumo'));
		
			$crud->callback_edit_field('id_impresora', array($this, 'carga_insumo1'));
					$crud->callback_before_insert(array($this,'before_insert1')); 
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
		/*	$crud->callback_add_field('id_sector', array($this, 'empty_state_dropdown_select'));
		*/	$crud->callback_edit_field('id_sector', array($this, 'empty_state_dropdown_select'));
									
			$output = $crud->render();
			
			
			
			
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_impresora','id_sector'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			
			$this->_example_output($output);
	}
	/* Agrega registros a partir de un id de impresora */
	function listar2($id_impresora = '')
	{
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();
			$this->session->set_userdata('id_impresora', $id_impresora);
			$crud->set_table('entregas');
			$crud->set_language('spanish');
			$crud->set_relation('id_impresora','impresoras','codigo');
			$crud->set_relation('id_sector','sectores','nombre');
			$crud->columns('id_insumo','id_impresora','id_sector','fecha_entrega','estado','observaciones','usuario_entrega');
			$crud->display_as('id_insumo','Insumo')
				 ->display_as('id_impresora','Impresora')
				 ->display_as('id_sector','Sector');
			$crud->set_subject('Customer');
			$crud->set_relation('id_insumo','Insumos','codigo');
			if ($this->grocery_crud->getState() == 'add') 
{

     $crud->change_field_type('id_sede','invisible');
     $crud->change_field_type('estado','invisible');
     $crud->change_field_type('usuario_entrega','invisible');
     $crud->change_field_type('fecha_entrega','invisible');
}
			if ($this->grocery_crud->getState() == 'edit') 
{

     $crud->change_field_type('id_sede','invisible');
      $crud->change_field_type('usuario_entrega','invisible');
    
}
			
			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
			$crud->callback_add_field('id_sector', array($this, 'empty_state_dropdown_select'));
			$crud->callback_edit_field('id_sector', array($this, 'empty_state_dropdown_select'));
									
			$output = $crud->render();
			
		
			
			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
				//GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
				'dd_state' =>  $crud->getState(),
				//SETUP YOUR DROPDOWNS
				//Parent field item always listed first in array, in this case countryID
				//Child field items need to follow in order, e.g stateID then cityID
				'dd_dropdowns' => array('id_impresora','id_sector'),
				//SETUP URL POST FOR EACH CHILD
				//List in order as per above
				'dd_url' => array('', site_url().'/entregas/get_states/'),
				//LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
				'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;
			
			$this->_example_output($output);
	}
	
	 function crear(){
     	
     	 $this->load->view('entregas/crear.php'); 
     	
		}
	//CALLBACK FUNCTIONS
	
	function carga_insumo($value, $row)
{
  $data = $this->entregas_model->obtener_impresoras($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la impresora para mostrar el sector)
//$this->session->set_userdata('impresora', $filas['id']);
$html = "<select id='field-id_impresora'  name='id_impresora' class='chosen-select' data-placeholder='Seleccionar Impresora' style='width:300px'>";
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
$id_insumo = $this->entregas_model->obtener_idinsumo($primary_key);
 $data = $this->entregas_model->obtener_impresoras($id_insumo);
 /* fin prueba */
 // $data = $this->entregas_model->obtener_impresoras($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la impresora para mostrar el sector)
//$this->session->set_userdata('impresora', $filas['id']);
$html = "<select id='field-id_impresora'  name='id_impresora' class='chosen-select' data-placeholder='Seleccionar Impresora' style='width:300px'>";
	 $html = $html."<option value=''></option>";
	foreach ($data as $filas)
	{
         if ( $filas['id'] == $value)
         {
		$html = $html."<option value='".$filas['id']."' selected>".$filas['codigo'].'</option>'; 	
		 
		 }
		 else
		 {
		 
         $html = $html."<option value='".$filas['id']."' >".$filas['codigo'].'</option>';
    	}
     }
     $html=$html.'</select>';
  	 return $html;
  	
}



function carga_insumo2($value, $row)
{

  $data = $this->insumos_model->obtener_codigo($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la impresora para mostrar el sector)
//$this->session->set_userdata('impresora', $filas['id']);
/*$html = "<select id='field-id_impresora'  name='id_impresora' class='chosen-select' data-placeholder='Seleccionar Impresora' style='width:300px'>";
	 $html = $html."<option value=''></option>";
foreach ($data as $filas)
	{
         $html = $filas['codigo'];
     }*/
    // $html=$html.'</select>';*/
  	 return $data;
}



	
	
	function empty_state_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		//$empty_select = '';
		$empty_select = '<select disabled name="id_sector" class="chosen-select" data-placeholder="Sector" style="width: 300px; display: none;" >';
		$empty_select_closed = '</select>';
		//$empty_select_closed = '';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {
			//GET THE STORED STATE ID
			$this->db->select('*')
					 ->from('entregas')
					 ->where('id', $listingID);
			$db = $this->db->get();
			$row = $db->row(0);
			//$countryID = $row->id_impresora;
			$stateID = $row->id_sector;
			
			//GET THE STATES PER COUNTRY ID
			$this->db->select('*')
					 ->from('sectores')
					 ->where('id', $stateID);
			$db = $this->db->get();
			
			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->id == $stateID) {
					$empty_select .= '<option value="'.$row->id.'" selected="selected">'.$row->nombre.'</option>';
				} 
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
		}
	}
/*	function empty_city_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="cityID" class="chosen-select" data-placeholder="Select City/Town" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);
		
		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();
		
		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {
			//GET THE STORED STATE ID
			$this->db->select('stateID, cityID')
					 ->from('customers')
					 ->where('customerNumber', $listingID);
			$db = $this->db->get();
			$row = $db->row(0);
			$stateID = $row->stateID;
			$cityID = $row->cityID;
			
			//GET THE CITIES PER STATE ID
			$this->db->select('*')
					 ->from('city')
					 ->where('stateID', $stateID);
			$db = $this->db->get();
			
			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->city_id == $cityID) {
					$empty_select .= '<option value="'.$row->city_id.'" selected="selected">'.$row->city_title.'</option>';
				} else {
					$empty_select .= '<option value="'.$row->city_id.'">'.$row->city_title.'</option>';
				}
			endforeach;
			
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;	
		}
	}*/
		
		function before_insert1($post_array) {

   
$post_array['id_insumo'] = $this->session->userdata('insumo');
$post_array['estado'] = "Entregado";
//$post_array['id_impresora'] = $this->session->userdata('impresora');
$post_array['id_sede'] = "1";
$post_array['id_sector'] =  $this->entregas_model->obtener_sector($post_array['id_impresora']);
$post_array['usuario_entrega'] = "-";
//$this->session->set_userdata('tipo', $post_array['tipo']);
 //unset($post_array['tipo']);
return $post_array;
} 


			
	//GET JSON OF STATES
	function get_states()
	{
		$id_impresora = $this->uri->segment(3);
		$this->db->select("id_sector")
				 ->from('impresoras')
				 ->where('id', $id_impresora);
		$db1 = $this->db->get();
		foreach($db1->result() as $row):
			$id_sector = $row->id_sector;
		endforeach;
		
		$this->db->select("*")
				 ->from('sectores')
				 ->where('id', $id_sector);
		$db = $this->db->get();
		
		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->id, "property" => $row->nombre);
		endforeach;
		//$array[] = array("value" => "5", "property" => "hola");
		echo json_encode($array);
		
		exit;
	}
	/*
	//GET JSON OF CITIES
	function get_cities()
	{
		$stateID = $this->uri->segment(3);
		
		$this->db->select("*")
				 ->from('city')
				 ->where('stateID', $stateID);
		$db = $this->db->get();
		
		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->city_id, "property" => $row->city_title);
		endforeach;
		
		echo json_encode($array);
		exit;
	}*/
}