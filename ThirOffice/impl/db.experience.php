<?php

require_once "util.config.php";

class Experience
{
    var $servername = null;
    var $username = null;
    var $password = null;
    var $dbname = null;
    var $tablename = "experience";
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
            `id`                int(11) NOT NULL AUTO_INCREMENT,
            `timestamp`         int(11) NOT NULL,
            `title`             varchar(128) NOT NULL,
            `keyword`           varchar(256) NOT NULL,
			`prefer_content_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            FULLTEXT KEY `experience_doc_title_keyword` (`title`,`keyword`) WITH PARSER ngram
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;"
		);
	}

    function update($id, $timestamp, $title, $keyword)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "UPDATE $this->dbname.$this->tablename SET `timestamp`=?, `title`=?, `keyword`=? where `id`=?;",
                                        array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($timestamp, $title, $keyword, $id));
        return $result;
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
   
    function search($keyword)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare(
            "SELECT * FROM $this->dbname.$this->tablename WHERE MATCH(title, keyword) AGAINST (? IN BOOLEAN MODE);",
            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($keyword));
        return $result;
    }

    function title_like($keyword)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }
        
        $result = $this->db->prepare(
            "SELECT * FROM $this->dbname.$this->tablename WHERE title LIKE ?;",
            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array("%".$keyword."%"));
        return $result;
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

	function delete_by_id($id)
	{
		if( !is_null( $this->db ) )
		{
			return $this->db->exec(
				"DELETE FROM $this->dbname.$this->tablename where `id`='$id';"
			);
		}
		return false;
	}

	function insert($timestamp, $title, $keyword)
	{
		if( !is_null( $this->db ) )
		{
            $result = $this->db->prepare( "INSERT INTO $this->dbname.$this->tablename (`timestamp`, `title`, `keyword`) values (?, ?, ?);",
                                    array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
            $result->execute(array($timestamp, $title, $keyword));
            return $result;
		}
        return false;
	}

	function get_last_id()
	{
		if( !is_null( $this->db ) )
		{
            $result = $this->db->query( "SELECT LAST_INSERT_ID() as lid;" )->fetch(PDO::FETCH_ASSOC);
			if( $result == FALSE ){
				return FALSE;
			}
            return $result["lid"];
		}
        return FALSE;
    }
}

?>