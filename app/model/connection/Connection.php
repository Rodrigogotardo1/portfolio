<?php


class Connection
{
    private static $conn;

    public static function getConnection(): PDO
    {
//        $dns = 'mysql:dbname=sitedb; host=localhost;charset=utf8;';
//        $user = 'francisco';
//        $password = '!@adminDB1';


        $dns = 'mysql:dbname=epiz_26679661_sitedb;host=sql312.epizy.com;port=3306;charset=utf8;';
        $user = 'epiz_26679661';
        $password = 'wQ288gISROz';

        try {
            if (self::$conn == null) {
                self::$conn = new PDO($dns, $user, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->exec();
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return self::$conn;

    }

}
