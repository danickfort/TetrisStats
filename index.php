<?php

session_start();

require("core/htmlgen.php");
require("core/database.php");
require("core/login.php");
require("core/chart.php");

$HTMLGen = HTMLGenerator::getInstance();
$databaseManager = DatabaseManager::getInstance();
$loginManager = LoginManager::getInstance($databaseManager->getDatabaseConnection());
$chartManager = null;
//$chartManager = new ChartManager($_SESSION['userId'],$databaseManager->getDatabaseConnection());

if (isset($_SESSION['userId']))
{
	$chartManager = new ChartManager($_SESSION['userId'],$databaseManager->getDatabaseConnection());	
}

if (isset($_GET['getLineChart']))
{
	if (isset($_SESSION['userId']))
	{
		die($chartManager->getLineChart());
	}
	else {
		die('');
	}
}

if (isset($_GET['getPieChart']))
{
	if (isset($_SESSION['userId']))
	{
		die($chartManager->getPieChart());
	}
	else {
		die('');
	}
}


if (isset($_POST['getUserName']))
{
	if (isset($_SESSION['user'])) die($_SESSION['user']);
	else die('');
}
if (isset($_SESSION['user'])) {
	if(isset($_GET['refreshHighScores']))
	{
		$return_array = Array();

		$query = "SELECT ts_users.name, ts_times.time FROM ts_times,ts_users WHERE ts_times.user = ts_users.id ORDER BY time ASC LIMIT 10";
		$res = $databaseManager->query($query);
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
			array_push($return_array,$row);
		}
		
		die(json_encode($return_array));
		
	}
	else if(isset($_GET['getPlayerHighscoresJSON']))
	{
		$return_array = Array();
		$userId = $_SESSION['userId'];

		$query = "SELECT ts_times.time FROM ts_times,ts_users WHERE ts_times.user = ts_users.id AND ts_users.id = $userId ORDER BY time ASC LIMIT 10";
		$res = $databaseManager->query($query);
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
			array_push($return_array,$row);
		}
		
		die(json_encode($return_array));
		
	}
	else if(isset($_GET['getNumberOfGamesPlayed']))
	{ 
		$userId = $_SESSION['userId'];

		$query = "SELECT COUNT(1) AS c FROM ts_times,ts_users WHERE ts_times.user = ts_users.id AND ts_users.id = $userId";
		$res = $databaseManager->query($query);
		$row = mysql_fetch_array($res, MYSQL_ASSOC);
		
		die($row['c']);
	}
}
$HTMLGen->header();

if (isset($_SESSION['user'])) {
	if(isset($_POST['time']))
	{
		//** A METTRE DANS CLASSE HIGHSCORE MANAGING!
		$time = $_POST['time'];
		$userId = $_SESSION['userId'];
		$query = "INSERT INTO ts_times VALUES ('', '$userId', '$time', CURRENT_TIMESTAMP)";
		if (!$databaseManager->query($query)) die('Error while posting time!' . $HTMLGen->footer());
		//**
	}

	if(isset($_GET['logout'])) {
		$loginManager->logout();
		$HTMLGen->loggedOut();
		$HTMLGen->loginForm();
		$HTMLGen->registerForm();
	}
	else {
		$HTMLGen->gameWrapper();

	}
	if(isset($_POST['tetrises']))
	{
		$tetrises = $_POST['tetrises'];
		$triples = $_POST['triples'];
		$doubles = $_POST['doubles'];
		$lines = $_POST['lines'];
		
		$query = "UPDATE ts_users_stats SET tetrises = tetrises + $tetrises, triples = triples + $triples, doubles = doubles + $doubles, ts_users_stats.lines = ts_users_stats.lines + $lines  WHERE user = " . $_SESSION['userId'];
		if (!$databaseManager->query($query)) die('Error while posting stats!' . $HTMLGen->footer());
		echo $query;
	}
}
else {
	if (isset($_POST['usermail']) && isset($_POST['password'])) {
		if($loginManager->connect($_POST['usermail'], $_POST['password'])) {
			$HTMLGen->gameWrapper();
		}
		else {
			$HTMLGen->loginForm();
			$HTMLGen->registerForm();
		}
	}
	else if(isset($_POST['userReg']) && isset($_POST['passReg'])) {
		$userReg = $_POST['userReg'];
		$passReg = $_POST['passReg'];
		if (!$loginManager->register($userReg,$passReg))
			{
			$HTMLGen->registerError();
			$HTMLGen->loginForm();
			$HTMLGen->registerForm();
		}
		
		else {
		$HTMLGen->registered();
		$HTMLGen->loginForm();
		$HTMLGen->registerForm();
		}
	}
	else {
		$HTMLGen->loginForm();
		$HTMLGen->registerForm();
	}
}

$HTMLGen->footer();

?>

