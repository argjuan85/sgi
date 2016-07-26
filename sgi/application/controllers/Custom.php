<?php
class Custom extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();

                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/custom/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('custom/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }

      function listar(){
$this->grocery_crud->set_model('custom_query_model');
$this->grocery_crud->set_table('detalle_pedido');
$this->grocery_crud->columns('descripcion','cantidad_pedida', 'cantidad_recibida');
$this->grocery_crud->basic_model->set_query_str('select d.id, i.descripcion, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where d.id_pedido = 1'); //Query text here
$output = $this->grocery_crud->render();
$this->_example_output($output);





}function _example_output($output = null){
$this->load->view('example',$output);
} 







}