
			<? 
if ((($tipo_pedido == "I")&& (($solicitante == $sede_log)||($id_proveedor == $sede_log)) )|| (($tipo_pedido == "P")&&(($solicitante == $sede_log)||($id_proveedor == $sede_log))))		
{
//si 
?>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
						
								/* fancybox */
				/*$('.fancybox').fancybox();*/
			 $(".fancybox").fancybox({
            type: 'iframe',
             autoSize : false,
             afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
                parent.location.reload(true);
            },
              beforeLoad : function() {         
            this.width  = parseInt(this.element.data('fancybox-width'));  
            this.height = parseInt(this.element.data('fancybox-height'));
        },
          helpers : {
        				overlay : {
            			css : {
                		'background' : 'rgba(30, 30, 30, 0.35)'
            				  }
        			}
    }
        
        
        });
				
				
				
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
				
				/* Apply the jEditable handlers to the table 
				debe ir 
				*/
				//.site_url('detalle_pedido/editable').
				oTable.$('td:eq(1)').editable( '<?php echo site_url('detalle_pedido/editable'); ?>', {
					"callback": function( sValue, y ) {
						var aPos = oTable.fnGetPosition( this );
						oTable.fnUpdate( sValue, aPos[0], aPos[1] );
					},
					"submitdata": function ( value, settings ) {
						return {
							"row_id": this.parentNode.getAttribute('id'),
							"column": oTable.fnGetPosition( this )[2]
						};
					},
					"height": "24px",
					"width": "100%",
				
				
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
				    },
				    "fnInfoCallback": null,
				    "oAria": {
				        "sSortAscending":  ": Activar para ordernar la columna de manera ascendente",
				        "sSortDescending": ": Activar para ordernar la columna de manera descendente"
				    }
					
				}
				
				
				
				} );
				
				
				
			} );
		</script>
		
				<? 
}
else
{  //si no es el proveedor del envio dejo las celdas no editables en otable se coloco (eq("111") deberia ser "1")
?>


<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				/* fancybox */
				/*$('.fancybox').fancybox();*/
			 $(".fancybox").fancybox({
            type: 'iframe',
            afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
                parent.location.reload(true);
            }
        });
				
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
				oTable.$('td:eq(111)').editable( '<?php echo site_url('detalle_pedido/editable'); ?>', {
				
					"callback": function( sValue, y ) {
						var aPos = oTable.fnGetPosition( this );
						oTable.fnUpdate( sValue, aPos[0], aPos[1] );
					},
					"submitdata": function ( value, settings ) {
						return {
							"row_id": this.parentNode.getAttribute('id'),
							"column": oTable.fnGetPosition( this )[2]
						};
					},
					"height": "14px",
					"width": "100%",
				
				
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
				    },
				    "fnInfoCallback": null,
				    "oAria": {
				        "sSortAscending":  ": Activar para ordernar la columna de manera ascendente",
				        "sSortDescending": ": Activar para ordernar la columna de manera descendente"
				    }
					
				}
				
				
				
				} );
				
				
				
			} );
		</script>
	<?
}
?>
		
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
   
   $atts = array(
        'width'       => 800,
        'height'      => 600,
        'scrollbars'  => 'yes',
        'status'      => 'yes',
        'resizable'   => 'no',
        'screenx'     => 0,
        'screeny'     => 0,
        'window_name' => '_blank'
);

if($tipo_pedido == "N")
{
	echo anchor(site_url('detalle_pedido/recibir'.'/'.$id_pedido), ' Confirmar Ingreso ');  
}

//echo anchor_popup('detalle_pedido/listar/add', 'Click Me!', $atts); 
else
{
	
	if (($tipo_pedido == "P")&&($id_sede == $sede_log))				
	{
		
	?>
  		
	  <li class="dropdown">
		         
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/notificar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-ok"> CONFIRMAR Y NOTIFICAR </span></a>
          </li>
          <?
		}
	elseif ((($tipo_pedido == "I")&&($id_proveedor == $sede_log))|| (($tipo_pedido == "P")&&($id_proveedor == $sede_log)))			
	{ 
		 	?>
  		
	  <li class="dropdown">
		          
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/confirmar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-ok"> CONFIRMAR ENVIO </span></a>
          </li>
          <?
	
	}  
	

}

 str_repeat('<br>','2');
 
 
   $segments = array('detalle_pedido', 'listar', $id_pedido, 'add');
   
if ((($tipo_pedido == "I")&&($id_proveedor == $sede_log)) || (($tipo_pedido == "P")&&($id_sede == $sede_log)))	
{
	

   ?>
   
    <li class="dropdown">
		           
           <a tabindex="-1" href="<?php echo site_url($segments);?>" class="fancybox fancybox.iframe" data-fancybox-width="800" data-fancybox-height="370" ><span class="glyphicon glyphicon-plus"> AGREGAR DETALLE</span></a>
          </li>
 <? } ?>  
     <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/cerrar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-lock"> CERRAR PEDIDO  </span></a>
          </li>
  
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
				<h1>
					<a href="/index">
						
					
				
					</a>
				</h1>

   
				<div class="css_clear"></div>
								
								
				
				<?foreach ( $datos_pedido as $filas)
		{		//echo $datos_pedido;
				
				
		
				
				
?>					
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
                               
<?  break;} ?>
	
		
		<div class="panel panel-default" id="panel-documentos">
    <div class="panel-heading titular-fondo-celeste">
					<center><h3 class="panel-title"><b>Detalle del Envio Interno</b></h3></center>
								    </div>
								    <br>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">						
				
			
				
			
		<div id="container">
				<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Descripcion</th>
			<th>Cantidad Enviada</th>
			<? 
if ((($tipo_pedido == "P")||($tipo_pedido == "I"))&&(($solicitante == $sede_log)||($id_proveedor == $sede_log)))	
{

?>
				<th> </th>
				<?
}
?>	
		</tr>
	</thead>
	<tbody>
	
<?	

	//	$query = $this->db->query('select d.id, i.descripcion, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where d.id_pedido = '.$id_pedido);
		

foreach ($detalle_pedido as $filas)
{
?>
	
		<tr id="<?php echo  $filas['id'] ?> " class="gradeA">
			<td><?php echo  $filas['codigo_insumo'] ?></td>
			<td><?php echo  $filas['cantidad_pedida'] ?></td>

<? //(($solicitante == $sede_log)||($id_proveedor == $sede_log))
if ((($tipo_pedido == "P")||($tipo_pedido == "I"))&&(($solicitante == $sede_log)||($id_proveedor == $sede_log)))	
{

?>
			<td class = "ss">
			
			
<form id="form1" TARGET="_self" action="<?php echo site_url('detalle_pedido/elimina'); ?>" method="POST">
<input type="hidden" value='<?= $filas['id']?>' name="xid" />
<input type="hidden" value='<?= $filas['id_pedido']?>' name="xped" />
<input onclick="if(!confirm(&quot; Esta seguro que desea eliminar el insumo?&quot;))return false;" value="Eliminar" type="submit"/>
</form>
								
				
			</td>
			<?
}
?>	
		</tr>
		
		<? } ?>
		
	</tbody>
	<tfoot>
		<tr>
			<th>Descripcion</th>
			<th>Cantidad Enviada</th>
		<? 
if ((($tipo_pedido == "P")||($tipo_pedido == "I"))&&(($solicitante == $sede_log)||($id_proveedor == $sede_log)))		
{

?>
			<th></th>
			<?
}
?>	
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
	