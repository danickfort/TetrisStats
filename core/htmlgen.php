<?php
/**
*
* @author Danick Fort, Gary Nietlispach
*/
class HTMLGenerator {

	private $selfURL;

	private static $instance;
	
	private function __construct() {
		$this->initVariables();
	}
	
	public function __destruct() {
	
	}
	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new HTMLGenerator();
		}
		return self::$instance;
	}
	
	private function initVariables() {
		$this->selfURL = $_SERVER['PHP_SELF'];
	}
	
	public function loginForm() {
?>
		<div class="logindiv cf">
		<form class="form-horizontal" id="loginform" action="<?php echo $this->selfURL; ?>" method="post" accept-charset="utf-8">
		<fieldset>
		
		<!-- Form Name -->
		<legend>Login</legend>
		
		<!-- Text input-->
		<div class="control-group">
		  <label class="control-label">User</label>
		  <div class="controls">
		    <input id="usermail" name="usermail" type="text" placeholder="e-mail" class="input-xlarge">
		    
		  </div>
		</div>
		
		<!-- Password input-->
		<div class="control-group">
		  <label class="control-label">Password</label>
		  <div class="controls">
		    <input id="password" name="password" type="password" placeholder="password" class="input-xlarge">
		    
		  </div>
		</div>
		
		<!-- Button -->
		<div class="control-group">
		  <label class="control-label"></label>
		  <div class="controls">
		    <button id="submit" name="submit" class="btn btn-info">Login</button>
		  </div>
		</div>
		
		</fieldset>
		</form>
		</div>
<?php
	}
	public function registerForm() {
?>
		
		<div class="logindiv cf">
		<form class="form-horizontal" id="registerform" action="<?php echo $this->selfURL; ?>" method="post" accept-charset="utf-8">
		<fieldset>
		
		<!-- Form Name -->
		<legend>Register</legend>
		
		<!-- Text input-->
		<div class="control-group">
		  <label class="control-label">User</label>
		  <div class="controls">
		    <input id="userReg" name="userReg" type="text" placeholder="e-mail" class="input-xlarge">
		    
		  </div>
		</div>
		
		<!-- Password input-->
		<div class="control-group">
		  <label class="control-label">Password</label>
		  <div class="controls">
		    <input id="passReg" name="passReg" type="password" placeholder="password" class="input-xlarge">
		    
		  </div>
		</div>
		
		<!-- Button -->
		<div class="control-group">
		  <label class="control-label"></label>
		  <div class="controls">
		    <button id="submit" name="submit" class="btn btn-info">Register</button>
		  </div>
		</div>
		
		</fieldset>
		</form>
		</div>
		
		<center><div id="showhideregister"><i class="icon-edit"> </i> Don't have an account?</div></center>
<?php
	}

	public function registered() {
?>
	<pre class="msg">You are registered!</pre>
<?php	
	}
	public function registerError() {
?>
	<pre class="err">Registration error!</pre>
<?php	
	}
	
	public function loggedOut() {
?>
	<pre class="msg">You are logged out!</pre>
<?php	
	}
	
	public function header() {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<title>TetrisStats</title>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="game/libraries/external/jqueryui.css" />
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
        <link rel="stylesheet" href="design.css" />
		<?php $this->headScripts(); ?>	
    </head>
    <body>
	<div id="wrapper">
		<header id="header">
			<img src="images/logotetris.png" />
		</header>
		<div id="middle">
<?php
	}
	
	public function gameWrapper() {
?>
		<center><span id="slideToStats"><i class="icon-chevron-down"></i> Show stats</span></center>
		<section id="game">
		<?php $this->gamePortion(); ?>
		</section>
		
		
		<section id="stats">
		<div id="charts"></div>
		<div id="numberOfGames"><span id="numberOfGamesSpan"></span> games played</div>
		<div id="highScoresPlayer"><table id="tableHighScoresPlayers"><thead><tr><th>Personal bests</th></tr></thead><tbody></tbody></table></div>
		</section>
<?php
	
	}
	
	public function footer() {
?>
		</div>
		<footer id="footer">
			<div id="left">Copyright Danick Fort, Gary Nietlispach @ HES-SO 2013</div>
			<div id="right"></div>
		</footer>
		<?php $this->bodyScripts(); ?>
	</div>
    </body>
</html>
<?php
	}
	
	public function gamePortion() {
?>
			<div id="dialog-help" title="TetrisStats help" style="display:none">
				<p>
					<span class="ui-icon ui-icon-info" style="float: left; margin: 0 7px 20px 0;"></span>
					<b>Clear <span style="font-size:20px">40</span> lines</b> in the shortest time possible!
					<p>
					To <b>clear lines</b>, fill up to 4 rows at a time by moving around falling <i>tetrominos</i>.
					</p>
				</p>
				<h3>Controls</h3>
				<p>
					<b>(← ↓	→)</b> to move the falling piece.
				</p>
				<p>
					<b>Space</b> to make the piece fall instantly.
				</p>
				<p>
					<b>C</b> to hold the current falling piece in the chest. Press the key again to replace the current piece by the held piece.
				</p>
			</div>
			<div id="buttons">
				<button id="newgame" onclick="newGame();" class="btn btn-danger">
					New Game
				</button>
				<!-- DEBUG REFRESH -->
				<button onclick="refreshScores('Debug refresh');" class="btn btn-action">
					Refresh scores
				</button>
				<button onclick="location.href='index.php?logout='" class="btn btn-action">
					Logout
				</button>
				<button onclick="displayHelp();" class="btn btn-info">
					Help
				</button>
			</div>
			<div id="container">
				<canvas width="400" height="600">Please update your browser</canvas>
			</div>
				<div id="scores">
					<table border="1" id="scoresTable"  style="border-collapse:collapse; border-style:hidden;" width="100%" cellpadding="3" cellspacing="3">
					<thead>
					<tr>
						<th colspan="2">GLOBAL HIGH SCORES</th>
					</tr>
					<tr>
						<th>Name</th>
						<th>Time</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
					</table>
					
					<p id="refreshed"></p>
					
					<table id="livestatstable" border="0" style="background-color:#FFFFCC" width="100%" cellpadding="3" cellspacing="3">
						<tr>
							<th colspan="2">Statistics</th>
						<tr>
							<td>Total cleared</td>
							<td><span id="total">0</span></td>
						</tr>
						<tr>
							<td>Double</td>
							<td><span id="double">0</span></td>
						</tr>
						<tr>
							<td>Triple</td>
							<td><span id="triple">0</span></td>
						</tr>
						<tr>
							<td>Tetris</td>
							<td><span id="tetris">0</span></td>
						</tr>
					</table>
				</div>
<?php
	}
	
	public function headScripts() {
?>
		<script src="game/libraries/external/jquery.js"></script>
		<script type="text/javascript">var j$ = jQuery.noConflict();</script>
		<script type="text/javascript" src="charts/highcharts/highcharts.js"></script>
		
		<script src="game/libraries/external/jqueryui/ui/jquery-ui.js"></script>
		<script src="dom_scripts.js"></script>
<?php
	
	}
	
	public function bodyScripts() {
?>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="game/libraries/homemade/tools.js"></script>
		
		<script src="game/engine/chrono.js"></script>
		<script src="game/engine/shapeGen.js"></script>
		<script src="game/engine/constants.js"></script>
		<script src="game/engine/tetris.js"></script>
		<script src="game/engine/controls/keycontrol.js"></script>
<?php
	}
	
}
?>
