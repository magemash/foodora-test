<?php

namespace Scottpringle\Console\Model;

/**
 * Class for interacting with the database
 *
 * Class Db
 * @package Scottpringle\Console\Model
 */
class Db
{
    private $host = "localhost";
    private $dbname = "foodora-test";
    private $user = "foodora";
    private $pass = "foodora";
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    function selectAll($table)
    {
        $stmt = $this->db->query("SELECT * FROM ".$table);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    function query($query)
    {
        $stmt = $this->db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function dump($name)
    {
        exec("mysqldump --user={$this->user} --password={$this->pass} --host={$this->host} {$this->dbname} > ./{$name}");
    }

    public function renameTable($old, $new)
    {
        $query = $this->db->prepare("ALTER TABLE {$old} RENAME TO {$new}");
        $query->execute();
    }

    public function cloneTable($new, $old)
    {
        $query = $this->db->prepare("CREATE TABLE {$old} LIKE {$new}");
        $query->execute();
    }

    public function dropTable($table)
    {
        $query = $this->db->prepare("DROP TABLE {$table}");
        $query->execute();
    }
}