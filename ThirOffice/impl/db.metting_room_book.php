
<?php

require_once "util.config.php";

class MettingRoomBook
{
    var $servername = null;
    var $username = null;
    var $password = null;
    var $dbname = null;
    var $tablename = "metting_room";
    var $db;

	function __construct()
	{
        $this->servername = Config::$database_servername;
        $this->username = Config::$database_username;
        $this->password = Config::$database_password;
        $this->dbname = Config::$database_dbname;
        $this->db = new PDO("mysql:host=$this->servername", $this->username, $this->password);
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
			`id`        INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name`      VARCHAR(128) NOT NULL,
            `room`      INTEGER NOT NULL,
            `from`      INTEGER NOT NULL,
            `to`        INTEGER NOT NULL
            ) CHARSET=utf8;"
		);
	}

	function checkvailid($room, $from, $to)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "SELECT * FROM $this->dbname.$this->tablename WHERE `room`=? AND `from`<=? AND `to`>=? ;",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($room, $to, $from));
        if( $result == NULL || $result == FALSE ) {
            return FALSE;
        }
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return ($row == FALSE);
    }

    function getbooked($room, $from, $to)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "SELECT * FROM $this->dbname.$this->tablename WHERE `room`=? AND `from`<=? AND `to`>=?;",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($room, $from, $to));
        return $result;
    }

	function find_by_id($id)
	{
		if( is_null( $this->db ) )
		{
			return NULL;
		}

		return $this->db->query(
			"SELECT * FROM $this->dbname.$this->tablename where id=$id;"
		)->fetchArray(SQLITE3_ASSOC);
	}

	function delete_by_id($id)
	{
		if( !is_null( $this->db ) )
		{
			return $this->db->exec(
				"DELETE FROM $this->dbname.$this->tablename where id=$id;"
			);
		}
		return false;
	}

	function insert($room, $name, $from, $to)
	{
		if( !is_null( $this->db ) )
		{
            $result = $this->db->prepare( "INSERT INTO $this->dbname.$this->tablename (`room`, `name`, `from`, `to`) values (?, ?, ?, ?);",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
			return $result->execute(array($room, $name, $from, $to));
		}
        return false;
    }
}

?>