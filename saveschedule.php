<?php
//include the database
include 'functions.php';

$sql = $testuser->select('usersmail', '*',[]);
if (!$sql) {
	$testuser->create("usersmail", [
		"id" => [
			"INT",
			"NOT NULL",
			"AUTO_INCREMENT",
			"PRIMARY KEY"
		],
		"name" => [
			"VARCHAR(50)",
			"NOT NULL"
		],
		"email" => [
			"VARCHAR(50)",
			"NOT NULL"
		],
		"created" => [
			"Date",
			"NOT NULL"
		],
		"timefrom" => [
			"VARCHAR(50)",
			"NOT NULL"
		],
		"timeto" => [
			"VARCHAR(50)",
			"NOT NULL"
		],
		"callfrom" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"callto" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"callduration" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"talkduration" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"status" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"drunk" => [
			"VARCHAR(100)",
			"NOT NULL"
		],
		"communicationtype" => [
			"VARCHAR(50)",
			"NOT NULL"
		],
		"pincode" => [
			"int(100)",
			"NOT NULL"
		],
		"number_fuzzy" => [
			"int(20)",
			"NOT NULL"
		],
		"sented" => [
			"date",
			"NOT NULL"
		]
	]);
}
// $data = $testuser->select('usersmail', '*', [
// 	'email'=>$_POST['email']
// ]);
// if($data){
// 	echo "This Email is existing now!";
// 	return;
// }
// else{
	$testuser->insert("usersmail", [
		"name" => $_POST['name'],
		"email" => $_POST['email'],
		"created" => date('y-m-d'),
		"timefrom" => $_POST['timefrom'],
		"timeto" => $_POST['timeto'],
		"callfrom" => $_POST['callfrom'],
		"callto" => $_POST['callto'],
		"callduration" => $_POST['callduration'],
		"talkduration" => $_POST['talkduration'],
		"status" => $_POST['status'],
		"drunk" => $_POST['drunk'],
		"communicationtype" => $_POST['communicationtype'],
		"pincode" => $_POST['pincode'],
		"number_fuzzy" => $_POST['number_fuzzy'],
		"sented" => date('Y-m-d h:i:s'),
	]);	

	echo json_encode("saved infomation Successfully!" );
// }


?>