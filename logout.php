<?php 
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "description" content = "Users are officially logged out, must log in again for access.">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="shared.css">
</head>
<body>
	<ul id="click">
        <li>
            <a id="title" href="home.php">Anime Finder</a>
            <ul>
               <li><a href="home.php">Home</a></li>
               <li><a href="review.php">Review</a></li>
               <li><a href="logout.php">Log Out</a></li>
            </ul>
        </li>
	</ul>
	<div id="wrapper">
		<div id="main">
			<div class="row">
				<h1 class="col-12 mt-4 mb-4">Logout</h1>
				<div class="col-12">You are logged out.</div>
				<div class="col-12 mt-3">Click <a href="login.php">here</a> to login again.</div>

			</div> 
		</div>
	</div> 

</body>
</html>