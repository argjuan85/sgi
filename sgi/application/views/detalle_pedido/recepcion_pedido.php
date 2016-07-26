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
				
				);
				
				
				/* Apply the jEditable handlers to the table */
				
				oTable.$('.myclass').editable( '<?php echo site_url('detalle_pedido/modifica_recepcion'); ?>', {
				
					"callback": function( sValue, y ) {
						var aPos = oTable.fnGetPosition( this );
				
						oTable.fnUpdate( sValue, aPos[0], aPos[1] );
					},
					"submitdata": function ( value, settings, nro, tipo ) {
								var nro = "<?php echo $numero; ?>" ;
						var tipo = "<?php echo $tipo_recepcion; ?>" ;
						return {
							
							"row_id": this.parentNode.getAttribute('id'),
							"column": oTable.fnGetPosition( this )[2],
							"numero": nro,
							"tipo_recepcion":tipo
						};
					},
					"height": "24px",
					"width": "100%",
					"tooltip"   : "Click para editar...",
					
				} );
								        
 oTable.$('.editable_select').editable('<?php echo base_url(); ?>assets/TableTools/recepcion2.php', { 
    indicator : '<img src="<?php echo base_url(); ?>assets/img/indicator.gif">',
    data   : "{'Remito':'R','Traza':'T'}",
    type   : "select",
    submit : "OK",
    style  : "inherit",
    tooltip   : "Click para editar...",
    
   submitdata : function() {
      return {};
    }
});
				
				
			} );
		</script>
		<style type="text/css">



.panel-default .panel-heading {
    color: #ffffff;
    background-image: none;
    background-color: #0A4273;
    border: none;
    text-transform: uppercase;
}
</style>
		 <header class="navbar navbar-inverse">
 <div class="container">
  <div class="navbar-collapse nav-collapse collapse navbar-header">
<ul class="nav navbar-nav">
		   		
<?
   if ($tipo_pedido == "P")				
	{
   $segments = array('pedidos', 'listar2');
   $proveedor = $proveedor_pedido;	
	}
	elseif($tipo_pedido == "I")
	{
	$segments = array('pedidos', 'listar3');
	$proveedor = $proveedor_pedido;		
	}
	else
	{
	$segments = array('pedidos', 'listar');
	$proveedor = $proveedor_pedido;		
	}
	
	
	
		?>
		<li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url($segments); ?>"> <span class="glyphicon glyphicon-chevron-left"> FINALIZAR RECEPCION </span></a>
          </li>
		
		 	
     
  <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/cerrar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-lock"> CERRAR PEDIDO  </span></a>
          </li>
   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/detalle_pedido/recibir/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-plus"> NUEVA RECEPCION </span></a>
          </li>
   
     <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/detalle_pedido/recibir_total/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-floppy-save"> RECIBIR TODO  </span></a>
          </li>
   
  
  </ul>  <!-- .navbar nav -->  
</div> <!-- .colapse -->  
</div> <!-- .container --> 
 </header> <!-- .navbar -->	
	
	
	
	<body id="dt_example">
		


			<div id="fw_header">
			<!--	<h1>
					<a href="/index">
						<img src="/media/images/DataTables.jpg" alt="DataTables logo">
						DataTables
				
					</a>
				</h1>-->
		
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
		<div class="col-lg-6"><label>Proveedor:&nbsp;</label><?php echo  $proveedor ?>	</div><!--Fin col-lg-6-->
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
	
			
			<? if (!isset($numero))
			{
				echo "no se pudo recuperar el dato de la recepcion"; 
			}
			else
			{
			
			
				
			?>	
			
			<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Descripcion</th>
			<th>Cantidad Pedida</th>
			<th>Cantidad Recibida</th>
			<th>Cantidad Pendiente</th>
			<th>Cantidad a Ingresar</th>
			<th> </th>
	
		</tr>
	</thead>
	<tbody>
	
<?	

	 $this->load->helper('recepcion');
foreach ($detalle_pedido as $filas)
{
	$recibida = obtener_recepciones($filas['id']);
	$pendiente = $filas['cantidad_pedida'] - $recibida;
	$aux = "-";
?>
	
		<tr id="<?php echo  $filas['id'] ?> " class="gradeA">
			<td><?php echo $filas['codigo_insumo'] ?></td>
			<td><?php  echo $filas['cantidad_pedida'] ?></td>
			<td ><?php echo $recibida ?></td>
			<td ><?php echo $pendiente ?></td>
			<td class="myclass"><?php  
			
			//if (!isset($_SESSION['valor']))
			//echo $aux;
			//else
			//echo $_SESSION['valor']; 
			//unset($_SESSION['valor']);*/
			?>
				
			</td>
		
		
			<td class = "ss">
<?		
	if(isset ($filas['id']))
	{	echo form_open('detalle_pedido/eliminar');
		$data = array(
        'id'  => $filas['id'],
        'id_pedido' => $filas['id_pedido'],
        'numero'   => $numero,
        'tipo_recepcion'   => $tipo_recepcion
);
echo form_hidden('data', $data);
$js = 'onClick="if(!confirm(&quot; Esta seguro que desea eliminar la recepción?&quot;))return false;"';
echo form_submit('submit', 'Eliminar Recepcion', $js);
echo form_close();
}
			
				
	?>			
				
			</td>
			
		</tr>
		
		<? } ?>
		
	</tbody>
	<tfoot>
		<tr>
			<th>Descripcion</th>
			<th>Cantidad Pedida</th>
			<th>Cantidad Recibida</th>
			<th>Tipo</th>
			<th>Nro</th>
			<th></th>
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
		<?
			}
			?>	
			
