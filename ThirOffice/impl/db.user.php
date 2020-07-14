<?php

require_once "util.config.php";

class User
{
    var $servername = null;
    var $username = null;
    var $password = null;
    var $dbname = null;
    var $tablename = "user";
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
			`id`         INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name`       VARCHAR(128) NOT NULL,
            `pswd`       VARCHAR(128) NOT NULL,
            `group`      VARCHAR(128) NOT NULL,
            `permission` TEXT,
            UNIQUE KEY `name_idx` (`name`)
            ) CHARSET=utf8;"
		);
	}

	function find_all()
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
		}

        $result = $this->db->prepare( "SELECT * FROM $this->dbname.$this->tablename;",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute();
        return $result;
	}

	function find_by_id($id)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
		}

        $result = $this->db->prepare( "SELECT * FROM $this->dbname.$this->tablename WHERE `id`=?;",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($id));
        if( $result->rowCount() <= 0 )
        {
            return FALSE;
        }
        return $result->fetch(PDO::FETCH_ASSOC);
	}

	function find_by_name($name)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
		}

        $result = $this->db->prepare( "SELECT * FROM $this->dbname.$this->tablename WHERE `name`=?;",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($name));
        if( $result->rowCount() <= 0 )
        {
            return FALSE;
        }
        return $result->fetch(PDO::FETCH_ASSOC);
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

	function insert($name, $group, $permission)
	{
		if( !is_null( $this->db ) )
		{
            $result = $this->db->prepare( "INSERT INTO $this->dbname.$this->tablename (`name`, `pswd`, `group`, `permission`) values (?, ?, ?, ?);",
                            array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
			return $result->execute(array($name, sha1("123456"), $group, $permission));
		}
        return false;
    }

    function update($id, $name, $group, $permission)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "UPDATE $this->dbname.$this->tablename SET `name`=?, `group`=?, `permission`=? where `id`=?;",
                                        array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array($name, $group, $permission, $id));
        return $result;
    }

    function reset_password($id)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "UPDATE $this->dbname.$this->tablename SET `pswd`=? where `id`=?;",
                                        array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array(sha1("123456"), $id));
        return $result;
    }

    function change_password($id, $pswd)
	{
		if( is_null( $this->db ) )
		{
			return FALSE;
        }

        $result = $this->db->prepare( "UPDATE $this->dbname.$this->tablename SET `pswd`=? where `id`=?;",
                                        array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) );
        $result->execute(array(sha1($pswd), $id));
        return $result;
    }
}


?>