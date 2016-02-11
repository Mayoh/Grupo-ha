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
$formValidation->addField("banner", true, "", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../imgs/banners/");
  $deleteObj->setDbFieldName("banner");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("banner");
  $uploadObj->setDbFieldName("banner");
  $uploadObj->setFolder("../imgs/banners/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

// Make an insert transaction instance
$ins_banner = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_banner);
// Register triggers
$ins_banner->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_banner->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_banner->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_banner->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_banner->setTable("banner");
$ins_banner->addColumn("banner", "FILE_TYPE", "FILES", "banner");
$ins_banner->setPrimaryKey("id_banner", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_banner = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_banner);
// Register triggers
$upd_banner->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_banner->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_banner->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_banner->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_banner->setTable("banner");
$upd_banner->addColumn("banner", "FILE_TYPE", "FILES", "banner");
$upd_banner->setPrimaryKey("id_banner", "NUMERIC_TYPE", "GET", "id_banner");

// Make an instance of the transaction object
$del_banner = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_banner);
// Register triggers
$del_banner->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_banner->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_banner->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_banner->setTable("banner");
$del_banner->setPrimaryKey("id_banner", "NUMERIC_TYPE", "GET", "id_banner");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsbanner = $tNGs->getRecordset("banner");
$row_rsbanner = mysql_fetch_assoc($rsbanner);
$totalRows_rsbanner = mysql_num_rows($rsbanner);
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
if (@$_GET['id_banner'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Banner </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsbanner > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="banner_<?php echo $cnt1; ?>">Banner:</label></td>
            <td><input type="file" name="banner_<?php echo $cnt1; ?>" id="banner_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("banner", "banner", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_banner_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsbanner['kt_pk_banner']); ?>" />
        <?php } while ($row_rsbanner = mysql_fetch_assoc($rsbanner)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_banner'] == "") {
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