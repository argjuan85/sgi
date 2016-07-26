<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller 
{
	function __construct() 
	{
		parent::__construct();
		
		// this loads the Auth_AD library. You can also choose to autoload it (see config/autoload.php)
		$this->load->library('Auth_AD');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->model('parametros_model');
		$this->load->model('sedes_model');
		$this->load->model('auth_model');
			$this->load->helper('url');
	}
	
	
	
	
		/*
* This function searchs in LDAP tree ($ad -LDAP link identifier)
* entry specified by samaccountname and returns its DN or epmty
* string on failure.
*/
public function getDN($ad, $samaccountname, $basedn) {
    $attributes = array('dn');
    $result = ldap_search($ad, $basedn,
        "(samaccountname={$samaccountname})", $attributes);
    if ($result === FALSE) { return ''; }
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count']>0) { return $entries[0]['dn']; }
    else { return ''; };
}

/*
* This function retrieves and returns CN from given DN
*/
public function getCN($dn) {
    preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
    return $matchs[0][0];
}

/*
* obtiene el ou del user
*/
public function getOU($dn) {
	$findme = "OU SJ";
	$pos = strpos($dn, $findme);
  if ($pos === False)
  {
  	return False;
  }
  else
    {
		 return $findme;
	}
   
}


/*
* This function checks group membership of the user, searching only
* in specified group (not recursively).
*/
public function checkGroup($ad, $userdn, $groupdn) {
    $attributes = array('members');
    $result = ldap_read($ad, $userdn, "(memberof={$groupdn})", $attributes);
    if ($result === FALSE) { return FALSE; };
    $entries = ldap_get_entries($ad, $result);
    return ($entries['count'] > 0);
}

/*
* This function checks group membership of the user, searching
* in specified group and groups which is its members (recursively).
*/
public function checkGroupEx($ad, $userdn, $groupdn) {
    $attributes = array('memberof');
    $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
    if ($result === FALSE) { return FALSE; };
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count'] <= 0) { return FALSE; };
    if (empty($entries[0]['memberof'])) { return FALSE; } else {
        for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
            if ($entries[0]['memberof'][$i] == $groupdn) { return TRUE; }
            elseif ($this->checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) { return TRUE; };
        };
    };
    return FALSE;
}	
		
		
		//ESTA FUNCION AUTENTICA USUARIOS Y VERIFICA QUE TENGA PERMISOS EN LOS GRUPOS PARAMETRIZADOS. DEVUELVE EN UN ARREGLO SI EL USER ESTA OK, SI PERTENECE A LOS GRUPOS DEL SISTEMA Y A SU VEZ LA UNIDAD ORGANIZATIVA DEL AD
	public function adtest2($usuario_LDAP,$contrasena_LDAP)
	{
		$data['resultado']= "0"; //bandera para saber si el user existe autentica y no esta bloqueado
		$tienepermiso= "0";// esta bandera indica si el user pertenece al menos a 1 grupo (para loguear al sistema)
		$data['nivel']= "0"; //calcula nivel de permisos por sede (Se maneja por pesos establecidos en la tabla parametros)
		$data['unicasede'] = "0"; // uso esta var para saber si teng permisos en mas de una sede y mostrar un select de sedes en caso afirmativo
	
  //desactivamos los erroes por seguridad
  //error_reporting(0);
  error_reporting(E_ALL); //activar los errores (en modo depuración)


//cargo parametros con la info para conectar al AD
  $servidor_LDAP = $this->parametros_model->obtener_parametro($this->parametros_model->obtener_id_parametro_nombre("0","servidor_ad"));
  $servidor_dominio = $this->parametros_model->obtener_parametro($this->parametros_model->obtener_id_parametro_nombre("0","Dominio"));
  $ldap_dn = $this->parametros_model->obtener_parametro($this->parametros_model->obtener_id_parametro_nombre("0","dn_ad"));
  $grupos = $this->parametros_model->obtener_grupos();
  

  //echo "<h3>Validar en servidor LDAP desde PHP</h3>";
  //echo "Conectando con servidor LDAP desde PHP...";

  $conectado_LDAP = ldap_connect($servidor_LDAP);//coneion 
  ldap_set_option($conectado_LDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($conectado_LDAP, LDAP_OPT_REFERRALS, 0);

  if ($conectado_LDAP) 
  {
    //echo "<br>Conectado correctamente al servidor LDAP " . $servidor_LDAP;

	   //echo "<br><br>Comprobando usuario y contraseña en Servidor LDAP";
    $autenticado_LDAP = ldap_bind($conectado_LDAP, $usuario_LDAP . "@" . $servidor_dominio, $contrasena_LDAP);
    if ($autenticado_LDAP)
    {
	    // echo "<br>Autenticación en servidor LDAP desde Apache y PHP correcta.";
	    $data['resultado'] = "1";
	    $userdn = $this->getDN($conectado_LDAP, $usuario_LDAP, $ldap_dn);
	    
	    //recorro los grupos definidos en los parametros para buscar la membresia del usuario autenticado y calcular permiso sobre sedes
	    
	    	    	    
 foreach($grupos as $grupo){
 	$grup_dn = $this->getDN($conectado_LDAP, $grupo['valor'], $ldap_dn);
 
         //$pertenece = $this->checkGroup($conectado_LDAP, $userdn, $this->getDN($conectado_LDAP, $grupo['valor'], $ldap_dn));
         $pertenece = $this->checkUserInGroups($userdn, $grupo['valor'], $grup_dn, $conectado_LDAP);
         If ($pertenece)
         {
         	$sedegrupo = $this->auth_model->obtener_sede_grupo($grupo['valor']);
         	$tienepermiso = "1"; 
         	$grupo_unico = $grupo['valor'];//guardo el nombre del grupo, solo lo uso para cuando es unica sede
         	$data['unicasede']= $data['unicasede'] + 1;//aca indico si el user tiene algun tipo de permisos en mas de una sede
         	$data['nivel']= $data['nivel'] + $this->sedes_model->obtener_nivel_sede($sedegrupo); // aca calculo el nivel de permisos en las distintas sedes
		 	
		 } 
      }
     
      
      //si tiene acceso a una sola sede, cargo el permiso respectivo  si no deberé esperar a que seleccione una
    /*  if ($data['unicasede'] == "1")
      {	  
      $permisosede =  $this->auth_model->obtener_permiso_sede($grupo_unico);//funcion que busca el permiso de un grupo en los Parametros (el nombre no ayuda pero la funcionalidad es la misma, dado un grupo trae el nivel)
      $data['permisosede']= $permisosede;
      }
      else
      {*/
	  	  //si tiene mas de una sede logueo por defecto con sede y permisos del ou donde esta el user (luego en el combo del menu puede cambiar)
	  	 $sede_ou = $this->sedes_model->obtener_id_sede($this->parametros_model->ou_sede($this->getOU($userdn)));
	  	 $this->session->set_userdata('sede_filtro', $sede_ou );//filtro para el menu
		//busco los permisos para la sede elegida
		$permisos_sede = $this->auth_model->obtener_permiso_sede($sede_ou);
		$this->session->set_userdata('permisosede', $permisos_sede );
	  	$data['permisosede']= $permisossede;// si esta en mas de uno asigno -1 ya que el permiso se establece al seleccionar la sede
	//  }
	  
	    // $pertenece = $this->checkGroupEx($conectado_LDAP, $userdn, $this->getDN($conectado_LDAP, $grupo, $ldap_dn));
	   
	     if ($tienepermiso) {
	     	
	     	       /**
				   * 
				   * Busco el nombre para mostrar en el menu 
				   * 
				   */
     
 
    // Especifico los parámetros que quiero que me regrese la consulta
    $attrs = array("displayname","mail","samaccountname","telephonenumber","givenname");
 
    // Creo el filtro para la busqueda
    $filter = "(samaccountname=$usuario_LDAP)";
 
    $search = ldap_search($conectado_LDAP, $ldap_dn, $filter, $attrs)
    or die ("");
 
    $entries = ldap_get_entries($conectado_LDAP, $search);
 
    if ($entries["count"] > 0)
        {
        for ($i=0; $i<$entries["count"]; $i++)
                {
                	
               $data['nombre'] = $entries[$i]["displayname"][0]; 	
            /*echo "<p>Nombre: ".$entries[$i]["displayname"][0]."<br />";
            echo "Email: <a href=mailto:".$entries[$i]["mail"][0].">".$entries[$i]["mail"][0]."</a><br />";
            echo "Nombre de Usuario: ".$entries[$i]["samaccountname"][0]."<br />";
            echo "Telefono: ".$entries[$i]["telephonenumber"][0]."</p>";*/
            }
    } else {
        echo "<p>No se ha encontrado ningun resultado</p>";
    }
                   
                         
                   /**
				   * 
				   * fin busqueda atributos
				   * 
				   */
	     	
		//if (checkGroup($ad, $userdn, getDN($ad, $group, $basedn))) {
    	//echo "You're authorized as ".$this->getCN($userdn);
   
    	$data['resultado'] = "2";
    	 $data['OU'] = $this->getOU($userdn);
		} else {
    	//echo 'Authorization failed';
		}
		ldap_unbind($conectado_LDAP);
	   }
    else
    {
      /*echo "<br><br>No se ha podido autenticar con el servidor LDAP: " . 
	      $servidor_LDAP .
	      ", verifique el usuario y la contraseña introducidos";*/
    }
  }
  else 
  {
    /*echo "<br><br>No se ha podido realizar la conexión con el servidor LDAP: " .
        $servidor_LDAP;*/
  }
return $data;
	}
	

	
	
			
// carga formulario de login
	public function inicio()
	{
		$data['content_view']='login';
   
	 $this->load->view('template4',$data);
		
		
	}
	

	
	
	//procesa las credenciales
	public function index()
     {
     	  error_reporting(E_ALL); //activar los errores (en modo depuración)
          //get the posted values
          $username = $this->input->post("txt_username");
          $password = $this->input->post("txt_password");

          //set validations
          $this->form_validation->set_rules("txt_username", "Usuario", "trim|required");
          $this->form_validation->set_rules("txt_password", "Clave", "trim|required");

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
               $this->load->view('login');
          }
          else
          {
               //validation succeeds
               if ($this->input->post('btn_login') == "Ingresar")
               {
                    //autenticacion y validacion de grupo AD
                    $usr_result = $this->adtest2($username,$password);
                   
 
                    
                    if ($usr_result['resultado'] == "2") //ok
                    {
                         //set the session variables
                         $sessiondata = array(
                              'username' => $username,
                              'nombre' => $usr_result['nombre'],
                              'loginuser' => TRUE, 
                              'nivel_sede' =>  15,//$usr_result['nivel'], //defino a que sedes tiene acceso
                              'permisosede' =>  $usr_result['permisosede'], //define los permisos que tiene en cada sede (esto se carga aca solo si pertenece a una sola sede, si no se establece de forma dinamica a medida que cambie de sede)
                          	  //'unicasede' =>  2,//$usr_result['unicasede'], //DESCOMENTAR CUANDO YA ESTEN LOS GRUPOS DE AD PARA HACER LAS PRUEBAS 
                              'unicasede' =>   $usr_result['unicasede'],//"2", // ASIGNO DOS PARA PODER SEGUIR VIENDO TODAS LAS SEDES 
                              'url_server' => base_url(),
                              'sede' => $usr_result['OU'] //sede del user
                              
                         );
                         $this->session->set_userdata($sessiondata);
                         //redireccion pagina principal
                         redirect('auth/principal');
                    }
                    elseif ($usr_result['resultado'] == "1") //user ok pero no esta en ningun grupo
                    {
                    	
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">El usuario no tiene permisos para acceder a este sistema</div>');
                         redirect('auth/inicio');
                    }
                    else 
                    {
							// no esta autenticado o en grupo AD
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Usuario y/o Clave inválidos</div>');
                         redirect('auth/inicio');
					}
               }
               else
               {
                    redirect('auth/inicio');
               }
          }
     }
	
//funcion para actualizar permiso ante cambio de sede y redireccion a la pagina desde donde se efectuo
public function actualiza_permiso()
	{
		$sede= $this->input->post('insumo');
		$url= $this->input->post('url');
		$this->auth_model->cambio_sede($sede);
		redirect($url, 'refresh');
		
	}

public function principal(){
 	if	($this->general_model->validapermiso("1"))
 		{
 		$data['content_view']='principal';
   	    $this->load->view('template2',$data);
		}
 }
  function salir(){
 	$this->load->view('salir',false);
 }
 
 function checkUserInGroups($user, $groupsToFind, $grup_dn, $ldapconn) {
  
     
        /* Recorremos el array donde se almacenan los grupos con permisos de lectura */
       // for($group=0;$group<count($groupsToFind);$group++){
            /* $groupDN = "CN=".$groupsToFind[$group].
                        ",OU=Usuarios,OU=Grupos,OU=miEmpresa,DC=raffo,DC=local";*/
             $groupDN = $grup_dn;
             $filter = '(memberof:1.2.840.113556.1.4.1941:='.$groupDN.')';
             /*$userDN = _LDAP_DN_;
             $userDN = str_replace('*replace_name*', $user, $userDN);*/
             $userDN = $user;
             $search = ldap_search($ldapconn, $userDN, $filter, array('dn'), 1);
             $items = ldap_get_entries($ldapconn, $search);
             if ($items['count'] > 0){
                //ldap_close($ldapconn);
                return true;
            }
       // }
 
        /* Si llegamos a este punto, el usuario NO se encuentra en el grupo  */
     
        return false;
 
   
   
}
 
 
 
}
