<?
//Me conecto y elijo la base de datos, devuelvo la conexion en una variable
function conexion () 
{ 
$hostdb = "localhost";
$userdb = "root";
$clavedb = "mtvac";
$dbname = "sgi";
     if (!($conexion=mysql_connect($hostdb, $userdb, $clavedb))) {
   	 printf("<p> Error de Conexion a la Base de Datos</p>") ;
	 exit() ;
	}
	// Selecciono la base
	if (!mysql_select_db($dbname, $conexion)) {
   	printf("<p> Base de datos no válida</p>") ;
	exit() ;						}
 return ($conexion);
}
//echo $_POST['id']; 
//echo $_POST['value'];


	if (isset($_POST['id']) ) 
		{
		
		$sql = "update recepcion set tipo_recepcion= ". "'" .$_POST['value']."' ";
	    $sql = $sql . " where id_detalle_pedido= " ."'".trim($_POST['id'])."'";
        $rtdo=mysql_query($sql,conexion()); 
    
    	  if($rtdo == '')
 
			{
       		echo "error";
       		//echo $rtdo;
       		}
       		else
       		{ 
       		echo $_POST['value'];  
      		
		
			}
        	}
        
       
		?>