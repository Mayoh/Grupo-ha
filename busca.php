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

$busca=$_GET['search'];

mysql_select_db($database_ha, $ha);
$query_search = "SELECT paginas.pag, CONCAT_WS(' ', TRIM(SUBSTRING_INDEX(SUBSTRING(paginas.contenido, 1, INSTR(paginas.contenido, '$busca') - 1 ),' ', -50)),'<span id=\"destacadoBusca\">','$busca','</span>', TRIM(SUBSTRING_INDEX(SUBSTRING(paginas.contenido, INSTR(paginas.contenido, '$busca') + LENGTH('$busca') ),' ',50))) AS contenido FROM paginas WHERE paginas.contenido LIKE '%$busca%'";
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
            <div class="resultados"><?php do { ?>
            <div class="resultado">
                <?php $contenido=$row_search['contenido'];?>
                <a href="index.php?mod=<?php echo $row_search['pag']; ?>"><?php echo strip_tags($contenido, '<span>'); ?>...</a>
              </div>
              <?php } while ($row_search = mysql_fetch_assoc($search)); ?></div>
        </div>
        <!--Fin contenido-->
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($search);
?>
