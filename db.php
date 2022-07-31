<?php

class Db
{
    private $connection;
    private static $db;
    private static $DB_NAME = "persian_names";
    private static $TABLE_NAME = "first_names";

    public static function getInstance($option = null)
    {
        if (self::$db == null)
            self::$db = new Db($option);
        return self::$db;
    }

    private function __construct($option = null)
    {
        if ($option != null) {
            $host = $option['host'];
            $user = $option['user'];
            $pass = $option['pass'];
            $name = $option['name'];
        } else {
            global $config;
            $host = 'localhost';
            $user = 'root';
            $pass = '';
            $name = self::$DB_NAME;
        }

        $this->connection = new mysqli($host, $user, $pass, $name);
        if ($this->connection->connect_error)
            die("Connection failed: " . $this->connection->connect_error);

        $this->connection->query("SET NAMES 'utf8'");
    }

    public function createTable()
    {
        $table_name = self::$TABLE_NAME;
        $dropTable = "DROP TABLE IF EXISTS $table_name;";
        $createTable = "CREATE TABLE `$table_name` (
                    `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `is_male` tinyint(1) NOT NULL,
                    `is_female` tinyint(1) NOT NULL,
                    `rarity_level` tinyint(3) unsigned NOT NULL COMMENT '0:por karbord ,1:mamooli, 2:nader',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `name` (`name`),
                    KEY `is_male` (`is_male`),
                    KEY `is_female` (`is_female`),
                    KEY `rarity_level` (`rarity_level`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->connection->query($dropTable);
        $this->connection->query($createTable);
    }

    public function addRow($name, $is_male, $is_female, $rarity_level)
    {
        $this->connection->query($this->getQuery($name, $is_male, $is_female, $rarity_level));
    }

    public function getQuery($name, $is_male, $is_female, $rarity_level)
    {
        $table_name = self::$TABLE_NAME;
        return "INSERT INTO $table_name (name, is_male, is_female, rarity_level) VALUES ('$name', $is_male, $is_female, $rarity_level);";
    }

    public function multiQuery($sql, $data = [])
    {
        $this->startTransAction();

        if (!$this->connection->multi_query($sql))
            die(print_r(["error" => mysqli_error($this->connection), "query" => $sql], true));

        $i = 1;
        while ($this->connection->more_results()) {
            $i++;
            if ($this->connection->next_result() === false)
                die(print_r(["error" => mysqli_error($this->connection), "query" => $sql], true));
        }

        $this->commitTransAction();
        return true;
    }


    public function startTransAction()
    {
        $this->connection->autocommit(false);
    }

    public function rollbackTransAction()
    {
        $this->connection->rollback();
        $this->connection->autocommit(true);
    }

    public function commitTransAction()
    {
        $this->connection->commit();
        $this->connection->autocommit(true);
    }


    public function connection()
    {
        return $this->connection;
    }

    public function close()
    {
        $this->connection->close();
    }

}