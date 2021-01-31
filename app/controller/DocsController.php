<?php
require_once "app/model/Document.php";

class DocsController
{
    public function index()
    {
        try {

            $docs = Document::findAll();
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('docs.html');

            session_start();
            $content = array();
            $content['docs'] = $docs;
            $content['path'] = './uploads/';
            $container = $template->render($content);
            echo $container;

        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function change($id)
    {
        try {
            $doc = Document::findById($id);

            // -----------------------------
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('documentForm.html');
            // -----------------------------

            $content = array();

            $content['id'] = $doc->id;
            $content['title'] = $doc->title;
            $content['subtitle'] = $doc->subtitle;
            $content['download'] = $doc->download;
            $content['description'] = $doc->docdesc;
            $content['price'] = $doc->price;
            $content['filePath'] = $doc->filePath;


            $content = UploadController::fragmentCode($content);
            $extension = pathinfo($doc->filePath, PATHINFO_EXTENSION);
            $content = self::extensionFileImage($doc, $extension, $content);
            $content['content'] = $doc->content;

            $container = $template->render($content);
            echo $container;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    public function update()
    {
        try {

            $bool = Document::update($_POST);
            if ($bool) {
                echo '<script>alert("Publicação atualizada com sucesso! ")</script>';
                header('location:?page=painel');
            } else {
                header('location:?page=painel&method&change&id=');
            }


        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function insert()
    {

        try {
            $bool = Document::insert($_POST);

            if ($bool) {
                echo '<script>alert("Publicação inserido com sucesso! ")</script>';
                header('location:?page=painel');
            } else {
                header('location:?page=painel&method&change&id=');
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }


    }

    public function delete()
    {
        try {
            Document::delete($_GET);
            echo '<script>alert("Publicação excluído com sucesso! ")</script>';

        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            header('location:?page=painel');
        }

    }

    public function details()
    {


        try {

            $doc = Document::findById($_GET['id']);

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('details.html');
//            -----------------------------


            $content = array();
            session_start();
            $content['userId'] = $_SESSION['id'];
            $content['user'] = User::findById($doc->author);
            $content['doc'] = $doc;
            $content['docs'] = Document::findAllById($doc->author);

            $content['contents'] = explode(";", $doc->content);
            $content['path'] = './uploads/';
            $container = $template->render($content);
            echo $container;


        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function extensionFileImage($doc, $extension, $content)
    {


        switch ($extension) {

            case 'doc':
                $docExtension = 'doc.png';
                break;

            case 'docx':
                $docExtension = 'docx.png';
                break;

            case 'pdf':
                $docExtension = 'pdf.png';
                break;

            case 'odt':
                $docExtension = 'odt.png';
                break;

            default:
                $docExtension = 'perfil.png';
                break;
        }


        if (empty($doc->imagePath)) {
            $content['imgFile'] = $docExtension;
            $content['imagePath'] = $docExtension;
            $content['path'] = './resources/img/capa/';
        } else {
            $content['imgFile'] = './resources/img/capa/'.$docExtension;
            $content['imagePath'] = $doc->imagePath;
            $content['filePath'] = $doc->filePath;
        }

        return $content;

    }


}

