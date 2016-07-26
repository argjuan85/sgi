<?php
class Blog extends CI_Controller {

  public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->database();
$this->load->helper('url'); 
                // Your own constructor code
        }


      public function index()
        {

$data['todo_list'] = array('Clean House', 'Call Mom', 'Run Errands');
                $data['title'] = "My Real Title";
                $data['heading'] = "My Real Heading";

                $this->load->view('blogview', $data);
        }
        
        function listar(){
		$this->grocery_crud->set_table('insumos');
		$output = $this->grocery_crud->render();
		$this->_example_output($output);
		}
		
		function _example_output($output = null){
		$this->load->view('example',$output);
		} 

  public function comments()
        {
                echo 'Look at this!';
        }


}
?>