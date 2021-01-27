<?php


class ErrorController
{
    public function index(){
        try {
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('error.html');
//            -----------------------------
            $content = array();

            $container = $template->render($content);
            echo $container;


        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
