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

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Could not create account.");
  $myThrowError->setField("contrasena");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("nombre", true, "text", "", "", "", "");
$formValidation->addField("apellidos", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
$formValidation->addField("usuario", true, "text", "", "", "", "");
$formValidation->addField("contrasena", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_CheckOldPassword trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
//end Trigger_CheckOldPassword trigger

// Make an insert transaction instance
$ins_usuarios = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_usuarios);
// Register triggers
$ins_usuarios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_usuarios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_usuarios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$ins_usuarios->registerConditionalTrigger("{POST.contrasena} != {POST.re_contrasena}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_usuarios->setTable("usuarios");
$ins_usuarios->addColumn("nombre", "STRING_TYPE", "POST", "nombre");
$ins_usuarios->addColumn("apellidos", "STRING_TYPE", "POST", "apellidos");
$ins_usuarios->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_usuarios->addColumn("usuario", "STRING_TYPE", "POST", "usuario");
$ins_usuarios->addColumn("contrasena", "STRING_TYPE", "POST", "contrasena");
$ins_usuarios->setPrimaryKey("id_usuario", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_usuarios = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_usuarios);
// Register triggers
$upd_usuarios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_usuarios->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_usuarios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
$upd_usuarios->registerConditionalTrigger("{POST.contrasena} != {POST.re_contrasena}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_usuarios->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
// Add columns
$upd_usuarios->setTable("usuarios");
$upd_usuarios->addColumn("nombre", "STRING_TYPE", "POST", "nombre");
$upd_usuarios->addColumn("apellidos", "STRING_TYPE", "POST", "apellidos");
$upd_usuarios->addColumn("email", "STRING_TYPE", "POST", "email");
$upd_usuarios->addColumn("usuario", "STRING_TYPE", "POST", "usuario");
$upd_usuarios->addColumn("contrasena", "STRING_TYPE", "POST", "contrasena");
$upd_usuarios->setPrimaryKey("id_usuario", "NUMERIC_TYPE", "GET", "id_usuario");

// Make an instance of the transaction object
$del_usuarios = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_usuarios);
// Register triggers
$del_usuarios->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_usuarios->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_usuarios->setTable("usuarios");
$del_usuarios->setPrimaryKey("id_usuario", "NUMERIC_TYPE", "GET", "id_usuario");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsusuarios = $tNGs->getRecordset("usuarios");
$row_rsusuarios = mysql_fetch_assoc($rsusuarios);
$totalRows_rsusuarios = mysql_num_rows($rsusuarios);
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
if (@$_GET['id_usuario'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Usuarios </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rsusuarios > 1) {
?>
          <h2><?php echo NXT_getResource("Record_FH"); ?> <?php echo $cnt1; ?></h2>
          <?php } 
// endif Conditional region1
?>
        <table cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td class="KT_th"><label for="nombre_<?php echo $cnt1; ?>">Nombre:</label></td>
            <td><input type="text" name="nombre_<?php echo $cnt1; ?>" id="nombre_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsusuarios['nombre']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("nombre");?> <?php echo $tNGs->displayFieldError("usuarios", "nombre", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="apellidos_<?php echo $cnt1; ?>">Apellidos:</label></td>
            <td><input type="text" name="apellidos_<?php echo $cnt1; ?>" id="apellidos_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsusuarios['apellidos']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("apellidos");?> <?php echo $tNGs->displayFieldError("usuarios", "apellidos", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="email_<?php echo $cnt1; ?>">Email:</label></td>
            <td><input type="text" name="email_<?php echo $cnt1; ?>" id="email_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsusuarios['email']); ?>" size="32" maxlength="255" />
              <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("usuarios", "email", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="usuario_<?php echo $cnt1; ?>">Usuario:</label></td>
            <td><input type="text" name="usuario_<?php echo $cnt1; ?>" id="usuario_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rsusuarios['usuario']); ?>" size="32" maxlength="50" />
              <?php echo $tNGs->displayFieldHint("usuario");?> <?php echo $tNGs->displayFieldError("usuarios", "usuario", $cnt1); ?></td>
          </tr>
          <?php 
// Show IF Conditional show_old_contrasena_on_update_only 
if (@$_GET['id_usuario'] != "") {
?>
            <tr>
              <td class="KT_th"><label for="old_contrasena_<?php echo $cnt1; ?>">Old Contrasena:</label></td>
              <td><input type="password" name="old_contrasena_<?php echo $cnt1; ?>" id="old_contrasena_<?php echo $cnt1; ?>" value="" size="32" maxlength="50" />
                <?php echo $tNGs->displayFieldError("usuarios", "old_contrasena", $cnt1); ?></td>
            </tr>
            <?php } 
// endif Conditional show_old_contrasena_on_update_only
?>
          <tr>
            <td class="KT_th"><label for="contrasena_<?php echo $cnt1; ?>">Contrasena:</label></td>
            <td><input type="password" name="contrasena_<?php echo $cnt1; ?>" id="contrasena_<?php echo $cnt1; ?>" value="" size="32" maxlength="50" />
              <?php echo $tNGs->displayFieldHint("contrasena");?> <?php echo $tNGs->displayFieldError("usuarios", "contrasena", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="re_contrasena_<?php echo $cnt1; ?>">Re-type Contrasena:</label></td>
            <td><input type="password" name="re_contrasena_<?php echo $cnt1; ?>" id="re_contrasena_<?php echo $cnt1; ?>" value="" size="32" maxlength="50" /></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_usuarios_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rsusuarios['kt_pk_usuarios']); ?>" />
        <?php } while ($row_rsusuarios = mysql_fetch_assoc($rsusuarios)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_usuario'] == "") {
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