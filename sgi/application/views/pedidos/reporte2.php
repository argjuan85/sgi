




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




 
<body>
<div id="container">
 
      

<p></p>
<?php 
echo form_open('pedidos/reporte2');?>
<div id="d1">
<div id="d1c">
			<?
			$options = array(
			'-1'	=> 'Seleccione una opción',
        'R'         => 'Remito',
        'T'           => 'Traza',

);

?>
<label for="sectores">Tipo de rececpción</label>
<?
echo form_dropdown('tipo_recepcion', $options, 'large');
?>

<span class="text-danger"><?php echo form_error('tipo_recepcion'); ?></span>
</div>
<div id="d2c">
<label for="sectores">Número de recepción</label>
<?	echo form_input('numero', ''); ?>
	<span class="text-danger"><?php echo form_error('numero'); ?></span>	
</div>

 
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
       No se han encontrado pedidos relacionados con la recepción indicada, por favor verifique.
      </div>
	<?
	
	//echo  "No se han encontrado consumos en el rango indicado, por favor verifique.";
echo "<br>";
}
?> 
</div>

</body>
</html>