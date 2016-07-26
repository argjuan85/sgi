<!DOCTYPE html>
<html lang="en">
<head>

<? /* Este archivo debe cargarse primero para que funcione el hover del menu */ ?>

<script src="<?php echo base_url(); ?>assets/js/jquery-latest.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<? /* fin archivos hoover menu */ ?>

<?php 
/*  js y css de la vista */
if(isset($css_files)){
      foreach($css_files as $style){
          echo '<link href="'.$style.'" rel="stylesheet"/>';
      }
}
if(isset($js_files)){
      foreach($js_files as $script){
          echo '<script src="'.$script.'" type="text/javascript"></script>';
      }
}
//fin archivos vista 

/* para la carga del select de sedes  mas abajo*/
//$controller =  $data['controller'];
//$sedes = $data['sedes'];
$sedes = $this->sedes_model->obtener_sedes();
$sede_log = $this->session->userdata('nivel_sede');
//tomo el segmento de la pagina para el submit del formulario del select de sedes. despues en cada crud debo preguntar por el post para ver si hubo cambios
$form_action = $this->uri->segment(1)."/".$this->uri->segment(2);
	
	   
   
?>
    
<? /*  Archivos necesarios para que funcione el menu  */ ?>

<script src="<?php echo base_url();?>assets/bootstrap-3.1.1/dist/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootstrap-hover-dropdown.js"></script> 
<? /*  fin archivos menu  */ ?>



  <link href="<?php echo base_url();?>assets/bootstrap-3.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  

  <? /*  <!--sobre escribo css de bootstrap debo colocar este codigo despues de la carga del css de bootsrap por ende , cargo tambien js dle menu */  ?>
  <link href="<?php echo base_url();?>assets/css/general.css" rel="stylesheet">
  <script src="<?php echo base_url();?>assets/js/general.js"></script> 
  <?
  
/* cargo archivos css y js necesarios  que deben ser cargados posterios a los del menu por conflictos (estos los envio por parametro a a hora de cargar la vista) */ 
if(isset($css_files2)){
      foreach($css_files2 as $style){
          echo '<link href="'.$style.'" rel="stylesheet"/>';
      }
}
if(isset($js_files2)){
      foreach($js_files2 as $script){
          echo '<script src="'.$script.'" type="text/javascript"></script>';
      }
      }
      
      
      
?>


 <header class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url();?>/auth/principal"><? echo $this->session->userdata('permisosede');?></a>
      <div class="navbar-collapse nav-collapse collapse navbar-header">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle js-activated">Pedidos <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo site_url();?>/pedidos/listar">Listado Pedidos Externos</a></li>
              <li><a href="<?php echo site_url();?>/pedidos/listar2">Listado Pedidos Internos</a></li>
              <li><a href="<?php echo site_url();?>/pedidos/listar3">Listado Envios Internos</a></li>
            
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown">Insumos <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="<?php echo site_url();?>/stock/listar">Stock</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/listar">Entregas</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown">ABM <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="<?php echo site_url();?>/equipos/listar">Equipos</a></li>
             
              <li><a tabindex="-1" href="<?php echo site_url();?>/insumos/listar">Insumos</a></li>
              
              <li><a tabindex="-1" href="<?php echo site_url();?>/sectores/listar">Sectores</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/proveedores/listar">Proveedores</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/modelos/listar">Modelos</a></li>
              
          
              
            </ul>
          </li>
             <li class="dropdown">
            <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Reportes <b class="caret"></b> </a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/generar_reporte">Consumos por impresora</a></li>
         
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/generar_reporte2">Consumos por codigo</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/generar_reporte3">Consumos por sector</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/generar_reporte4">Rehazos por proveedor</a></li>
                          <li class="divider"></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/pedidos/generar_reporte">Pedidos por proveedor</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/pedidos/generar_reporte2">Pedidos por recepción</a></li>
             
           
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown"><? echo $this->session->userdata('nombre');?> <span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
            <ul class="dropdown-menu">
              <li><a tabindex="-1" href="<?php echo site_url();?>/auth/salir">Salir</a></li>
            
            </ul>
          </li>
           <ul class="dropdown-menu">
              <li><a tabindex="-1" href="<?php echo site_url();?>/stock/listar">Stock</a></li>
              <li><a tabindex="-1" href="<?php echo site_url();?>/entregas/listar">Entregas</a></li>
                <li>    <a>     Sede:     </a>      </li>
            </ul>
   
     
			<li class="dropdown">
     
      <a>
		<?php 
		if (!isset($menu_sede_oculto))//esto lo paso en el arreglo data al cargar una vista cuando no puedo hacer cambios de sede (reportes por ejemplo))
	{
	
		echo form_open($form_action);
		
		$ou_usuario = $this->parametros_model->ou_sede($this->session->userdata('sede'))
		?>
			<select name="insumo"  onchange="this.form.submit()" id="insumo">
    		<?
    //si el user tiene mas de una sede autorizada muestro el menu de sedes de otra forma
    if ($this->session->userdata('unicasede') > "1")
    {
			
			
    		?>
    	<option value="">Selecciona Sede...</option>
    		<?
		foreach($sedes as $fila)
		{
		
				
			$b = $fila['id'];
			$nivel_sede = $b; 
			$c= pow(2,$nivel_sede - 1);
			$d= $c & $sede_log; 
			
		
			If ( $d  == $c)
			{
				//si esta seteado el filtro de la sede muestro asi
				if($this->session->userdata('sede_filtro'))
				{
							
				
						if ($this->session->userdata('sede_filtro') == $fila['id'])
						{
						?>
						<option value="<?=$fila['id'] ?>" selected><?=$fila['nombre_sede'] ?></option>
		   				<?php
					
						}
							
						else
						{
					
						?>
						<option value="<?=$fila['id'] ?>"><?=$fila['nombre_sede'] ?></option>
						<?php
						}
				}
				else
				//no esta el filtro sede
				{
						if ( $fila['nombre_sede'] == $ou_usuario)
						{
						?>
						<option value="<?=$fila['id'] ?>" selected><?=$fila['nombre_sede'] ?></option>
		   				<?php
					
						}
							
						else
						{
					
						?>
						<option value="<?=$fila['id'] ?>"><?=$fila['nombre_sede'] ?></option>
						<?php
						}
				}
				
		    }//end if sede valida para el user
		}//end for each
	
	}
	else
	{
			//menu de sedes para users con una sola sede en los permisos
		foreach($sedes as $fila)
		{
		
			
				if ( $fila['nombre_sede'] == $ou_usuario)
				{
			?>
			<option value="<?=$fila['id'] ?>" selected><?=$fila['nombre_sede'] ?></option>
		   <?php
					
				}
				
		}
	}
		
	
		?>		
	</select>
<? 	echo form_close(); 
}

?>
</a>
 </li>
 <li class="dropdown">
<a href="#">Stock minimo: <span class="badge"><? echo $this->stock_model->obtener_cantstockminimo($this->session->userdata('sede_filtro'));?></span></a>
</li>
        </ul>
        
      </div> <!-- .nav-collapse -->
    </div> <!-- .container -->
  </header> <!-- .navbar -->


      
</head>
<body>

