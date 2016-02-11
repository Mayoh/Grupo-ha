<?php require_once('Connections/ha.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_search = "-1";
if (isset($_GET['busca'])) {
  $colname_search = $_GET['busca'];
}
mysql_select_db($database_ha, $ha);
$query_search = sprintf("SELECT * FROM paginas WHERE contenido LIKE %s", GetSQLValueString("%" . $colname_search . "%", "text"));
$search = mysql_query($query_search, $ha) or die(mysql_error());
$row_search = mysql_fetch_assoc($search);
$totalRows_search = mysql_num_rows($search);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>
<div class="row">
	<div class="col-md-1"></div>
    <div class="col-md-10 bloques">
    	<div class="col-md-12">
        	<h2 class="titulos-ha">Resultados</h2>
            <div class="resultado">
            	<a href="inex.php?mod=<?php echo $row_search['pag']; ?>"><?php echo $row_search['contenido']; ?></a>
            </div>
        </div>
        <!--Fin contenido-->
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($search);
?>
