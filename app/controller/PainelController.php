<?php


class PainelController
{
    public function index()
    {
        session_start();

        if ((!isset($_SESSION['user'])) == true) {
            unset($_SESSION['user']);
            header('location:?page=user');

        }

        $user = User::findById($_SESSION['id']);
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('painel.html');
        $content = array();
        $content['docs'] = $user->documents;

        $container = $template->render($content);
        echo $container;


    }


    public static function submit()
    {
        session_start();
        $url = UploadController::uploadFile($_SESSION['dir']);
        if ($url) {
            echo '<script>alert("Arquivo Enviado com sucesso!")</script>';
            self::upload();
        }
    }


}
