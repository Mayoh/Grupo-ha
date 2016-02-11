<?php require_once('../Connections/ha.php'); ?>
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
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
$formValidation->addField("id_cat", true, "numeric", "", "", "", "");
$formValidation->addField("pag", true, "text", "", "", "", "");
$formValidation->addField("contenido", true, "text", "", "", "", "");
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
$query_cat = "SELECT * FROM categorias ORDER BY categoria ASC";
$cat = mysql_query($query_cat, $ha) or die(mysql_error());
$row_cat = mysql_fetch_assoc($cat);
$totalRows_cat = mysql_num_rows($cat);

mysql_select_db($database_ha, $ha);
$query_subcat = "SELECT * FROM subcategorias ORDER BY subcat ASC";
$subcat = mysql_query($query_subcat, $ha) or die(mysql_error());
$row_subcat = mysql_fetch_assoc($subcat);
$totalRows_subcat = mysql_num_rows($subcat);

// Make an insert transaction instance
$ins_paginas = new tNG_multipleInsert($conn_ha);
$tNGs->addTransaction($ins_paginas);
// Register triggers
$ins_paginas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_paginas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_paginas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$ins_paginas->setTable("paginas");
$ins_paginas->addColumn("id_cat", "NUMERIC_TYPE", "POST", "id_cat");
$ins_paginas->addColumn("id_subcat", "NUMERIC_TYPE", "POST", "id_subcat");
$ins_paginas->addColumn("pag", "STRING_TYPE", "POST", "pag");
$ins_paginas->addColumn("contenido", "STRING_TYPE", "POST", "contenido");
$ins_paginas->setPrimaryKey("id_pag", "NUMERIC_TYPE");

// Make an update transaction instance
$upd_paginas = new tNG_multipleUpdate($conn_ha);
$tNGs->addTransaction($upd_paginas);
// Register triggers
$upd_paginas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_paginas->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_paginas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$upd_paginas->setTable("paginas");
$upd_paginas->addColumn("id_cat", "NUMERIC_TYPE", "POST", "id_cat");
$upd_paginas->addColumn("id_subcat", "NUMERIC_TYPE", "POST", "id_subcat");
$upd_paginas->addColumn("pag", "STRING_TYPE", "POST", "pag");
$upd_paginas->addColumn("contenido", "STRING_TYPE", "POST", "contenido");
$upd_paginas->setPrimaryKey("id_pag", "NUMERIC_TYPE", "GET", "id_pag");

// Make an instance of the transaction object
$del_paginas = new tNG_multipleDelete($conn_ha);
$tNGs->addTransaction($del_paginas);
// Register triggers
$del_paginas->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Delete1");
$del_paginas->registerTrigger("END", "Trigger_Default_Redirect", 99, "../includes/nxt/back.php");
// Add columns
$del_paginas->setTable("paginas");
$del_paginas->setPrimaryKey("id_pag", "NUMERIC_TYPE", "GET", "id_pag");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rspaginas = $tNGs->getRecordset("paginas");
$row_rspaginas = mysql_fetch_assoc($rspaginas);
$totalRows_rspaginas = mysql_num_rows($rspaginas);
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
if (@$_GET['id_pag'] == "") {
?>
      <?php echo NXT_getResource("Insert_FH"); ?>
      <?php 
// else Conditional region1
} else { ?>
      <?php echo NXT_getResource("Update_FH"); ?>
      <?php } 
// endif Conditional region1
?>
    Paginas </h1>
  <div class="KT_tngform">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <?php $cnt1 = 0; ?>
      <?php do { ?>
        <?php $cnt1++; ?>
        <?php 
// Show IF Conditional region1 
if (@$totalRows_rspaginas > 1) {
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
              <option value="<?php echo $row_cat['id_cat']?>"<?php if (!(strcmp($row_cat['id_cat'], $row_rspaginas['id_cat']))) {echo "SELECTED";} ?>><?php echo $row_cat['categoria']?></option>
              <?php
} while ($row_cat = mysql_fetch_assoc($cat));
  $rows = mysql_num_rows($cat);
  if($rows > 0) {
      mysql_data_seek($cat, 0);
	  $row_cat = mysql_fetch_assoc($cat);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("paginas", "id_cat", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="id_subcat_<?php echo $cnt1; ?>">Subcategoría:</label></td>
            <td><select name="id_subcat_<?php echo $cnt1; ?>" id="id_subcat_<?php echo $cnt1; ?>">
              <option value=""><?php echo NXT_getResource("Select one..."); ?></option>
              <?php 
do {  
?>
              <option value="<?php echo $row_subcat['id_subcat']?>"<?php if (!(strcmp($row_subcat['id_subcat'], $row_rspaginas['id_subcat']))) {echo "SELECTED";} ?>><?php echo $row_subcat['subcat']?></option>
              <?php
} while ($row_subcat = mysql_fetch_assoc($subcat));
  $rows = mysql_num_rows($subcat);
  if($rows > 0) {
      mysql_data_seek($subcat, 0);
	  $row_subcat = mysql_fetch_assoc($subcat);
  }
?>
            </select>
              <?php echo $tNGs->displayFieldError("paginas", "id_subcat", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="pag_<?php echo $cnt1; ?>">Página:</label></td>
            <td><input type="text" name="pag_<?php echo $cnt1; ?>" id="pag_<?php echo $cnt1; ?>" value="<?php echo KT_escapeAttribute($row_rspaginas['pag']); ?>" size="32" maxlength="20" />
              <?php echo $tNGs->displayFieldHint("pag");?> <?php echo $tNGs->displayFieldError("paginas", "pag", $cnt1); ?></td>
          </tr>
          <tr>
            <td class="KT_th"><label for="contenido_<?php echo $cnt1; ?>">Contenido:</label></td>
            <td><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = "".$row_rspaginas['contenido']  ."";
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "ha";
$CKEditor_config["wa_preset_file"] = "ha.xml";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "200px";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["filebrowserBrowseUrl"] = "../webassist/kfm/index.php?uicolor=".urlencode(isset($CKEditor_config["uiColor"])?str_replace("#","#",$CKEditor_config["uiColor"]):"#eee")."&theme=webassist_v2";
$CKEditor_config["toolbar"] = array(
array( 'Source','-','Save','NewPage','Preview','-','Templates'),
array( 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Print','SpellChecker','Scayt'),
array( 'Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
array( 'Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'),
array( 'BidiLtr','BidiRtl'),
array( 'Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
array( 'NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
array( 'Link','Unlink','Anchor'),
array( 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'),
('/'),
array( 'Styles','Format','Font','FontSize'),
array( 'TextColor','BGColor'),
array( 'Maximize','ShowBlocks','-','About'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor_config["contentsCss"] = array("../css/style-grupoha.css");
$CKEditor->editor("contenido_".$cnt1  ."", $CKEditor_initialValue, $CKEditor_config);
?>
<?php echo $tNGs->displayFieldHint("contenido");?> <?php echo $tNGs->displayFieldError("paginas", "contenido", $cnt1); ?></td>
          </tr>
        </table>
        <input type="hidden" name="kt_pk_paginas_<?php echo $cnt1; ?>" class="id_field" value="<?php echo KT_escapeAttribute($row_rspaginas['kt_pk_paginas']); ?>" />
        <?php } while ($row_rspaginas = mysql_fetch_assoc($rspaginas)); ?>
      <div class="KT_bottombuttons">
        <div>
          <?php 
      // Show IF Conditional region1
      if (@$_GET['id_pag'] == "") {
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
mysql_free_result($cat);

mysql_free_result($subcat);
?>
