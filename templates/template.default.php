<?php
namespace mnhcc\ml;
    use mnhcc\ml\classes\Filter,
	mnhcc\ml\classes\SERVER;  
if(!\defined("\\mnhcc\\ml\\INDEX")) die();
$vserver = (Filter::input('SERVER', 'VSERVER')) ? Filter::input('SERVER', 'VSERVER') : Filter::input('SERVER', 'SERVER_NAME');
$ip = Filter::input('SERVER', 'REMOTE_ADDR');
$server = (SERVER::getHost() != 'localhost') ? '//myserver.mn-hegenbarth.de' : 'http://localhost';
/**
 * 
 * @param mixed $variable Value to filter. 
 * @param int $filter The ID of the filter to apply. The Types of filters manual page lists the available filters. 
 * @param mixed $options Associative array of options or bitwise disjunction of flags. If filter accepts options, flags can be provided in "flags" field of array. For the "callback" filter, callable type should be passed. The callback must accept one argument, the value to be filtered, and return the value after filtering/sanitizing it. 
 * @return bool indicates whether the filter is applicable 
 */
function filter_var_is($variable, $filter, $options) {
    $filter_var = filter_var($variable, $filter, $options);
    return (bool) !empty($filter_var);
}
?>
<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="utf-8">
        <title>Server -<?= $vserver ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Le styles -->
        <!-- TODO: make sure bootstrap.min.css points to BootTheme generated file -->
		<link type="text/css" href="<?=$this->base($server)?>assets/css/bootstrap.css"  rel="stylesheet">
		<link type="text/css" href="<?=$this->base($server)?>assets/css/jquery-ui-1.10.0.custom.css"  rel="stylesheet">
		<link type="text/css" href="<?=$this->base($server)?>assets/css/main.css"  rel="stylesheet">
		<mnhccTemplate:include type="head" name="style" renderer="none" />
		<mnhccTemplate:include type="head" name="script" renderer="none" />
		<mnhccTemplate:include type="head" name="head" renderer="none" />
        <link href='https://fonts.googleapis.com/css?family=Alfa+Slab+One|Istok+Web:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <!-- TODO: make sure bootstrap-responsive.min.css points to BootTheme
        generated file -->
        <link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-responsive.min.css"
              rel="stylesheet">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="<?=$this->base($server)?>assets/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=$this->base($server)?>assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$this->base($server)?>assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$this->base($server)?>assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?=$this->base($server)?>assets/ico/apple-touch-icon-57-precomposed.png">
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>

                    </a>
                    <a class="brand brinside" href="http://mnhcc.mn-hegenbarth.de/ml/"><b>Minimalus Layoutilus</b></a>
                    <div class="nav-collapse collapse">
                        <p class="navbar-text pull-right">Logged in as 

                            <a data-ip="<?= filter_var($ip, FILTER_VALIDATE_IP) ?>" href="javascript:login();" class="navbar-link">
                                <?php
                                if (!Filter::varIs($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ||
                                        (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']))) {
                                    $user = (isset($_SERVER['PHP_AUTH_USER'])) ? $_SERVER['PHP_AUTH_USER'] : "Local User";
                                    echo $user;
                                } else {
                                    echo "Anonymus";
                                }
                                ?>
                            </a>

                        </p>
                        <ul class="nav">
                            <li class="active">
                                <a href="<?=$this->base($server)?>">Home</a>
                            </li>
                            <li>
                                <a href="http://mnhcc.mn-hegenbarth.de/">Project</a>
                            </li>
                            <li>
                                <a href="<?=$this->base($server)?>docu/">Docu</a>
                            </li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span3">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">Docnobbi</li>
                            <li>
                                <a href="http://docnobbi.norbert-hegenbarth.de/">docnobbi.norbert-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://blog.norbert-hegenbarth.de/">blog.norbert-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://spiritus.norbert-hegenbarth.de/">spiritus.norbert-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://forum.norbert-hegenbarth.de/">forum.norbert-hegenbarth.de</a>
                            </li>
                            <li class="nav-header">Carschrotter (Michael Hegenbarth)</li>
                            <li>
                                <a href="http://blog.mn-hegenbarth.de/">blog.mn-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://www.mn-hegenbarth.de/">www.mn-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://bugcatcher.mn-hegenbarth.de/">bugcatcher.mn-hegenbarth.de</a>
                            </li>
                            <li class="active">
                                <a href="http://myserver.mn-hegenbarth.de/">myserver.mn-hegenbarth.de</a>
                            </li>
                            <li>
                                <a href="http://mnhcc.mn-hegenbarth.de/">mnhcc.mn-hegenbarth.de</a>
                            </li>
                            <li class="nav-header">Brigitte Hegenbarth</li>
                            <li>
                                <a href="http://crearestyle.mn-hegenbarth.de/">crearestyle.mn-hegenbarth.de</a>
                            </li>
                        </ul>
                    </div>
                    <!--/.well -->
                </div>
                <!--/span-->
                <div class="span9">
                    <div class="hero-unit">
			<mnhccTemplate:include type="component" name="content" renderType="html" />
                    </div>
                    <div class="row">
                        <a id="about"></a>
                        <div class="span12">
                            <mnhccTemplate:include type="system" name="message" renderType="html" />
                        </div>
                        <div class="span12">
                            <h2>Wo bin ich hier eigentlich?</h2>
                            <p>
                                Da mich alles rund ums Web interessiert lag es nicht fern es mal mit einen eigenen Server zu probieren. 
                                <br>
                                Keine Webspace sondert etwas wo man selber walten und schalten kann.
                            </p>
                            <p>
                                Und genau hier bist du nun gelandet, naja eigentlich eher auf einer Seite die in einen Webspace lieg der sich auf den Server befindet und von einen Webserver… (Wenn du dich damit auskennst weißt du es sowieso, wenn nicht dann langweile ich dich damit wahrscheinlich).
                                <br>
                                Und Damit du hier keine langweilige weiße Seite mit hier entsteht demnächst „blabliblub“ ansehen musst stelle ich wenigstens meine hier gehosteten Seiten vor.
                            </p>
                        </div>
                        <div class="span5">
                            <h2>mn-hegenbarth.de</h2>

                            <p>Meine Homepage gnadenlose Selbstdarstellung. <br>Steckbrief und ein wenig Infos über meine Hobbys erwarten euch hier. </p>
                            <p>
                                <a class="btn" href="http://www.mn-hegenbarth.de/">Besuchen&raquo;</a>
                            </p>
                        </div>
                        <!--/span-->
                        <div class="span4">
                            <h2>MNHcC</h2>

                            <p>Meine Homepage für alle meine Programmierprojekte Sprich alle Skripte Programme und Co befinden sich dort.</p>
                            <p>
                                <a class="btn" href="http://mnhcc.mn-hegenbarth.de/">Besuchen&raquo;</a>
                            </p>
                        </div>
                        <!--/span-->
                        <div class="span4">
                            <h2>Heading</h2>

                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus
                                ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
                                sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed
                                odio dui.</p>
                            <p>
                                <a class="btn" href="#">Besuchen&raquo;</a>
                            </p>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                    <div class="row-fluid">
                        <div class="span4">
                            <h2>Heading</h2>

                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus
                                ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
                                sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed
                                odio dui.</p>
                            <p>
                                <a class="btn" href="#">Besuchen&raquo;</a>
                            </p>
                        </div>
                        <!--/span-->
                        <div class="span4">
                            <h2>Heading</h2>

                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus
                                ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
                                sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed
                                odio dui.</p>
                            <p>
                                <a class="btn" href="#">Besuchen&raquo;</a>
                            </p>
                        </div>
                        <!--/span-->
                        <div class="span4">
							<mnhccTemplate:include type="modul" name="modul7" renderType="html" />
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->
                </div>
                <!--/span-->
            </div>
            <!--/row-->
            <hr>

            <footer>
                <p>&copy; Michael Hegenbarth 2013</p> <a href="https://carschrotter.startssl.com/"><img src="openid.gif"></a>
            </footer>
        </div>

        <!--/.fluid-container-->
        <!-- Le javascript==================================================-
        ->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
    </body>

</html>

