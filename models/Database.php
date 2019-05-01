<?php

class Database {
	private $dbh=null;
	private function __construct($hostName, $user, $password, $dbName) {
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		$this->dbh = new PDO('mysql:host='.$hostName.';dbname='.$dbName, $user, $password, $options);
	}
	
	static public function connect($hostName, $user, $password, $dbName) {
		return new Database($hostName, $user, $password, $dbName);
	}
	
	public function disconnect() {
		$this->dbh=null;
	}
	
	public function query($sql, $bindFields=[]) {
		$stmt = $this->dbh->prepare($sql);
		$bindings=[];
		foreach($bindFields as $key=>$value)
			if (!is_numeric($key)) {
				$stmt->bindValue(':'.$key, $value, PDO::PARAM_STR);
				$bindings[] = " :".$key."  => ".$value;
			}
		$stmt->execute();
		$result=[];
		try {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
		finally {
			$stmt=null;
		}
		return $result;
	}
}