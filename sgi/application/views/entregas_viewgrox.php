<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#impresora").change(function() {
				$("#impresora option:selected").each(function() {
					impresora = $('#impresora').val();
					$.post("http://127.0.0.1:8080/sgi/index.php/entregas/llena_insumos", {
						impresora : impresora
					}, function(data) {
						$("#insumo").html(data);
					});
				});
			})
		});
	</script>
</head>
<body>
	<select name="impresora" id="impresora">
		<?php 
		foreach($impresora as $fila)
		{
		?>
			<option value="<?=$fila->id ?>"><?=$fila->codigo ?></option>
		<?php
		}
		?>		
	</select>
	
	<select name="insumo" id="insumo">
    		<option value="">Selecciona insumo...</option>
    </select>
</body>
</html>