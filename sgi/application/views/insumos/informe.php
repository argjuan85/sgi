<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		
		<title>Sistema de Administraci&oacute;n de Salas</title>
	
		<link href="lib/menu/css.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>application/libraries/datatable/media/css/demo_page.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/libraries/datatable/media/css/demo_table.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/libraries/datatable/ColVis/media/css/ColVis.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>application/libraries/datatable/media/css/demo_page.css" rel="stylesheet">
		<script type="text/javascript" src="lib/menu/menu.js"></script>	
		<script src="<?php echo base_url(); ?>application/libraries/datatable/media/js/jquery.js"></script>
		<script src="<?php echo base_url(); ?>application/libraries/datatable/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url(); ?>application/libraries/datatable/media/js/ColVis.js"></script>
		
		
		<script type="text/javascript" charset="utf-8">
		var asInitVals = new Array();
		 
		$(document).ready(function() {
		    var oTable = $('#example').dataTable( {
		    	
		    	

							
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

		 
		    $("tfoot input").keyup( function () {
		        /* Filter on the column (the index) of this element */
		        oTable.fnFilter( this.value, $("tfoot input").index(this) );
		    } );
		     
		     
		     
		    /*
		     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
		     * the footer
		     */
		    $("tfoot input").each( function (i) {
		        asInitVals[i] = this.value;
		    } );
		     
		    $("tfoot input").focus( function () {
		        if ( this.className == "search_init" )
		        {
		            this.className = "";
		            this.value = "";
		        }
		    } );
		     
		    $("tfoot input").blur( function (i) {
		        if ( this.value == "" )
		        {
		            this.className = "search_init";
		            this.value = asInitVals[$("tfoot input").index(this)];
		        }
		    } );

		    
			
		} );
		</script>
	</head>
	<body id="dt_example">
	
	<?php
	


$rtdo="SELECT * FROM proveedores ";
//Me conecto y elijo la base de datos, devuelvo la conexion en una variable
function conexion () 
{ 
     if (!($conexion=mysql_connect("localhost", "root", "mtvac"))) {
   	 printf("<p> Error de Conexion a la Base de Datos</p>") ;
	 exit() ;
	}
	// Selecciono la base
	if (!mysql_select_db("sgi", $conexion)) {
   	printf("<p> Base de datos no válida</p>") ;
	exit() ;						}
 return ($conexion);
}
$rtdo=mysql_query($rtdo,conexion());  
if(mysql_affected_rows()==0)
 {
 	echo "<br><br><br>";
  echo "No existen sectores cargados en la base de datos";?>
 					<table cellpadding="0" cellspacing="0" border="0"  width="100%">
	 <td align='center'>
<form id="form1" TARGET="_self" action="../salas/sector/cargar_sector" method="POST">
<input  value="+ Agregar Sector" type="submit"/>
</form></td>
</table>	
<?php 
 }
else{
	
$cantidadregistros=mysql_affected_rows();

?>
		<div id="container" style="width:80%">
			
				
			<h1 align="center" Style="Font: 21px Arial; color: #333; font-weight:none;border-bottom: 1px solid #333;" > Sectores Cargados en el Sistema</h1>
			<div id="demo">
			<table cellpadding="0" cellspacing="0" border="0"  width="100%">
	 <td align='center'>
<form id="form1" TARGET="_self" action="../salas/sector/cargar_sector" method="POST">
<input  value="+ Agregar Sector" type="submit"/>
</form></td>
</table>		
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
	
		<tr>
		 <th>Nombre</th>
         <th>Habilitado?</th>
         <th>Modificar</th>
         <th>Eliminar</th>
		</tr>
	</thead>
	<tbody>
	
<?   
$aux=0; //lo uso simplemente para ir variando el estilo de las celdas de manera alternada
while($rtdos=mysql_fetch_array($rtdo))
{$xid=$rtdos["id"];
if(10%2)
{
	echo "<tr class='odd_gradeX'>

<td align='center'>";
echo utf8_encode($rtdos["nombre"]);
echo "</td>
<td align='center'>";
if (1 == 1) 
echo "Si";
else 
echo "No";
echo "</td>
<td align='center'>";
?>

<form id="form1" TARGET="_self" action="../salas/sector/modificar_sector" method="POST">
<input type="hidden" value='<?= $xid?>' name="id" />
<input type="submit" value="Modificar" >
</form></td>
<td align='center'>
<form id="form1" TARGET="_self" action="../salas/sector/eliminar_sector" method="POST">
<input type="hidden" value='<?= $xid?>' name="id" />
<input onclick="if(!confirm(&quot; Esta seguro que desea eliminar el Sector?&quot;))return false;" value="Eliminar" type="submit"/>
</form></td>
</tr>
<?php //en I.E no me tomaba el target self (se abria otra pagina) probe con blank y despues volver y funciono, tener en cuenta 
}
else
{
	echo "<tr class='even_gradeC'>
<td align='center'>";
echo utf8_encode($rtdos["nombre"]);
echo "</td>
<td align='center'>";
if (1 == 1) 
echo "Si";
else 
echo "No";
echo "</td>
<td align='center'>";

?>

<form id="form1" TARGET="_self" action="../salas/sector/modificar_sector" method="POST">
<input type="hidden" value='<?= $xid?>' name="id" />
<input type="submit" value="Modificar" >
</form></td>
<td align='center'>
<form id="form1" TARGET="_self" action="../salas/sector/eliminar_sector" method="POST">
<input type="hidden" value='<?= $xid?>' name="id" />
<input onclick="if(!confirm(&quot; Esta seguro que desea eliminar el Sector?&quot;))return false;" value="Eliminar" type="submit"/>
</form></td>
</tr>
<?php 
} 
$aux=$aux+1;
} ?>
		
</tbody>
</table>
</div>
</div>
<?php
}
?>
</body>
</html>