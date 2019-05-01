<?php

require_once('Database.php');
require_once('Model.php');

class Country extends Model {
	static public function getTableName() {
		return "country";
	}


}