<?php
/**
*
* @author Danick Fort, Gary Nietlispach
*/

class DatabaseManager {
	
	// !! EDIT THESE VARIABLES !!
	private $databaseName = 'tetrisstats';			// DATABASE NAME
	private $databaseHost = 'localhost';	// MYSQL HOST
	private $databaseUsername = 'root';		// MYSQL USERNAME
	private $databasePassword = 'from4ge';	// MYSQL PASSWORD
	
	private $databaseConnection;
	
	private static $instance;
	
	private function __construct() {
		$this->connectToDatabase();
		$this->selectDb();
	}
	
	public function __destruct() {
		$this->disconnectDatabase();
	}
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new DatabaseManager();
		}
		return self::$instance;
	}
	
	private function connectToDatabase() {
		$this->databaseConnection = mysql_connect($this->databaseHost,
				$this->databaseUsername,
				$this->databasePassword);
				
		if (!$this->databaseConnection) {
			echo 'Impossible de se connecter à la base de données (mysql_connect) !';
		}
	}
	
	private function selectDb() {
		$databaseSelect = mysql_select_db($this->databaseName,
				$this->databaseConnection);
		
		if (!$databaseSelect) {
			echo 'Impossible de sélectionner la base de données (mysql_select_db) !';
		}
	}
	
	private function disconnectDatabase() {
		mysql_close($this->databaseConnection);
	}
	
	public function query($query) {
		return mysql_query($query);
	}
	
	public function getDatabaseConnection() {
		if(!$this->databaseConnection) die('Can\'t return database connection from manager !');
		else return $this->databaseConnection;
	}
}
?>
