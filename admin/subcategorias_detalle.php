<?php require_once('../Connections/ha.php'); ?>
<?php
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Load the KT_back class
require_once('../includes/nxt/KT_back.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_ha = new KT_connection($ha, $database_ha);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("id_cat", true, "numeric", "", "", "", "");
$formValidation->addField("subcat", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

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
$query_cats = "SELECT * FROM categorias ORDER BY categoria ASC";
$cats = mysql_query($query_cats, $ha) or die(mysql_error());
$row_cats = mysql_fetch_assoc($cats);
$totalRows_cats = mysql_num_rows($cats);

// Make an insert transaction instance
$ins_subcategorias = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_subcategorias);
// Register triggers
$ins_subcategorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_subcategorias->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_subcategorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_subcategorias->setTable("subcategorias");
$ins_subcategorias->addColumn("id_cat", "NUMERIC_TYPE", "POST", "id_cat");
$ins_subcategorias->addColumn("subcat", "STRING_TYPE", "POST", "subcat");
$ins_subcategorias->setPrimaryKey("id_subcat", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_subcategorias = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_subcategorias);
// Register triggers
$upd_subcategorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_subcategorias->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_subcategorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_subcategorias->setTable("subcategorias");
$upd_subcategorias->addColumn("id_cat", "NUMERIC_TYPE", "POST", "id_cat");
$upd_subcategorias->addColumn("subcat", "STRING_TYPE", "POST", "subcat");
$upd_subcategorias->setPrimaryKey("id_subcat", "NUMERIC_TYPE", "GET", "id_subcat");

// Make an instance of the transaction object
$del_subcategorias = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_subcategorias);
// Register triggers
$del_subcategorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_subcategorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_subcategorias->setTable("subcategorias");
$del_subcategorias->setPrimaryKey("id_subcat", "NUMERIC_TYPE", "GET", "id_subcat");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rssubcategorias = $tNGs->getRecordset("subcategorias");
$row_rssubcategorias = mysql_fetch_assoc($rssubcategorias);
$totalRows_rssubcategorias = mysql_num_rows($rssubcategorias);
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
<?php echo $tNGs->displayValidationRules();?>
<script src="../includes/nxt/scripts/form.js" type="text/javascript"></script>
<script src="../includes/nxt/scripts/form.js.php" type="text/javascript"></script>
<script type="text/javascript">
$NXT_FORM_SETTINGS = {
  duplicate_buttons: false,
  show_as_grid: false,
  merge_down_value: false
}
</script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<div class="KT_tng">
  <h1>
    <?php 
// Show IF Conditional region1 
if (@$_GET['id_subcat'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Subcategorias </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rssubcategorias > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="id_cat_<?php echo $cnt1; ?>">Categoría:</label></td>
            <td><select name="id_cat_<?php echo $cnt1; ?>" id="id_cat_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_cats['id_cat']?>"<?php if (!(strcmp($row_cats['id_cat'], $row_rssubcategorias['id_cat']))) {echo "SELECTED";} ?>><?php echo $row_cats['categoria']?></option>
              <?php
} while ($row_cats = mysql_fetch_assoc($cats));
  $rows = mysql_num_rows($cats);
  if($rows > 0) {
      mysql_data_seek($cats, 0);
	  $row_cats = mysql_fetch_assoc($cats);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("subcategorias", "id_cat", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="subcat_<?php echo $cnt1; ?>">Subcategoría:</label></td>
            <td><input type="text" name="subcat_<?php echo $cnt1; ?>" id="subcat_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rssubcategorias['subcat']); ?>" size="32" maxlength="20" />
              <?php echo $tNGs->displayFieldHint("subcat");?> <?php echo $tNGs->displayFieldError("subcategorias", "subcat", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_subcategorias_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rssubcategorias['kt_pk_subcategorias']); ?>" />
        <?php } while ($row_rssubcategorias = mysql_fetch_assoc($rssubcategorias)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_subcat'] == "") {
      ?>
            <input type="submit" name="KT_Insert1" id="KT_Insert1" value="<?php echo NXT_getResource("Insert_FB"); ?>" />
            <?php 
      // else Conditional region1
      } else { ?>
            <input type="submit" name="KT_Update1" value="<?php echo NXT_getResource("Update_FB"); ?>" />
            <input type="submit" name="KT_Delete1" value="<?php echo NXT_getResource("Delete_FB"); ?>" onclick="return confirm('<?php echo NXT_getResource("Are you sure?"); ?>');" />
            <?php }
      // endif Conditional region1
      ?>
          <input type="button" name="KT_Cancel1" value="<?php echo NXT_getResource("Cancel_FB"); ?>" onclick="return UNI_navigateCancel(event, '../includes/nxt/back.php')" />
        </div>
      </div>
    </form>
  </div>
  <br class="clearfixplain" />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($cats);
?>
