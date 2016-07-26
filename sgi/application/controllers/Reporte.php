<?php



class Reporte extends CI_Controller {


  public function __construct()
    {
        parent::__construct();
        $this->load->library('Datatables');
        $this->load->library('table');
        $this->load->database();
        $this->load->helper('url'); 
    }
    
public function index()
{
$this->load->view('reporte');
}

public function getdatabyajax()
{
$this->load->library('Datatables');
$this->datatables
->select('id,nombre_sede,habilitada')
->from('sedes'); 

echo $this->datatables->generate(); 
}

}
?>