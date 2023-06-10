<?php
    session_start();
    $use = $_SESSION["username"];
    // echo $use;
    if ( !isset($_GET['title_id']) || trim($_GET['title_id']) == ''
        || !isset($_GET['user_id']) || trim($_GET['user_id']) == '' ) {
        $error = "Invalid URL";
    }
    else{
        $host = "303.itpwebdev.com";
        $user = "karajlee_db_user";  
        $pass = "uscitp2023";
        $db = "karajlee_project_final"; 
        $mysqli = new mysqli($host, $user, $pass, $db);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

        $mysqli->query("SET SESSION query_cache_type = OFF");
        $sql = "DELETE FROM reviews
						WHERE user_id = " . $_GET['user_id'] . " AND title_id = ". $_GET['title_id'] . ";";
        // echo "<hr>$sql<hr>";

		$results = $mysqli->query($sql);

		if (!$results) {
			echo $mysqli->error;
			$mysqli->close();
			exit();
		}


        $sqlTitle = "SELECT title FROM karajlee_project_final.titles WHERE title_id =  " . $_GET['title_id'];
        $sqlTitle = $sqlTitle . ";";
        $resultsT = $mysqli->query($sqlTitle);
        $rows = $resultsT->fetch_assoc();
        $title = $rows["title"];
        // echo $title;

		$mysqli->close();
    }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name = "description" content = "Tells users that review is deleted permanently">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Review Added</title>
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
	<div class="wrapper">
        <div id="main">
			<div class="row mt-4">
				<h1>Review Deleted</h1>
				<div class="col-12">

					<?php if ( isset($error) && trim($error) != '' ) : ?>

						<div class="text-danger">
							<?php echo $error; ?>
						</div>

					<?php else: ?>

						<div class="text-success">
							<span class="font-italic">Deleted Review <?php echo $title; ?></span>
						</div>

					<?php endif; ?>

				</div> 
			</div> 
			<div class="row mt-4 mb-4">
				<div class="col-12">
					<a href="review.php" role="button" class="btn btn-primary">Updated Reviews</a>
				</div> 
			</div>
		</div>
	</div> 
</body>
</html>