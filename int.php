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

$colname_pag = "-1";
if (isset($_GET['mod'])) {
  $colname_pag = $_GET['mod'];
}
mysql_select_db($database_ha, $ha);
$query_pag = sprintf("SELECT contenido FROM paginas WHERE pag = %s", GetSQLValueString($colname_pag, "text"));
$pag = mysql_query($query_pag, $ha) or die(mysql_error());
$row_pag = mysql_fetch_assoc($pag);
$totalRows_pag = mysql_num_rows($pag);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>
<?php echo $row_pag['contenido']; ?>
</body>
</html>
<?php
mysql_free_result($pag);
?>
