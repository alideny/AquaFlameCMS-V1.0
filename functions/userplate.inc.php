<?php
class Userplate
{
	private $connection;
	private $userID;
	private $queryResult;
	
	public function __construct()
	{
		require_once("configs.php");
		global $server_adb,$server_cdb,$serveraddress,$serveruser,$serverpass;
		$this->connection = mysql_connect($serveraddress,$serveruser,$serverpass) or die(musql_error());
		mysql_select_db($server_adb,$this->connection);
		$this->userID = mysql_result(mysql_query("SELECT id FROM `account` WHERE username = '".mysql_real_escape_string($_SESSION['username'])."';",$this->connection),0);
		mysql_select_db($server_cdb,$this->connection);
		$this->_executeQuery();
	}
	
	public function __destruct()
	{
		mysql_free_result($this->queryResult);
		mysql_close($this->connection);
	}
	
	private function _executeQuery()
	{
		$this->queryResult = mysql_query("SELECT name,race,class,gender,level FROM `characters` WHERE account=".$this->userID,$this->connection);
	}
	
	public function getChars()
	{
		$result = array();
		for($i = 0;$i < mysql_num_rows($this->queryResult); $i++)
		{
			$row = mysql_fetch_object($this->queryResult);
			$obj = new Userplate_Character();
			$obj->setClass($row->class)
				->setLevel($row->level)
				->setName($row->name)
				->setGender($row->gender)
				->setRace($row->race);
			$result[] = $obj;
			mysql_data_seek($this->queryResult,$i);
		}
		return $result;
	}
	
	public function getNumChars()
	{
		return mysql_num_rows($this->queryResult);
		//var_dump($this->queryResult);
	}
}
class Userplate_Character
{
	private $Name;
	private $Gender;
	private $Race;
	private $Class;
	private $Level;
	
	public function getLevel()
	{
		return $this->Level;
	}
	
	public function setLevel($level)
	{
		$this->Level = (int) $level;
		return $this;
	}
	
	public function getName()
	{
		return $this->Name;
	}
	
	public function setName($name)
	{
		$this->Name = (string) $name;
		return $this;
	}
	
	public function getRace()
	{
		return $this->Race;
	}
	
	public function setRace($race)
	{
		$this->Race = (int) $race;
		return $this;
	}
	
	public function getGender()
	{
		return $this->Gender;
	}
	
	public function setGender($gender)
	{
		$this->Gender = (int) $gender;
		return $this;
	}
	
	public function getClass()
	{
		return $this->Class;
	}
	
	public function setClass($class)
	{
		$this->Class = (int) $class;
		return $this;
	}
}

function checkClass($classId)
{
	switch($classId)
	{
		case 1:
			return "Warrior";
			break;
		case 2:
			return "Paladin";
			break;
		case 3:
			return "Hunter";
			break;
		case 4:
			return "Rouge";
			break;
		case 5:
			return "Priest";
			break;
		case 6:
			return "Death Knight";
			break;
		case 7:
			return "Shaman";
			break;
		case 8:
			return "Mage";
			break;
		case 9:
			return "Warlock";
			break;
		case 10:
			return "Druid";
			break;
	}
}
?>