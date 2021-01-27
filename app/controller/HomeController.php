<?php


class HomeController
{

    public function index()
    {

        try {
            session_start();
            $perfis = User::findAll();
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('home.html');
            $content = array();
            $content['titleHome'] = "Lista de Alunos";
            $content['users'] = $perfis;
            $content['path'] = 'http:'. './uploads/';

            $container = $template->render($content);
            echo $container;


        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

    }

    public function portfolio($id)
    {


        try {
            $user = User::findById($id);
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('portfolios.html');
            $content = array();
            $content['titlePortfolio'] = "Portfólios";
            $content['user'] = $user;
            $content['docs'] = $user->documents;
            $content['path'] = './uploads/';
            $container = $template->render($content);
            echo $container;


        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

    }


}
