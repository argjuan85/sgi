<?php
class Provee extends CI_Controller {
/*
 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/provee/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('provee/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

      function listar(){
$this->grocery_crud->set_table('provee');

$this->grocery_crud->columns('id_proveedor','id_insumo');
$this->grocery_crud->set_language('spanish');

 
$this->grocery_crud->set_relation('id_insumo','insumos','codigo_insumo'); 
$this->grocery_crud->set_relation('id_proveedor','proveedores','nombre_proveedor'); 
$this->grocery_crud->display_as('id_insumo','Codigo de Insumo');
$this->grocery_crud->display_as('id_proveedor','Proveedor');
$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));





$output = $this->grocery_crud->render();
$this->_example_output($output);
}function _example_output($output = null){
$this->load->view('example',$output);
} 





function before_insert1($post_array) {


  return $post_array;
} 
*/
}