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
$formValidation->addField("servicio", true, "text", "", "", "", "");
$formValidation->addField("imagen", true, "", "", "", "", "");
$formValidation->addField("pag_serv", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_FileDelete trigger
//remove this line if you want to edit the code by hand 
function Trigger_FileDelete(&$tNG) {
  $deleteObj = new tNG_FileDelete($tNG);
  $deleteObj->setFolder("../imgs/servicios/");
  $deleteObj->setDbFieldName("imagen");
  return $deleteObj->Execute();
}
//end Trigger_FileDelete trigger

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("imagen");
  $uploadObj->setDbFieldName("imagen");
  $uploadObj->setFolder("../imgs/servicios/");
  $uploadObj->setMaxSize(1500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

// Make an insert transaction instance
$ins_servicios = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_servicios);
// Register triggers
$ins_servicios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_servicios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_servicios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_servicios->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_servicios->setTable("servicios");
$ins_servicios->addColumn("servicio", "STRING_TYPE", "POST", "servicio");
$ins_servicios->addColumn("desc_serv", "STRING_TYPE", "POST", "desc_serv");
$ins_servicios->addColumn("imagen", "FILE_TYPE", "FILES", "imagen");
$ins_servicios->addColumn("pag_serv", "STRING_TYPE", "POST", "pag_serv");
$ins_servicios->setPrimaryKey("id_servicio", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_servicios = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_servicios);
// Register triggers
$upd_servicios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_servicios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_servicios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_servicios->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_servicios->setTable("servicios");
$upd_servicios->addColumn("servicio", "STRING_TYPE", "POST", "servicio");
$upd_servicios->addColumn("desc_serv", "STRING_TYPE", "POST", "desc_serv");
$upd_servicios->addColumn("imagen", "FILE_TYPE", "FILES", "imagen");
$upd_servicios->addColumn("pag_serv", "STRING_TYPE", "POST", "pag_serv");
$upd_servicios->setPrimaryKey("id_servicio", "NUMERIC_TYPE", "GET", "id_servicio");

// Make an instance of the transaction object
$del_servicios = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_servicios);
// Register triggers
$del_servicios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_servicios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$del_servicios->registerTrigger("AFTER", "Trigger_FileDelete", 98);
// Add columns
$del_servicios->setTable("servicios");
$del_servicios->setPrimaryKey("id_servicio", "NUMERIC_TYPE", "GET", "id_servicio");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsservicios = $tNGs->getRecordset("servicios");
$row_rsservicios = mysql_fetch_assoc($rsservicios);
$totalRows_rsservicios = mysql_num_rows($rsservicios);
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
if (@$_GET['id_servicio'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Servicios </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsservicios > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="servicio_<?php echo $cnt1; ?>">Servicio:</label></td>
            <td><input type="text" name="servicio_<?php echo $cnt1; ?>" id="servicio_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsservicios['servicio']); ?>" size="32" maxlength="150" />
              <?php echo $tNGs->displayFieldHint("servicio");?> <?php echo $tNGs->displayFieldError("servicios", "servicio", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="desc_serv_<?php echo $cnt1; ?>">Descripción:</label></td>
            <td><textarea name="desc_serv_<?php echo $cnt1; ?>" cols="32" rows="4" id="desc_serv_<?php echo $cnt1; ?>"><?php echo KT_escapeAttribute($row_rsservicios['desc_serv']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("desc_serv");?> <?php echo $tNGs->displayFieldError("servicios", "desc_serv", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="imagen_<?php echo $cnt1; ?>">Imagen:</label></td>
            <td><input type="file" name="imagen_<?php echo $cnt1; ?>" id="imagen_<?php echo $cnt1; ?>" size="32" />
              <?php echo $tNGs->displayFieldError("servicios", "imagen", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="pag_serv_<?php echo $cnt1; ?>">Página:</label></td>
            <td><input type="text" name="pag_serv_<?php echo $cnt1; ?>" id="pag_serv_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsservicios['pag_serv']); ?>" size="32" maxlength="50" />
              <?php echo $tNGs->displayFieldHint("pag_serv");?> <?php echo $tNGs->displayFieldError("servicios", "pag_serv", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_servicios_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsservicios['kt_pk_servicios']); ?>" />
        <?php } while ($row_rsservicios = mysql_fetch_assoc($rsservicios)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_servicio'] == "") {
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