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
            $extension = pathinfo($doc->filePath, PATHINFO_EXTENSION);
            $pathDOC = "";
            switch ($extension) {

                case 'doc':
                    $pathDOC = './resources/img/capa/doc.png';
                    break;

                case 'docx':
                    $pathDOC = './resources/img/capa/docx.png';
                    break;

                case 'pdf':
                    $pathDOC = './resources/img/capa/pdf.png';
                    break;

                case 'odt':
                    $pathDOC = './resources/img/capa/odt.png';
                    break;

                default:
                    $pathDOC = './resources/img/capa/perfil.png';
                    break;
            }
            $content = UploadController::fragmentCode($content);

            $content['imgFile'] = $pathDOC;

            if (empty($doc->imagePath)) {
                $content['imagePath'] = $pathDOC;
            } else {
                $content['imagePath'] = $_SESSION['dir'] . $doc->imagePath;
            }

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
            $content['user'] = User::findById($doc->author);
            $content['doc'] = $doc;

//            $content['title'] = $doc->title;
//            $content['subtitle'] = $doc->subtitle;
//            $content['desc'] = $doc->docdesc;
//            $content['img'] = $doc->imagePath;
//            $content['file'] = $doc->filePath;
//            $content['download'] = $doc->download;
            $content['contents'] = explode(";", $doc->content);
            $content['path'] = './uploads/';
            $container = $template->render($content);
            echo $container;


        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


}

