




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
 
      
<?php  /*
if ($error == "vacio")
{
	?>
	<div class="alert alert-danger">
       No se han encontrado consumos en el rango indicado, por favor verifique.
      </div>
	<?
	
	//echo  "No se han encontrado consumos en el rango indicado, por favor verifique.";
echo "<br>";
}*/
?> 
<p></p>
<?php 


echo form_open('auth/login'); ?>
<label for="username">username</label>
<input type="text" id="username" name="username">
<label for="password">password</label>
<input type="text" id="password" name="password">
<span>formato: DD/MM/YYYY</span>
<? //echo form_dropdown('codigo', $equipos);?>
 <?php echo form_submit('date','Generar');?>
<?php echo form_close()?>

<?php //}else{ echo $message; } ?> 
<p class="footer"></p>
</div>

</body>
</html>