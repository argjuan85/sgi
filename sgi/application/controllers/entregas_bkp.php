<?php
class Entregas extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->model('entregas_model');
                $this->load->model('insumos_model');
                $this->load->model('sectores_model');
                $this->load->library('session');
                $this->load->database();
				$this->load->helper('url'); 
                // Your own constructor code
        }


        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/entregas/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('entregas/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }


      function listar($cod=''){
      	$this->session->set_userdata('insumo', $cod);
      	//$this->session->set_userdata('impresora', $this->entregas_model->  )
        $this->grocery_crud->set_language('spanish');
      	$this->grocery_crud->set_table('entregas');
		$this->grocery_crud->columns('fecha_entrega','id_insumo', 'id_impresora', 'id_sector','estado', 'observaciones', 'usuario_entrega');
		$this->grocery_crud->display_as('id_sector','Sector');
		$this->grocery_crud->display_as('id_impresora','Codigo Impresora');
		$this->grocery_crud->display_as('id_insumo','Codigo Insumo');
		$this->grocery_crud->add_fields('id_sector','id_impresora','observaciones','estado','fecha_entrega', 'id_insumo', 'usuario_entrega');
		$this->grocery_crud->set_relation('id_insumo','Insumos','codigo'); 
		$this->grocery_crud->set_relation('id_sector','Sectores','nombre'); 
		$this->grocery_crud->set_relation('id_impresora','Impresoras','codigo');
		 	if ($this->grocery_crud->getState() == 'add') 
			{
			$this->grocery_crud->change_field_type('estado','invisible');
			$this->grocery_crud->change_field_type('fecha_entrega','invisible');
    		$this->grocery_crud->change_field_type('usuario_entrega','invisible');
      
			}

$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));              $this->grocery_crud->callback_add_field('id_impresora',array($this,'callback_add1'));
//$this->grocery_crud->field_type('Sectores', 'hidden');

//$this->grocery_crud->callback_add_field('id_sector',array($this,'callback_add2'));
$this->grocery_crud->callback_add_field('id_insumo',array($this,'callback_add3'));
$output = $this->grocery_crud->render();
$this->_example_output($output);
}




function _example_output($output = null){
$this->load->view('example',$output);
} 

  
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


//reemplazo por combo en add

function callback_add1($value, $primary_key)
{  

$data = $this->entregas_model->obtener_impresoras($this->session->userdata('insumo'));
//guardo id para el campo sector (necesito conocer la impresora para mostrar el sector)
//$this->session->set_userdata('impresora', $filas['id']);
$html = '<select id="id_impresora" name="id_impresora">';
	foreach ($data as $filas)
	{
     $html = $html.'<option value="'.$filas['id'].'">'.$filas['codigo'].'</option>';
     }
     $html=$html.'</select>';
  	 return $html;
}
 /*
 // oculto campo sectores, ya que lo tomo de la impresora
 function callback_add2($value, $primary_key)
{  
$data = $this->sectores_model->obtener_nombre($this->session->userdata('impresora'));
  	 return  '<input type="text" readonly="readonly"   value="'.$data.'" name="id_sector>';
}*/
 // oculto campo insumos , ya que lo traigo por parametro
 function callback_add3($value, $primary_key)
{  
$data = $this->insumos_model->obtener_codigo($this->session->userdata('insumo'));
  	 return  '<input type="text" readonly="readonly"   value="'.$data.'" name="id_insumo">';
}


}
?>