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

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_ha, "../");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("categoria", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_categorias = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_categorias);
// Register triggers
$ins_categorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_categorias->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_categorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_categorias->setTable("categorias");
$ins_categorias->addColumn("categoria", "STRING_TYPE", "POST", "categoria");
$ins_categorias->addColumn("liga_cat", "STRING_TYPE", "POST", "liga_cat");
$ins_categorias->setPrimaryKey("id_cat", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_categorias = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_categorias);
// Register triggers
$upd_categorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_categorias->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_categorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_categorias->setTable("categorias");
$upd_categorias->addColumn("categoria", "STRING_TYPE", "POST", "categoria");
$upd_categorias->addColumn("liga_cat", "STRING_TYPE", "POST", "liga_cat");
$upd_categorias->setPrimaryKey("id_cat", "NUMERIC_TYPE", "GET", "id_cat");

// Make an instance of the transaction object
$del_categorias = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_categorias);
// Register triggers
$del_categorias->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_categorias->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_categorias->setTable("categorias");
$del_categorias->setPrimaryKey("id_cat", "NUMERIC_TYPE", "GET", "id_cat");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscategorias = $tNGs->getRecordset("categorias");
$row_rscategorias = mysql_fetch_assoc($rscategorias);
$totalRows_rscategorias = mysql_num_rows($rscategorias);
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
if (@$_GET['id_cat'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Categorias </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rscategorias > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="categoria_<?php echo $cnt1; ?>">Categoria:</label></td>
            <td><input type="text" name="categoria_<?php echo $cnt1; ?>" id="categoria_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscategorias['categoria']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("categoria");?> <?php echo $tNGs->displayFieldError("categorias", "categoria", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="liga_cat_<?php echo $cnt1; ?>">Liga:</label></td>
            <td><input type="text" name="liga_cat_<?php echo $cnt1; ?>" id="liga_cat_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rscategorias['liga_cat']); ?>" size="32" maxlength="50" />
              <?php echo $tNGs->displayFieldHint("liga_cat");?> <?php echo $tNGs->displayFieldError("categorias", "liga_cat", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_categorias_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rscategorias['kt_pk_categorias']); ?>" />
        <?php } while ($row_rscategorias = mysql_fetch_assoc($rscategorias)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_cat'] == "") {
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