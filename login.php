<?php
session_start();
require 'vendor/autoload.php';
require 'connected.php';
 
use Parse\ParseQuery;
use Parse\ParseObject;

if($_POST){
	$login = new ParseQuery("user");
	$login->equalTo('username', $_POST['user']);
	$login = $login->find();
	if(!empty($login)&&$login[0]->get('password')==sha1($_POST['pwd'])){
		$_SESSION['name'] = $login[0]->get('name');
		$_SESSION['news'] = $login[0]->get('news');
		$_SESSION['privilege'] = $login[0]->get('privilege');
		header( "location: ./news.php" );
 		exit(0);
	}else{
		echo 'Incorrect username or password';
	}
}elseif(isset($_SESSION['news'])&&$_SESSION['news']){
	header( "location: ./news.php" );
	exit(0);
}else{
?>
<form name="myForm" action="login.php" method="POST" enctype="multipart/form-data">
Username : <input type='username' name='user'><br/>
Password : <input type='password' name='pwd'><br/>
<input type='submit' value='Login'>
</form>
<?php
}
?>