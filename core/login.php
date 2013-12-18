<?php
/**
*
* @author Danick Fort, Gary Nietlispach
*/

class loginManager {

   private $_id;
   private $databaseConnection;
   private $userId;

	private static $instance;
	
	private function __construct($databaseConnection) {
		$this->databaseConnection = $databaseConnection;
	}
	
	public function __destruct() {
	
	}
	
	public static function getInstance($databaseConnection) {
		if (is_null(self::$instance)) {
			self::$instance = new loginManager($databaseConnection);
		}
		return self::$instance;
	}
	
		
   public function logout() {
   unset($_SESSION["user"]);
   unset($_SESSION['userId']);
   unset($this->_id);
   }
   
   private function encrypt_decrypt($action, $string) {
	   $output = false;

	   $key = 'My strong random secret key';

	   // initialization vector 
	   $iv = md5(md5($key));

	   if( $action == 'encrypt' ) {
		   $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
		   $output = base64_encode($output);
	   }
	   else if( $action == 'decrypt' ){
		   $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
		   $output = rtrim($output, "");
	   }
	   return $output;
	}
   
   public function connect($id, $pw) {
      if ($this->verify_user($id, $pw)) {
         $this->_id = $id;

         $_SESSION["user"] = $id;
	 $_SESSION['userId'] = $this->userId;

         return 1;
      }

      return 0;
   }

    private function verify_user($id,$pw) {
		$secureId = stripslashes(mysql_real_escape_string($id));
		$securePassword = stripslashes(mysql_real_escape_string($pw));
        $query = "SELECT id FROM ts_users WHERE name='$secureId' AND password='$securePassword'";
		
        $result = mysql_query($query, $this->databaseConnection);
        $row = mysql_fetch_array( $result );
		
		if (mysql_num_rows($result) > 0) {
			$this->userId = $row[0];
			return 1;
		}
		else return 0;
		}  

   public function has_logon() {
      if (isset($_SESSION["user"])) {
         $this->_id = $_SESSION["user"];
         return 1;
      }

      return 0;
   }
   
   public function register($userReg,$passReg) {
		$secureId = stripslashes(mysql_real_escape_string($userReg));
		$securePassword = stripslashes(mysql_real_escape_string($passReg));
		$query = "INSERT INTO ts_users VALUES ('', '$secureId', '', '$securePassword')";
		return mysql_query($query, $this->databaseConnection);
		}

   public function get_id() { return $this->_id; }
   
   public function getUserId() { return $this->userId; }
}

?>