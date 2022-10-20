<?php

require_once("functions.php");

class template extends main{

    private $objForm = false;

    function __construct() {

    }

    public function run_ajax() {
        if( !empty($_REQUEST["drawMenu"]) ) {
            header("Content-Type: text/html; charset=iso-8859-1");
            $strBusqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
            $this->template_menu(true,false,0,0,$strBusqueda);
            exit();
        }
    }

    public function template_header($strTitle) {

        global $lang;
        $this->run_ajax();

        if( empty($strTitle) ) $strTitle = $lang["core"]["title"];
        $boolIsLogin = core_is_login();
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="ISO-8859-1">
                <title>ADGECO- <?php print $strTitle; ?></title>
                <link rel="shortcut icon" href="templates/idc/images/icon.png"/>
                <!-- Tell the browser to be responsive to screen width -->
                <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
                <!-- Bootstrap 3.3.4 -->
                <link href="templates/idc/libraries/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
                <!-- Font Awesome Icons -->
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                <!-- Ionicons -->
                <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
                <!-- Theme style -->
                <link href="templates/idc/libraries/AdminLTE.css" rel="stylesheet" type="text/css" />
                <link href="templates/idc/libraries/plugins/bootstrap-treeview/bootstrap-treeview.css" rel="stylesheet" type="text/css" />
                <link href="templates/idc/libraries/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
                <!-- DATA TABLES -->
                <link href="templates/idc/libraries/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
                <!-- AdminLTE Skins. Choose a skin from the css/skins
                folder instead of downloading all of them to reduce the load. -->
                <link href="templates/idc/libraries/skins/_all-skins.css" rel="stylesheet" type="text/css" />

                <link href="libraries/jquery/ui/jquery.ui.min.css" rel="stylesheet">
                <link href="libraries/jquery/ui/jquery.ui.multiselect.min.css" rel="stylesheet">
                <link href="libraries/jquery/ui/jquery.ui.combobox.min.css" rel="stylesheet">

                <link href="templates/idc/libraries/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
                <link href="templates/idc/libraries/plugins/pnotify/pnotify.min.css" rel="stylesheet" type="text/css" />

                <link href="templates/idc/styles.css" rel="stylesheet" type="text/css" />
                <link href="templates/idc/idc_font.min.css" rel="stylesheet" type="text/css" />

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
 
               <!-- jQuery 2.1.4 -->
                <script src="templates/idc/libraries/jQuery-2.1.4.min.js" type="text/javascript"></script>
                <!-- Bootstrap 3.3.2 JS -->
                <script src="templates/idc/libraries/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/chartjs/Chart.js" type="text/javascript"></script>

                <script src="templates/idc/libraries/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/input-mask/jquery.inputmask.regex.extensions.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/moment/moment.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/moment/locale/es.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/pnotify/pnotify.min.js" type="text/javascript"></script>


                <!-- SlimScroll -->
                <script src="templates/idc/libraries/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
                <!-- FastClick -->
                <script src="templates/idc/libraries/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
                <!-- AdminLTE App -->
                <script src="templates/idc/libraries/app.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/bootstrap-treeview/bootstrap-treeview.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
                <!-- DATA TABES SCRIPT -->
                <script src="templates/idc/libraries/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
                <script src="templates/idc/libraries/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

                <script src="libraries/jquery/ui/jquery.ui.min.js" type="text/javascript"></script>
                <script src="libraries/jquery/ui/jquery.ui.multiselect.min.js" type="text/javascript"></script>
                <script src="libraries/jquery/ui/jquery.ui.combobox.min.js" type="text/javascript"></script>

                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
                

                <!-- AdminLTE for demo purposes -->
                <script src="templates/idc/libraries/demo.js" type="text/javascript"></script>
                <script src="core/core.js" type="text/javascript"></script>
            </head>
            <body class="skin-blue sidebar-mini fixed">
                <!-- Site wrapper -->
                <div class="wrapper">
                    <?php
                    if( $boolIsLogin ) {
                        ?>
                        <header class="main-header">
                            <!-- Logo -->
                            <a href="index.php" class="logo">
                                <span class="logo-mini" style="padding-left: 5px; padding-top: 16px;">ADGECO</span>
                                <span class="logo-lg">ADGECO</span>
                            </a>
                            <nav class="navbar navbar-static-top" role="navigation" style="z-index:1000">                             
                                <div class="navbar-custom-menu">
                                    <ul class="nav navbar-nav">
                                        <!-- User Account: style can be found in dropdown.less -->
                                        <li class="dropdown user user-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <?php
                                                if( !empty($_SESSION["hml"]["imagen_persona"]) && file_exists($_SESSION["hml"]["imagen_persona"]) ){
                                                    ?>
                                                    <img src="<?php print $_SESSION["hml"]["imagen_persona"]; ?>" class="user-image" alt="User Image" />
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <img src="templates/idc/images/user.png" class="user-image" alt="User Image" />
                                                    <?php
                                                }
                                                ?>
                                                <span class="hidden-xs"><?php print $_SESSION["hml"]["nombre"]; ?></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <!-- User image -->
                                                <li class="user-header">
                                                    <?php
                                                    if( !empty($_SESSION["hml"]["imagen_persona"]) && file_exists($_SESSION["hml"]["imagen_persona"]) ){
                                                        ?>
                                                        <img src="<?php print $_SESSION["hml"]["imagen_persona"]; ?>" class="img-circle" alt="User Image" />
                                                        <?php
                                                    }
                                                    else {
                                                        ?>
                                                        <img src="templates/idc/images/user.png" class="img-circle" alt="User Image" />
                                                        <?php
                                                    }
                                                    ?>
                                                    <p>
                                                        <?php print $_SESSION["hml"]["nombre"]; ?>
                                                        <?php print ( $_SESSION["hml"]["tipo_usuario"] == "admin" ) ? "<small>(Administrador)</small>" : ""; ?>
                                                    </p>
                                                </li>
                                                <!-- Menu Footer-->
                                                <li class="user-footer">
                                                    <div class="pull-left">
                                                        <a href="usuarios_mi_cuenta.php" class="btn btn-default btn-flat"><?php print $lang['core']['account']; ?></a>
                                                    </div>
                                                    <div class="pull-right">
                                                        <a href="index.php?act=logout" class="btn btn-default btn-flat"><?php print $lang['core']['logout']; ?></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <!-- Control Sidebar Toggle Button -->
                                        <!--li>
                                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                                        </li-->
                                    </ul>
                                </div>
                            </nav>
                        <?php
                    }
    }

    public function template_include_libreria($strNombre,$strTipo = ".min") {
        ?>
        <link href="templates/idc/libraries/plugins/<?php print $strNombre.$strTipo; ?>.css" rel="stylesheet" type="text/css" />
        <script src="templates/idc/libraries/plugins/<?php print $strNombre.$strTipo; ?>.js" type="text/javascript"></script>
        <?php
    }

    public function template_button_open( $boolEmpty = false ){
        if( $boolEmpty ){
            ?>
            <nav class="navbar navbar-static-top" role="navigation" style="min-height: 30px; z-index:1;">
                <div id="divButtonbar" class="buttonbar" role="navigation" style=" z-index:1;">
                    <div class="navbar-right" style="margin-right: 0px; z-index:1;">
            <?php
        }
        else {
            ?>
            <nav class="navbar navbar-static-top" role="navigation" style="min-height: 30px; z-index:1;">
                <div id="divButtonbarTools" class="buttonbarherramientas" role="navigation" style="z-index:1;">
                    <div class="navbar-right" style="margin-right: 0px; z-index:1;">
                        <?php
                        $this->draw_button("btnButtonbarTools", "Opciones", "","list","sm");
                        ?>
                    </div>
                </div>
                <script>
                    $(function(){
                        $("#btnButtonbarTools").bind( "click", function() {
                            $("#divButtonbar").toggle();
                            if( $("#divMenu").hasClass("collapse in") ){
                                $("#divMenu").collapse("hide");
                            }
                        }).css("margin-bottom", "0");
                    });
                </script>
                <div id="divButtonbar" class="buttonbar" role="navigation">
                    <div class="navbar-right" style="margin-right: 0px;z-index:1;">
            <?php
        }
    }

    public function template_button_close() {
        ?>
                    </div>
                </div>
            </nav>
        </header>
        <!-- =============================================== -->
        <?php
    }

    public function template_sidebar_open($boolDrawPrivateMenu,$boolDrawPublicMenu,$intModulo=0,$intAcceso=0) {
       $strBusqueda = "";
        ?>
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <?php
                        if( !empty($_SESSION["hml"]["imagen_persona"]) && file_exists($_SESSION["hml"]["imagen_persona"]) ){
                            ?>
                            <img src="<?php print $_SESSION["hml"]["imagen_persona"]; ?>" class="img-circle" alt="User Image" />
                            <?php
                        }
                        else {
                            ?>
                            <img src="templates/idc/images/user.png" class="img-circle" alt="User Image" />
                            <?php
                        }
                        ?>
                    </div>
                    <div class="pull-left info">
                        <p style="text-overflow: ellipsis;">
                            <?php print $_SESSION["hml"]["nombre"]; ?>
                        </p>
                        <?php print ( $_SESSION["hml"]["tipo_usuario"] == "admin" ) ? '<a href="#">(Administrador)</a>' : ""; ?>
                    </div>
                </div>
                <!-- search form -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="txtMenuBusqueda" id="txtMenuBusqueda" class="form-control" value="<?php print $strBusqueda; ?>" placeholder="Buscar..." />
                        <span class="input-group-btn">
                            <button type="button" id="btnMenuBusqueda" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <!-- /.search form -->
                <div id="divMenu">
                    <?php
                    $this->template_menu($boolDrawPrivateMenu,$boolDrawPublicMenu,$intModulo,$intAcceso);
                    ?>
                </div>
                <script>
                    $(function(){
                        $("#btnMenuBusqueda").click(function() {
                            drawMenu('index.php',$("#txtMenuBusqueda").val().trim());
                        });
                        $(function() {
                            $("#txtMenuBusqueda").select();
                        });
                    });
                </script>
                <?php

    }

    public function template_sidebar_close() {
                ?>
            </section>
            <!-- /.sidebar -->
        </aside>
        <?php
    }

    public function template_content_open($boolBreadCrumb=true) {
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!--section class="content-header">
                <h1>
                    Blank page
                    <small>it all starts here</small>
                </h1>
                <?php
                if( $boolBreadCrumb ) {
                    ?>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Examples</a></li>
                        <li class="active">Blank page</li>
                    </ol>
                    <?php
                }
                ?>
            </section-->

            <!-- Main content -->
            <section class="content">

                <?php
                    //$this->draw_alert("danger","Error","Mensaje", true);
                ?>

                <!-- Default box -->
                <!--div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Title</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!--div class="box-body">
                        Start creating your amazing application!


                    </div><!-- /.box-body -->
                    <!--div class="box-footer">
                        Footer
                    </div><!-- /.box-footer-->
                    <!--div class="overlay ocultar">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->

                <?php
                //$arrMenu = core_getInfoMenu(true,false,0,"", 1);
                //drawDebug($arrMenu);
                ?>

        <?php
    }

    public function template_content_close() {
        ?>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        <?php
    }

    public function template_footer() {
                    $this->draw_icon_fa("imgCoreLoading","spinner","",false,"5x","ocultar",false,false,false,true);
                    ?>
                    <!--<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation" style="margin-bottom: 0; border-top: 2px solid transparent;">
                        <div class="row" >
                            <div id="divAlert" class="col-lg-6 col-lg-offset-3"></div>
                        </div>
                    </nav>-->
                </div>
                <!-- ./wrapper -->
                <script>
                    $(function(){
                        fntCentrarLoading();
                    });
                    $( window ).resize(function() {
                        fntCentrarLoading();
                    });
                </script>
            </body>
        </html>
        <?php
        db_close();
    }

    public function template_menu($boolDrawPrivateMenu,$boolDrawPublicMenu,$intModulo=0,$intAcceso=0,$strBusqueda="") {
        ?>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <li class="treeview">
                <a href="index.php">
                    <i class="icon-idc-home153"></i> <span><?php print "&nbsp;Inicio"; ?></span>
                </a>
            </li>
            <?php
            $arrMenu = core_getInfoMenu($boolDrawPrivateMenu,$boolDrawPublicMenu,0,$strBusqueda, $intAcceso);
            reset($arrMenu);
            while( $arrModulo = each($arrMenu) ) {
                $strModuloActivo = ($arrModulo["key"] == $intModulo) ? "active" : "";
                ?>
                <li class="treeview <?php print $strModuloActivo; ?>">
                    <a href="#">
                        <i class="<?php print $arrModulo["value"]["icono"]; ?>"></i> <span>&nbsp;<?php print $arrModulo["value"]["nombre"]; ?></span>
                        <i class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                        if( isset($arrModulo["value"]["acceso"]) ) {
                            reset($arrModulo["value"]["acceso"]);
                            while( $arrA = each($arrModulo["value"]["acceso"]) ) {
                                $strAccesoActivo = ($arrA["key"] == $intAcceso ) ? "active": "";
                                ?>
                                <li class="<?php print $strAccesoActivo; ?>"><a href="<?php print $arrA["value"]["path"]; ?>"><i class="<?php print $arrA["value"]["icono"]; ?>"></i>&nbsp;<?php print $arrA["value"]["nombre"]; ?></a></li>
                                <?php
                            }
                        }
                        if( isset($arrModulo["value"]["contenido"]) ) {
                            reset($arrModulo["value"]["contenido"]);
                            while( $arrContenido = each($arrModulo["value"]["contenido"]) ){
                                $strContenidoActivo = isset($arrContenido["value"]["accesos"][$intAcceso]) ? "active" : "";
                                ?>
                                <li class="<?php print $strContenidoActivo; ?>">
                                    <a href="#"><i class="<?php print $arrContenido["value"]["icono"]; ?>"></i> <?php print $arrContenido["value"]["nombre"]; ?> <i class="fa fa-angle-right pull-right"></i></a>
                                    <ul class="treeview-menu">
                                        <?php
                                        reset($arrContenido["value"]["accesos"]);
                                        while( $arrAcceso = each($arrContenido["value"]["accesos"]) ) {
                                            $strAccesoActivo = ($arrAcceso["key"] == $intAcceso ) ? "active": "";
                                            ?>
                                            <li class="<?php print $strAccesoActivo; ?>"><a href="<?php print $arrAcceso["value"]["path"]; ?>"><i class="<?php print $arrAcceso["value"]["icono"]; ?>"></i> <?php print $arrAcceso["value"]["nombre"]; ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </li>
                <?php

            }
            ?>
        </ul>
        <?php
    }

    /**
    * Metodo que dibuja el breadcrumb
    *
    * @param array Datos para dibujar el breadcrumb
    */
    public function draw_breadcrumb($arrBreadCrumb) {

        if( is_array($arrBreadCrumb) && count($arrBreadCrumb) ) {

            ?>
            <script>
                strScript = '<ol class="breadcrumb break-text">';
                <?php
                $intContador = 1;
                reset($arrBreadCrumb);
                while( $arrTMP = each($arrBreadCrumb) ) {

                    if( !empty($arrTMP['value']['nombre']) && !empty($arrTMP['value']['link']) ) {
                        ?>
                        strScript += '<li <?php print ( $intContador == count($arrBreadCrumb) ) ? 'class="active"' : ''; ?>><a href="<?php print $arrTMP['value']['link']; ?>"><?php print $arrTMP['value']['nombre']; ?></a></li>';
                        <?php
                    }
                    elseif( !empty($arrTMP['value']['nombre']) && empty($arrTMP['value']['link']) ) {
                        ?>
                        strScript += '<li <?php print ( $intContador == count($arrBreadCrumb) ) ? 'class="active"' : ''; ?>><?php print $arrTMP['value']['nombre']; ?></li>';
                        <?php
                    }
                    $intContador++;
                }
                ?>
                strScript += '</ol>';
                $("#divBreadCrumb").html(strScript);
            </script>
            <?php

        }

    }

    public function public_index() {

    }

    public function private_index() {

        global $lang, $boolYaConectado, $strMensajeError;
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="ISO-8859-1">
                <title><?php print $lang["core"]["title"]; ?></title>
                <link rel="shortcut icon" href="templates/idc/images/icon.png"/>
                <!-- Tell the browser to be responsive to screen width -->
                <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
                <!-- Bootstrap 3.3.4 -->
                <link href="templates/idc/libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <!-- Font Awesome Icons -->
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                <!-- Theme style -->
                <link href="templates/idc/libraries/AdminLTE.css" rel="stylesheet" type="text/css" />

                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->

                <!-- jQuery 2.1.4 -->
                <script src="templates/idc/libraries/jQuery-2.1.4.min.js" type="text/javascript"></script>
                <!-- Bootstrap 3.3.2 JS -->
                <script src="templates/idc/libraries/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

            </head>
            <body class="login-page">
                <div class="login-box">
                    <div class="login-logo">
                        <a href="index.php"><img src="templates/idc/images/logo_login.png" alt="<?php print $lang["core"]["site_name"]; ?>" class="img-responsive center-block"></a>
                    </div>
                    <div class="login-box-body">
                        <?php
                        $objForm = new form("form-login","form-login","POST","index.php","","");
                        $objForm->form_setExtraTag("role","form");
                        $objForm->form_openForm();
                        ?>

                            <div class="form-group">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    <?php
                                    $strUserName = isset($_POST["login_name"]) ? $_POST["login_name"] : "";
                                    if(isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true"){
                                        $strUserName = $_SESSION["hml"]["usuario"];
                                    }
                                    $objForm->add_input_text("login_name",$strUserName,"",true,$lang["core"]["user_name"],"",false,false);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-lock"></span>
                                    </span>
                                    <?php
                                    $objForm->add_input_password("login_passwd","",true,$lang["core"]["password"],"",false,false);
                                    ?>
                                </div>
                            </div>
                            <?php
                            if( false ) {
                                ?>
                                <div class="checkbox">
                                    <label>
                                        <?php
                                        $objForm->add_input_checkbox("login_auto",false,"",false,false,false,false);
                                        $objForm->add_input_extraTag("login_auto","value","1");
                                        $objForm->draw_input_checkbox("login_auto");
                                        print "No cerrar sesión";
                                        ?>
                                    </label>
                                </div>
                                <?php
                            }
                            if( isset($boolYaConectado) && $boolYaConectado ) {
                                ?>
                                <div class="checkbox">
                                    <label>
                                        <?php
                                        $objForm->add_input_checkbox("force_disconnect",false,"",true,false,false,false);
                                        echo $lang["core"]["core_marque_aqui_para_desconectar"];
                                        ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="form-group">
                                <br>
                                <button id="btnLogin" class="btn btn-primary btn-block" tabindex="1">
                                    <?php print $lang["core"]["login"]; ?>
                                </button>
                            </div>
                            <div class="form-group text-center">
                                <a href="reset_password.php?act=1">Olvide mi password</a>
                            </div>

                        <?php
                        $objForm->form_closeForm();
                        if( (isset($_POST["login_name"]) && isset($_POST["login_passwd"]) && !core_is_login()) ) {
                            ?>
                            <div class="row">
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <span class="glyphicon glyphicon-info-sign"></span>&nbsp;
                                        <?php
                                        print empty($strMensajeError) ? $lang["core"]["invalid_password"] : $strMensajeError;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if( (isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true") ) {
                            $strMensajeError = $lang["core"]["mensaje_sesion_expirada"];
                            ?>
                            <div class="row">
                                <div class="col-lg-12">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <span class="glyphicon glyphicon-info-sign"></span>&nbsp;
                                        <?php
                                        print empty($strMensajeError) ? "" : $strMensajeError;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            unset($_SESSION["hml"]["sesion_expirada"]);
                        }
                        ?>
                    </div>
                </div>
            </body>
        </html>
        <?php
    }

    public function private_index1() {

        global $lang, $boolYaConectado, $strMensajeError;

        $this->template_header($lang["core"]["title"],false,false,false,false);
        $objForm = new form("form-login","form-login","POST","index.php","","form-horizontal");
        $objForm->form_setExtraTag("role","form");
        ?>
        <style>
            body {
                background-color: #dfe0e6;
                margin-top: 0px;
            }
        </style>
        <div class="row">
            <div class="row">
                <div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                    <div style="margin-left: 25%; margin-right: 0%;">
                        <img src="templates/default/images/logo_login.png" style="width: 50%; height: 50%" alt="<?php print $lang["core"]["site_name"]; ?>" class="img-responsive">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                    <?php
                    $objForm->form_openForm();
                        $strUserName = isset($_POST["login_name"]) ? $_POST["login_name"] : "";
                        ?>
                        <fieldset>
                            <div class="form-group">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    <?php
                                    $strUserName = isset($_POST["login_name"]) ? $_POST["login_name"] : "";
                                    if(isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true"){
                                        $strUserName = $_SESSION["hml"]["usuario"];
                                    }
                                    $objForm->add_input_text("login_name",$strUserName,"",true,$lang["core"]["user_name"],"",false,false);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-lock"></span>
                                    </span>
                                    <?php
                                    $objForm->add_input_password("login_passwd","",true,$lang["core"]["password"],"",false,false);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-1 form-control-static">
                                    <?php
                                    $objForm->add_input_checkbox("login_auto",false,"",false,false,false,false);
                                    $objForm->add_input_extraTag("login_auto","value","1");
                                    $objForm->draw_input_checkbox("login_auto");
                                    ?>
                                </div>
                                <div class="col-xs-11 form-control-static">No cerrar sesión</div>
                            </div>
                            <?php
                            if( isset($boolYaConectado) && $boolYaConectado ) {
                                ?>
                                <div class="form-group">
                                    <div class="col-xs-1 form-control-static">
                                        <?php
                                        $objForm->add_input_checkbox("force_disconnect",false,"",true,false,false,false);
                                        ?>
                                    </div>
                                    <div class="col-xs-11" style="color: #00318c;margin-bottom: 0;padding-top: 7px;text-align: left;">
                                        <?php echo $lang["core"]["core_marque_aqui_para_desconectar"]; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div id="divIncompatible" class="form-group" style="display: none;">
                                <div class="col-xs-1 form-control-static">
                                    <?php
                                    $objForm->add_input_checkbox("chkIncompatible",false,"",true,false,false,false);
                                    ?>
                                </div>
                                <div class="col-xs-11" style="color: #00318c;margin-bottom: 0;padding-top: 7px;text-align: left;">
                                    Este explorador no es compatible con el sistema, por favor marque la casilla si aun así desea ingresar, considerando que podrá tener resultados inesperados.
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="btnLogin" class="btn btn-primary btn-block" tabindex="1">
                                    <?php print $lang["core"]["login"]; ?>
                                </button>
                            </div>
                        </fieldset>
                        <?php
                    $objForm->form_closeForm();
                    ?>
                </div>
            </div>
            <?php
            if( (isset($_POST["login_name"]) && isset($_POST["login_passwd"]) && !core_is_login()) ) {
                ?>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                        <div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                          <?php
                            print empty($strMensajeError) ? $lang["core"]["invalid_password"] : $strMensajeError;
                          ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            if( (isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true") ) {
                $strMensajeError = $lang["core"]["mensaje_sesion_expirada"];
                ?>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                        <div class="alert alert-danger alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                          <?php
                            print empty($strMensajeError) ? "" : $strMensajeError;
                          ?>
                        </div>
                    </div>
                </div>
                <?php
                unset($_SESSION["hml"]["sesion_expirada"]);

            }
            ?>
        </div>
        <script>
            if( typeof FormData == "undefined" ) {
                $("#btnLogin").hide();
                $("#divIncompatible").show();
            }
            $(function() {
                $("#chkIncompatible").change(function() {
                    if( $(this).prop('checked') ) {
                        $("#btnLogin").show();
                    }
                });
            });
        </script>
        <?php
        //clear_login();
        $this->template_footer();
        ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php print $lang["core"]["title"]; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.4 -->
        <link href="templates/idc/libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="templates/idc/libraries/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery 2.1.4 -->
        <script src="templates/idc/libraries/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="templates/idc/libraries/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="index.php"><b>IDC</a>
            </div>
            <div class="login-box-body">
                <?php
                $objForm = new form("form-login","form-login","POST","index.php","","form-horizontal");
                $objForm->form_setExtraTag("role","form");
                $objForm->form_openForm();
                ?>
                <fieldset>
                    <div class="form-group">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-user"></span>
                            </span>
                            <?php
                            $strUserName = isset($_POST["login_name"]) ? $_POST["login_name"] : "";
                            if(isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true"){
                                $strUserName = $_SESSION["hml"]["usuario"];
                            }
                            $objForm->add_input_text("login_name",$strUserName,"",true,$lang["core"]["user_name"],"",false,false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-lock"></span>
                            </span>
                            <?php
                            $objForm->add_input_password("login_passwd","",true,$lang["core"]["password"],"",false,false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-1 form-control-static">
                            <?php
                            $objForm->add_input_checkbox("login_auto",false,"",false,false,false,false);
                            $objForm->add_input_extraTag("login_auto","value","1");
                            $objForm->draw_input_checkbox("login_auto");
                            ?>
                        </div>
                        <div class="col-xs-11 form-control-static">No cerrar sesión</div>
                    </div>
                    <?php
                    if( isset($boolYaConectado) && $boolYaConectado ) {
                        ?>
                        <div class="form-group">
                            <div class="col-xs-1 form-control-static">
                                <?php
                                $objForm->add_input_checkbox("force_disconnect",false,"",true,false,false,false);
                                ?>
                            </div>
                            <div class="col-xs-11" style="color: #00318c;margin-bottom: 0;padding-top: 7px;text-align: left;">
                                <?php echo $lang["core"]["core_marque_aqui_para_desconectar"]; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div id="divIncompatible" class="form-group" style="display: none;">
                        <div class="col-xs-1 form-control-static">
                            <?php
                            $objForm->add_input_checkbox("chkIncompatible",false,"",true,false,false,false);
                            ?>
                        </div>
                        <div class="col-xs-11" style="color: #00318c;margin-bottom: 0;padding-top: 7px;text-align: left;">
                            Este explorador no es compatible con el sistema, por favor marque la casilla si aun así desea ingresar, considerando que podrá tener resultados inesperados.
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="btnLogin" class="btn btn-primary btn-block" tabindex="1">
                            <?php print $lang["core"]["login"]; ?>
                        </button>
                    </div>
                </fieldset>
                <?php
                $objForm->form_closeForm();
                if( (isset($_POST["login_name"]) && isset($_POST["login_passwd"]) && !core_is_login()) ) {
                    ?>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?php
                                print empty($strMensajeError) ? $lang["core"]["invalid_password"] : $strMensajeError;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if( (isset($_SESSION["hml"]["sesion_expirada"]) && $_SESSION["hml"]["sesion_expirada"] == "true") ) {
                    $strMensajeError = $lang["core"]["mensaje_sesion_expirada"];
                    ?>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?php
                                print empty($strMensajeError) ? "" : $strMensajeError;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION["hml"]["sesion_expirada"]);

                }
                ?>
            </div>
        </div>
    </body>
</html>


        <?php
    }

    public function private_dashboard() {
        global $lang;
        $objTemplate = new template();

        $this->template_header($lang["core"]["title"]);

        $this->template_button_open(true);
        $this->template_button_close();

        $this->template_sidebar_open(true,false);
        $this->template_sidebar_close();

        $this->template_content_open();
            $this->drawContentDashboard();
        $this->template_content_close();

        $this->template_footer();
    }

    public function drawContentDashboard() {

        require_once("modules/clientes/main.php");
        clientes_dashboard();

    }

    public function getInfoModulo(){

        $arrData = array();
        $strFrom = "";
        $strWhere = "";
        $intPersona = core_getUserId();
        $intIdioma = core_get_idioma();

        if( $_SESSION["hml"]["tipo_usuario"] != "admin" ) {
            $strFrom = ", perfil, perfil_acceso, persona_perfil";
            $strWhere = "  AND perfil.activo = 'Y'
                            AND perfil_acceso.tipo_acceso = 1
                            AND persona_perfil.persona = {$intPersona}
                            AND perfil_acceso.perfil = perfil.perfil
                            AND perfil_acceso.acceso = acceso.acceso
                            AND persona_perfil.perfil = perfil.perfil";
        }

        $strQuery = "SELECT modulo.modulo, modulo.codigo, modulo_idioma.nombre nombreModulo, modulo.orden orden_modulo
                     FROM   acceso_idioma,
                            acceso,
                            modulo_idioma,
                            modulo {$strFrom}
                     WHERE  modulo.activo = 'Y'
                     AND    acceso.activo = 'Y'
                     AND    acceso.privado = 'Y'
                     AND    acceso.acceso_extra = 'N'
                     AND    acceso_idioma.idioma = {$intIdioma}
                     AND    modulo_idioma.idioma = {$intIdioma}
                     {$strWhere}
                     AND    modulo_idioma.modulo = modulo.modulo
                     AND    modulo.modulo = acceso.modulo
                     AND    acceso.acceso = acceso_idioma.acceso
                     ORDER  BY orden_modulo";
        $qTMP = db_query($strQuery);

        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["modulo"]]["modulo"] = $rTMP["modulo"];
            $arrData[$rTMP["modulo"]]["nombre"] = $rTMP["nombreModulo"];
            $arrData[$rTMP["modulo"]]["codigo"] = $rTMP["codigo"];

        }

        db_free_result($qTMP);

        return $arrData;
    }

    public function drawTitle($strTitle) {

        ?>
        <h2 class="nav-header"><?php print $strTitle; ?></h2>
        <?php

    }

    public function drawTitleLeft($strTitle, $boolRequired = false) {

        ?>
        <span class="inputSizeComplete editTitles">
            <?php
            print $strTitle.( $boolRequired ? '<span style="color: red;">*</span>' : '' );
            ?>
        </span>
        <?php


    }

    public function drawTitleInfo( $strTexto ){
        ?>
        <span style="color: rgb(165,165,165); font-style: italic; font-weight: 500;">
            <?php echo $strTexto; ?>
        </span>
        <?php
    }

    public function draw_open_group($strTitle = "") {

        if( !empty($strTitle) )
            $this->drawTitle($strTitle);

        ?>
        <ul class="j-links-group nav nav-list">
        <?php

    }

    public function draw_close_group() {

        ?>
        </ul>
        <?php


    }

    public function draw_group_element($strTitle, $strHref = "", $strIcon = "") {

        ?>



        <li>
            <a href="<?php print $strHref; ?>">
                <?php
                if( !empty($strIcon) ) {
                    ?>
                    <span class="glyphicon glyphicon-<?php print $strIcon; ?>"></span>
                    <?php
                }
                ?>
                <span class="j-links-link"><?php print $strTitle; ?></span>
            </a>
        </li>
        <?php

    }

    public function draw_open_border($strTitle) {

        ?>
        <div class="row-fluid">
            <div class="well well-small">
                <h2 class="module-title nav-header"><?php print $strTitle; ?></h2>
                <div class="row-striped">

        <?php

    }

    public function draw_open_border_row() {

        ?>
        <div class="row-fluid">
        <?php

    }

    public function draw_open_border_cell($intWidth) {

        ?>
        <div class="span<?php print $intWidth; ?>">
        <?php

    }

    public function draw_close_border_cell() {
        ?>
        </div>
        <?php
    }

    public function draw_close_border_row() {

        ?>
        </div>
        <?php

    }

    public function draw_close_border() {

        ?>
                </div>
            </div>
        </div>
        <?php

    }

    public function draw_alert($strType = "", $strTitle = "", $strAlert = "", $boolAddClose = true) {
        ?>
        <script>
            $(document).ready(function(){

                var strAddClose = "<?php print $boolAddClose ? "Y" : "N";  ?>";
                var boolAddClose = ( strAddClose == "Y" );

                draw_Alert("<?php print $strType; ?>", "<?php print $strTitle; ?>", "<?php print $strAlert; ?>", boolAddClose);

            });
        </script>
        <?php
    }


    public function draw_button($strId, $strTexto, $strOnClick = "", $strIcon = "th-list", $strTamanio = "sm", $strColor = "", $boolIconRight = false, $strIconTipo = "glyphicon glyphicon-" ) {

        $strColor = (empty($strColor)) ? "" : "btn-{$strColor}";
        ?>
        <button id="<?php print $strId; ?>" type="button" class="btn btn-<?php print $strTamanio; ?> btn-default btn-ADGECO<?php print $strColor; ?>" onclick="<?php print $strOnClick; ?>">
            <?php
            if( !$boolIconRight ) {
                ?>
                <span class="<?php print $strIconTipo.$strIcon; ?>"></span>&nbsp;
                <?php
                print $strTexto;
            }
            else {
                print $strTexto;
                ?>
                &nbsp;<span class="<?php print $strIconTipo.$strIcon; ?>"></span>
                <?php
            }
            ?>
        </button>
        <?php

    }
    public function draw_switch(){
        ?>
         <input type="checkbox" name="chkPublico_" id="chkPublico_" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="mini">
        <?php
    }

    /**
    * Metodo que agrega iconos de Bootstrap
    *
    * @param string $strId
    * @param string $strOnClick
    * @param string $strIcon
    * @param string $strTamanio
    * @param boolean $boolPointer
    * @param string $strExtraClass
    */
    public function draw_icon($strId, $strOnClick = "", $strIcon = "th-list", $strTamanio = "sm", $boolPointer = false, $strExtraClass = "glyphicon-color") {

        $strPointer = ($boolPointer) ? " cursor" : "";
        $strExtraClass = empty($strExtraClass) ? "" : " ".$strExtraClass;
        ?>
        <span id="<?php print $strId; ?>" onclick="<?php print $strOnClick; ?>" class="glyphicon glyphicon-<?php print $strIcon; ?> btn-<?php print $strTamanio.$strPointer.$strExtraClass; ?>"></span>
        <?php

    }

    /**
    * Metodo que agrega iconos de Font Awesome Ejemplo de iconos en http://fortawesome.github.io/Font-Awesome/
    *
    * @param string $strId Id del tag
    * @param string $strIcon Nombre del icono de Font Awesome Ejemplo de iconos en http://fortawesome.github.io/Font-Awesome/icons/
    * @param string $strOnClick Agrega funciónes ó codigo javascript que se ejecutara al hacer click sobre el icono
    * @param boolean $boolPointer Agrega el class para despliegue del icono del mouse cursor:pointer
    * @param string $strTamano Tamaño del icono, posible valores: lg, 2x, 3x, 4x y 5x
    * @param string $strExtraClass Agrega una o varias clases
    * @param boolean $boolFixedWidth Establecer iconos con un ancho fijo
    * @param boolean $boolList Agrega si se desea colocar el icono en tipo lista
    * @param string $strBorderedPulled Agrega borde y alineación a la derecha ó izquierda, posibles valores: fa-border, pull-right, pull-left, fa-border pull-right y fa-border pull-left
    * @param boolean $boolSpinning Agrega efecto de rotación al icono
    * @param string $strRotatedFlipped Agrega rotación ó giro estatico al icono, posibles valores: rotate-90, rotate-180, rotate-270, flip-horizontal y flip-vertical
    */
    public function draw_icon_fa($strId, $strIcon, $strOnClick = "", $boolPointer = false, $strTamano = "", $strExtraClass = "fa-color", $boolFixedWidth = false, $boolList = false, $strBorderedPulled = "", $boolSpinning = false, $strRotatedFlipped = "") {
        $strTamano = empty($strTamano) ? "" : " fa-".$strTamano;
        $strExtraClass = empty($strExtraClass) ? "" : " ".$strExtraClass;
        $strFixedWidth = ($boolFixedWidth) ? " fa-fw" : "";
        $strList = ($boolList) ? "fa-li " : "";
        $strBorderedPulled = empty($strBorderedPulled) ? "" : " ".$strBorderedPulled;
        $strSpinning = ($boolSpinning) ? " fa-spin" : "";
        $strRotatedFlipped = empty($strRotatedFlipped) ? "" : " fa-".$strRotatedFlipped;
        $strPointer = ($boolPointer) ? " cursor" : "";
        ?>
        <i id="<?php print $strId; ?>" class="<?php print $strList; ?>fa fa-<?php print $strIcon.$strTamano.$strFixedWidth.$strBorderedPulled.$strSpinning.$strRotatedFlipped.$strPointer.$strExtraClass; ?>" onclick="<?php print $strOnClick; ?>"></i>
        <?php
    }

    public function draw_icon_generico($strId, $strIcon, $strOnClick = "", $boolPointer = true, $strExtraClass = "") {
        $strPointer = ($boolPointer) ? " cursor" : "";
        $strExtraClass = empty($strExtraClass) ? "" : " ".$strExtraClass;
        ?>
        <span id="<?php print $strId; ?>" onclick="<?php print $strOnClick; ?>" class="<?php print $strIcon.$strPointer.$strExtraClass; ?>"></span>
        <?php
    }

    public function draw_image($strId, $strImagePath = "", $strOnClick = "", $strStyles = "", $strExtraTags = "", $strTooltip = "" ){
        $strToolTip = empty($strTooltip) ? "" : "rel=\"tooltip\" title=\"{$strTooltip}\"" ;
        $strTagStyles = empty($strStyles) ? "" : "style=\"{$strStyles}\"";
        $strTagOnclick = empty($strOnClick) ? "" : "onclick=\"{$strOnClick}\"";

        ?>
        <img id="<?php print $strId;?>" src="<?php print $strImagePath;?>" <?php print $strToolTip."  ".$strTagStyles."  ".$strExtraTags."  ".$strTagOnclick; ?>>
        <?php
    }

    public function template_draw_link($strId, $strTexto, $strOnClick = "", $strExtraClass = ""){

        ?>
        <span id="<?php print $strId; ?>" onclick="<?php print $strOnClick; ?>" class="btn-link <?php print $strExtraClass; ?>"><?php print $strTexto; ?></span>
        <?php

    }


    /*--Accordions*/

    public function draw_accordion_open( $strUniqId = "accordion" ){
        ?>
        <div class="panel-group" id="<?php echo $strUniqId; ?>">
        <?php
    }

    public function draw_accordion_close(){
        ?>
        </div>
        <?php
    }

    public function draw_accordion_open_element($strHrefAccodion, $strTitleAccordion, $strIDTitleAccordion = "", $strIDContentAccordion = "", $strOnclickAccordion = "", $boolCollapseAccordion = false, $strIcon = "", $boolCloseElement = false){
        $strHrefAccodion = !empty($strHrefAccodion) ? str_replace(" ", "_", $strHrefAccodion) : "";
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#<?php print $strHrefAccodion; ?>" id="<?php print $strIDTitleAccordion; ?>" <?php print !empty($strOnclickAccordion) ? "onclick=\"".$strOnclickAccordion."\"" : ""; ?>>
                        <?php print $strTitleAccordion; ?>
                        <?php !empty($strIcon) ? $this->draw_icon("", "", $strIcon) : ""; ?>
                    </a>
                </span>
            </div>
            <div id="<?php print $strHrefAccodion; ?>" style="width: 100%" class="panel-collapse collapse <?php print $boolCollapseAccordion ? "in" : "";?>">
                <div class="panel-body" id="<?php print $strIDContentAccordion; ?>">
        <?php

        if( $boolCloseElement )
            $this->draw_accordion_close_element();

    }

    public function draw_accordion_close_element(){
        ?>
                </div>
            </div>
        </div>
        <?php
    }

    /*--Accordions*/

    /*--Tabs*/

    public function draw_tab_open($strId,$strTitle = ""){

        if( !empty($strTitle) )
            $this->drawTitle($strTitle);
        ?>
        <div class="row">
        <div class="col-lg-12" style="border:1px solid #DEDEDE; padding: 5px; border-radius: 5px;">
            <ul class="nav nav-tabs" id="<?php print $strId; ?>">
            <?php
    }

    public function draw_tab_addElement($strTexto,$strContenedorId,$strOnclick = "",$boolSelected = false,$boolShowLoading = true,$strIcon = ""){

        $strClass = $boolSelected ? "active" : "";
        $strShowLoading = $boolShowLoading ? "showImgCoreLoading();" : "";
        $strHideLoading = $boolShowLoading ? "hideImgCoreLoading();" : "";
        ?>
        <li class="<?php print $strClass; ?>">
            <a href="#<?php print $strContenedorId; ?>" data-toggle="tab" onclick="<?php print $strShowLoading; ?> <?php print $strOnclick; ?> <?php print $strHideLoading; ?>">
                <?php if( !empty($strIcon) ) $this->draw_icon("","",$strIcon);   print $strTexto; ?>
            </a>
        </li>
        <?php
    }

    public function draw_tab_close(){
        ?>
        </ul>
        <?php
    }

    public function draw_tab_open_contenido($strContenedorId,$boolSelected = false,$boolOpenContent = false){

        $strClass = $boolSelected ? "active in" : "";

        if( $boolOpenContent ){
            ?>

            <div class="tab-content">

            <?php
        }

        ?>

        <div class="tab-pane  <?php print $strClass; ?>" id="<?php print $strContenedorId; ?>">

        <?php
    }

    public function draw_tab_close_contenido($boolCloseContent = false){

        ?>

        </div>

        <?php

        if( $boolCloseContent ){
            ?>
            </div>
            </div>
            </div>
            <?php
        }
    }

    /*--Tabs*/

    /*--Modals*/

    public function draw_modal_open($strId,$strTamanio = "lg",$strPadding = "lg",$strBackDrop = "static",$boolCloseOnScape = false){

        $strCloseKeyboard = $boolCloseOnScape ? "true" : "false";
        $strTamanio = empty($strTamanio) ? "" : "modal-{$strTamanio}";
        $strPadding = empty($strPadding) ? "" : "modal-dialog-padding-{$strPadding}";

        ?>
        <div class="modal fade" id="<?php print $strId; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php print $strId; ?>Label" aria-hidden="true" data-backdrop="<?php print $strBackDrop; ?>" data-keyboard="<?php print $strCloseKeyboard; ?>">
            <div class="modal-dialog <?php print $strTamanio; ?> <?php print $strPadding; ?>">
                <div class="modal-content">

        <?php

    }

    public function draw_modal_draw_header($strTexto,$strId = "",$boolClose = true,$strOnclick = ""){

        ?>

        <div class="modal-header">
            <?php
            if( $boolClose ){
                ?>
                <button type="button" class="close" onclick="<?php print $strOnclick; ?>" data-dismiss="modal" aria-hidden="true">&times;</button>
                <?php
            }
            ?>
            <h4 class="modal-title break-text" id="<?php print $strId; ?>"><?php print $strTexto; ?></h4>
        </div>

        <?php

    }

    public function draw_modal_open_content($strId = ""){

        ?>

        <div class="modal-body" id="<?php print $strId; ?>">

        <?php

    }

    public function draw_modal_close_content($boolOpenFooter = false){

        ?>

        </div>

        <?php

        if( $boolOpenFooter )
            $this->draw_modal_open_footer();


    }

    public function draw_modal_open_footer($boolCloseDefault = false){

        ?>

        <div class="modal-footer">

        <?php

    }

    public function draw_modal_close_footer($boolCloseModal = false){

        ?>
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

        <?php

        if( $boolCloseModal )
            $this->draw_modal_close();


    }

    public function draw_modal_close(){

        ?>
                </div>
            </div>
        </div>
        <?php

    }

    /*--Modals*/

    /*--Panels*/

    /**
    * Función que dibuja el contenedor de un panel
    *
    * @param string $strPanelId
    * @param string Que tipo de panel va a ser. Opciones: "default", "primay", "success", "info", "warning", "danger"
    * @param boolean Indica si se cierra la apertura del div principal true: si se cierra, false: no se cierra
    */
    public function draw_panel_open( $strPanelId, $strPanelType = "default", $boolClosePanel = false ){
        $strPanelType = empty($strPanelType) ? "default" : $strPanelType;
        ?>
        <div id="<?php echo $strPanelId; ?>" class="panel panel-<?php echo $strPanelType; ?>">
        <?php

        if( $boolClosePanel ){
            $this->draw_panel_close();
        }

    }

    public function draw_panel_open_header( $boolCloseHeader = false ){
        ?>
        <div class="panel-heading break-text">
        <?php
        if( $boolCloseHeader ){
            $this->draw_panel_close_header();
        }
    }

    /**
    * Función que dibuja el cierre del header de un panel, y si se envia el array correctamente, se dibujaran los botones de acción
    *
    * @param array Este arreglo puede ser de uno o dos niveles. De un nivel cuando no se necesitan divisiones y de dos niveles cuando se necesitan. UN NIVEL: Se necesita un "key" en el arreglo, el cual indica que se dibujara un botón, el valor del "key" es indiferente; se necesita, por cada botón o posición en el arreglo, tres posiciones, las cuales el "key" se deberan de llamar "texto", "onclick" e "icono", la posición "texto" indica el texto que aparecera en el botón, si no se envia se pondra un '&nbsp;', la posición "onclick" indica la acción en javascript que se realizará al hacer click sobre el botón, si no se envia no se realizará ninguna acción, la posición "icono" indica el nombre del icono, según los css, que aparecerá al lado del texto, si no se envia no se pondrá ningún icono y la posición "id" indicará el id del botón para lo que sea necesario, no se envia se pondrá un uniqid. DOS NIVELES: será igual que el de un nivel solo que aquí se enviará un nivel mas en el arreglo, antes de todos los botones, y ese nivel, se agruparán por medio de su key y se pondrá un divisor entre cada grupo.
    */
    public function draw_panel_close_header( $arrButtons = array() ){

        if( count($arrButtons) > 0 ){
            ?>
            <div class="btn-group pull-right">
                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-chevron-down"></i></button>
                <ul class="dropdown-menu slidedown">
                    <?php
                    $strKey = uniqid();
                    $strPrimerKey = $strKey;
                    while( $rTMP = each($arrButtons) ){
                        if( is_array($rTMP["value"]) && array_key_exists("texto",$rTMP["value"]) ){
                            $strTexto = array_key_exists("texto",$rTMP["value"]) ? $rTMP["value"]["texto"] : "&nbsp;";
                            $strIcono = array_key_exists("icono",$rTMP["value"]) ? $rTMP["value"]["icono"] : "";
                            $strOnclick = array_key_exists("onclick",$rTMP["value"]) ? $rTMP["value"]["onclick"] : "";
                            $strButtonId = array_key_exists("id",$rTMP["value"]) ? $rTMP["value"]["id"] : uniqid();
                            ?>
                            <li <?php echo empty($strOnclick) ? "" : "onclick=\"{$strOnclick}\""; ?> id="<?php echo $strButtonId; ?>">
                                <a href="#">
                                    <?php
                                    if( !empty($strIcono) ){
                                        ?>
                                        <i class="fa fa-<?php echo $strIcono; ?> fa-fw"></i>
                                        <?php
                                    }
                                    ?>
                                    <?php echo $strTexto; ?>
                                </a>
                            </li>
                            <?php
                        }
                        else{
                            if( ($strKey != $rTMP["key"]) && ($strPrimerKey != $strKey) ){
                                ?>
                                <li class="divider"></li>
                                <?php
                            }
                            while( $tTMP = each($rTMP["value"]) ){
                                if( is_array($tTMP["value"]) && array_key_exists("texto",$tTMP["value"]) ){
                                    $strTexto = array_key_exists("texto",$tTMP["value"]) ? $tTMP["value"]["texto"] : "&nbsp;";
                                    $strIcono = array_key_exists("icono",$tTMP["value"]) ? $tTMP["value"]["icono"] : "";
                                    $strOnclick = array_key_exists("onclick",$tTMP["value"]) ? $tTMP["value"]["onclick"] : "";
                                    $strButtonId = array_key_exists("id",$tTMP["value"]) ? $tTMP["value"]["id"] : uniqid();
                                    ?>
                                    <li <?php echo empty($strOnclick) ? "" : "onclick=\"{$strOnclick}\""; ?> id="<?php echo $strButtonId; ?>">
                                        <a href="#">
                                            <?php
                                            if( !empty($strIcono) ){
                                                ?>
                                                <i class="fa fa-<?php echo $strIcono; ?> fa-fw"></i>
                                                <?php
                                            }
                                            ?>
                                            <?php echo $strTexto; ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            $strKey = $rTMP["key"];
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>

        </div>

        <?php
    }

    public function draw_panel_open_body( $boolCloseBody = false ){
        ?>
        <div class="panel-body">
        <?php

        if( $boolCloseBody ){
            $this->draw_panel_close_body();
        }

    }

    public function draw_panel_close_body(){
        ?>

        </div>

        <?php
    }

    public function draw_panel_open_footer( $boolCloseFooter = false ){
        ?>
        <div class="panel-footer">
        <?php

        if( $boolCloseFooter ){
            $this->draw_panel_close_footer();
        }

    }

    public function draw_panel_close_footer(){
        ?>

        </div>

        <?php
    }

    public function draw_panel_close(){
        ?>

        </div>

        <?php
    }

    /*--Panels*/

    public function draw_search( $strInputNameId, $strPlaceHolder = "", $strButtonOnClick = "" ){
        $strPlaceHolderTag = empty($strPlaceHolder) ? "" : "placeholder=\"{$strPlaceHolder}\"";
        $strButtonOnClickTag = empty($strButtonOnClick) ? "" : "onclick=\"{$strButtonOnClick}\"";
        ?>
        <div class="input-group custom-search-form">
            <input type="text" name="<?php echo $strInputNameId; ?>" id="<?php echo $strInputNameId; ?>" <?php echo $strPlaceHolderTag; ?> class="form-control">
            <span class="input-group-btn">
                <button type="button" class="btn btn-default" <?php echo $strButtonOnClickTag; ?>>
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
        <?php
    }

}