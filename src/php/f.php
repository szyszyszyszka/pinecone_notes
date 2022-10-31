<?php
	/*
	* Including a Crypto class from php-encryption library
	*/
	require_once("defuse-crypto.phar");
	use Defuse\Crypto\Crypto;
	
	/*
	* Including login cridentials for database
	*/
	require_once("././cridentials/login_cridentials.php");
	
	/*
	* Checking if user exists in a database
	* If it does, returns its ID, username and veryfication status in an associative array
	* If it doesn't - returns only veryfication status in an associative array
	* Function arguments: $usr - username, $passwd - users password
	*/
	function validateUser($usr,$passwd)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$usr = mysqli_real_escape_string($connection,$usr);
		$passwd = mysqli_real_escape_string($connection,$passwd);
		$query = mysqli_query($connection,'SELECT users.User_ID, users.username, users_passwd.passwd FROM users INNER JOIN users_passwd ON users_passwd.User_ID = users.User_ID WHERE users.username ="'.$usr.'" LIMIT 1;');
		$result = mysqli_fetch_assoc($query);
		mysqli_free_result($query);
		mysqli_close($connection);
		if($result != NULL)
		{
			$veryfication = password_verify($passwd,$result["passwd"]);
			if($veryfication == true)
			{
				$data = Array("User_ID" => $result["User_ID"], "username" => $usr,"veryfied" => $veryfication);
				unset($result);
				return $data;
			}else
			{
				unset($result);
				$data = Array("veryfied" => $veryfication);
				return $data;
			}
		}else
		{
			unset($result);
			$data = Array("veryfied" => false);
			return $data;
		}
	}

	/*
	* Adding new user to a database
	* First, it checks if user with given cridentials already exists in database
	* If it does, function returns 'false' as a field of numeric array
	* If it dosen't, function performs a query which adds user to a databasde and then returns - numeric array of each query success status, associative array with new User_ID - in numeric array
	* Function arguments: $usr - username, $passwd - users password
	*/
	function registerUser($usr,$passwd)
	{
		$connection_one = mysqli_connect(HOST,USER,PASSWD,DB);
		$connection_two = mysqli_connect(HOST,USER,PASSWD,DB);
		$usr = mysqli_real_escape_string($connection_one,$usr);
		$query_check = mysqli_query($connection_two,'SELECT users.User_ID, users.username FROM users WHERE username = "'.$usr.'" LIMIT 1;');
		$result_check = mysqli_fetch_assoc($query_check);
		mysqli_free_result($query_check);
		if($usr != $result_check["username"])
		{
			$query_one = mysqli_query($connection_one,'INSERT INTO users(username) VALUES ("'.$usr.'");');
			$query_two = mysqli_query($connection_two,'SELECT users.User_ID, users.username FROM users WHERE username = "'.$usr.'" LIMIT 1;');
			$result_two = mysqli_fetch_assoc($query_two);
			mysqli_free_result($query_two);
			mysqli_close($connection_two);
			$passwd = mysqli_real_escape_string($connection_one,$passwd);
			$passwd_h = password_hash($passwd,PASSWORD_DEFAULT);
			$query_one_second = mysqli_query($connection_one,'INSERT INTO users_passwd(User_ID, passwd) VALUES ("'.$result_two["User_ID"].'","'.$passwd_h.'");');
			mysqli_close($connection_one);
			$success_status = Array ($query_one, $query_two, $query_one_second);
			$ld = Array ("User_ID" => $result_two["User_ID"]);
			$dr = Array($success_status,$ld);
			return $dr;
		}else
		{
			mysqli_close($connection_one);
			mysqli_close($connection_two);
			$dr = Array(false);
			return $dr;
		}
	}

	/*
	* Getting user categories from a database
	* Returns a numeric array containg catgories for user with specified ID
	* Function arguments: $usr_id - unique user ID
	*/
	function getUserCategories($usr_id)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$query = mysqli_query($connection,'SELECT notes_category.Category_ID, notes_category.category FROM notes_category WHERE User_ID ='.$usr_id.';');
		$rows_number = mysqli_num_rows($query);
		$categories = Array();
		for($i = 0; $i < $rows_number; $i++)
			$categories[$i] = mysqli_fetch_assoc($query);
		mysqli_free_result($query);
		mysqli_close($connection);
		return $categories;
	}

	/*
	* Getting user notes from a database and decrypting them
	* Returns a numeric array containg associative array with unique note IDs, notes categories and notes themself
	* Function arguments: $usr_id - unique user ID
	*/
	function getUserNotes($usr_id)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$query = mysqli_query($connection,'SELECT notes_content.Note_ID, notes_content.Category_ID, notes_content.User_ID, notes_content.note FROM notes_content WHERE notes_content.User_ID = '.$usr_id.';');
		$rows_number = mysqli_num_rows($query);
		$notes = Array();
		for($i = 0; $i < $rows_number; $i++)
			$notes[$i] = mysqli_fetch_assoc($query);
		mysqli_free_result($query);
		mysqli_close($connection);
		for($i = 0; $i < count($notes); $i++)
			$notes[$i]["note"] = Crypto::decryptWithPassword($notes[$i]["note"], ENCR_PASSWD);
		return $notes;
	}

	/*
	* Encrypting new note and inserting it into database
	* Returns a success status of the query
	* Function arguments: $usr_id - unique user ID, $cat - name of the category, $n - note content
	*/
	function addingNote($usr_id, $cat, $n)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$cat = mysqli_real_escape_string($connection,$cat);
		$n = mysqli_real_escape_string($connection,$n);
		$n = Crypto::encryptWithPassword($n, ENCR_PASSWD);
		$query_one = mysqli_query($connection,'SELECT notes_category.Category_ID, notes_category.category, notes_category.User_ID FROM notes_category WHERE category = "'.$cat.'" AND User_ID = "'.$usr_id.'" LIMIT 1;');
		$result_one = mysqli_fetch_assoc($query_one);
		$query_two = mysqli_query($connection,'INSERT INTO notes_content(Category_ID, User_ID, note) VALUES ('.$result_one["Category_ID"].','.$usr_id.',"'.$n.'");');
		mysqli_close($connection);
		return $query_two;
	}

	/*
	* Inserting new category into database
	* Returns a success status of the query
	* Function arguments: $usr_id - unique user ID, $c
	*/
	function addingCategory($usr_id, $c)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$c = mysqli_real_escape_string($connection,$c);
		$query = mysqli_query($connection,'INSERT INTO notes_category(User_ID, category) VALUES ('.$usr_id.',"'.$c.'");');
		mysqli_close($connection);
		return $query;
	}

	/*
	* Encrypting new content and updating existing note
	* Returns a success status of the query
	* Function arguments: $usr_id - unique user ID, $n - new content, $note_id - unique note ID
	*/
	function updatingNote($usr_id, $n, $note_id)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$n = mysqli_real_escape_string($connection,$n);
		$n = Crypto::encryptWithPassword($n, ENCR_PASSWD);
		$query = mysqli_query($connection,'UPDATE notes_content SET note ="'.$n.'" WHERE Note_ID ='.$note_id.' AND User_ID ='.$usr_id.';');
		mysqli_close($connection);
		return $query;
	}

	/*
	* Deleting existing note
	* Returns a success status of the query
	* Function arguments: $usr_id - unique user ID, $note_id - unique note ID
	*/
	function deletingNote($usr_id, $note_id)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$query = mysqli_query($connection,'DELETE FROM notes_content WHERE notes_content.Note_ID ='.$note_id.';');
		mysqli_close($connection);
		return $query;
	}

	/*
	* Deleting existing category
	* Based on the category name it finds unique category ID tied to specific user
	* Returns a success status of the query
	* Function arguments: $usr_id - unique user ID, $c - name of the category
	*/
	function deleteCateory($usr_id, $c)
	{
		$connection_main = mysqli_connect(HOST,USER,PASSWD,DB);
		$query_one = mysqli_query($connection_main,'SELECT Category_ID FROM notes_category WHERE category ="'.$c.'" AND User_ID ='.$usr_id.';');
		$result_one = mysqli_fetch_assoc($query_one);
		mysqli_free_result($query_one);
		$query_two = mysqli_query($connection_main,'DELETE FROM notes_category WHERE notes_category.User_ID ='.$usr_id.' AND notes_category.category ="'.$c.'";');
		mysqli_close($connection_main);
		$connection_second = mysqli_connect(HOST,USER,PASSWD,DB);
		$query_three = mysqli_query($connection_second,'DELETE FROM notes_content WHERE notes_content.User_ID ='.$usr_id.' AND notes_content.Category_ID ='.$result_one["Category_ID"].';');
		mysqli_close($connection_second);
		return Array($query_two,$query_three);
	}

	/*
	* Collecting user log
	* Log contains user ID, IP address and current timestamp
	* Returns nothing
	* Function arguments: $usr_id - unique user ID
	*/
	function collectLog($usr_id)
	{
		$connection = mysqli_connect(HOST,USER,PASSWD,DB);
		$query = mysqli_query($connection,'INSERT INTO logs(User_ID, IP, Activity_time) VALUES ('.$usr_id.',"'.$_SERVER['REMOTE_ADDR'].'",CURRENT_TIMESTAMP);');
		mysqli_close($connection);
	}
?>