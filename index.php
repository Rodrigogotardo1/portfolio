<?php


require_once 'app/controller/core/Core.php';
require_once 'app/controller/HomeController.php';
//
require_once 'app/controller/ErrorController.php';
require_once 'app/controller/UploadController.php';
//
require_once 'app/controller/DocsController.php';
require_once 'app/model/Document.php';
//
require_once 'app/controller/PainelController.php';
//
require_once 'app/controller/UserController.php';
require_once 'app/model/User.php';
//
require_once 'vendor/autoload.php';


$template = file_get_contents('app/template/header.html');

ob_start();
$core = new Core();
$core->start($_GET);
$container = ob_get_contents();
ob_end_clean();


$tpl_prompt = str_replace('{{content}}', $container, $template);

echo $tpl_prompt;
