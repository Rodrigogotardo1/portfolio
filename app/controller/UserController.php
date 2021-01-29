<?php

require_once 'app/model/User.php';


class UserController
{


    public function index()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            self::perfil();
        } else {
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('login.html');

            $content = array();

            $container = $template->render($content);
            echo $container;
        }


    }

    public function login()
    {
        $login = User::login($_POST);
        if ($login) {
            self::perfil();
        } else {
            $this->index();
        }

    }

    public function change()
    {
        session_start();
        $user = User::findById($_GET['id']);

        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('userForm.html');

        $content = array();
        $content['user'] = $user;
        $content['password'] = base64_decode($user->psw);
        $content['confirm'] = base64_decode($user->psw);
        $content = UploadController::fragmentCode($content);
        $container = $template->render($content);
        echo $container;
    }

    public function insert()
    {

        $user = User::insert($_POST);

        if ($user) {
            $this->index();
        } else {
            $this->change();
        }


    }

    public function update()
    {

        $user = User::update($_POST);


        if ($user) {
            header("location:?page=user");

        } else {
            header("location:?page=user&method=change&id=" . $_POST['id']);
        }


    }

    public function delete()
    {
        try {
            $bool = User::delete($_GET);
            if ($bool) {
                header("location:?page=home");
            }
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function perfil()
    {

        $user = User::perfil();
        session_start();
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('perfil.html');

        $content = array();
        $content['user'] = $user;
        $content['avatarPath'] = $_SESSION['dir'] . $user->avatarPath;


        $container = $template->render($content);
        echo $container;

    }

    public function email()
    {
        $email = User::emailSubmit($_POST);

        if ($email) {
            echo 'Enviado';
        }
    }

    public function close()
    {
        User::close();
       header('Location:?page=home');
    }
}
