<?php
class Sedes extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->library('session');
                $this->load->database();
                $this->load->model('sedes_model');
                $this->load->model('stock_model');
                $this->load->helper('url'); 

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/sedes/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        
		
        $this->load->view('sedes/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }
function pepe (){
	$this->load->view('templates/menu', false);
	
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
  
$this->grocery_crud->set_table('sedes');
$this->grocery_crud->set_theme('Datatables');
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->display_as('mail','Mail administrador');
$this->grocery_crud->add_fields('nombre_sede','mail', 'habilitada');
$this->grocery_crud->edit_fields('nombre_sede','mail', 'habilitada');
$this->grocery_crud->unset_read_fields('habilitada');


//set validations
          $this->grocery_crud->unique_fields('nombre_sede');
          $this->grocery_crud->set_rules('nombre_sede', 'Nombre Sede','trim|required|min_length[3]');
          $this->grocery_crud->callback_before_delete(array($this,'before_delete'));
		  $this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar la sede, la misma posee registros asociados');
		  $this->grocery_crud->set_lang_string('delete_success_message', 'La sede se ha eliminado correctamente');	




	
if ($this->grocery_crud->getState() == 'add') 
{
     $this->grocery_crud->change_field_type('habilitada','invisible');
}
$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));
$output = $this->grocery_crud->render();
$output->content_view='crud_content_view'; //we add a new attribute called content_view
//because our template loads the view content_view for the content.
//data extra para la vista del crud
//$data=array();
///$data['sedes'] = $this->sedes_model->obtener_sedes();	



//etc, then assign data to output.
//$output->data=$data;
//$this->load->view('template',$output);


$this->_example_output($output);

}

function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 

function before_insert1($post_array) {

   
$post_array['habilitada'] = "1";
  return $post_array;
}
    
public function before_delete($primary_key)
{
    //funcion para chequear si el modelo debe ser borrado o no (enganches en las tablas.) 
 		
  	$band= $this->sedes_model->verificar_sede($primary_key);
  	if ($band)
  	{
		return false;
	}		
  	
 
    
       return true;
} 


}
?>