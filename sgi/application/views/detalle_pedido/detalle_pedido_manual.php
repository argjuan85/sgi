<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
		
		<title>Sistema de Gestion de Insumos </title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css"; @import "/media/css/header.ccss";
			@import "../../media/css/demo_table.css";
		</style>
		<script src="<?php echo base_url();?>assets/DataTables/media/js/jquery.js"></script> 
		<script src="<?php echo base_url();?>assets/DataTables/media/js/jquery.jeditable.js"></script> 
		<script src="<?php echo base_url();?>assets/DataTables/media/js/jquery.dataTables.js"></script> 
		<link href="<?php echo base_url();?>assets/DataTables/media/css/demo_page.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/DataTables/media/css/demo_table.css" rel="stylesheet">
		
		<!-- Add jQuery library 
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/fancybox/jquery-1.10.1.min.js"></script>-->

	
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/fancybox/jquery.fancybox.js?v=2.1.5"></script>
	
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
	
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
					"width": "100%"
				} );
			} );
		</script>
	</head>
	
	
	<body id="dt_example">
		


			<div id="fw_header">
			<!--	<h1>
					<a href="/index">
						<img src="/media/images/DataTables.jpg" alt="DataTables logo">
					
				
					</a>
				</h1>
				-->
		
<div>
		<span>
   
   		<input type="button" value="Volver" id="volver" onclick="location.href='../../Pedidos/listar'"></span>
   <span>
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

//echo anchor_popup('detalle_pedido/listar/add', 'Click Me!', $atts); 
   
   $segments = array('detalle_pedido', 'listar', $id_pedido, 'add');

   
   echo anchor(site_url($segments), 'Click Here', 'class="fancybox fancybox.iframe"');
  /* <a class="fancybox fancybox.iframe" href="<?=site_url('detalle_pedido/listar/add');?>">Iframe</a>*/
   ?>
   </span>
   </div>
   
				<div class="css_clear"></div>
								
								
				
			
				
			
		<div id="container">
			
			
		
			
			<h1>Detalle del pedido </h1>
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
		
	


		
		
		
//foreach ($query->result() as $row)
// foreach ($nombres as $filas){echo $filas['Nombres']."<br>";}  
foreach ($detalle_pedido as $filas)
{
?>
	
		<tr id="<?php echo  $filas['id'] ?> " class="gradeA">
			<td><?php echo  $filas['codigo'] ?></td>
			<td><?php echo  $filas['cantidad_pedida'] ?></td>
			<td class = "ss">
			
			

<form id="form1" TARGET="_self" action="<?php echo site_url('detalle_pedido/elimina'); ?>" method="POST">
<input type="hidden" value='<?= $filas['id']?>' name="xid" />
<input type="hidden" value='<?= $filas['id_pedido']?>' name="xped" />
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
			
			
			</body>
</html>