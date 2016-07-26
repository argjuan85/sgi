<?php
class Parametros extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->model('Parametros_model');
                $this->load->library('session');
                 $this->load->model('sedes_model');
                 $this->load->model('stock_model');
                $this->load->database();

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/parametros/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('parametros/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

    // revisar este crud, ver como va quedar
      function listar(){
      	
      	  	 	  if(isset($_POST['insumo']))
    	{

        $sede_consulta = $this->input->post('insumo');
        $this->session->set_userdata('sede_filtro', $sede_consulta );

    	}
    	else
    	{
			$sede_consulta= $this->general_model->ou_sede_id($this->session->userdata('sede'));
		}
		
$this->grocery_crud->set_table('parametros');
$this->grocery_crud->set_theme('Datatables');
$this->grocery_crud->columns('nombre_parametro','valor');
$this->grocery_crud->add_fields('nombre_parametro','id_sede','valor','tipo_parametro');
$this->grocery_crud->edit_fields('nombre_parametro','valor');
$this->grocery_crud->unset_delete();
$this->grocery_crud->change_field_type('id_sede','invisible');
$this->grocery_crud->change_field_type('tipo_parametro','invisible');
$this->grocery_crud->unset_read_fields('id_sede','tipo');	
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));

if ($this->grocery_crud->getState() == 'add') 
{
$this->grocery_crud->set_relation('nombre_parametro','parametros','valor', 'nombre_parametro="tipo parametro"'); 
}

$output = $this->grocery_crud->render();
$output->content_view='crud_content_view';
$this->_example_output($output);

}

function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 



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


function before_insert1($post_array) {

$id_param = $this->Parametros_model->obtener_parametro($post_array['nombre_parametro']);
$post_array['id_sede'] = $this->session->userdata('sede');;
$post_array['tipo_parametro'] = "u";
$post_array['nombre_parametro'] = $id_param;
$post_array['tipo_parametro'] = "1";

//$post_array['nombre_parametro'] = ;

//echo $aux;
  return $post_array;
} 
}
