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
$tfi_listservicios1 = new TFI_TableFilter($conn_ha, "tfi_listservicios1");
$tfi_listservicios1->addColumn("servicios.servicio", "STRING_TYPE", "servicio", "%");
$tfi_listservicios1->addColumn("servicios.desc_serv", "STRING_TYPE", "desc_serv", "%");
$tfi_listservicios1->addColumn("servicios.pag_serv", "STRING_TYPE", "pag_serv", "%");
$tfi_listservicios1->Execute();

// Sorter
$tso_listservicios1 = new TSO_TableSorter("rsservicios1", "tso_listservicios1");
$tso_listservicios1->addColumn("servicios.servicio");
$tso_listservicios1->addColumn("servicios.desc_serv");
$tso_listservicios1->addColumn("servicios.pag_serv");
$tso_listservicios1->setDefault("servicios.servicio");
$tso_listservicios1->Execute();

// Navigation
$nav_listservicios1 = new NAV_Regular("nav_listservicios1", "rsservicios1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rsservicios1 = $_SESSION['max_rows_nav_listservicios1'];
$pageNum_rsservicios1 = 0;
if (isset($_GET['pageNum_rsservicios1'])) {
  $pageNum_rsservicios1 = $_GET['pageNum_rsservicios1'];
}
$startRow_rsservicios1 = $pageNum_rsservicios1 * $maxRows_rsservicios1;

// Defining List Recordset variable
$NXTFilter_rsservicios1 = "1=1";
if (isset($_SESSION['filter_tfi_listservicios1'])) {
  $NXTFilter_rsservicios1 = $_SESSION['filter_tfi_listservicios1'];
}
// Defining List Recordset variable
$NXTSort_rsservicios1 = "servicios.servicio";
if (isset($_SESSION['sorter_tso_listservicios1'])) {
  $NXTSort_rsservicios1 = $_SESSION['sorter_tso_listservicios1'];
}
mysql_select_db($database_ha, $ha);

$query_rsservicios1 = "SELECT servicios.servicio, servicios.desc_serv, servicios.pag_serv, servicios.id_servicio FROM servicios WHERE {$NXTFilter_rsservicios1} ORDER BY {$NXTSort_rsservicios1}";
$query_limit_rsservicios1 = sprintf("%s LIMIT %d, %d", $query_rsservicios1, $startRow_rsservicios1, $maxRows_rsservicios1);
$rsservicios1 = mysql_query($query_limit_rsservicios1, $ha) or die(mysql_error());
$row_rsservicios1 = mysql_fetch_assoc($rsservicios1);

if (isset($_GET['totalRows_rsservicios1'])) {
  $totalRows_rsservicios1 = $_GET['totalRows_rsservicios1'];
} else {
  $all_rsservicios1 = mysql_query($query_rsservicios1);
  $totalRows_rsservicios1 = mysql_num_rows($all_rsservicios1);
}
$totalPages_rsservicios1 = ceil($totalRows_rsservicios1/$maxRows_rsservicios1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listservicios1->checkBoundries();
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
  .KT_col_servicio {width:140px; overflow:hidden;}
  .KT_col_desc_serv {width:140px; overflow:hidden;}
  .KT_col_pag_serv {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listservicios1">
  <h1> Servicios
    <?php
  $nav_listservicios1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listservicios1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listservicios1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listservicios1']; ?>
          <?php 
  // else Conditional region1
  } else { ?>
          <?php echo NXT_getResource("all"); ?>
          <?php } 
  // endif Conditional region1
?>
<?php echo NXT_getResource("records"); ?></a> &nbsp;
        &nbsp;
        <?php 
  // Show IF Conditional region2
  if (@$_SESSION['has_filter_tfi_listservicios1'] == 1) {
?>
          <a href="<?php echo $tfi_listservicios1->getResetFilterLink(); ?>"><?php echo NXT_getResource("Reset filter"); ?></a>
          <?php 
  // else Conditional region2
  } else { ?>
          <a href="<?php echo $tfi_listservicios1->getShowFilterLink(); ?>"><?php echo NXT_getResource("Show filter"); ?></a>
          <?php } 
  // endif Conditional region2
?>
      </div>
      <table cellpadding="2" cellspacing="0" class="KT_tngtable">
        <thead>
          <tr class="KT_row_order">
            <th>&nbsp;</th>
            <th id="servicio" class="KT_sorter KT_col_servicio <?php echo $tso_listservicios1->getSortIcon('servicios.servicio'); ?>"> <a href="<?php echo $tso_listservicios1->getSortLink('servicios.servicio'); ?>">Servicio</a> </th>
            <th id="desc_serv" class="KT_sorter KT_col_desc_serv <?php echo $tso_listservicios1->getSortIcon('servicios.desc_serv'); ?>"> <a href="<?php echo $tso_listservicios1->getSortLink('servicios.desc_serv'); ?>">Descripción</a> </th>
            <th id="pag_serv" class="KT_sorter KT_col_pag_serv <?php echo $tso_listservicios1->getSortIcon('servicios.pag_serv'); ?>"> <a href="<?php echo $tso_listservicios1->getSortLink('servicios.pag_serv'); ?>">Página</a> </th>
            <th>&nbsp;</th>
          </tr>
          <?php 
  // Show IF Conditional region3
  if (@$_SESSION['has_filter_tfi_listservicios1'] == 1) {
?>
            <tr class="KT_row_filter">
              <td>&nbsp;</td>
              <td><input type="text" name="tfi_listservicios1_servicio" id="tfi_listservicios1_servicio" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listservicios1_servicio']); ?>" size="20" maxlength="150" /></td>
              <td><input type="text" name="tfi_listservicios1_desc_serv" id="tfi_listservicios1_desc_serv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listservicios1_desc_serv']); ?>" size="20" maxlength="255" /></td>
              <td><input type="text" name="tfi_listservicios1_pag_serv" id="tfi_listservicios1_pag_serv" value="<?php echo KT_escapeAttribute(@$_SESSION['tfi_listservicios1_pag_serv']); ?>" size="20" maxlength="50" /></td>
              <td><input type="submit" name="tfi_listservicios1" value="<?php echo NXT_getResource("Filter"); ?>" /></td>
            </tr>
            <?php } 
  // endif Conditional region3
?>
        </thead>
        <tbody>
          <?php if ($totalRows_rsservicios1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="5"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsservicios1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="hidden" name="id_servicio" class="id_field" value="<?php echo $row_rsservicios1['id_servicio']; ?>" /></td>
                <td><div class="KT_col_servicio"><?php echo KT_FormatForList($row_rsservicios1['servicio'], 20); ?></div></td>
                <td><div class="KT_col_desc_serv"><?php echo KT_FormatForList($row_rsservicios1['desc_serv'], 20); ?></div></td>
                <td><div class="KT_col_pag_serv"><?php echo KT_FormatForList($row_rsservicios1['pag_serv'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="index.php?mod=servicios_detalle&amp;id_servicio=<?php echo $row_rsservicios1['id_servicio']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsservicios1 = mysql_fetch_assoc($rsservicios1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listservicios1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"></div>
        <span>&nbsp;</span><a class="KT_additem_op_link" href="index.php?mod=servicios_detalle&amp;KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsservicios1);
?>
