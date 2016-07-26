
	
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
				
				/* Apply the jEditable handlers to the table */
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
	?>
	 <li class="dropdown">
	   
           <a tabindex="-1" href="<?php echo site_url();?>/detalle_pedido/recibir/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-ok"> CONFIRMAR INGRESO </span></a>
          </li>
	<?
	  
}

//echo anchor_popup('detalle_pedido/listar/add', 'Click Me!', $atts); 
elseif (($tipo_pedido == "P")&&($id_sede == $sede_log))				
	{
		
	?>
  		
	  <li class="dropdown">
		         
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/notificar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-ok"> CONFIRMAR Y NOTIFICAR </span></a>
          </li>
          <?
		}
		else
{?>

  <li class="dropdown">
		    
           
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/confirmar/<?php echo $id_pedido?>"><span class="glyphicon glyphicon-ok"> CONFIRMAR PEDIDO </span></a>
          </li>
		   		
<?
}

 str_repeat('<br>','2');
 
 
   $segments = array('detalle_pedido', 'listar', $id_pedido, 'add');

   ?>
   
    <li class="dropdown">
		    
           
           <a tabindex="-1" href="<?php echo site_url();?>/detalle_pedido/listar/<?php echo $id_pedido?>/add" class="fancybox fancybox.iframe" data-fancybox-width="800" data-fancybox-height="370" ><span class="glyphicon glyphicon-plus"> AGREGAR DETALLE</span></a>
          </li>
          
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
			<th>Descripcion</th>
			<th>Cantidad Pedida</th>
				<th> </th>
		</tr>
	</thead>
	<tbody>
	
<?	

	//	$query = $this->db->query('select d.id, i.descripcion, d.cantidad_pedida from detalle_pedido d inner join insumos i on i.id=d.id_insumo where d.id_pedido = '.$id_pedido);
		
	


if ($detalle_pedido == "-1")
{
$detalle_pedido = "";	
}		
	
		
//foreach ($query->result() as $row)
// foreach ($nombres as $filas){echo $filas['Nombres']."<br>";}  
foreach ($detalle_pedido as $filas)
{
?>
	
		<tr id="<?php echo  $filas['id'] ?> " class="gradeA">
			<td><?php echo  $filas['codigo_insumo'] ?></td>
			<td><?php echo  $filas['cantidad_pedida'] ?></td>
			<td class = "ss">
			
<form id="form1" TARGET="_self" action="<?php echo site_url('detalle_pedido/elimina'); ?>" method="POST">			
<input type="hidden" value='<?= $filas['id']?>' name="xid" />
<input type="hidden" value='<?= $filas['id_pedido']?>' name="xped" />
<input type="hidden" value='<?= $this->session->userdata('url_server')?>' name="url" />
<input onclick="if(!confirm(&quot; Esta seguro que desea eliminar el insumo?&quot;))return false;" value="Eliminar" type="submit"/>
</form>
			
				
				
				
			</td>
		</tr>
		
		<? } ?>
		
	</tbody>
	<tfoot>
		<tr>
			<th>Descripcion</th>
			<th>Cantidad Pedida</th>
		
			<th></th>
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
			
	

	
			
			
