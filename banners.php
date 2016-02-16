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
$query_banners = "SELECT * FROM banner";
$banners = mysql_query($query_banners, $ha) or die(mysql_error());
$row_banners = mysql_fetch_assoc($banners);
$totalRows_banners = mysql_num_rows($banners);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>
<!--            Banners-->
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 bloques">

        <div class="col-md-12">
            <div class="blueberry">
                
                    <ul class="slides">
                        <?php do { ?>
                        <li><img class="ns-img" src="imgs/<?php echo $row_banners['banner']; ?>" /></li>
                          <?php } while ($row_banners = mysql_fetch_assoc($banners)); ?>
                    </ul>
                
            </div>
        </div>

    </div>
    <div class="col-md-1"></div>
</div>
<!--                Fin banners-->
</body>
</html>
<?php
mysql_free_result($banners);
?>
