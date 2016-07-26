<?php
class Equipos extends CI_Controller {

 public function __construct()
        {
                parent::__construct();
                $this->load->library('grocery_CRUD');
                $this->load->library('session');
                $this->load->model('sedes_model');
                $this->load->model('stock_model');
                $this->load->model('Parametros_model');
                 $this->load->model('entregas_model');
                $this->load->database();
				$this->load->helper('url'); 
				//$this->session->set_userdata('sede', "1");
                // Your own constructor code
        }



        public function view($page = 'home')
        {
          if ( ! file_exists(APPPATH.'/views/equipos/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('equipos/'.$page, $data);
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
      	
      
$this->grocery_crud->set_table('equipos');
$this->grocery_crud->set_theme('Datatables');


if ($this->session->userdata('sede_filtro'))
        $this->grocery_crud->where('Equipos.id_sede',$this->session->userdata('sede_filtro'));
	  	else
      	$this->grocery_crud->where('Equipos.id_sede',$sede_consulta);

$this->grocery_crud->set_language('spanish');
$this->grocery_crud->columns('codigo_equipo','id_modelo', 'serie', 'estado_equipo','observaciones','tipo_equipo','tipo_conexion','ip','id_sector','id_proveedor');
$this->grocery_crud->add_fields('codigo_equipo','id_modelo','serie', 'estado_equipo','observaciones','tipo_equipo','tipo_conexion','ip','id_sector','id_proveedor', 'id_sede', 'componentes');

 $this->grocery_crud->set_relation_n_n('componentes', 'componentes', 'insumos', 'id_equipo', 'id_insumo', 'codigo_insumo');

if ($this->grocery_crud->getState() == 'add') 
{
    $this->grocery_crud->change_field_type('id_sede','invisible');
  
    

}


//validacion
 $this->grocery_crud->unique_fields('codigo_equipo','serie');
 
			$this->grocery_crud->set_rules('codigo_equipo', 'Equipo','trim|required|min_length[5]');
			$this->grocery_crud->set_rules('id_modelo', 'Modelo','trim|required');
			$this->grocery_crud->set_rules('estado_equipo', 'Estado','trim|required');
			$this->grocery_crud->set_rules('tipo_equipo', 'Tipo Equipo','trim|required');
			$this->grocery_crud->set_rules('id_proveedor', 'Proveedor','trim|required');
	

$this->grocery_crud->unset_read_fields('id_sede','habilitado');	
$this->grocery_crud->change_field_type('id_sede','invisible');


$this->grocery_crud->display_as('id_proveedor','Proveedor');
$this->grocery_crud->display_as('id_sector','Sector');
$this->grocery_crud->display_as('id_sede','Sede');
$this->grocery_crud->display_as('id_modelo','Modelo');

$this->grocery_crud->set_relation('id_proveedor','proveedores','nombre_proveedor'); 
$this->grocery_crud->set_relation('id_sector','sectores','nombre_sector', 'id_sede= "'.$this->session->userdata('sede_filtro').'"'); 
$this->grocery_crud->set_relation('estado_equipo','parametros','valor', 'nombre_parametro="estado equipo" and habilitado="1"'); 
/**
* 
* @var /deberia obtener el id  del nombre del parametro para "tipo_modelo"
* 
*/
//aca habria que hacer una relacion una vez seleccionado el combo el tipo de equipo para que traiga el tipo de modelo
//de momento se dejan todos los modelos cargados
//$id_param = $this->Parametros_model->obtener_id_parametro("tipo equipo","impresora");
$this->grocery_crud->set_relation('id_modelo','modelos','nombre_modelo', 'habilitado="1"'); 

$this->grocery_crud->set_relation('tipo_equipo','parametros','valor', 'nombre_parametro="tipo equipo" and habilitado="1"'); 
$this->grocery_crud->set_relation('tipo_conexion','parametros','valor', 'nombre_parametro="tipo conexion" and habilitado="1"'); 


$this->grocery_crud->callback_before_insert(array($this,'before_insert1'));
$this->grocery_crud->callback_before_delete(array($this,'before_delete'));
$this->grocery_crud->set_lang_string('delete_error_message', 'Imposible eliminar el equipo, el mismo posee registros asociados');
$this->grocery_crud->set_lang_string('delete_success_message', 'El equipo se ha eliminado correctamente');
$output = $this->grocery_crud->render();
$output->content_view='crud_content_view';
$this->_example_output($output);

}

      
function _example_output($output = null){
// cargo template del sitio y envio la data a traves de output	
$this->load->view('template',$output);
} 

function before_insert1($post_array) {
$post_array['id_sede'] = $this->session->userdata('sede_filtro');

  return $post_array;
}

public function before_delete($primary_key)
{
    //funcion para chequear si el equipo debe ser borrado o no (enganches en las tablas.) (entregas)
    //$band= false;
  	$band= $this->entregas_model->verificar_entregas($primary_key);
 
    if($band)
        {return false;}
	else
    	{return true;}
}
/*
//reemplazo por combo en add
function callback_add1($value, $primary_key)
{  
   return '<select id="tipo_conexion" name="tipo_conexion">
        <option value="red">Red</option>
        <option value="local">Local</option>
         </select>';

}

function callback_add2($value, $primary_key)
{  
   return '<select id="estado_equipo" name="estado_equipo">
        <option value="">Seleccione...</option>
        <option value="Operativa">Operativa</option>
        <option value="Backup">Backup</option>
        <option value="Ok">Ok</option>
        <option value="Rota">Rota</option>
      </select>';

}

/* revisar estaria bueno que el combo se arme tomando de parametros pensando a futuro  */
/*
function callback_add3($value, $primary_key)
{  
   return '<select id="tipo_equipo" name="tipo_equipo">
        <option value="">Seleccione...</option>
        <option value="Operativa">Operativa</option>
        <option value="Backup">Backup</option>
        <option value="Ok">Ok</option>
        <option value="Rota">Rota</option>
      </select>';

}


function callback_edit1($value, $primary_key)
{  
   return '<select id="estado_equipo" name="estado_equipo">
        <option value="">Seleccione...</option>
        <option value="Operativa">Operativa</option>
        <option value="Backup">Backup</option>
        <option value="Ok">Ok</option>
        <option value="Rota">Rota</option>
      </select>';

}
*/

}
?>