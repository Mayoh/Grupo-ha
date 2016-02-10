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

mysql_select_db($database_ha, $ha);
$query_Servicios = "SELECT * FROM servicios";
$Servicios = mysql_query($query_Servicios, $ha) or die(mysql_error());
$row_Servicios = mysql_fetch_assoc($Servicios);
$totalRows_Servicios = mysql_num_rows($Servicios);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>
<div class="row"><!--Aquí inicia servicios. Al dar click en cada recuadro, debe mandar a la descripción general del servicio y sus subservicios-->
                <div class="col-md-1"></div>
                <div class="col-md-10 bloques">
                    <h2 class="titulos-ha">Servicios</h2>
                    <?php do { ?>
                    <div class="col-md-4">
                        <center>
                          <div class="servicios">
                            <img src="imgs/<?php echo $row_Servicios['imagen']; ?>" alt="<?php echo $row_Servicios['servicio']; ?>">
                            <div class="servicio-abstract">
                              <p class="abstract"><a href="index.php?mod=<?php echo $row_Servicios['pag_serv']; ?>"><span class="titulo-servicio"><?php echo $row_Servicios['servicio']; ?></span></a><br/><br/><?php echo $row_Servicios['desc_serv']; ?></p>
                              </div>
                            </div>
                          </center>
                      </div>
                      <?php } while ($row_Servicios = mysql_fetch_assoc($Servicios)); ?>
<div class="col-md-1"></div>
                </div>
            </div><!--Aquí termina servicios-->
</body>
</html>
<?php
mysql_free_result($Servicios);
?>
