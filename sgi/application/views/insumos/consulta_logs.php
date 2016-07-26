<?php 
session_start();
include "funciones.inc.php";
conexion ();
validasesion(); 
validapermiso(256);

if (isset($_REQUEST['tabla'])) {
	$tabla = $_REQUEST['tabla'];}
if (isset($_REQUEST['accion'])) {
	$accion = $_REQUEST['accion'];}
if (isset($_REQUEST['usuario'])) {
	$usuario = $_REQUEST['usuario'];}
if (isset($_REQUEST['fecha1'])) {
	$fecha1 = $_REQUEST['fecha1'];}
if (isset($_REQUEST['fecha2'])) {
	$fecha2 = $_REQUEST['fecha2'];}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Sistema de Administraci&oacute;n de Salas</title>
		<style type="text/css" title="currentStyle">
			@import "lib/datatable/media/css/demo_page.css"; @import "/media/css/header.ccss";
			@import "lib/datatable/media/css/demo_table.css";
			@import "lib/datatable/ColVis/media/css/ColVis.css";
		</style>
		<link href="lib/menu/css.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="lib/menu/menu.js"></script>	
		<script type="text/javascript" language="javascript" src="lib/datatable/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="lib/datatable/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="lib/datatable/ColVis/media/js/ColVis.js"></script>
		<script type="text/javascript" charset="utf-8">
		var asInitVals = new Array();


		$(document).ready(function() {
			var  oTable = $('#example').dataTable( {
				"sDom": 'RC<"clear">lfrtip',
				/* Menu desplegable de columnas visibles al pasar el mouse encima */
		    	"oColVis": {
		    		
					"activate": "mouseover",
					
						"aiExclude": [ 5 ]
						
						
				},
				"aoColumnDefs": [
					{ "bVisible": false, "aTargets": [ 2 ] }
				],
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
				},
				"bSortCellsTop": true
			} );

		
			
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
	</head>
	<body id="dt_example">
	
	<?php
	
menu ();
$band=0;
$rtdo="SELECT * FROM logs";


/*
 * VOY ARMANDO LA CONSULTA EN FUNCION DE LOS CAMPOS QUE SE HAYAN COMPLETADO EN EL FORMULARIO 
 */

if ($_REQUEST['tabla'] != "")
{
	$tabla = $_REQUEST['tabla'];
		if ( $band == 0)
		{$rtdo= $rtdo." where ".tabla."='$tabla'";
		$band=1;
		}
		else 
		{
		$rtdo= $rtdo." and ".tabla."='$tabla'";
		}
	
}

if ($_REQUEST['accion'] != "")
{
	$accion = $_REQUEST['accion'];
		if ( $band == 0)
		{$rtdo= $rtdo." where ".accion."='$accion'";
		$band=1;
		}
		else 
		{
		$rtdo= $rtdo." and ".accion."='$accion'";
		}
	
}

if ($_REQUEST['usuario'] != "")
{
	$usuario = $_REQUEST['usuario'];
		if ( $band == 0)
		{$rtdo= $rtdo." where ".usuario_sistema."='$usuario'";
		$band=1;
		}
		else 
		{
		$rtdo= $rtdo." and ".usuario_sistema."='$usuario'";
		}
	
}
$horamindesde=" 00:00:00";
$horaminhasta=" 23:59:59";
if ($_REQUEST['fecha1'] != "")
{
	$fecha1 = cambiaf_a_mysqllog($_REQUEST['fecha1']).$horamindesde;
		if ( $band == 0)
		{$rtdo= $rtdo." where ".fechahora.">='$fecha1'";
		$band=1;
		}
		else 
		{
		$rtdo= $rtdo." and ".fechahora.">='$fecha1'";
		}
	
}

if ($_REQUEST['fecha2'] != "")
{
	$fecha2 = cambiaf_a_mysqllog($_REQUEST['fecha2']).$horaminhasta;
		if ( $band == 0)
		{$rtdo= $rtdo." where ".fechahora."<='$fecha2'";
		$band=1;
		}
		else 
		{
		$rtdo= $rtdo." and ".fechahora."<='$fecha2'";
		}
	
}

/*
 * EJECUTO LA CONSULTA
 */

$rtdo=mysql_query($rtdo);  

if(mysql_affected_rows()==0)
 {
 	echo "<br><br><br>";
 ?>	<table align="center"  style="background-color:#FFDDDD;  Font: 17px Arial; font-weight:bold;" border="1" bordercolor="FFB7B7" cellspacing=0>
<tr><td width="450"><div align="center"  style="color:#B30000">No existen Logs cargados en la base de datos con los criterios seleccionados.</div></td></tr>
</table>
				
<?php 
 }
else{
	
$cantidadregistros=mysql_affected_rows();

?>
<div id="container" style="width:80%">
			
<h1 align="center"  Style="Font: 21px Arial; color: #333; font-weight:none;border-bottom: 1px solid #333;"> Logs registrados por el Sistema</h1>
<div id="demo">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
		 <th>Tabla</th>
         <th>Id Registro</th>
         <th>Registro</th>
         <th width="160">Accion</th>
         <th>Equipo</th>
         <th>Usuario Sistema</th>
         <th width="160">Fecha y Hora</th>
		</tr>
		<tr>
			<td align="center"><input type="text" name="search_engine" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_platform" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_version" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_grade" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_version" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_grade" value="" class="search_init" /></td>
		
		</tr>
	</thead>
	<tfoot>
		<tr>
		 <th>Tabla</th>
         <th>Id Registro</th>
         <th>Registro</th>
         <th>Accion</th>
         <th>Equipo</th>
         <th>Usuario Sistema</th>
         <th>Fecha y Hora</th>
		</tr>
	</tfoot>
	<tbody>
	
<?   
$aux=0; //lo uso simplemente para ir variando el estilo de las celdas de manera alternada
while($rtdos=mysql_fetch_array($rtdo))
{$xid=$rtdos["iduser"];
if($numero%2)
{
	echo "<tr class='odd_gradeX'>

<td align='center'>";
echo  muestratabla($rtdos["tabla"]);
echo "</td>
<td align='center'>";
echo $rtdos["idregistro"];
echo "</td>
<td align='center'>";
echo utf8_encode($rtdos["registro"]);
echo "</td>
<td align='center'>";
echo $rtdos["accion"];
echo "</td>
<td align='center'>";
echo utf8_encode($rtdos["equipo"]); 
echo "</td>
<td align='center'>";
echo $rtdos["usuario_sistema"];
echo "</td>
<td align='center'>";
echo $rtdos["fechahora"];
echo "</td>
</tr>";
}
else
{
	echo "<tr class='even_gradeC'>
<td align='center'>";
echo  muestratabla($rtdos["tabla"]);
echo "</td>
<td align='center'>";
echo $rtdos["idregistro"];
echo "</td>
<td align='center'>";
echo utf8_encode($rtdos["registro"]);
echo "</td>
<td align='center'>";
echo $rtdos["accion"];
echo "</td>
<td align='center'>";
echo utf8_encode($rtdos["equipo"]); 
echo "</td>
<td align='center'>";
echo $rtdos["usuario_sistema"];
echo "</td>
<td align='center'>";
echo $rtdos["fechahora"];
echo "</td>
</tr>";
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