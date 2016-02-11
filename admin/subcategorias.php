<?php require_once('../Connections/ha.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the required classes
require_once('../includes/tfi/TFI.php');
require_once('../includes/tso/TSO.php');
require_once('../includes/nav/NAV.php');

// Make unified connection variable
$conn_ha = new KT_connection($ha, $database_ha);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_ha, "../");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page

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
$tfi_listsubcat1 = new TFI_TableFilter($conn_ha, "tfi_listsubcat1");
$tfi_listsubcat1->addColumn("categoria", "STRING_TYPE", "categoria", "%");
$tfi_listsubcat1->addColumn("subcat", "STRING_TYPE", "subcat", "%");
$tfi_listsubcat1->Execute();

// Sorter
$tso_listsubcat1 = new TSO_TableSorter("subcat", "tso_listsubcat1");
$tso_listsubcat1->addColumn("categoria");
$tso_listsubcat1->addColumn("subcat");
$tso_listsubcat1->setDefault("categoria");
$tso_listsubcat1->Execute();

// Navigation
$nav_listsubcat1 = new NAV_Regular("nav_listsubcat1", "subcat", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_subcat = $_SESSION['max_rows_nav_listsubcat1'];
$pageNum_subcat = 0;
if (isset($_GET['pageNum_subcat'])) {
  $pageNum_subcat = $_GET['pageNum_subcat'];
}
$startRow_subcat = $pageNum_subcat * $maxRows_subcat;

// Defining List Recordset variable
$NXTFilter_subcat = "1=1";
if (isset($_SESSION['filter_tfi_listsubcat1'])) {
  $NXTFilter_subcat = $_SESSION['filter_tfi_listsubcat1'];
}
// Defining List Recordset variable
$NXTSort_subcat = "categoria";
if (isset($_SESSION['sorter_tso_listsubcat1'])) {
  $NXTSort_subcat = $_SESSION['sorter_tso_listsubcat1'];
}
mysql_select_db($database_ha, $ha);

$query_subcat = "SELECT categorias.categoria, subcategorias.subcat, subcategorias.id_subcat FROM (categorias LEFT JOIN subcategorias ON subcategorias.id_cat=categorias.id_cat) WHERE subcategorias.id_subcat is not null  AND  {$NXTFilter_subcat}  ORDER BY  {$NXTSort_subcat} ";
$query_limit_subcat = sprintf("%s LIMIT %d, %d", $query_subcat, $startRow_subcat, $maxRows_subcat);
$subcat = mysql_query($query_limit_subcat, $ha) or die(mysql_error());
$row_subcat = mysql_fetch_assoc($subcat);

if (isset($_GET['totalRows_subcat'])) {
  $totalRows_subcat = $_GET['totalRows_subcat'];
} else {
  $all_subcat = mysql_query($query_subcat);
  $totalRows_subcat = mysql_num_rows($all_subcat);
}
$totalPages_subcat = ceil($totalRows_subcat/$maxRows_subcat)-1;
//End NeXTenesio3 Special List Recordset

$nav_listsubcat1->checkBoundries();
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
  .KT_col_subcat {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listsubcat1">
  <h1> Subcategorías
    <?php
  $nav_listsubcat1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listsubcat1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listsubcat1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listsubcat1']; ?>
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
            <th id="categoria" class="KT_sorter KT_col_categoria <?php echo $tso_listsubcat1->getSortIcon('categoria'); ?>"> <a href="<?php echo $tso_listsubcat1->getSortLink('categoria'); ?>">Categoría</a> </th>
            <th id="subcat" class="KT_sorter KT_col_subcat <?php echo $tso_listsubcat1->getSortIcon('subcat'); ?>"> <a href="<?php echo $tso_listsubcat1->getSortLink('subcat'); ?>">Subcategoría</a> </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_subcat == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="4"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_subcat > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="hidden" name="categoria" class="id_field" value="<?php echo $row_subcat['categoria']; ?>" /></td>
                <td><div class="KT_col_categoria"><?php echo KT_FormatForList($row_subcat['categoria'], 20); ?></div></td>
                <td><div class="KT_col_subcat"><?php echo KT_FormatForList($row_subcat['subcat'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="index.php?mod=subcategorias_detalle&amp;id_subcat=<?php echo $row_subcat['id_subcat']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a></td>
              </tr>
              <?php } while ($row_subcat = mysql_fetch_assoc($subcat)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listsubcat1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"></div>
        <span>&nbsp;</span><a class="KT_additem_op_link" href="index.php?mod=subcategorias_detalle&amp;KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($subcat);
?>
