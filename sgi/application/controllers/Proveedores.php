<?php
class Proveedores extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();
                $this->load->library('session');
                $this->load->model('sedes_model');
                $this->load->model('proveedores_model');
                $this->load->model('stock_model');
                 $this->load->model('provee_model');
                  $this->load->model('pedidos_model');
                  $this->load->model('equipos_model');

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/proveedores/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('proveedores/'.$page, $data);
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
$this->grocery_crud->set_table('proveedores');
$this->grocery_crud->set_theme('Datatables');
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->columns('nombre_proveedor','direccion', 'correo','telefono','habilitado');
$this->grocery_crud->add_fields('nombre_proveedor','direccion', 'correo','telefono','habilitado','insumos');
$this->grocery_crud->edit_fields('nombre_proveedor','direccion', 'correo','telefono','habilitado','insumos');
$this->grocery_crud->set_relation_n_n('insumos', 'provee', 'insumos', 'id_proveedor', 'id_insumo', 'codigo_insumo','','habilitado="1"');
 
	//set validations
          $this->grocery_crud->unique_fields('nombre_proveedor');
          $this->grocery_crud->set_rules('nombre_proveedor', 'Nombre Proveedor','trim|required|min_length[3]');
          $this->grocery_crud->callback_before_delete(array($this,'before_delete'));
		  $this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar el proveedor, el mismo posee registros asociados');
		  $this->grocery_crud->set_lang_string('delete_success_message', 'El proveedor se ha eliminado correctamente');	

 
if ($this->grocery_crud->getState() == 'add') 
{

     $this->grocery_crud->change_field_type('habilitado','invisible');
    
}
$this->grocery_crud->unset_read_fields('id_sede','habilitado');	

$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));

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
return $post_array;
} 

public function before_delete($primary_key)
{
    //funcion para chequear si el proveedor debe ser borrado o no (enganches en las tablas.) (entregas)
   	$band= $this->equipos_model->verificar_equipos2($primary_key);
   	if ($band)
  	{
		return false;
	}	
  	$band= $this->pedidos_model->verificar_pedidos($primary_key);
  	if ($band)
  	{
		return false;
	}		
  	$band= $this->provee_model->verificar_provee($primary_key);
  	if ($band)
  	{
		return false;
	}		
  	
 
    
       return true;
}    


}
?>