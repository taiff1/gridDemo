<?php
// required headers

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../models/Country.php');

//die(print_r($_POST).'<br/>'.print_r($_GET));

if(isset($_POST["pq_curpage"]) && isset($_POST["pq_rpp"]) ) {
//die("AAAAAAAA");	
    $currPage = $_POST["pq_curpage"];
    $rowsPerPage=$_POST["pq_rpp"];
	$toSearch= isset($_POST["toSearch"])?$_POST["toSearch"]:"";
    $fields=['code'=>'%'.$toSearch.'%','name'=>'%'.$toSearch.'%','population'=>'%'.$toSearch.'%','headOfState'=>'%'.$toSearch.'%'];
	$result=null;
	try {
		$data=Country::getResults($fields, $totalRecords, $toSearch, $currPage, $rowsPerPage);
		$result = ['totalRecords'=>$totalRecords, 'currPage'=>$currPage, 'data'=>$data];
	}
	catch (Exception $e) {
		
	}
	http_response_code(200);
    echo json_encode($result);	
}
