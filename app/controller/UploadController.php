<?php

class UploadController
{


    public static function uploadFile($dir): string
    {


        $destination = $dir . basename($_FILES['fileProduct']['name']);
        $fileName = $_FILES['fileProduct']['name'];

        if (isset($fileName) && $_FILES['fileProduct']['error'] == 0) {


            if (!move_uploaded_file($_FILES['fileProduct']['tmp_name'], $destination)) {
                echo "Possível ataque de upload de arquivo!\n";
                echo 'Aqui está mais informações de debug:';
                print_r($_FILES);
            }

        }
        $icon = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = preg_replace("[^a-zA-Z0-9_]", "", strtr($fileName, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
        rename($destination, $dir . strtolower($fileName));

        return $destination;
    }

    public function index()
    {

        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader);

        $template = $twig->load('upload.html');
        $content = array();
        $content = self::fragmentCode($content);


        $container = $template->render($content);
        echo $container;
    }

    public static function submit()
    {
        session_start();
        $url = self::uploadFile($_SESSION['dir']);
        if ($url) {
            echo '<script>alert("Arquivo Enviado com sucesso!")</script>';
            self::index();
        }
    }

    public static function deleteFile()
    {
        session_start();

        $file = $_SESSION['dir'] . $_POST['fileDelete'];
//        var_dump($file);


        if (unlink($file)) {
            self::index();
        }
    }

    public static function fragmentCode($content)
    {
        session_start();
        $path = $_SESSION['dir'];
        $dir = scandir($path, 1);


        $extensions = array('png', 'jpeg', 'jpg', 'svg');
        $listImage = array();
        $listFile = array();
        $files = array_diff($dir, array('..', '.'));
        foreach ($files as &$value) {
            $extension = pathinfo($value, PATHINFO_EXTENSION);

            if (in_array($extension, $extensions)) {
                $listImage[] = $value;
            } else {
                $listFile[] = $value;
//               var_dump($listFile);
            }

        }


        $content['titleFile'] = "Seus arquivos";
        $content['titleImage'] = "Suas imagens";
        $content['path'] = $path;


        $content['allFiles'] = $files;


        $content['files'] = $listFile;
        $content['images'] = $listImage;


        return $content;
    }

}



