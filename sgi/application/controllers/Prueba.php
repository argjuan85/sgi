<?php
class Prueba extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();
$this->load->helper('url'); 
                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/detalle_pedidos/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('detalle_pedido/'.$page, $data);
        $this->load->view('templates/footer', $data);
        }


      function listar(){
$this->grocery_crud->set_table('impresoras');
$this->grocery_crud->callback_before_insert(array($this,'checking_estado'));
$output = $this->grocery_crud->render();
$this->_example_output($output);
}

 
 
function checking_estado($post_array)
{
    if(empty($post_array['estado']))
    {
        $post_array['estado'] = 'Not U.S.';
    }
    return $post_array;
}

function _example_output($output = null){
$this->load->view('example',$output);
} 

    


}
?>