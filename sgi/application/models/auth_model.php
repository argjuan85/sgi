<?php
class auth_model extends CI_Model
	{
		
		public function __construct()
	{
		parent::__construct();
	}
	
	//un user con permisos en mas de una sede al cambiar en el combo debe actualizar su permiso, recibo la sede nueva, y devuelvo el permiso del grupo en donde pertenece dicho user.
	public function cambio_sede($sede)
	{
		
	$sede_consulta = $sede;//sede nueva
        $this->session->set_userdata('sede_filtro', $sede_consulta );
		//busco los permisos para la sede elegida, hardcodeo para test
		$permisos_sede = $this->auth_model->obtener_permiso_sede($sede_consulta);
		 $this->session->set_userdata('permisosede', $permisos_sede );
	}
	
	//los usuarios con acceso a mas de una sede. deben cargar los permisos al cambiar de sede
	public function obtener_permiso_sede($sede)
	{
		//obtengo los grupos de la Sedes
		$grupos = $this->parametros_model->obtener_grupos_sede($sede);//hasta aca ok
		
	//obtengo el permiso del user para el grupo, aca harcodeo
		$permiso = $this->obtener_permiso($grupos, $this->session->userdata('username'));
		return $permiso;
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
	//paso array de grupos de sede y un usuario, luego veo en cual tiene permisos y en base a eso asigno el nivel correspondient
	public function obtener_permiso($grupos, $usuario_LDAP)
	{
		$tienepermiso= "0";// esta bandera indica si el user pertenece al menos a 1 grupo (para loguear al sistema)
		$data['nivel']= "0"; //calcula nivel de permisos por sede (Se maneja por pesos establecidos en la tabla parametros)
		$unicasede = "0"; // uso esta var para saber si teng permisos en mas de una sede y mostrar un select de sedes en caso afirmativo
	
  //desactivamos los erroes por seguridad
  error_reporting(0);
  //error_reporting(E_ALL); //activar los errores (en modo depuración)


//cargo parametros con la info para conectar al AD
  $servidor_LDAP = $this->parametros_model->obtener_parametro($this->parametros_model->obtener_id_parametro_nombre("0","servidor_ad"));
   $ldap_dn = $this->parametros_model->obtener_parametro($this->parametros_model->obtener_id_parametro_nombre("0","dn_ad"));
  //$grupos = $this->parametros_model->obtener_grupos_sede();
  

  //echo "<h3>Validar en servidor LDAP desde PHP</h3>";
  //echo "Conectando con servidor LDAP desde PHP...";

  $conectado_LDAP = ldap_connect($servidor_LDAP);//conexion 
  ldap_set_option($conectado_LDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
  ldap_set_option($conectado_LDAP, LDAP_OPT_REFERRALS, 0);

  if ($conectado_LDAP) 
  {
   
	    // echo "<br>Autenticación en servidor LDAP desde Apache y PHP correcta.";
	
	    $userdn = $this->getDN($conectado_LDAP, $usuario_LDAP, $ldap_dn);
	    
	    //recorro los grupos definidos en los parametros para buscar la membresia del usuario autenticado y calcular permiso sobre sedes
	    foreach($grupos as $grupo)
	  {
         //$pertenece = $this->checkGroup($conectado_LDAP, $userdn, $this->getDN($conectado_LDAP, $grupo['valor'], $ldap_dn));
         //al no haber grupos definidos no hay membresia simulo que pertenece a un grupo en particular ( en este caso el primero del array)
         $pertenece = true;
         
         //fin harcode despues descomentar la linea de arriba
         If ($pertenece)
         {
         	$permisosede = $this->obtener_permiso_grupo($grupo);//aqui asigno el permiso asociado al grupo 
         	break;
		 } 
		 else 
		 {
		 	$permisosede = "-1";
		 }
      }
      ldap_unbind($conectado_LDAP);//cierro conexion AD
  }
  else 
  {
    /*echo "<br><br>No se ha podido realizar la conexión con el servidor LDAP: " .
        $servidor_LDAP;*/
  }
  
return $permisosede;
	}
	
			//dado el nombre del grupo devuelvo el nivel de permisos asociado
		public function obtener_permiso_grupo($grupo)
		{   
			$this->load->database();
		$sql= 	'select valor from Parametros where nombre_parametro= '.'"'.$grupo.'"';
		$query = $this->db->query($sql);
			$nombre=$query->row();
			return $nombre->valor;
			
		}
		
		//si hay que agregar una sede, ademas de cargar en el abm se debe agregar en esta funcion.
public function obtener_sede_grupo($dn) {
	
	//el criterio de busqueda dependera del formato de los grupos designados en el AD
	$findme = $rest = substr($dn, 0, 4);
	switch ($findme)
	{
		//definir demas sedes cuando ya esten los grupos
		
		case "APSJ":{
			$id = $this->sedes_model->obtener_id_sede("San Juan");
			break;
			}
		case "APVM":{
			$id = $this->sedes_model->obtener_id_sede("Villa Martelli");
			break;
			}	
		case "APMU":{
			$id = $this->sedes_model->obtener_id_sede("Munro");
			break;
			}	
		case "APTO":{
			$id = $this->sedes_model->obtener_id_sede("Tortuguitas");
			break;
			}
			
	}
	
	 return $id;
   
}
}
?>