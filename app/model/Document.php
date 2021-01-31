<?php
require_once 'connection/Connection.php';

class Document
{


    public static function findAll(): array
    {

        try {


            $conn = Connection::getConnection();
            $sql = $conn->prepare("select  document.id,  title,subtitle,docdesc,download,price,filePath,imagePath,content,  author, login from document inner join author on document.author  = author.id;");
            $result = $sql->execute();


           $list[] = $result->author;

            $list = array();
            while ($row = $sql->fetchObject('Document')) {
                $list[] = $row;
            }
            return $list;
        } catch (Exception $e) {
            echo $e->getMessage();

        }


    }

    public static function findAllById($author): array
    {
        $list = array();
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM document  where author= :author");
            $sql->bindValue(':author', $author, PDO::PARAM_INT);
            $res = $sql->execute();

            if ($res) {
                new Exception("Erro de conexão com o banco");
            }


            while ($row = $sql->fetchObject('Document')) {
                $list[] = $row;
            }

        } catch (Exception $e) {
            echo $e->getMessage();

        }
        return $list;

    }

    public static function findById($id)
    {
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("SELECT * FROM document  where id=:id");
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetchObject('Document');
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;

        }

    }

    public static function update($doc): bool
    {

        if (empty($doc['title']) or empty($doc['content'])) {
            new Exception("Preencha todos os campos", 1);
            return false;
        }
        $value = "";
        if (isset($doc['download'])) {
            $value = 'checked';
        }
        $image = trim($doc['imagePath']);
        if (empty($image)) {
            $image = "image.png";
        }


        session_start();
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("UPDATE document SET title=:title, subtitle=:subtitle, download=:download ,docdesc=:description, price=:price, filePath=:filePath, imagePath=:imagePath, author=:author, content=:content where id=:id");
            $sql->bindValue(':title', trim($doc['title']));
            $sql->bindValue(':subtitle', trim($doc['subtitle']));
            $sql->bindValue(':download', trim($value));
            $sql->bindValue(':description', trim($doc['description']));
            $sql->bindValue(':content', trim($doc['content']));
            $sql->bindValue(':price', trim($doc['price']));
            $sql->bindValue(':filePath', trim($doc['filePath']));
            $sql->bindValue(':imagePath', $image);
            $sql->bindValue(':author', $_SESSION['id']);
            $sql->bindValue(':id', $doc['id']);
            $sql->execute();

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;

        }
        return true;

    }

    public static function delete($doc): bool
    {
        try {
            $conn = Connection::getConnection();
            $sql = $conn->prepare("delete from document where id=:id");
            $sql->bindValue(':id', $doc['id'], PDO::PARAM_INT);
            $sql->execute();

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;

        }
        return true;
    }

    public static function insert($doc): bool
    {
        if (empty($doc['title']) or empty($doc['content'])) {
            new Exception("Preencha todos os campos", 1);
            return false;
        }

        $file = trim($doc['filePath']);
        if (empty($file)) {
            $file = "file.doc";
        }

        $image = trim($doc['imagePath']);
        if ($image) {
            $image = "image.png";
        }

        $value = "";
        if (isset($doc['download'])) {
            $value = 'checked';
        }

        try {
            session_start();
            $conn = Connection::getConnection();
            $sql = $conn->prepare("INSERT INTO document (title, subtitle, download ,docdesc, price, filepath, imagepath, content, author) values(:title, :subtitle, :download ,:description, :price, :filePath, :imagePath, :content, :author)");
            $sql->bindValue(':title', trim($doc['title']));
            $sql->bindValue(':subtitle', trim($doc['subtitle']));
            $sql->bindValue(':download', trim($value));
            $sql->bindValue(':description', trim($doc['description']));
            $sql->bindValue(':price', trim($doc['price']));
            $sql->bindValue(':content', trim($doc['content']));

            $sql->bindValue(':filePath', $file);
            $sql->bindValue(':imagePath', $image);

            $sql->bindValue(':author', $_SESSION['id']);

            $sql->execute();

        } catch (Exception  $e) {
            echo $e->getMessage();
            return false;
        }

        return true;

    }
}
