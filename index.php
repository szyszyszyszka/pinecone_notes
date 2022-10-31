<?php
	/*
	* Checking if any user is already logged in
	*/
	session_start();
	if(isset($_SESSION["USER_ID"]) && isset($_SESSION["USERNAME"]))
	{
		/*
		* Redirecting to dashboard because user is already logged in
		*/
		header("Location: dashboard.php");
	}else
	{
		/*
		* Rendering page because no user is logged in
		*/
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel = "stylesheet" type = "text/css" href = "src/css/main.css"/>
	<script src = "src/js/libraries/jquery-3.6.1.min.js"></script><!-- Including jQuery framework into index.php document -->
	<script src = "src/js/libraries/jquery.waypoints.min.js"></script><!-- Including Waypoint jQuery plugin into index.php document -->
	<script src = "src/js/main.js"></script>
	<title>Pinecone Notes</title>
</head>
<body>
<main>
	<header>
	<h1>Pinecone Notes</h1>
	</header>
	<section id = "banner"><div>Taking notes<br>simplified</div></section>
	<article>
	<div class = "brief_introduction" id = "about">FAQ</div>
	<div class = "brief_header">What is Pinecone Notes?</div>
	<div class = "brief_descriptions">Pinecone Notes is a straightforward web app for taking notes with no additional, unnecessary features.
 It was at first built with only personal use in mind but it was decided to publish it as an open beta to a broader audience.</div><br>
 	<div class = "brief_header">Is it commercial product?</div>
	<div class = "brief_descriptions">Pinecone Notes is a amateur project developed by a novice programmer. It is not commercial product but a small project created as skill practice!</div><br>
	<div class = "brief_header">Why it was published?</div>
	<div class = "brief_descriptions">Pinecone Notes was published because why not :)<br>Publishing it to a broader audience can help finding and fixing bugs that I have missed, it is a good skill practice, and Pinecone Notes turned out to work really well in my opinion so maybe it will help someone be more productive in everyday life.</div><br>
	<div class = "brief_header">How it was created?</div>
	<div class = "brief_descriptions">Pinecone Notes was made using technologies like: HTML, CSS, JavaScript (+ <a href = "https://jquery.com/" target = "_blank">jQuery</a> framework + <a href = "https://github.com/imakewebthings/waypoints" target = "_blank">Waypoint</a> and <a href = "https://github.com/ro31337/jquery.ns-autogrow" target = "_blank">jquery.ns-autogrow</a> plugins) and PHP (+ <a href = "https://github.com/defuse/php-encryption" target = "_blank">php-encryption</a> library).</div><br>
	<div class = "brief_header">Who is the creator of Pinecone Notes?</div>
	<div class = "brief_descriptions">Pinecone Notes was created as a solo project. You can find out more about who I am <a href ="https://szyszyszyszka.pl" target = "_blank">here</a></div><br>
	<div class = "brief_header">Is Pinecone Notes open-source? Is there any source code avalible?</div>
	<div class = "brief_descriptions">You can find published source code on <a href = "https://gitgub.com" target = "_blank">GitHub</a></div><br>
	<div class = "brief_header">Why is UI so awful?</div>
	<div class = "brief_descriptions">Because I'm not UI designer :)<br>If you have any ideas for improving UI feel free to reach out to me!</div><br>
	<div class = "brief_header">I want to report bug or an issue regarding Pinecone Notes</div>
	<div class = "brief_descriptions">Avalible contact forms are listed at the fotter of this website.</div><br>
	<div class = "brief_header">I have a question about project</div>
	<div class = "brief_descriptions">Avalible contact forms are listed at the fotter of this website.</div><br>
	</article>
	<section class = 'login_fields'>
	<h1>Use Pinecone Notes!</h1>
	<h2>Log In</h2>
	<form method = "post" action = "log_in.php" onsubmit = "return loginFormValidation()">
	<input type = "text" name = "username" placeholder = "Username"/>
	<br/>
	<input type = "password" name = "password" placeholder = "Password"/>
	<br/>
	<input type = "submit" value = "Log In"/>
	</form>
	<p id = 'failed_login'>Incorrect login details</p>
	<h2>Create account</h2>
	<form method = "post" action = "register.php" onsubmit = "return registerFormValidation()">
	<input type = "text" name = "username_r" placeholder = "Username"/>
	<br/>
	<input type = "password" name = "password_r" placeholder = "Password"/>
	<br/>
	<input type = "password" id = "paasword_r_conf" placeholder = "Confirm password"/>
	<br/>
	<input type = "submit" value = "Create account"/>
	</form>
	<p id = 'failed_reg'>Incorrect registration details</p>
	</section>
	<footer>
		<h1>Pinecone Notes</h1>
		<br/>
		<a href = "#about">About</a>
		<br/>
		<a href = "">Contact</a>
		<br/>
		<a href = "https://szyszyszyszka.pl">About creator</a>
		<br/>
		<a href = "documents/privacy-policy.html">Privacy Policy</a>
		<br/>
		<a href = "documents/cookie-policy.html">Cookie Policy</a>
		<br/>
		<br/>
		<div></div>
	</footer>
</main>
	<div id = "cookie_banner"><div id = "cookie_consent1">This website uses cookies in order to function properly<br/>By continuing using this website you agree to our <a href = "documents/cookie-policy.html">Cookie Policy</a></div>
	<div id = "cookie_consent2"><button id = "accept_cookies">Accept</button> <button id = "reject_cookies">Reject</button></div>
	</div>
</body>
</html>
<?php
	}
	exit();
?>