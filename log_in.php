<?php
	/*
	* Includng (requiring) f.php file with functions necessary for register procces to work
	*/
	require_once("src/php/f.php");

	/*
	* Checking if any user tried to log in
	*/
	if(isset($_POST["username"]) && isset($_POST["password"]))
	{	//User tried to log in

		/*
		* Checking if user exists in database
		* validateUser($usr,$passwd) - from f.php file
		*/
		$zapt = validateUser($_POST["username"],$_POST["password"]);
		if($zapt["veryfied"] == true)
		{	//User exists in database, saving user data into session, user is logged in
			session_start();
			$_SESSION["USER_ID"] = $zapt["User_ID"];
			$_SESSION["USERNAME"] = $zapt["username"];
			unset($zapt);
			unset($_POST["username"]);
			unset($_POST["password"]);
			header("Location: dashboard.php");
		}else
		{	//User doesn't exist in database, redirecting to main page
			unset($zapt);
			unset($_POST["username"]);
			unset($_POST["password"]);
			header("Location: index.php");
		}
	}else
	{	//User didn't try to log in, redirecting to main page
		header("Location: index.php");
	}
?>