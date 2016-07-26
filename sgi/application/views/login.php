
    
<!DOCTYPE html>
<html>

     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Sistema de Gestion de Insumos</title>
     <!--link the bootstrap css file-->
     <link href="  <?php echo base_url();?>assets/css/login/bootstrap.min.css" rel="stylesheet">
    
     <style type="text/css">
     .colbox {
          margin-left: 0px;
          margin-right: 0px;
     }
  
     </style>
     
          <style type="text/css">


   #cont {
   
   opacity: 0.9;
  

   
}
</style>




<div  id="cont" class="container" align="center">
     <div class="row" align="center">
          <div class="col-lg-4 col-sm-4 well">
          <?php 
          //solo por si se accede esta pagina estando logueado me aseguro que no queden variables de sesion activas
          $this->session->sess_destroy();
          $attributes = array("class" => "form-horizontal", "id" => "loginform", "name" => "loginform");
          echo form_open("auth/index", $attributes);?>
          <fieldset>
               <legend>Ingreso al sistema</legend>
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-4 col-sm-4">
                    <label for="txt_username" class="control-label">Usuario</label>
               </div>
               <div class="col-lg-8 col-sm-8">
                    <input class="form-control" id="txt_username" name="txt_username" placeholder="Usuario" type="text" value="<?php echo set_value('txt_username'); ?>" />
                    <span class="text-danger"><?php echo form_error('txt_username'); ?></span>
               </div>
               </div>
               </div>
               
               <div class="form-group">
               <div class="row colbox">
               <div class="col-lg-4 col-sm-4">
               <label for="txt_password" class="control-label">Clave</label>
               </div>
               <div class="col-lg-8 col-sm-8">
                    <input class="form-control" id="txt_password" name="txt_password" placeholder="Clave" type="password" value="<?php echo set_value('txt_password'); ?>" />
                    <span class="text-danger"><?php echo form_error('txt_password'); ?></span>
               </div>
               </div>
               </div>
                              
               <div class="form-group">
               <div class="col-lg-12 col-sm-12 text-center">
                    <input id="btn_login" name="btn_login" type="submit" class="btn btn-default" value="Ingresar" />
                    <input id="btn_cancel" name="btn_cancel" type="reset" class="btn btn-default" value="Blanquear" />
               </div>
               </div>
          </fieldset>
          <?php echo form_close(); ?>
          <?php echo $this->session->flashdata('msg'); ?>
          </div>
     </div>
</div>
     
<!--load jQuery library-->
<script src="  <?php echo base_url();?>assets/js/login/1.11.1/jquery.min.js"></script>
<!--load bootstrap.js-->
<script src="  <?php echo base_url();?>assets/js/bootstrap-3.1.1/login/bootstrap.min.js"></script>

</html>