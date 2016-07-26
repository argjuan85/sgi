<!DOCTYPE html>
<html lang="en">
<head>

<? /* Este archivo debe cargarse primero para que funcione el hover del menu */ ?>

<script src="<?php echo base_url(); ?>assets/js/jquery-latest.min.js"></script> 

<? /* fin archivos hoover menu */ ?>



<?php 
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


/* para la carga del select de sedes  mas abajo*/
//$controller =  $data['controller'];
//$sedes = $data['sedes'];
$sedes = $this->sedes_model->obtener_sedes();
$sede_log = $this->session->userdata('nivel_sede');
//tomo el segmento de la pagina para el submit del formulario del select de sedes. despues en cada crud debo preguntar por el post para ver si hubo cambios
$form_action = $this->uri->segment(1)."/".$this->uri->segment(2);
	
  
 /*  Archivos necesarios para que funcione el menu  */ ?>

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
  <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <header class="navbar navbar-fixed-top navbar-inverse">
    <div class="container" align="center">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="">SISTEMA DE GESTION DE INSUMOS</a>
      
    </div> <!-- .container -->
  </header> <!-- .navbar -->

       <style type="text/css">


   #principal {
    /*content : "";*/
    display: block;
  
   background-attachment: fixed;
 background-position: center center;
    background-image: url('../../assets/img/imagen2.jpg');
    background-repeat: no-repeat;
    background-size: 40%;
   /* width: 100%;
    height: 100%;*/
     z-index: -1;
}
</style>     
      
</head>
<body id="principal">
<br>
<br>
