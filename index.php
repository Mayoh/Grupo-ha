<?php require_once('Connections/ha.php'); ?>
<?php
// Require the MXI classes
require_once ('includes/mxi/MXI.php');

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

// Include Multiple Dynamic Pages
$mxiObj = new MXI_Includes("mod");
$mxiObj->IncludeDynamic($conn_ha, "paginas", "pag", "base", "", "", "");

mysql_select_db($database_ha, $ha);
$query_menu = "SELECT * FROM categorias";
$menu = mysql_query($query_menu, $ha) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php if($_GET[mod]=='') {?>
        <meta http-equiv="refresh" content="0;URL=index.php?mod=inicio">
        <?php } ?>
        <title>Grupo Ha´</title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
        <meta name="description" content="Ofrece un portafolio de servicios especializados en temas del agua para tomar mejores decisiones, basadas en el conocimiento científico para enfrentar la problemática actual del agua en nuestro país." />
        <meta name="keywords" content="grupoha, agua, servicios especializados en agua, conocimientos científicos, grupo especialistas,modelo de geobases," />
        <meta name="author" content="Plastik 2016" />
        <link type="text/css" rel="stylesheet" href="css/style-grupoha.css"/>
        <link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
        <link href='https://fonts.googleapis.com/css?family=Federo' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Cambay:400,700' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="js/script.js"></script>
        <script src="js/modernizr.custom.js"></script>
        <script src="js/ninja-slider.js"></script>
        <base href="<?php echo mxi_getBaseURL(); ?>" />
    </head>

    <body>

        <div class="container body-ha">

            <header><!--Aquí inicia header-->
                <div id="top-head">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div class="col-md-5">
                                <img id="logo-head" src="imgs/head-img.png"/>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5">

                                <ul id= "iconos2" class="rs-head">
                                    <li id="facebook"><a href="https://www.facebook.com/grupoha.stillman" title="Síguenos en Facebook"></a></li>
                                    <li id="twitter"><a href="https://twitter.com/GrupoHa" title="Síguenos en Twitter"></a> </li>
                                </ul>

                                <form action="" id="searchbox">
                                    <input id="search" type="text" placeholder="Buscar" name="search">
                                    <input type="hidden" name="mod" value="busca">
                                    <button id="submit" type="submit" method="get" action="index.php"></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>  
                <br/>
                <br/>

                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10 bloques menu-head">

                        <div class="col-md-12">
                            <div id='cssmenu'>
                                <ul>
                                    <!--Menu-->
                                    <?php do { 
    $cat=$row_menu['id_cat'];
    mysql_select_db($database_ha, $ha);
    $query_subcategorias = "SELECT * FROM subcategorias WHERE id_cat = $cat AND id_cat != 2";
    $subcategorias = mysql_query($query_subcategorias, $ha) or die(mysql_error());
    $row_subcategorias = mysql_fetch_assoc($subcategorias);
    $totalRows_subcategorias = mysql_num_rows($subcategorias);

    mysql_select_db($database_ha, $ha);
    $query_ligas = "SELECT DISTINCT paginas.pag FROM paginas";
    $ligas = mysql_query($query_ligas, $ha) or die(mysql_error());
    $row_ligas = mysql_fetch_assoc($ligas);
    $totalRows_ligas = mysql_num_rows($ligas);
                                    ?>
                                    <?php if($totalRows_subcategorias == 0) { ?> 
                                    <li><a href="index.php?mod=<?php echo $row_menu['liga_cat']; ?>"><?php echo $row_menu['categoria']; ?></a></li>
                                    <?php } else { ?>
                                    <li class="has-sub"><a href="#"><?php echo $row_menu['categoria']; ?></a>
                                        <ul>
                                            <?php do { ?>
                                            <li class="has-sub"><a href="index.php?mod=<?php echo $row_subcategorias['liga_subcat']; ?>"><?php echo $row_subcategorias['subcat']; ?></a></li>
                                            <?php } while ($row_subcategorias = mysql_fetch_assoc($subcategorias)); ?>
                                        </ul>
                                    </li>
                                    <?php }?>
                                    <?php } while ($row_menu = mysql_fetch_assoc($menu)); ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                </div>

            </header><!--Aquñi termina header-->
            <!--Contenido-->
            <!--Banner-->
            <?php
            if($_GET['mod']=="inicio") { 
                mxi_includes_start("banners.php");
                require(basename("banners.php"));
                mxi_includes_end();
            }
            ?>
            <?php
            $incFileName = $mxiObj->getCurrentInclude();
            if ($incFileName !== null)  {
                mxi_includes_start($incFileName);
                require(basename($incFileName)); // require the page content
                mxi_includes_end();
            }
            ?>
            <!--Fin contenido-->
        </div>

        <script>
            //  The function to change the class
            var changeClass = function (r,className1,className2) {
                var regex = new RegExp("(?:^|\\s+)" + className1 + "(?:\\s+|$)");
                if( regex.test(r.className) ) {
                    r.className = r.className.replace(regex,' '+className2+' ');
                }
                else{
                    r.className = r.className.replace(new RegExp("(?:^|\\s+)" + className2 + "(?:\\s+|$)"),' '+className1+' ');
                }
                return r.className;
            };	

            //  Creating our button in JS for smaller screens
            var menuElements = document.getElementById('menu');
            menuElements.insertAdjacentHTML('afterBegin','<button type="button" id="menutoggle" class="navtoogle" aria-hidden="true"><i aria-hidden="true" class="icon-menu"> </i> Menú</button>');

            //  Toggle the class on click to show / hide the menu
            document.getElementById('menutoggle').onclick = function() {
                changeClass(this, 'navtoogle active', 'navtoogle');
            }

            // http://tympanus.net/codrops/2013/05/08/responsive-retina-ready-menu/comment-page-2/#comment-438918
            document.onclick = function(e) {
                var mobileButton = document.getElementById('menutoggle'),
                    buttonStyle =  mobileButton.currentStyle ? mobileButton.currentStyle.display : getComputedStyle(mobileButton, null).display;

                if(buttonStyle === 'block' && e.target !== mobileButton && new RegExp(' ' + 'active' + ' ').test(' ' + mobileButton.className + ' ')) {
                    changeClass(mobileButton, 'navtoogle active', 'navtoogle');
                }
            }
        </script>

    </body>
</html>
<?php
mysql_free_result($menu);

mysql_free_result($subcategorias);

mysql_free_result($ligas);
?>
