<?php 
	session_start();
    $host = "303.itpwebdev.com";
	$user = "karajlee_db_user"; 
	$pass = "uscitp2023";
	$db = "karajlee_project_final"; 

    $mysqli = new mysqli($host, $user, $pass, $db);

    if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
    if (!isset($_SESSION["username"])) {
        $error = "Login First Please.";
    }
    else{
        $use = $_SESSION["username"];
        // echo $use;
    
        $sql = "SELECT titles.title, review, rating, reviews.title_id 
        FROM karajlee_project_final.reviews 
        LEFT JOIN titles ON reviews.title_id = titles.title_id 
        LEFT JOIN users ON reviews.user_id = users.user_id
        WHERE users.name = '$use'";
    
        $sql = $sql . ";";
    
        $results = $mysqli->query($sql);
    
        if ( !$results ) {
            echo $mysqli->error;
            $mysqli->close();
            exit();
        }
    
        $sqlUser = "SELECT user_id FROM karajlee_project_final.users WHERE users.name='$use'";
        $sqlUser = $sqlUser . ";";
    
        $resultsUser = $mysqli->query($sqlUser);
    
        if ( !$resultsUser ) {
            echo $mysqli->error;
            $mysqli->close();
            exit();
        }
        $row = $resultsUser->fetch_assoc();
        $user_id = $row['user_id'];
    }

    
    $mysqli->close();
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Final Project</title>
    <meta name = "description" content = "Review page where users can add or update reviews and see previous reviews.">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="shared.css">
    <style>
    </style>
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
        <?php if( isset($error) && trim($error) != '' ): ?>
            <div class="text-danger">
                <?php echo $error; ?>
                <div class="row mt-2 mb-2 col-10">
                    <a href="login.php" role="button" class="btn btn-primary">Login</a>
                </div>
            </div>
        <?php else: ?>
			<h1 id="intro">Reviews</h1>
            <form id="review-sub" action = "review_confirm.php" method="POST">
                <div class="form-row">
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <input type="text" class="form-control" id="title-item" placeholder="Title" name="title">
                        <small id="title-error" class="form-text text-danger"></small>
                    </div>
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <input type="text" class="form-control" id="comment" placeholder="Comment" name="comment">
                        <small id="comm-error" class="form-text text-danger"></small>
                    </div>
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <input type="number" min="0" max="10" step="1" class="form-control" id="rating" placeholder="Rating (out of 10)" name="rating">
                        <small id="rate-error" class="form-text text-danger"></small>
                    </div>
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <button role="button" type="submit" class="btn btn-primary" name="review">New Review</button>
                    </div>
                </div>
            </form>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Commentary</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <?php while ( $row = $results->fetch_assoc() ) : ?>
                        <tr>
							<td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['review']; ?></td>
                            <td><?php echo $row['rating']; ?></td>
                            <td>
                                <a href="delete_confirm.php?title_id=<?php echo $row['title_id']; ?>&user_id=<?php echo $user_id; ?>" class="btn btn-outline-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

				</tbody>
            </table>
            <?php endif; ?>
		</div> 
	</div> 


</body>
</html>