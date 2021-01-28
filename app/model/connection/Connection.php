<?php


class Connection
{
    private static $conn;

    public static function getConnection(): PDO
    {
        //Adicionar sua base de dados
//        $dns = 'mysql:dbname=sitedb; host=localhost; ';
//        $user = 'francisco';
//        $password = '!@adminDB1';


        $dns = 'mysql:dbname=epiz_26679661_sitedb;host=sql312.epizy.com;port=3306;';
        $user = 'epiz_26679661';
        $password = 'wQ288gISROz';

        try {
            if (self::$conn == null) {
                self::$conn = new PDO($dns, $user, $password);
                self::$conn->exec("names utf-8");
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return self::$conn;

    }

}
