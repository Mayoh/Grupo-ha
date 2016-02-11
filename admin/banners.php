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
$tfi_listbanner1 = new TFI_TableFilter($conn_ha, "tfi_listbanner1");
$tfi_listbanner1->addColumn("banner.banner", "STRING_TYPE", "banner", "%");
$tfi_listbanner1->Execute();

// Sorter
$tso_listbanner1 = new TSO_TableSorter("rsbanner1", "tso_listbanner1");
$tso_listbanner1->addColumn("banner.banner");
$tso_listbanner1->setDefault("banner.banner");
$tso_listbanner1->Execute();

// Navigation
$nav_listbanner1 = new NAV_Regular("nav_listbanner1", "rsbanner1", "../", $_SERVER['PHP_SELF'], 50);

//NeXTenesio3 Special List Recordset
$maxRows_rsbanner1 = $_SESSION['max_rows_nav_listbanner1'];
$pageNum_rsbanner1 = 0;
if (isset($_GET['pageNum_rsbanner1'])) {
  $pageNum_rsbanner1 = $_GET['pageNum_rsbanner1'];
}
$startRow_rsbanner1 = $pageNum_rsbanner1 * $maxRows_rsbanner1;

// Defining List Recordset variable
$NXTFilter_rsbanner1 = "1=1";
if (isset($_SESSION['filter_tfi_listbanner1'])) {
  $NXTFilter_rsbanner1 = $_SESSION['filter_tfi_listbanner1'];
}
// Defining List Recordset variable
$NXTSort_rsbanner1 = "banner.banner";
if (isset($_SESSION['sorter_tso_listbanner1'])) {
  $NXTSort_rsbanner1 = $_SESSION['sorter_tso_listbanner1'];
}
mysql_select_db($database_ha, $ha);

$query_rsbanner1 = "SELECT banner.banner, banner.id_banner FROM banner WHERE {$NXTFilter_rsbanner1} ORDER BY {$NXTSort_rsbanner1}";
$query_limit_rsbanner1 = sprintf("%s LIMIT %d, %d", $query_rsbanner1, $startRow_rsbanner1, $maxRows_rsbanner1);
$rsbanner1 = mysql_query($query_limit_rsbanner1, $ha) or die(mysql_error());
$row_rsbanner1 = mysql_fetch_assoc($rsbanner1);

if (isset($_GET['totalRows_rsbanner1'])) {
  $totalRows_rsbanner1 = $_GET['totalRows_rsbanner1'];
} else {
  $all_rsbanner1 = mysql_query($query_rsbanner1);
  $totalRows_rsbanner1 = mysql_num_rows($all_rsbanner1);
}
$totalPages_rsbanner1 = ceil($totalRows_rsbanner1/$maxRows_rsbanner1)-1;
//End NeXTenesio3 Special List Recordset

$nav_listbanner1->checkBoundries();
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
  .KT_col_banner {width:140px; overflow:hidden;}
</style>
</head>

<body>
<div class="KT_tng" id="listbanner1">
  <h1> Banners
    <?php
  $nav_listbanner1->Prepare();
  require("../includes/nav/NAV_Text_Statistics.inc.php");
?>
  </h1>
  <div class="KT_tnglist">
    <form action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" method="post" id="form1">
      <div class="KT_options"> <a href="<?php echo $nav_listbanner1->getShowAllLink(); ?>"><?php echo NXT_getResource("Show"); ?>
        <?php 
  // Show IF Conditional region1
  if (@$_GET['show_all_nav_listbanner1'] == 1) {
?>
          <?php echo $_SESSION['default_max_rows_nav_listbanner1']; ?>
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
            <th id="banner" class="KT_sorter KT_col_banner <?php echo $tso_listbanner1->getSortIcon('banner.banner'); ?>"> <a href="<?php echo $tso_listbanner1->getSortLink('banner.banner'); ?>">Banner</a> </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalRows_rsbanner1 == 0) { // Show if recordset empty ?>
            <tr>
              <td colspan="3"><?php echo NXT_getResource("The table is empty or the filter you've selected is too restrictive."); ?></td>
            </tr>
            <?php } // Show if recordset empty ?>
          <?php if ($totalRows_rsbanner1 > 0) { // Show if recordset not empty ?>
            <?php do { ?>
              <tr class="<?php echo @$cnt1++%2==0 ? "" : "KT_even"; ?>">
                <td><input type="hidden" name="id_banner" class="id_field" value="<?php echo $row_rsbanner1['id_banner']; ?>" /></td>
                <td><div class="KT_col_banner"><?php echo KT_FormatForList($row_rsbanner1['banner'], 20); ?></div></td>
                <td><a class="KT_edit_link" href="index.php?mod=banners_detalle&amp;id_banner=<?php echo $row_rsbanner1['id_banner']; ?>&amp;KT_back=1"><?php echo NXT_getResource("edit_one"); ?></a></td>
              </tr>
              <?php } while ($row_rsbanner1 = mysql_fetch_assoc($rsbanner1)); ?>
            <?php } // Show if recordset not empty ?>
        </tbody>
      </table>
      <div class="KT_bottomnav">
        <div>
          <?php
            $nav_listbanner1->Prepare();
            require("../includes/nav/NAV_Text_Navigation.inc.php");
          ?>
        </div>
      </div>
      <div class="KT_bottombuttons">
        <div class="KT_operations"></div>
        <span>&nbsp;</span><a class="KT_additem_op_link" href="index.php?mod=banners_detalle&amp;KT_back=1" onclick="return nxt_list_additem(this)"><?php echo NXT_getResource("add new"); ?></a> </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsbanner1);
?>
