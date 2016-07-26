<html>
        <head>
                <title>Sistema de Gestion de Insumos</title>
        </head>
        <body>
	<?php 
	
	switch ($controller) {
		
	
	case "Entregas":  {
		echo form_open('entregas/listar');
		break;
	}
	case "Pedidos":  {
		echo form_open('pedidos/listar');
		break;
	}
	case "Equipos":  {
		echo form_open('equipos/listar');
		break;
	}
	case "Sectores":  {
		echo form_open('sectores/listar');
		break;
	}
	case "Stock":  {
		echo form_open('stock/listar');
		break;
	}
	case "Envios":  {
		echo form_open('pedidos/listar2');
		break;
	}
   }
   
   
?>

             	<select name="insumo"  onchange="this.form.submit()" id="insumo">
    		<option value="">Selecciona Sede...</option>
   
    
    
  
		<?php 
		foreach($sedes as $fila)
		{
		
				
			$b = $fila['id'];
			$nivel_sede = $b; 
			$c= pow(2,$nivel_sede - 1);
			$d= $c & $sede_log; 
			
		
			If ( $d  == $c)
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
		}
		?>		
	</select>
	<?php 
	echo form_close();
		echo anchor(site_url('equipos/listar'), '   Equipos   ');  
		echo anchor(site_url('insumos/listar'), '   Insumos   ');  
		echo anchor(site_url('stock/listar'), '   Stock   ');  	
		echo anchor(site_url('entregas/listar'), '   Entregas   ');  
		echo anchor(site_url('pedidos/listar'), '   Pedidos   '); 
		echo anchor(site_url('pedidos/listar2'), '   Envios Internos   ');
		  
		?>
 