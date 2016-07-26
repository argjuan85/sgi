<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- REVISAR POR QUE SE CAMBIO LA IP POR BASE_URL EN LAS LINEAS DE JQUERY DEBERIA ANDAR. -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#equipo").change(function() {
				$("#equipo option:selected").each(function() {
					equipo = $('#equipo').val();
					$.post("<?php echo base_url(); ?>index.php/entregas/llena_insumos", {
						equipo : equipo
					}, function(data) {
						$("#insumo").html(data);
					});
				});
			})
			
			$("#insumo1").change(function() {
				$("#insumo1 option:selected").each(function() {
					insumo1 = $('#insumo1').val();
					$.post("<?php echo base_url(); ?>index.php/entregas/llena_equipos", {
						insumo1 : insumo1
					}, function(data) {
						$("#equipo1").html(data);
					});
				});
			})
			
			
		});
	</script>
	
</head>
<body>
<?php
echo anchor(site_url('entregas/listar1/add'), ' Codigo de Equipo ');  
echo anchor(site_url('entregas/listar2/add'), ' Codigo de Insumo ');  
?>
	<select name="equipo" id="equipo">
		<?php 
		foreach($equipo as $fila)
		{
		?>
			<option value="<?=$fila->id ?>"><?=$fila->codigo_equipo ?></option>
		<?php
		}
		?>		
	</select>
	
	<select name="insumo" id="insumo">
    		<option value="">Selecciona insumo...</option>
    </select>
    
    
    <select name="insumo1" id="insumo1">
		<?php 
		foreach($insumo as $fila)
		{
		?>
			<option value="<?=$fila->id ?>"><?=$fila->codigo_insumo ?></option>
		<?php
		}
		?>		
	</select>
	
	<select name="equipo1" id="equipo1">
    		<option value="">Selecciona equipo...</option>
    </select>
    
    
    
    
    
</body>
</html>