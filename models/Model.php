<?php
class Model {
	static protected $db=null;

	static public function getTableName() {}

	/**
	 * Example Format of $fields: ['code'=>'%'.$toSearch.'%','name'=>'%'.$toSearch.'%','population'=>'%'.$toSearch.'%','headOfState'=>'%'.$toSearch.'%'];
	 */
	static public function getResults($fields, &$totalRecords, $toSearch, $currPage=1, $rowsPerPage=10000) {
		$tableName=static::getTableName();
		if (is_null(Model::$db))
			Model::$db=Database::connect('localhost','root','','world');
		
		$searchStr="";  $results=[];
		try {
			$sql="";
			// first need to figure out TOTAL NUMBER OF RECORDS for the given query

			// build an expression that searches for target in the listed fields
			$searchStr=""; $fieldTypes=""; $searchArray=[]; $fieldList=[];
			$sql = "SELECT count(*) as totalRecords FROM ".$tableName; $whereClause=[];
			foreach($fields as $key=>$value) {
				if (!is_numeric($key)) {	// i.e. has a key-value pair
					$whereClause[] = $key." LIKE ".":".$key;
					$searchList[] = $value;
					$fieldList[] = $key;
				}
				else
					$fieldList[] = $value;
			}
			if (count($whereClause)>0)
				$sql .= " WHERE ".implode(" OR ", $whereClause);

			$results = Model::$db->query($sql, $fields);
			$totalRecords = $results[0]['totalRecords'];

			// build an expression that searches for target in the listed fields
			$sql = "SELECT ".implode(",", $fieldList)." FROM ".$tableName." ";
			if (count($whereClause)>0)
				$sql .= " WHERE ".implode(" OR ", $whereClause);
			
			$skip = ($rowsPerPage * ($currPage - 1));

			if ($skip >= $totalRecords) {        
				$currPage = ceil($totalRecords / $rowsPerPage);
				$skip = ($rowsPerPage *$currPage - 1);
			}
			$sql .= " LIMIT ".$skip." , ".$rowsPerPage;
	
			$results = Model::$db->query($sql, $fields);
			Model::$db->disconnect();
		}
		catch(Exception $e) {
			Model::$db->disconnect();
			Model::$db=null;
			die($e->getMessage());	// rethrow the exception
		}
		return $results;
	}

}