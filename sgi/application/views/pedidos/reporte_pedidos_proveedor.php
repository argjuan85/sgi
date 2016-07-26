

<script type="text/javascript">
        $(document).ready(function() {
	var oTable = $('#example_ad').dataTable( {
		
		  dom: 'T<"clear">lfrtip',  
	
      
           tableTools: {
           
"sSwfPath": "<?php echo base_url(); ?>assets/TableTools/swf/copy_csv_xls.swf",
   
                                                    
                                                                },
                                                                
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
				    
				    "oPaginate": {
				        "sFirst":    "Primero",
				        "sLast":     "Último",
				        "sNext":     "Siguiente",
				        "sPrevious": "Anterior"
				    },
				    "sLoadingRecords": "Cargando...",
				    "fnInfoCallback": null,
				    "oAria": {
				        "sSortAscending":  ": Activar para ordernar la columna de manera ascendente",
				        "sSortDescending": ": Activar para ordernar la columna de manera descendente"
				    }
				    },
    "bSortCellsTop": true
          
        });  
        
        
        	/* Add the events etc before DataTables hides a column */
			$("thead input").keyup( function () {
				/* Filter on the column (the index) of this element */
				oTable.fnFilter( this.value, oTable.oApi._fnVisibleToColumnIndex( 
					oTable.fnSettings(), $("thead input").index(this) ) );
			} );
			
			/*
			 * Support functions to provide a little bit of 'user friendlyness' to the textboxes
			 */
			$("thead input").each( function (i) {
				this.initVal = this.value;
			} );
			
			$("thead input").focus( function () {
				if ( this.className == "search_init" )
				{
					this.className = "";
					this.value = "";
				}
			} );
			
			$("thead input").blur( function (i) {
				if ( this.value == "" )
				{
					this.className = "search_init";
					this.value = this.initVal;
				}
			} );
        
        
        
        
	
} );
</script>


<header class="navbar navbar-inverse">
 <div class="container">
  <div class="navbar-collapse nav-collapse collapse navbar-header">
<ul class="nav navbar-nav">
		   		
		   <li class="dropdown">
		              
           <a tabindex="-1" href="<?php echo site_url();?>/pedidos/generar_reporte"><span class="glyphicon glyphicon-chevron-left"> VOLVER </span></a>
          </li>
    
  
  </ul>  <!-- .navbar nav -->  
</div> <!-- .colapse -->  
</div> <!-- .container --> 
 </header> <!-- .navbar -->	

	<div class="panel panel-default" id="panel-documentos">

				
		<div id="container">
			
		
										    
													    
													    
													    
								    <br>
	<table id="example_ad" class="display" cellspacing="0" width="100%">
		
      


<div class="panel panel-default" id="panel-datosgenerales">
 <div class="panel-heading titular-fondo-celeste">
<center><h3 class="panel-title"><b><? echo $titulo_reporte; ?></b></h3></center>
	  </div><!-- heading -->
													    
  <div class="panel-body">
		<div class="row">
		<div class="col-lg-6"><label>Proveedor:&nbsp;</label><?php echo  $proveedor; ?>	</div><!--Fin col-lg-6-->
		<div class="col-lg-6"><label>Fecha desde:&nbsp;</label><?php echo  $desde; ?>	</div><!--Fin col-lg-6-->
		<div class="col-lg-12">
						

<div class="row">	
<div class="col-lg-6"><label>Sede:&nbsp;</label><?php echo  $sede; ?>	</div>
<div class="col-lg-6">		<label>Fecha Hasta:&nbsp;</label><?php echo  $hasta; ?>	</div>

 
</div>	<!--fin row-->						
															</div><!--Fin col-lg-12-->
													</div><!--fin row-->
					</div><!--fin panel-body-->
													    
													    
													    
	</div> 	<!--fin datos generales-->			 
		<thead>
	
			<tr>
				<td align="center" class="disp" ><b>N&uacute;mero de Pedido</b></td>
				<td align="center" class="disp"><b>Fecha Pedido </b></td>
				<td align="center" class="disp"><b>Estado </b></td>
				<td align="center" class="disp"><b>Ticket SRP </b></td>
				<td align="center" class="disp"><b>Observaciones </b></td>
					<td align="center" class="disp"><b>Proveedor </b></td>
				 
	
			</tr>
			<tr>
			<td align="center"><input type="text" name="search_engine" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser1" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser2" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser3" value="" class="search_init" /></td>
		<td align="center"><input type="text" name="search_browser4" value="" class="search_init" /></td>
			</tr>
			
			</thead>
		 
     
		<tfoot>
				<tr>
					<td align="center" class="disp" ><b>N&uacute;mero de Pedido</b></td>
				<td align="center" class="disp"><b>Fecha Pedido </b></td>
				<td align="center" class="disp"><b>Estado </b></td>
				<td align="center" class="disp"><b>Ticket SRP </b></td>
				<td align="center" class="disp"><b>Observaciones </b></td>
					<td align="center" class="disp"><b>Proveedor </b></td>
			 
			</tr>
		</tfoot>
		<tbody>
			<?php
			foreach ($query_ad as $row)
			{
				echo "<tr>";
				echo "<td align='center'>". $row['nro_pedido'] ."</td>";
			 	echo "<td align='center'>".$this->general_model->cambia_sql_normal($row['fecha_pedido']) ."</td>";
				echo "<td align='center'>".$row['estado_pedido'] ."</td>";
				echo "<td align='center'>".$row['nro_tk'] ."</td>";
				echo "<td align='center'>".$row['observaciones'] ."</td>";
				echo "<td align='center'>".$this->proveedores_model->obtener_nombre($row['id_proveedor']) ."</td>";
				echo "</tr>";
			}
			
			?>
		</tbody>
	</table>
	

</div>  <!--fin advertisement-->	
</div>	<!--fin container-->	
