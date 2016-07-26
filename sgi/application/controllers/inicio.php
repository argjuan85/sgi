<?php
class Inicio extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		 $this->load->model('parametros_model');
		   $this->load->helper('url'); 
	}
 
	public function sendMail()
	{
		//cargamos la libreria email de ci
		$this->load->library("email");
 
		//configuracion para echange raffo
		$configexch = array(
			'protocol' => 'smtp',
			'smtp_host' => 'SMTP-SJ',//ssl://
			'smtp_port' => 25,//con 587 envia sas....
			'smtp_user' => 'raffo\mtvsj',
			'smtp_pass' => 'mtvsj1234',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);    
		
		
 
		//cargamos la configuración para enviar con gmail
		$this->email->initialize($configexch);
 		$this->email->from('mtvsj@raffo.com.ar');
		$this->email->to('jarganaraz@raffo.com.ar');
		$this->email->subject('Bienvenido/a a uno-de-piera.com');
		$this->email->message('<h2>Email enviado con codeigniter haciendo uso del smtp de gmail</h2><hr><br> Bienvenido al blog');
		$this->email->send();
		//con esto podemos ver el resultado
		//var_dump($this->email->print_debugger());
	}
 
 // carga vista principal del sistema
     	 function principal($cod = '', $error = ''){
     	     
     	
     	$data['error'] = $error;
     				
		//css y js de la vista a cargar
     	$data['js_files']=array('subvariable1'=> base_url().'assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
		$data['css_files']=array('subariable2'=>base_url().'assets/fancybox/source/jquery.fancybox.css?v=2.1.5');		
		 
		//$data['proveedores']  = $this->proveedores_model->obtener_proveedores();		
		//cargo vista para realizar recepciones
          $data['content_view']='inicio.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('template',$data);
            
     	}
     	
     	 // cargo combo seleccion sede
     	 function selecciona($cod = '', $error = ''){
     	     
     	
     	$data['error'] = $error;
     				
		//css y js de la vista a cargar
     	//$data['js_files']=array('subvariable1'=> base_url().'assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
		//$data['css_files']=array('subariable2'=>base_url().'assets/fancybox/source/jquery.fancybox.css?v=2.1.5');		
		 
		//$data['proveedores']  = $this->proveedores_model->obtener_proveedores();		
		//cargo vista para realizar recepciones
        //  $data['content_view']='selecciona.php';
     	 //template diferente para vistas que no incluyen grocery 
    	 $this->load->view('selecciona');
      
     
     	}
 
 
	
}