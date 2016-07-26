<?php
class Insumos extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();
				$this->load->helper('url'); 
				$this->load->library('session');
                $this->load->model('sedes_model');
                $this->load->model('stock_model');
                $this->load->model('detalle_pedido_model');
                 $this->load->model('componentes_model');
                  $this->load->model('entregas_model');
                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/insumos/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('insumos/'.$page, $data);
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
$this->grocery_crud->set_table('insumos');
$this->grocery_crud->set_theme('Datatables');
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->columns('codigo_insumo','descripcion', 'tipo_insumo', 'habilitado');
$this->grocery_crud->add_fields('codigo_insumo','descripcion', 'tipo_insumo','habilitado');
$this->grocery_crud->edit_fields('codigo_insumo','descripcion', 'tipo_insumo','habilitado');

$this->grocery_crud->set_relation('tipo_insumo','parametros','valor','nombre_parametro="tipo insumo"');

//validacion
 $this->grocery_crud->unique_fields('codigo_insumo');
 
$this->grocery_crud->set_rules('codigo_insumo', 'Codigo','trim|required|min_length[3]');
//$this->grocery_crud->set_rules('descripcion', 'descripcion','trim|required');
$this->grocery_crud->set_rules('tipo_insumo', 'tipo_insumo','trim|required');
$this->grocery_crud->callback_before_delete(array($this,'before_delete'));
$this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar el insumo, el mismo posee registros asociados');
$this->grocery_crud->set_lang_string('delete_success_message', 'El insumo se ha eliminado correctamente');


if ($this->grocery_crud->getState() == 'add') 
{

     $this->grocery_crud->change_field_type('habilitado','invisible');

}

$this->grocery_crud->unset_read_fields('habilitado');	
$this->grocery_crud->display_as('habilitado','Estado');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1')); 
$output = $this->grocery_crud->render();
$output->content_view='crud_content_view';
$this->_example_output($output);

}


//revisar
   function listar2(){
$this->grocery_crud->set_model('custom_query_model');
$this->grocery_crud->set_table('insumos');
$this->grocery_crud->set_theme('datatables');
$this->grocery_crud->columns('codigo_insumo','descripcion', 'nombre_proveedor');
$this->grocery_crud->basic_model->set_query_str('select * from proveedores pr inner join provee p on p.id_proveedor=pr.id inner join insumos i on i.id=p.id_insumo '); //Query text here
$output = $this->grocery_crud->render();
$this->_example_output($output);
}
      function stock_minimo(){
      //$this->grocery_crud->set_model('custom_query_model');
$this->grocery_crud->set_table('insumos');
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->add_fields('codigo_insumo','descripcion','stock_minimo', 'stock_real', 'tipo_insumo', 'id_sede', 'cant_reorden', 'habilitado');
if ($this->grocery_crud->getState() == 'add') 
{

     $this->grocery_crud->change_field_type('habilitado','invisible');

}

$this->grocery_crud->unset_read_fields('id_sede','habilitado');	
$this->grocery_crud->change_field_type('id_sede','invisible');
$this->grocery_crud->set_relation('tipo_insumo','parametros','valor','nombre="tipo insumo"');
//$this->grocery_crud->display_as('id_proveedor','Proveedor');
$this->grocery_crud->display_as('id_sede','Sede');
//$this->grocery_crud->set_relation('id_proveedor','proveedores','nombre'); 
//$this->grocery_crud->set_relation('id_sede','sedes','nombre');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1')); 
$this->grocery_crud->where('stock_real < stock_minimo',NULL);
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
 
function callback_webpage_url2($value, $row)
{
  
   return " 
    <a href='".site_url('entregas/listar1'.('/').$row->id.'/add')."' class='edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary'>
    <span class='ui-button-icon-primary ui-icon ui-icon-plus G5dcbd956'> </span>
    <span class='ui-button-text'> Entregar</span>
    </a>";
  

  
}  

public function before_delete($primary_key)
{
    //funcion para chequear si el insumo debe ser borrado o no (enganches en las tablas.) (entregas)
   	$band= $this->detalle_pedido_model->verificar_detalle($primary_key);
   	if ($band)
  	{
		return false;
	}	
  	//en teoria si hay detalle no es necesario este chequeo... no deberia haber entregas ni componente sin detalle
  	$band= $this->entregas_model->verificar_entregas2($primary_key);
  	if ($band)
  	{
		return false;
	}		
  	$band= $this->componentes_model->verificar_componentes($primary_key);
  	if ($band)
  	{
		return false;
	}	
  	//no verificaria stock dado que el ingreso del producto si o si se da con un pedido/envio por ende genera un detalle  una recepcion
  	//$band= $this->stock_model->verificar_stock($primary_key);
 
    
       return true;

}


}
?>