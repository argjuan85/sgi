		 <header class="navbar navbar-inverse">
 <div class="container">
  <div class="navbar-collapse nav-collapse collapse navbar-header">
<ul class="nav navbar-nav">
<?
  if ($tipo_pedido == "P")			
	{
   $segments = array('pedidos', 'listar2');
   $close_label = " CERRAR ENVIO INTERNO";
	}
	elseif ($tipo_pedido == "I")
	{
	$segments = array('pedidos', 'listar3');
   $close_label = " CERRAR ENVIO INTERNO";
	}
	else
	{
	$segments = array('pedidos', 'listar');
	$close_label = " CERRAR PEDIDO";
	}

?>

		   		
		   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url($segments);?>"><span class="glyphicon glyphicon-chevron-left"> VOLVER </span></a>
          </li>
		   		
   	

   		 

   
   <?php
   $segments = array('pedidos', 'cerrar', $id_pedido);

   


 str_repeat('<br>','2');
 
    

   ?>
   
     
  </ul>  <!-- .navbar nav -->  
</div> <!-- .colapse -->  
</div> <!-- .container --> 
 </header> <!-- .navbar -->	
							
	 
<body>
<div id="container">
 
      

<p></p>
   <?php 
   
   
   	$pedido= $this->uri->segment(3);
		
	if ($tipo_pedido == "E")			
	{
	echo form_open('detalle_pedido/recibir1'.('/').$pedido);
	$confirm_label = "Cargar Recepción";
	}
	elseif ($tipo_pedido == "N")
	{
	echo form_open('detalle_pedido/genera_ingreso'.('/').$pedido);
	$confirm_label = "Cargar Ingreso";
	}
	else
	{
	 echo form_open('detalle_pedido/recibir2'.('/').$pedido);
	 $confirm_label = "Cargar Recepción";
	
	 
	}
   
  ?>
						
<div id="d1">
<div id="d1c">
			<?
			$options = array(
		
        'R'         => 'Remito',
        'T'           => 'Traza',

);

?>
<label for="sectores">Tipo de rececpción</label>
<?
echo form_dropdown('tipo_recepcion', $options, 'large');
?>

<span class="text-danger"><?php //echo form_error('tipo_recepcion'); ?></span>
</div>
<div id="d2c">
<label for="sectores">Número de recepción</label>
<?	echo form_input('numero', ''); ?>
	<span class="text-danger"><?php echo form_error('numero'); ?></span>	
</div>

 
<div id="d4c">
 <?php echo form_submit('mysubmit', $confirm_label);?>
 </div>
  </div>
<?php echo form_close()?>


<p class="footer"></p>
<?php  /*
if ($error == "vacio")
{
	?>
	<div class="alert alert-danger">
       No se han encontrado pedidos relacionados con la recepción indicada, por favor verifique.
      </div>
	<?
	
	//echo  "No se han encontrado consumos en el rango indicado, por favor verifique.";
echo "<br>";
}*/
?> 
</div>

</body>

	
		
			
			
	