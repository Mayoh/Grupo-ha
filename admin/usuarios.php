<?php require_once('../Connections/ha.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_ha = new KT_connection($ha, $database_ha);

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

// Filter
$tfi_listusuarios1 = new TFI_TableFilter($conn_ha, "tfi_listusuarios1");
$tfi_listusuarios1->addColumn("usuarios.nombre", "STRING_TYPE", "nombre", "%");
$tfi_listusuarios1->addColumn("usuarios.apellidos", "STRING_TYPE", "apellidos", "%");
$tfi_listusuarios1->addColumn("usuarios.usuario", "STRING_TYPE", "usuario", "%");
$tfi_listusuarios1->Execute();

// Sorter
$tso_listusuarios1 = new TSO_TableSorter("rsusuarios1", "tso_listusuarios1");
$tso_listusuarios1->addColumn("usuarios.nombre");
$tso_listusuarios1->addColumn("usuarios.apellidos");
$tso_listusuarios1->addColumn("usuarios.usuario");
$tso_listusuarios1->setDefault("usuarios.nombre");
$tso_listusuarios1->Execute();

// Navigation
$nav_listusuarios1 = new NAV_Regular("nav_listusuarios1", "rsusuarios1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rsusuarios1 = $_SESSION['max_rows_nav_listusuarios1'];
$pageNum_rsusuarios1 = 0;
if (isset($_GET['pageNum_rsusuarios1'])) {
  $pageNum_rsusuarios1 = $_GET['pageNum_rsusuarios1'];
}
$startRow_rsusuarios1 = $pageNum_rsusuarios1 * $maxRows_rsusuarios1;

// Defining List Recordset variable
$NXTFilter_rsusuarios1 = "1=1";
if (isset($_SESSION['filter_tfi_listusuarios1'])) {
  $NXTFilter_rsusuarios1 = $_SESSION['filter_tfi_listusuarios1'];
}
// Defining List Recordset variable
$NXTSort_rsusuarios1 = "usuarios.nombre";
if (isset($_SESSION['sorter_tso_listusuarios1'])) {
  $NXTSort_rsusuarios1 = $_SESSION['sorter_tso_listusuarios1'];
}
mysql_select_db($database_ha, $ha);

$query_rsusuarios1 = "SELECT usuarios.nombre, usuarios.apellidos, usuarios.usuario, usuarios.id_usuario FROM usuarios WHERE {$NXTFilter_rsusuarios1} ORDER BY {$NXTSort_rsusuarios1}";
$query_limit_rsusuarios1 = sprintf("%s LIMIT %d, %d", $query_rsusuarios1, $startRow_rsusuarios1, $maxRows_rsusuarios1);
$rsusuarios1 = mysql_query($query_limit_rsusuarios1, $ha) or die(mysql_error());
$row_rsusuarios1 = mysql_fetch_assoc($rsusuarios1);

if (isset($_GET['totalRows_rsusuarios1'])) {
  $totalRows_rsusuarios1 = $_GET['totalRows_rsusuarios1'];
} else {
  $all_rsusuarios1 = mysql_query($query_rsusuarios1);
  $totalRows_rsusuarios1 = mysql_num_rows($all_rsusuarios1);
}
$totalPages_rsusuarios1 = ceil($totalRows_rsusuarios1/$maxRows_rsusuarios1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listusuarios1->checkBoundries();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/list.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_LIST_SETTINGS = {
  duplicate_buttons: false,
  duplicate_navigation: false,
  row_effects: true,
  show_as_buttons: false,
  record_counter: false
}
</script>
<style type="text/css">
  /* Dynamic List row settings */
  .KT_col_nombre {width:140px; overflow:hidden;}
  .KT_col_apellidos {width:140px; overflow:hidden;}
  .KT_col_usuario {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listusuarios1">
  <h1> Usuarios
    <?php
  $nav_listusuarios1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listusuarios1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listusuarios1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listusuarios1']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
<?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp; </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th> <input type="checkbox" name="KT_selAll" id="KT_selAll"/>
            </th>
            <th id="nombre" class="KT_sorter KT_col_nombre <?php echo $tso_listusuarios1->getSortIcon('usuarios.nombre'); ?>"> <a href="<?php echo $tso_listusuarios1->getSortLink('usuarios.nombre'); ?>">Nombre</a> </th>
            <th id="apellidos" class="KT_sorter KT_col_apellidos <?php echo $tso_listusuarios1->getSortIcon('usuarios.apellidos'); ?>"> <a href="<?php echo $tso_listusuarios1->getSortLink('usuarios.apellidos'); ?>">Apellidos</a> </th>
            <th id="usuario" class="KT_sorter KT_col_usuario <?php echo $tso_listusuarios1->getSortIcon('usuarios.usuario'); ?>"> <a href="<?php echo $tso_listusuarios1->getSortLink('usuarios.usuario'); ?>">Usuario</a> </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_rsusuarios1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsusuarios1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="hidden" name="id_usuario" class="id_field" value="<?php echo $row_rsusuarios1['id_usuario']; ?>" /></td>
                <td><div class="KT_col_nombre"><?php echo KT_FormatForList($row_rsusuarios1['nombre'], 20); ?></div></td>
                <td><div class="KT_col_apellidos"><?php echo KT_FormatForList($row_rsusuarios1['apellidos'], 20); ?></div></td>
                <td><div class="KT_col_usuario"><?php echo KT_FormatForList($row_rsusuarios1['usuario'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="index.php?mod=usuarios_detalle&amp;id_usuario=<?php echo $row_rsusuarios1['id_usuario']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsusuarios1 = mysql_fetch_assoc($rsusuarios1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listusuarios1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"></div>
        <span>&nbsp;</span><a class="KT_additem_op_link" href="index.php?mod=usuarios_detalle&amp;KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsusuarios1);
?>
