<?php require_once('../Connections/ha.php'); ?><?php
// Require the MXI classes
require_once ('../includes/mxi/MXI.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_ha = new KT_connection($ha, $database_ha);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_ha, "../");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page

// Include Multiple Static Pages
$mxiObj = new MXI_Includes("mod");
$mxiObj->IncludeStatic("usuarios", "usuarios.php", "", "", "");
$mxiObj->IncludeStatic("usuarios_detalle", "usuarios_detalle.php", "", "", "");
$mxiObj->IncludeStatic("banners", "banners.php", "", "", "");
$mxiObj->IncludeStatic("banners_detalle", "banners_detalle.php", "", "", "");
$mxiObj->IncludeStatic("categorias", "categorias.php", "", "", "");
$mxiObj->IncludeStatic("categorias_detalle", "categorias_detalle.php", "", "", "");
$mxiObj->IncludeStatic("subcategorias", "subcategorias.php", "", "", "");
$mxiObj->IncludeStatic("subcategorias_detalle", "subcategorias_detalle.php", "", "", "");
$mxiObj->IncludeStatic("servicios", "servicios.php", "", "", "");
$mxiObj->IncludeStatic("servicios_detalle", "servicios_detalle.php", "", "", "");
$mxiObj->IncludeStatic("paginas", "paginas.php", "", "", "");
$mxiObj->IncludeStatic("paginas_detalle", "paginas_detalle.php", "", "", "");
// End Include Multiple Static Pages
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $mxiObj->getTitle(); ?></title>
<link rel="stylesheet" href="css/style.css">
<meta name="keywords" content="<?php echo $mxiObj->getKeywords(); ?>" />
<meta name="description" content="<?php echo $mxiObj->getDescription(); ?>" />
<base href="<?php echo mxi_getBaseURL(); ?>" />
</head>

<body>
<img src="../imgs/logotipo-grupoha.png" style="float:left; margin: 10px 20px;"/>
<div class="admontitle" style="float: right; margin: 40px 20px;">Administrador</div>
<div style="clear:both;"></div>
<div class="contenedor">
    <header>
        <nav class="animenu">
        	<button class="animenu__toggle">
			    <span class="animenu__toggle__bar"></span>
			    <span class="animenu__toggle__bar"></span>
			    <span class="animenu__toggle__bar"></span>
		  </button>
       	  <ul class="animenu__nav">
       		<li><a href="index.php?mod=inicio">Inicio</a></li>
			<li><a href="index.php?mod=usuarios">Usuarios</a></li>
            <li><a href="index.php?mod=banners">Banners</a></li>
            <li><a href="#">Categorías</a>
	            <ul class="animenu__nav__child">
					<li><a href="index.php?mod=categorias">Categorías</a></li>
		            <li><a href="index.php?mod=subcategorias">Subcategorías</a></li>
				</ul>
            </li>
            <li><a href="index.php?mod=servicios">Servicios</a></li>
            <li><a href="index.php?mod=paginas">Páginas</a></li>
            <!--<li><a href="#">Información financiera</a>
	            <ul class="animenu__nav__child">
					<li><a href="index.php?mod=info_trimestral">Info. Trimestral</a></li>
		            <li><a href="index.php?mod=info_anual">Info. Anual</a></li>
				</ul>
            </li>
            <li><a href="#">Información bursátil</a>
	            <ul class="animenu__nav__child">
					<li><a href="index.php?mod=prospectos">Prospectos de colocación</a></li>
                    <li><a href="index.php?mod=eventos_relevantes">Eventos relevantes</a></li>
				</ul>
            </li>
            <li><a href="#">Gobierno corporativo</a>
            <ul class="animenu__nav__child">
				<li><a href="index.php?mod=estatutos_sociales">Estatutos sociales</a></li>
	             <li><a href="index.php?mod=codigo_etica">Código de ética</a></li>
	             <li><a href="index.php?mod=mejores_practicas">Mejores prácticas</a></li>
			</ul>
             </li>
             <li><a href="#">Noticias corporativas</a>
             <ul class="animenu__nav__child">
                <li><a href="index.php?mod=cat_calendario_eventos">Categorías calendario de eventos</a></li>
                <li><a href="index.php?mod=calendario_eventos">Calendario de eventos</a></li>
                <li><a href="index.php?mod=sala_prensa">Sala de prensa</a></li>
			</ul>
             </li>-->
            <li><a href="salir.php">Salir</a></li>
       	  </ul>
        </nav>
    </header>
<div class="contenido">
  <?php
  $incFileName = $mxiObj->getCurrentInclude();
  if ($incFileName !== null)  {
    mxi_includes_start($incFileName);
    require(basename($incFileName)); // require the page content
    mxi_includes_end();
}
?>
</div>
</div>
</body>
</html>
