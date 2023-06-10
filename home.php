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

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta name = "description" content = "Homepage where users can search up anime with keywords like title, status, and rating">
	<title>Final Project | Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="shared.css">
	<style>
		body {
			margin: 0px;
		}
		h1 {
			margin: 0px;
			color: #9899A6;
		}
		p {
			padding-top: 15px;
		}
		.images{
			padding-top: 15px;
			width: 150px;
			float: right;
			margin-left: 15px;
			margin-bottom: 15px;
		}
		.sc {
			text-align: center;
		}
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
			<h1>Searching...</h1>
                <form id="search-res">
                    <div class="form-row">
                        <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                            <input type="text" class="form-control" id="title-item" placeholder="Title">
                        </div>
                        <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                            <select name="status" id="status-id" class="form-control">
                                <option value="" selected="">N/A</option>
                                <option value="airing">Airing</option>
                                <option value="complete">Complete</option>
                                <option value="upcoming">Upcoming</option>
                            </select>
                        </div>
                        <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                            <input type="text" class="form-control" id="score-item" placeholder="Score">
                            <small id="score-error" class="form-text text-danger"></small>
                        </div>
                    </div>
                </form>
                <div class="form-row">
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <button type="submit" id = "searching" class="form-control btn btn-primary btn-style">Search</button>
                    </div>
                    <div class="col-10 mt-4 col-sm-3 mt-sm-0">
                        <button id="delete" class="btn btn-outline-danger mb-3">Delete All</button>
                    </div>
                </div>
                <img class="images" src="img/anime.png" alt="Logo">

                <p>Welcome to Anime Finder! You can browse through all sorts of anime by either title, status, or score. You can save what you've already watched so you can either binge watch again or use as reference for future searches with the option of adding reviews. You can also add reviews for animes you've watched or your friends have talked about.</p>

                <p></p>
			
                <h2>Search Results</h2>
                <form action="add_watch.php" method="GET"> 
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Episodes</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Link</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                            <tbody id="tbody"></tbody>

                    </table>
			    </form>

		    </div> 
        <?php endif; ?>

	</div>

	<div class = "footer"></div>

	<script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>

	<script>
		document.querySelector("#delete").onclick = () => {
			document.querySelector("#tbody").innerHTML = ''
		}
		document.querySelector('#searching').onclick = function() {
			const title = document.querySelector("#title-item").value.trim();
			const status = document.querySelector("#status-id").value.trim();
			const score = document.querySelector("#score-item").value.trim();
			let endpoint = "https://api.jikan.moe/v4/anime?q=" +title + "&status=" + status +"&score=" + score;
			if(score.length != 0){
				if(/(^([0-9]|10)\.[0-9]{2})$/.test(score) === false && /^([0-9]|10)$/.test(score) === false){
                	document.querySelector("#score-error").innerHTML = "number between 0 and 10 as whole number, 2 numbers after decimal or nothing";
					return false;
				}
				else{
					document.querySelector("#score-error").innerHTML = ""
					if(title.length === 0 && status.length === 0){
						endpoint = "https://api.jikan.moe/v4/anime?score=" + score;
					}
					else if(title.length === 0 && status.length != 0){
						endpoint = "https://api.jikan.moe/v4/anime?status=" + status +"&score=" + score;
					}
					else if(title.length != 0 && status.length === 0){
						endpoint = "https://api.jikan.moe/v4/anime?q=" +title +"&score=" + score;
					}	
				}
			}
			if(score.length === 0){
				if(title.length != 0 && status.length != 0){
					endpoint = "https://api.jikan.moe/v4/anime?q=" +title + "&status=" + status;
				}
				else if(title.length === 0 && status.length != 0){
					endpoint = "https://api.jikan.moe/v4/anime?status=" + status;
				}
				else if(title.length != 0 && status.length === 0){
					endpoint = "https://api.jikan.moe/v4/anime?q=" +title;
				}
			}
			$.ajax({
				url: endpoint,
				dataType: 'json',
				success: function(d) {
					console.log(endpoint)
					document.querySelector("#tbody").innerHTML = "";
					for(let j = 0; j < 10; j++){
						createResults(d.data[j]);
					}
				},
				error: function(e) {
					alert("AJAX error");
					console.log(e);
				}
			})

		} 
		function createResults(find){
			const tr = document.createElement("tr");
			const th_name = document.createElement("th");
			const th_episode = document.createElement("th");
			const th_status = document.createElement("th");
			const th_score = document.createElement("th");
			const th_url = document.createElement("th");
			const th_image = document.createElement("th");
			const img = document.createElement("img");
			const a = document.createElement("a");
			a.target = "_blank";

			th_name.innerHTML = find.title;
			th_name.name = "title";

			th_episode.innerHTML = find.episodes;
			th_status.innerHTML = find.status;
			th_score.innerHTML = find.score;
			img.src = find.images.jpg.small_image_url;
			img.alt = "N/A";
			a.href = find.url;
			a.innerHTML = "link";
			th_score.classList.add("sc");

			tr.appendChild(th_name);
			tr.appendChild(th_episode);
			tr.appendChild(th_status);
			tr.appendChild(th_score);

			th_image.appendChild(img);
			th_url.appendChild(a);
			tr.appendChild(th_url);
			tr.appendChild(th_image);
			document.querySelector("#tbody").appendChild(tr);
		}


	</script>

</body>
</html>