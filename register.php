<?php
	/*
	* Includng (requiring) f.php file with functions necessary for register procces to work
	*/
	require_once("src/php/f.php");

	/*
	* Checking if any user tried to register
	*/
	if(isset($_POST["username_r"]) && isset($_POST["password_r"]))
	{	//User tried to regsiter

		/*
		* Adding new user to a database
		* registerUser($usr,$passwd) - from f.php file
		*/
		$zapt = registerUser($_POST["username_r"],$_POST["password_r"]);

		/*
		*	Checking if adding new user to a database was a success
		*/
		if($zapt[0] == false)
		{//Adding new success wasn't successful, redirecting to main page
			header("Location: index.php");
		}else
		{//Adding new success was successful
			for($i = 0; $i < count($zapt); $i++)
			{
				/*
				* Checking if all queries were successful
				*/
				if($zapt[0][$i] == false)
				//They weren't, redirecting to a main page
					header("Location: index.php");
			}
			session_start();

			/*
			*	Saving user data into session, now new user is logged in
			*/
			$_SESSION["USER_ID"] = $zapt[1]["User_ID"];
			$_SESSION["USERNAME"] = $_POST["username_r"];
			unset($zapt);
			unset($_POST["username_r"]);
			unset($_POST["password_r"]);
			header("Location: dashboard.php");
		}
	}else
	{//User didn't try to regsiter, redirecting to main page
		header("Location: index.php");
	}
?>