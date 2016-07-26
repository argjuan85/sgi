




 <script>
  $(function() {
    $( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });
  </script>


</head>

 <header class="navbar navbar-inverse">
 <div class="container">
  <div class="navbar-collapse nav-collapse collapse navbar-header">
<ul class="nav navbar-nav">
		   		
		   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/Pedidos/listar/add"><span class="glyphicon glyphicon-chevron-left"> VOLVER </span></a>
          </li>

   
      
   
  
  </ul>  <!-- .navbar nav -->  
</div> <!-- .colapse -->  
</div> <!-- .container --> 
 </header> <!-- .navbar -->	

<body>
<div id="container">
 
  
<p></p>
<?php 



  echo form_open('detalle_pedido/genera_consumo'.('/').$id_pedido); ?>
<div id="d1">
<div id="d1c">
<label for="from">Desde</label>
<input type="text" id="from" name="from">
<span class="text-danger"><?php echo form_error('from'); ?></span>
</div>
<div id="d2c">
<label for="to">Hasta</label>
<input type="text" id="to" name="to">

<span class="text-danger"><?php echo form_error('to'); ?></span>
</div>
<? if ($tipo == "P")
{ ?>
<div id="d3c">
<div id="d3c1">
<label for="sectores">Proveedor</label>
</div>
<div id="d3c1">
<? echo form_dropdown('proveedor', $proveedores);?>
<span class="text-danger"><?php echo form_error('proveedor'); ?></span>
</div>

 </div>
 
<?}  ?>
 <div id="d4c">
  <?php echo form_submit('date','Generar');?>
   </div>
 </div>
<?php echo form_close()?>  
    <p class="footer"></p>
    
<?php  
if ($error == "vacio")
{
	?>
	<div class="alert alert-danger">
       No se han encontrado pedidos en el rango indicado, por favor verifique.
      </div>
	<?
	
	//echo  "No se han encontrado consumos en el rango indicado, por favor verifique.";
echo "<br>";
}
?> 

</div>

</body>
</html>