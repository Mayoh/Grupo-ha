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
$tfi_listcategorias1 = new TFI_TableFilter($conn_ha, "tfi_listcategorias1");
$tfi_listcategorias1->addColumn("categorias.categoria", "STRING_TYPE", "categoria", "%");
$tfi_listcategorias1->addColumn("categorias.liga_cat", "STRING_TYPE", "liga_cat", "%");
$tfi_listcategorias1->Execute();

// Sorter
$tso_listcategorias1 = new TSO_TableSorter("rscategorias1", "tso_listcategorias1");
$tso_listcategorias1->addColumn("categorias.categoria");
$tso_listcategorias1->addColumn("categorias.liga_cat");
$tso_listcategorias1->setDefault("categorias.categoria");
$tso_listcategorias1->Execute();

// Navigation
$nav_listcategorias1 = new NAV_Regular("nav_listcategorias1", "rscategorias1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rscategorias1 = $_SESSION['max_rows_nav_listcategorias1'];
$pageNum_rscategorias1 = 0;
if (isset($_GET['pageNum_rscategorias1'])) {
  $pageNum_rscategorias1 = $_GET['pageNum_rscategorias1'];
}
$startRow_rscategorias1 = $pageNum_rscategorias1 * $maxRows_rscategorias1;

// Defining List Recordset variable
$NXTFilter_rscategorias1 = "1=1";
if (isset($_SESSION['filter_tfi_listcategorias1'])) {
  $NXTFilter_rscategorias1 = $_SESSION['filter_tfi_listcategorias1'];
}
// Defining List Recordset variable
$NXTSort_rscategorias1 = "categorias.categoria";
if (isset($_SESSION['sorter_tso_listcategorias1'])) {
  $NXTSort_rscategorias1 = $_SESSION['sorter_tso_listcategorias1'];
}
mysql_select_db($database_ha, $ha);

$query_rscategorias1 = "SELECT categorias.categoria, categorias.liga_cat, categorias.id_cat FROM categorias WHERE {$NXTFilter_rscategorias1} ORDER BY {$NXTSort_rscategorias1}";
$query_limit_rscategorias1 = sprintf("%s LIMIT %d, %d", $query_rscategorias1, $startRow_rscategorias1, $maxRows_rscategorias1);
$rscategorias1 = mysql_query($query_limit_rscategorias1, $ha) or die(mysql_error());
$row_rscategorias1 = mysql_fetch_assoc($rscategorias1);

if (isset($_GET['totalRows_rscategorias1'])) {
  $totalRows_rscategorias1 = $_GET['totalRows_rscategorias1'];
} else {
  $all_rscategorias1 = mysql_query($query_rscategorias1);
  $totalRows_rscategorias1 = mysql_num_rows($all_rscategorias1);
}
$totalPages_rscategorias1 = ceil($totalRows_rscategorias1/$maxRows_rscategorias1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listcategorias1->checkBoundries();
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
  .KT_col_categoria {width:140px; overflow:hidden;}
  .KT_col_liga_cat {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listcategorias1">
  <h1> Categorias
    <?php
  $nav_listcategorias1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listcategorias1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listcategorias1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listcategorias1']; ?>
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
            <th>&nbsp;</th>
            <th id="categoria" class="KT_sorter KT_col_categoria <?php echo $tso_listcategorias1->getSortIcon('categorias.categoria'); ?>"> <a href="<?php echo $tso_listcategorias1->getSortLink('categorias.categoria'); ?>">Categoria</a> </th>
            <th id="liga_cat" class="KT_sorter KT_col_liga_cat <?php echo $tso_listcategorias1->getSortIcon('categorias.liga_cat'); ?>"> <a href="<?php echo $tso_listcategorias1->getSortLink('categorias.liga_cat'); ?>">Liga</a> </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_rscategorias1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rscategorias1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="hidden" name="id_cat" class="id_field" value="<?php echo $row_rscategorias1['id_cat']; ?>" /></td>
                <td><div class="KT_col_categoria"><?php echo KT_FormatForList($row_rscategorias1['categoria'], 20); ?></div></td>
                <td><div class="KT_col_liga_cat"><?php echo KT_FormatForList($row_rscategorias1['liga_cat'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="index.php?mod=categorias_detalle&amp;id_cat=<?php echo $row_rscategorias1['id_cat']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a></td>
              </tr>
              <?php } while ($row_rscategorias1 = mysql_fetch_assoc($rscategorias1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listcategorias1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"></div>
        <span>&nbsp;</span><a class="KT_additem_op_link" href="index.php?mod=categorias_detalle&amp;KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rscategorias1);
?>
