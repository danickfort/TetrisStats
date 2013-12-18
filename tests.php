<?php
require_once('simpletest/autorun.php');
require_once('core/login.php');
require_once('core/database.php');
 

/**
*	Test class. Uses SimpleTest 1.1.0 @ http://www.simpletest.org/
* @author Danick Fort, Gary Nietlispach
*/
class DatabaseTests extends UnitTestCase
{
	private $loginManager;
	private $databaseConnection;
	//----TOOLS----
	function registerAccount($user,$pass)
	{
		return $this->loginManager->register($user,$pass);
	}
	
	function connectToAccount($user,$pass)
	{
		return $this->loginManager->connect($user, $pass);
	}
	
	function deleteAccount($user)
	{
		$query = "DELETE FROM ts_users_stats WHERE user IN (SELECT id FROM ts_users WHERE name='$user');";
		$this->databaseConnection->query($query);
		$query = "DELETE FROM ts_users WHERE name='$user';";
		$this->databaseConnection->query($query);
	}

	function setUp()
	{
		$this->databaseConnection = DataBaseManager::getInstance();
		$this->loginManager = LoginManager::getInstance($this->databaseConnection->getDatabaseConnection());
	}
	
	//----TESTS----
	function testBasicLogin()
	{
		
		// Test with known user in database
		$this->assertTrue(($this->loginManager->connect('Gary@Tetris.com', 'prout')) == 1);
		// Test with non-existant user in database
		$this->assertFalse(($this->loginManager->connect('Gary@Tetuis.com', 'prtut')) == 1);
	}
	
	function testRegisterAndLogin()
	{
		$user = 'user';
		$pass = 'pass';
	
		// Make sure account doesnt exist before testing registration
		$this->deleteAccount($user);
		
		$this->assertTrue($this->registerAccount($user,$pass));
		$this->assertTrue($this->connectToAccount($user,$pass));
		
		// Test with already registered username (usernames are UNIQUE in user's table)
		$this->assertFalse($this->registerAccount($user,$pass));
	}
	
	
}

?>