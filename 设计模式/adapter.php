<?php
namespace IDatabase;


interface IDatabase{
	function connect($host,$user,$pwd,$dbname);
	function query($sql);
	function close();
}

class MySQL implements IDatabase 
{
    protected $conn;
        function connect($host, $user, $passwd, $dbname)
        {
            $conn = mysql_connect($host, $user, $passwd);
            mysql_select_db($dbname, $conn);
            $this->conn = $conn;
    }
    function query($sql)
        {
            $res = mysql_query($sql, $this->conn);
            return $res;
    }
    function close()
    {
        mysql_close($this->conn);
    }
}


class Mysqli implements IDatabase{
	protected $conn;
	function connect($host, $user,$pwd,$dbname){
		$conn = mysqli_connect($host,$user,$pwd,$dbname);
		$this->conn = $conn;
	}
	function query($sql){
		return mysqli_query($this->conn,$sql);
	}
	function close(){
		mysql_close($this->conn);
	}
}

$mysql = new Mysqli();
$conn = $mysql->connect('localhost','root','root','crm');
$sql = "SELECT * FROM sys_role";
$res = $mysql->query($sql);

var_dump($res);




