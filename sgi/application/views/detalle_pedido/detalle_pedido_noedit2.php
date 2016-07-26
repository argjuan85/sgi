		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				/* Init DataTables */
					var oTable = $('#example').dataTable(
					{
						"oLanguage": {
							"sProcessing":     "Procesando...",
				    "sLengthMenu":     "Mostrar _MENU_ registros",
				    "sZeroRecords":    "No se encontraron resultados",
				    "sEmptyTable":     "Ningún dato disponible en esta tabla",
				    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
				    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
				    "sInfoPostFix":    "",
							  "sSearch":         "Buscar:",
							  "sUrl":            "",
				    "sInfoThousands":  ",",
				    "sLoadingRecords": "Cargando...",
				    "oPaginate": {
				        "sFirst":    "Primero",
				        "sLast":     "Último",
				        "sNext":     "Siguiente",
				        "sPrevious": "Anterior"
				    }
				    
						}
					}
					
					)
				
										});
		</script>
			<style type="text/css">


h1
{

font-size: 18px;
}

.panel-default .panel-heading {
    color: #ffffff;
    background-image: none;
    background-color: #0A4273;
    border: none;
    text-transform: uppercase;
}
</style>
	</head>
	
		 <header class="navbar navbar-inverse">
 <div class="container">
  <div class="navbar-collapse nav-collapse collapse navbar-header">
<ul class="nav navbar-nav">
		   		
<?
 if ($tipo_pedido == "P")				
	{
   $segments = array('pedidos', 'listar2');
	}
	elseif ($tipo_pedido == "I")
	{
$segments = array('pedidos', 'listar3');		
	}
	else
	{
	$segments = array('pedidos', 'listar');
	}

?>		 
	  	<li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url($segments);?>"><span class="glyphicon glyphicon-chevron-left"> VOLVER </span></a>
          </li>
	  	 <?php 
   
   $segments = array('pedidos', 'cerrar', $id_pedido);
$segments2 = array('detalle_pedido', 'recibir', $id_pedido);   

    if (($estado == "En Proceso") && ($id_sede == $sede_log))
  { 
  ?>
   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/detalle_pedido/recibir/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-plus"> NUEVA RECEPCION </span></a>
          </li>
  <?
  
  }
  // REVISAR  , cuando este el loguin por AD aca debo mostrar el cierre solo si soy solicitante de momento lo hago con la sede filtro
  
  //el envio entre sedes, una vez confirmado no se puede cerrar por la sede que emite, dado que pueden estar los insumos en transito, en tal caso debera cerrar la sede que recibe. y no cargar recepcion para restaurar stock
   if (($estado != "Cerrado")&&($id_sede == $sede_log)) 
  { 
  ?>
   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/cerrar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-lock"> CERRAR PEDIDO  </span></a>
          </li>
  <?
  
 }
    
  
  ?>
  
   
  
  </ul>  <!-- .navbar nav -->  
</div> <!-- .colapse -->  
</div> <!-- .container --> 
 </header> <!-- .navbar -->	
	 <?
	if($this->session->flashdata('error')){?>
  <div class="alert alert-danger">      
    <?php echo $this->session->flashdata('error')?>
  </div>
<?php } ?>
	
	<body id="dt_example">
	
			<div id="fw_header">
			
		
			<div class="css_clear"></div>
			
			
				<?foreach ($datos_pedido as $filas)
{?>					
			<div class="row">
						<!-- MODULO DATOS GENERALES -->
			<div class="col-lg-6">
				<div class="panel panel-default" id="panel-datosgenerales">
					<div class="panel-heading">
					<h3 class="panel-title"><b>Datos del Pedido</b></h3>
					</div><!--fin panel heading-->
					<div class="panel-body">
		<div class="row">
		<div class="col-lg-6"><label>Solicitante:&nbsp;</label><?php echo  $this->sedes_model->obtener_nombre($filas['id_sede']) ?>	</div><!--Fin col-lg-6-->
		
		<? 
		
		//obtengo el nombre del proveedor segun el tipo de pedido
		if  (($tipo_pedido == "I")|| ($tipo_pedido == "P"))
		{
			?>
				<div class="col-lg-6"><label>Proveedor:&nbsp;</label><?php echo  $this->sedes_model->obtener_nombre($filas['id_proveedor']) ?>	</div><!--Fin col-lg-6-->
			
			<?  
		}
			else{
				
			?>
		<div class="col-lg-6"><label>Proveedor:&nbsp;</label><?php echo  $this->proveedores_model->obtener_nombre($filas['id_proveedor']) ?>	</div><!--Fin col-lg-6-->
		
		<? } ?>
		<div class="col-lg-12">
						

<div class="row">	
<div class="col-lg-6"><label>Tipo de Pedido:&nbsp;</label><?php echo  $this->general_model->label_pedido($filas['tipo_pedido']) ?>	</div>
<div class="col-lg-6">		<label>N&deg; de Ticket:&nbsp;</label><?php echo  $filas['nro_tk'] ?>	</div>
<div class="col-lg-6">	<label>Observaciones:&nbsp;</label><?php echo  $filas['observaciones'] ?>		</div>
 
</div>								<!--<label>Observaciones:</label>-->
															</div><!--Fin col-lg-12-->
													</div><!--fin row-->
					</div><!--fin panel-body-->
				</div><!--fin panel-datosgenerales-->
			</div>
			<!-- FIN DATOS GENERALES -->
			<div class="col-lg-6">
				<!-- MODULO DATOS ESTADO -->
				<div id="panel-estado">
					<div class="panel panel-default">
						<div class="panel-heading">
						<h3 class="panel-title"><b>Estado del Pedido</b></h3>
						</div>
						<div class="panel-body">
							<div class="row">
<div class="col-sm-6">	<label>N&uacutemero de Pedido:&nbsp; </label><?php echo  $filas['nro_pedido'] ?></div><!--Fin col-sm-6-->
<div class="col-sm-6"><label>Estado:&nbsp; </label><?php echo  $filas['estado_pedido']?></div><!--Fin col-sm-6-->
<div class="col-sm-6"><label>Fecha Pedido:&nbsp; </label><?php echo  $this->general_model->cambia_sql_normal($filas['fecha_pedido']) ?></div><!--Fin col-sm-6-->
							</div><!--fin row-->
						</div><!--Fin panel-body-->
					</div><!--Fin panel panel-default-->
				</div><!--Fin panel-estado-->
				<!-- FIN DATOS ESTADO -->
			</div>
			<!--Fin de col-lg-6-->
		</div>
                                

	<? } ?>		
		
		<div class="panel panel-default" id="panel-documentos">
    <div class="panel-heading titular-fondo-celeste">
					<center><h3 class="panel-title"><b>Detalle del Pedido</b></h3></center>
								    </div>
								    <br>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
            <div id="container">
			
		
			<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Descripci&oacuten</th>
			<th>Cantidad Pedida</th>
			<th>Cantidad Recibida</th>
			<th>Cantidad Pendiente</th>
		</tr>
	</thead>
	<tbody>
	
<?	

	//	$query = $this->db->query('select d.id, i.descripcion, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where d.id_pedido = '.$id_pedido);
		
		
		
 $this->load->helper('recepcion');
foreach ($detalle_pedido as $filas)
{$recibida = obtener_recepciones($filas['id']);
$pendiente = $filas['cantidad_pedida'] - $recibida;
?>
	
		<tr id="<?php echo  $filas['id'] ?> " class="gradeA">
			<td><?php echo  $filas['codigo_insumo'] ?></td>
			<td><?php echo  $filas['cantidad_pedida'] ?></td>
		<td><?php echo  $recibida ?></td>
		<td><?php echo  $pendiente ?></td>
		</tr>
		
		<? } ?>
		
	</tbody>
	<tfoot>
		<tr>
			<th>Descripci&oacuten</th>
			<th>Cantidad Pedida</th>
			<th>Cantidad Recibida</th>
			<th>Cantidad Pendiente</th>
		
			
		</tr>
	</tfoot>
</table>
			</div>
                 </table>
        </div>
</div>		
			
		
			<div class="spacer"></div>
			
