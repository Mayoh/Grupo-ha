<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Grupo Ha´</title>
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
                                    <input id="search" type="text" placeholder="Buscar">
                                    <input id="submit" type="submit" value="">
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
                                    <li><a href='#'>Inicio</a></li>
                                    <li><a href='#'>Servicios</a></li>
                                    <li class='active has-sub'><a href='#'>Enlaces comerciales</a>
                                        <ul>
                                            <li class='has-sub texto'><a href='#'>Clientes</a></li>
                                            <li class='has-sub texto'><a href='#'>Alianzas y convenios</a></li>
                                            <li class='has-sub texto'><a href='#'>Consultas de interés</a></li>
                                        </ul>
                                    </li>
                                    <li><a href='#'>Contacto</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                </div>

            </header><!--Aquñi termina header-->
            

            


            


            

            

            

            

            

            

            

            


            

            

            

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
