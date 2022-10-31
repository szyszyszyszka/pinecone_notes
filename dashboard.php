<?php
	session_start();

	/*
	* Checking if any user is already logged in
	*/
	if(isset($_SESSION["USER_ID"]) && isset($_SESSION["USERNAME"]))
	{	//User already logged in

		/*
		* Includng (requiring) f.php file with functions necessary for dashboard to work
		*/
		require("src/php/f.php");

		/*
		* Colleting user log and inserting it into database, getting user categories and user notes from database
		* collectLog($usr_id), getUserCategories($usr_id), getUserNotes($usr_id) - from f.php file
		*/
		collectLog($_SESSION["USER_ID"]);
		$user_categories = getUserCategories($_SESSION["USER_ID"]);
		$user_notes = getUserNotes($_SESSION["USER_ID"]);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8" />
	<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel = "stylesheet" type = "text/css" href = "src/css/dashboard.css"/>
	<script src = "src/js/libraries/jquery-3.6.1.min.js"></script><!-- Including jQuery framework into dashboard.php document -->
	<script src = "src/js/libraries/jquery.ns-autogrow.js"></script><!-- Including Waypoint jQuery plugin into dashboard.php document -->
	<script src = "src/js/dashboard.js"></script>
	<title>Notes - dashboard</title>
</head>
<body>
	<header><div id = "title">Pinecone Notes</div><div id = "username_display"><?php echo($_SESSION["USERNAME"]);?><br/><button id = "log_out">Log out</button></div></header>
		<section id = "categories">
			<section id = "displaying_categories">
			<form method = "get" action = "dashboard.php">
			<?php
			/*
			* Displaying user categories on a website as a form submit buttons
			*/
			for($i = 0; $i < count($user_categories); $i++)
				echo("<input type = 'submit' class = 'category_buttons' name = 'category' value = '".$user_categories[$i]["category"]."'/><br>");
			?>
			</form>
			</section>
			<section id = "adding_categories">
				<form method = 'post' action = 'dashboard.php' autocomplete='off' onsubmit = "return addingCategoryValidation()">
				<input type = 'text' id = "add_category_input" name = 'new_category' placeholder = "Category name" maxlength = '150'><br/>
				<input type = 'submit' id = 'add_category_button' class = 'category_buttons' value = 'Add category'>
				</form>
				<br/>
			</section>
			<?php
			/*
			* Adding new category to a database
			* addingCategory($usr_id, $c) - from f.php file
			*/
			if(isset($_POST["new_category"]))
			{
				$addingCategory = addingCategory($_SESSION["USER_ID"], $_POST["new_category"]);
				unset($_POST["new_category"]);
				header("Refresh:0");
			}

			/*
			* Displaying form used for category deletion
			*/
			if(isset($_GET["category"]))
				echo("<form method = 'post' onsubmit = 'return deletingCategoryConfimation()'><button type = 'submit'  id = 'delete_category_button' name = 'delete_category' value = '".$_GET["category"]."'>Delete category '".$_GET["category"]."'</button></form>");
			?>
		</section>
		<section id = "notes">
			<?php
			/*
			* If user has choosen category, notes from choosen category are displayed in forms which are used for modifing notes, deleting notes and adding new notes, also form for completly new note is displayed
			*/
			if(isset($_GET["category"]))
			{
				$current_category = $_GET["category"];
				for($i = 0; $i < count($user_categories); $i++)
				{
					if($user_categories[$i]["category"] == $_GET["category"])
					$category_id = $user_categories[$i]["Category_ID"];
				}
				for($i = 0; $i < count($user_notes); $i++)
				{
					if($user_notes[$i]["Category_ID"] == $category_id)
						echo("<form method = 'post' autocomplete='off' onsubmit = 'return changingNoteConfirmation()'><textarea class = 'notes_input' name = 'displayed_dote' maxlength = '255'>".$user_notes[$i]["note"]."</textarea><br/><div class = 'note_manipulation'><button type = 'submit' class = 'note_manipulation_buttons' name = 'delete_note' value = '".$user_notes[$i]["Note_ID"]."'>Delete</button><button type = 'submit' class = 'note_manipulation_buttons' name = 'update_note' value = '".$user_notes[$i]["Note_ID"]."'>Update</button></div><br/></form>");
				}
				echo("<section id = 'adding_note'><form method = 'post' action = 'dashboard.php' autocomplete='off' onsubmit = 'return addingNoteValidation()'><textarea class = 'notes_input' name = 'new_note'></textarea><div class = 'note_manipulation'><button type = 'submit' class = 'note_manipulation_buttons' name = 'add_note' value = '".$_GET["category"]."'>Add note</button></div></form></section>");
				unset($category_id);
			}

			/*
			* Deleting note from a database if the appropriate form was submited
			* deletingNote($usr_id, $note_id) - from f.php file
			*/
			if(isset($_POST["delete_note"]))
			{
				$deletedNoteStatus = deletingNote($_SESSION["USER_ID"], $_POST["delete_note"]);
				unset($_POST["delete_note"]);
				header("Refresh:0");
			}

			/*
			* Updating given note if the appropriate form was submited
			* updatingNote($usr_id, $n, $note_id) - from f.php file
			*/
			if(isset($_POST["update_note"]))
			{
				$updatedNoteStatus = updatingNote($_SESSION["USER_ID"], $_POST["displayed_dote"], $_POST["update_note"]);
				unset($_POST["update_note"]);
				unset($_POST["displayed_dote"]);
				header("Refresh:0");
			}

			/*
			* Adding and assigning note to a category if the appropriate form was submited
			* addingNote($usr_id, $cat, $n) - from f.php file
			*/
			if(isset($_POST["new_note"]))
			{
				$addedNoteStatus = addingNote($_SESSION["USER_ID"], $_POST["add_note"], $_POST["new_note"]);
				unset($_POST["new_note"]);
				header("Location: dashboard.php?category=".$_POST["add_note"]);
			}

			/*
			* Deleting category from database if the appropriate form was submited
			* deleteCateory($usr_id, $c) - from f.php file
			*/
			if(isset($_POST["delete_category"]))
			{
				$deletedCategoryStatus = deleteCateory($_SESSION["USER_ID"], $_POST["delete_category"]);
				unset($_POST["delete_category"]);
				header("Location: dashboard.php");
			}
			?>
	</section>
</body>
</html>
<?php
		exit();
	}else
	{	//User is not logged in, redirecting to main page
		header("Location: index.php");
	}
?>