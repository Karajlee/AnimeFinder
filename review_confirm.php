<?php
    session_start();
    $use = $_SESSION["username"];
    // echo $use;
    if ( !isset($_POST['title']) || trim($_POST['title']) == ''
        || !isset($_POST['comment']) || trim($_POST['comment']) == ''
        || !isset($_POST['rating']) || trim($_POST['rating']) == '' ) {
        $error = "Please fill out all required fields.";
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

        $sqluser = "SELECT user_id
                FROM karajlee_project_final.users 
                WHERE name = '$use'";
                $sqluser = $sqluser . ";";
        $resultuser = $mysqli->query($sqluser);
        $rowuser = $resultuser->fetch_assoc();
        $user_id =  $rowuser['user_id'];


        if ( !$resultuser ) {
            echo $mysqli->error;
            $mysqli->close();
            exit();
        }

        $title = strtolower($_POST['title']);
        $comment = $_POST['comment'];
        $rating = $_POST['rating'];
        $sql1 = "SELECT titles.title, titles.title_id
                FROM karajlee_project_final.reviews 
                LEFT JOIN titles ON reviews.title_id = titles.title_id 
                LEFT JOIN users ON reviews.user_id = users.user_id
                WHERE titles.title = '$title' AND users.user_id = $user_id";
            $sql1 = $sql1 . ";";
        $results1 = $mysqli->query($sql1);
        
        if($results1->num_rows > 0){

            $row1 = $results1->fetch_assoc();
            $title_id =  $row1['title_id'];
            $sqlupdate = "UPDATE karajlee_project_final.reviews SET rating=$rating, review='$comment' WHERE title_id=$title_id AND user_id =$user_id";
            $sqlupdate = $sqlupdate . ";";
            $resultupdate = $mysqli->query($sqlupdate);
        }
        else {
            $insert_title = $_POST['title'];
            $sqlinsert1 = "INSERT INTO karajlee_project_final.titles (title)
            VALUES ('$insert_title')";
            $sqlinsert1 = $sqlinsert1 . ";";
            $resultinsert1 = $mysqli->query($sqlinsert1);
            
            $sql_title_id = "SELECT title_id FROM karajlee_project_final.titles WHERE title='$insert_title'";
            $sql_title_id = $sql_title_id . ";";
            $resultititleInsert= $mysqli->query($sql_title_id);
            $rowIns = $resultititleInsert->fetch_assoc();
            $title_id1 = $rowIns["title_id"];

            
            $sqlinsert2 = "INSERT INTO karajlee_project_final.reviews (title_id, review, rating, user_id)
            VALUES ($title_id1, '$comment', $rating, $user_id)";
            $sqlinsert2 = $sqlinsert2 . ";";
            $resultinsert2 = $mysqli->query($sqlinsert2);
            

        }
        $mysqli->close();
    }


?>
<!DOCTYPE html>
<html>
<head>
    <meta name = "description" content = "Tells users that a new review is added or updated a current one.">
	<meta charset="UTF-8">
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
                <h1>Review Added</h1>
                <div class="col-12">

                    <?php if ( isset($error) && trim($error) != '' ) : ?>

                        <div class="text-danger">
                            <?php echo $error; ?>
                        </div>

                    <?php else: ?>

                        <div class="text-success">
                            <span class="font-italic">Added Review for <?php echo $title; ?></span>
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