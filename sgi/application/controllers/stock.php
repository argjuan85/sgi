<?php
class Stock extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->library('session');
                $this->load->model('sedes_model');
                 $this->load->model('stock_model');
                 $this->load->model('auth_model');
                $this->load->database();

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/stock/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('sectores/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

      function listar(){
      	
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
      	
$this->grocery_crud->set_table('stock');
$this->grocery_crud->set_theme('Datatables');
if ($this->session->userdata('sede_filtro'))
        $this->grocery_crud->where('id_sede',$this->session->userdata('sede_filtro'));
	  	else
      	$this->grocery_crud->where('id_sede',$sede_consulta);

$this->grocery_crud->set_language('spanish');
$this->grocery_crud->columns('id_insumo','stock_real','stock_minimo','cant_reorden');
$this->grocery_crud->add_fields('id_insumo','stock_real','stock_minimo','cant_reorden','habilitado','id_sede');
$this->grocery_crud->edit_fields('id_insumo','stock_real','stock_minimo','cant_reorden','habilitado');
$this->grocery_crud->set_relation('id_insumo','insumos','codigo_insumo'); 

//$this->grocery_crud->set_relation('id_sede','sedes','nombre_sede'); 
$this->grocery_crud->unset_read_fields('id_sede','habilitado');	
$this->grocery_crud->display_as('id_insumo','Codigo de Insumo');
$this->grocery_crud->display_as('id_sede','Sede');
if ($this->grocery_crud->getState() == 'add') 
{

     $this->grocery_crud->change_field_type('habilitado','invisible');
     $this->grocery_crud->change_field_type('id_sede','invisible');
}
if ($this->grocery_crud->getState() == 'edit') 
{

     $this->grocery_crud->change_field_type('habilitado','invisible');
     $this->grocery_crud->change_field_type('id_sede','invisible');
     $this->grocery_crud->field_type('stock_real','readonly'); 
}
$this->grocery_crud->unset_add();
//$this->grocery_crud->unset_edit();
$this->grocery_crud->unset_read();
$this->grocery_crud->unset_delete();


//$this->grocery_crud->callback_column('habilitado',array($this,'_callback_columna'));
 //$this->grocery_crud->change_field_type('id_sede','invisible');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));
//$this->grocery_crud->callback_edit_field('habilitado',array($this,'edit_field_callback_1'));
$output = $this->grocery_crud->render();
$output->content_view='crud_content_view';
$this->_example_output($output);

}

function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 




function before_insert1($post_array) {

   
$post_array['habilitado'] = "1";
$post_array['id_sede'] = $this->session->userdata('sede_filtro');

  return $post_array;
}

//reemplazo columnas en el listado gral 
public function _callback_columna($value, $row)
{
	if ($value == "1")
	{
return "Si";		
	}
  else
  {
  	return "No";
  }
}


 
 
 
}
   