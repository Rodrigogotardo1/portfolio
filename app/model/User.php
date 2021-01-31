<?php

require_once 'connection/Connection.php';

//require_once 'PHPMailer/src/PHPMailer.php';

class User
{


    public static function perfil()
    {
        try {


            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM author where id=:id");
            $sql->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
            $sql->execute();

            return $sql->fetchObject('User');
        } catch (Exception $e) {
            return $e->getMessage();
        }


    }

    public static function login($login)
    {

        try {


            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM author where login= :login and psw=:psw");
            $sql->bindValue(':login', trim($login['login']));
            $sql->bindValue(':psw', base64_encode($login['psw']));

            $sql->execute();

            $row = $sql->fetchObject('User');

            if(!$row){
                return  false;
            }


            session_start();

            if ((!isset($_SESSION['user'])) == true) {
                $_SESSION['id'] = $row->id;
                $_SESSION['user'] = self::userName($row->login);
                $_SESSION['avatar'] = $row->avatarPath;
            }

            $dir = './uploads/' . $_SESSION['user'] . $_SESSION['id'] . '/';
            if (!file_exists($dir)) {
                $oldUmask = umask(0);
                mkdir($dir, 0777, true);
                umask($oldUmask);
                $_SESSION['dir'] = $dir;


                $file = array('./resources/img/capa/image.png', './resources/file/file.doc', './resources/img/capa/perfil.png');
                $copy = array($_SESSION['dir'] . 'image.png', $_SESSION['dir'] . 'file.doc', $_SESSION['dir'] . 'perfil.png');
                for ($i = 0; $i < count($file); $i++) {
                    if (!copy($file[$i], $copy[$i])) {
                        echo "falha ao copiar $file...\n";
                    }
                }


            }
            $_SESSION['dir'] = $dir;
            return $row;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public static function findAll(): array
    {
        $array = array();
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM author order by rand();");
            $sql->execute();

            while ($row = $sql->fetchObject('User')) {
                $array[] = $row;

            }

        } catch (Exception $e) {
            echo $e->getMessage();

        }
        return $array;
    }

    public static function findById($id)
    {
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM author  where id=:id");

            $sql->bindValue(':id', $id);
            $sql->execute();

            $row = $sql->fetchObject('User');

            $row->documents = Document::findAllById($id);

            return $row;
        } catch (Exception $e) {
            return $e->getMessage();


        }
    }

    public static function insert($user): bool
    {

        if (!User::existeLogin($user['login'], $user['psw'])) {

            try {


                if ($user['psw'] != $user['confirm']) {
                    return false;
                }
                $conn = Connection::getConnection();
                $sql = $conn->prepare("INSERT INTO author(authorName, email, login, psw, avatarPath, functionName, story) values(:author,:email,:login,:psw,:avatar,:functionName,:story)");

                $user['avatarPath'] = "perfil.png";
                $sql->bindValue(':author', trim($user['authorName']));
                $sql->bindValue(':email', trim($user['email']));
                $sql->bindValue(':login', trim($user['login']));
                $sql->bindValue(':psw', base64_encode($user['psw']));
                $sql->bindValue(':avatar', trim($user['avatarPath']));
                $sql->bindValue(':functionName', trim($user['functionName']));
                $sql->bindValue(':story', trim($user['story']));
                $sql->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        } else {
            return false;
        }


        return true;

    }

    public static function update($user): bool
    {


        try {
            if ($user['psw'] != $user['confirm']) {
                return false;
            }

            $conn = Connection::getConnection();
            $sql = $conn->prepare("UPDATE author SET authorName=:author, avatarPath=:avatar, email=:email, login=:login, psw=:psw, story=:story, functionName=:functionName where id=:id");

            $sql->bindValue(':author', trim($user['authorName']));
            $sql->bindValue(':avatar', trim($user['avatarPath']));
            $sql->bindValue(':email', trim($user['email']));
            $sql->bindValue(':login', trim($user['login']));

            $sql->bindValue(':psw', base64_encode($user['psw']));

            $sql->bindValue(':story', trim($user['story']));
            $sql->bindValue(':functionName', trim($user['functionName']));
            $sql->bindValue(':id', $user['id']);

            $sql->execute();

            session_start();
            $update = rename($_SESSION['dir'], './uploads/' . self::userName($user['login']) . $user['id'] . '/');
            if ($update) {
                unset($_SESSION['dir']);
                session_start();
                $_SESSION['dir'] = './uploads/' . self::userName($user['login']) . $user['id'] . '/';
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }


        return true;


    }

    public static function delete($user): bool
    {
        try {
            session_start();
            if (file_exists($_SESSION['dir'])) {
                $path = scandir($_SESSION['dir'], 1);
                $allFiles = array_diff($path, array('..', '.'));
                foreach ($allFiles as $value) {
                    unlink($_SESSION['dir'] . $value);
                }
                if (rmdir($_SESSION['dir'])) {

                    $conn = Connection::getConnection();
                    $sql = $conn->prepare("DELETE FROM author where id=:id");
                    $sql->bindValue(':id', $user['id'], PDO::PARAM_INT);
                    $sql->execute();

                };
            }

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            self::allDocuments($user['id']);
            self::close();
        }

    }

    /*
     * Esse método foi implementado pelo fato de o banco de dados
     * não responder ao ON DELETE CASCADE;
     */
    public static function allDocuments($author): bool
    {
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("delete from document where author=:id");
            $sql->bindValue(':id', $author, PDO::PARAM_INT);
            $sql->execute();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;

        }

    }

    public static function emailSubmit($email): bool
    {

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->addAddress($email['email']);
        $mail->setFrom($email['title']);
//        $mail->($email['email']);
        $mail->setFrom($mail['from']);
        $mail->addReplyTo('francisco.vieira.dev@gmail.com');
        $mail->send();

//        try {
//            mail($email['email'], $email['title'], $email['msg'], $headers);
//        } catch (Exception $e) {
//            echo $e->getMessage();
//            return false;
//        }
        return true;


    }


    private function userName($userName): string
    {
        return str_replace(' ', '_', strtolower($userName));
    }

    public static function close()
    {
        session_start();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);
    }

    public static function existeLogin($login, $psw): bool
    {
        $conn = Connection::getConnection();
        $sql = $conn->prepare("SELECT login, psw FROM author where login= :login and psw=:psw");
        $sql->bindValue(':login', trim($login));
        $sql->bindValue(':psw', base64_encode($psw));
        $sql->execute();

        $row = $sql->fetchObject('User');


        if ($row) {
            unset($row);
            return true;
        }
        return false;

    }


}
