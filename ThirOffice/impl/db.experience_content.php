<?php

require_once "util.config.php";

class ExperienceContent
{
    var $servername = null;
    var $username = null;
    var $password = null;
    var $dbname = null;
    var $tablename = "experience_content";
    var $db;

	function __construct()
	{
        $this->servername = Config::$database_servername;
        $this->username = Config::$database_username;
        $this->password = Config::$database_password;
        $this->dbname = Config::$database_dbname;
        $this->db = new PDO("mysql:host=$this->servername;charset=utf8", $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$this->createif();
	}

	function __destruct()
	{
		if( !is_null( $this->db ) )
		{
			$this->db = null;
		}
	}

	function createif()
	{
		if( is_null( $this->db ) )
		{
			return NULL;
		}

        $this->db->exec("CREATE DATABASE IF NOT EXISTS $this->dbname;");

		$this->db->exec(
			"CREATE TABLE IF NOT EXISTS $this->dbname.$this->tablename (
            `id`            int(11) NOT NULL AUTO_INCREMENT,
            `timestamp`     int(11) NOT NULL,
            `author_id`     int(11) NOT NULL,
            `experience_id` int(11) NOT NULL,
            `content`       text NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;"
		);
	}

	function find_by_id($id)
	{
		if( is_null( $this->db ) )
		{
			return NULL;
		}

		return $this->db->query(
			"SELECT * FROM $this->dbname.$this->tablename where `id`=$id;"
		)->fetch(PDO::FETCH_ASSOC);
    }

    function search_all()
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
		}

		return $this->db->query(
			"SELECT * FROM $this->dbname.$this->tablename;"
		);
	}
	
	function find_by_exprence_id($id)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
		}

		return $this->db->query(
			"SELECT * FROM $this->dbname.$this->tablename WHERE `experience_id`=$id ORDER BY `timestamp` DESC;"
		);
	}

	function delete_by_id($id)
	{
		if( !is_null( $this->db ) )
		{
			return $this->db->exec(
				"DELETE FROM $this->dbname.$this->tablename WHERE `id`='$id';"
			);
		}
		return false;
	}

	function insert($timestamp, $author_id, $experience_id, $content)
	{
		if( !is_null( $this->db ) )
		{
            $result = $this->db->prepare( "INSERT INTO $this->dbname.$this->tablename (`timestamp`, `author_id`, `experience_id`, `content`) values (?, ?, ?, ?);",
                                    array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
            $result->execute(array($timestamp, $author_id, $experience_id, $content));
            return $result;
		}
        return FALSE;
    }
}

?>