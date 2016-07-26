<?php
class Componentes extends CI_Controller {

 
 
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
			$this->load->model('equipos_model');
			$this->load->model('parametros_model');
			//aca va la sede del user logueado (o sedes segun corresponda (habra un grupo especial para admins de sedes ba, que en sj no se utilizara))
			//de momento piso el nivel que asigna el loguin hasta que este definido
			//$this->session->set_userdata('sede', "15");
			//asigno para que no de error en los crud de todas maneras se pisa cuando seleccionan sede en el MenuEnter
			//$this->session->set_userdata('sede_filtro', "15" );
			//de momento se pisa el user, luego ya no haria falta
			//$this->session->set_userdata('usuario', "jarganaraz");
	
	}
	
		
	
	
/* esta definido asi temporalmente hasta que este el loguin */ 
 /*function inicio(){
 	redirect('/stock/listar');
 }*/
/* function salir(){
 	$this->load->view('salir',false);
 }*/
 
/*

        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/componentes/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('componentes/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

      function listar(){
$this->grocery_crud->set_table('componentes');

$this->grocery_crud->columns('id_equipo','id_insumo');



$this->grocery_crud->set_relation('id_insumo','insumos','codigo_insumo'); 
$this->grocery_crud->set_relation('id_equipo','equipos','codigo_equipo'); 
//$this->grocery_crud->set_relation('tipo','parametros','valor','nombre="tipo modelo"'); 
$this->grocery_crud->set_language('spanish');
$this->grocery_crud->display_as('id_insumo','Codigo de Insumo');
$this->grocery_crud->display_as('id_equipo','Codigo de Equipo');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));





$output = $this->grocery_crud->render();
$this->_example_output($output);
}function _example_output($output = null){
$this->load->view('example',$output);
} 





function before_insert1($post_array) {

  /* 
$post_array['habilitado'] = "1";
$post_array['id_sede'] = "1";*/
 /* return $post_array;
} 
*/
}
    