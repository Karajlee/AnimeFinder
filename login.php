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
	$mysqli->set_charset('utf8');

	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
		header("Location: home.php");
	}
    if (isset($_POST['login'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $sql = "SELECT users.name FROM users WHERE users.name = '" . $_POST['username'] . "' AND users.passwords ='" . $_POST['password'] . "'";
            $sql = $sql. ";";
            $results = $mysqli->query($sql);
            if ( !$results ) {
                echo $mysqli->error;
                $mysqli->close();
                exit();
            }
            if (empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
                $error = "Please enter username and password.";
            } elseif ( $results->num_rows > 0) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_POST['username'];
                header("Location: home.php");
            } else {
                $error = "Invalid username or password.";
            }

        }
    }
    else if (isset($_POST['signup'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
                $error = "Please enter username and password.";
            } 
            else {
                $sqlCheck = "SELECT * FROM karajlee_project_final.users WHERE name = '{$_POST['username']}' OR passwords = '{$_POST['password']}'";
                $sqlCheck = $sqlCheck. ";";
                $resultCheck = $mysqli->query($sqlCheck);
                if($resultCheck->num_rows > 0){
                    $error = "Please use a different username or password.";
                }
                else{
                    $sql = "INSERT INTO users (name, passwords) VALUES ('" . $_POST['username'] . "','" . $_POST['password'] . "')";
                    $sql = $sql. ";";
                    $results = $mysqli->query($sql);
                    if ( !$results ) {
                        echo $mysqli->error;
                        $mysqli->close();
                        exit();
                    }
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $_POST['username'];
                    header("Location: home.php");
                }
            } 
        }
    }
    $mysqli->close();
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta name = "description" content = "Login and Sign Up page for users. Uses form validation where username and password can't be blank and must be valid.">
	<title>Project | Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="shared.css">
	<style>
		body {
			margin: 0px;
		}
		h1 {
            text-align: center;
			margin: 0px;
		}
        h2 {
            padding-left: 15px;
        }
		#header {
			background-image: url('img/anime.png');
			background-size: cover;
			background-position: center;
			color: #FFF;
			font-size: 3em;
			text-align: center;
			line-height: 450px;
			text-shadow: 1px 1px 10px #000;
		}
		.images{
			width: 150px;
			margin-left: 15px;
			margin-bottom: 15px;
		}
        .row {
            padding-left: 50px;
        }
        p {
            padding-left: 50px;
        }
	</style>

</head>
<body>
	<div id="header">
		<h1>Anime Finder</h1>
	</div> 
    <h1>Login Page</h1>


	<div id="wrapper">
		<div id="main">
            <form id="sign-in" action = "login.php" method="POST">
                <div class="form-group">
                    <div class="col-10 col-sm-5 mt-sm-0">
                        <label for="username" class="col-14 col-form-label">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Username" name="username">
                        <small id="user-error" class="form-text text-danger"></small>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-10 col-sm-5 mt-sm-0">
                        <label for="password" class="col-14 col-form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                        <small id="password-error" class="form-text text-danger"></small>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-10 col-sm-3 mt-sm-0">
                        <button role="button" type="submit" class="btn btn-primary" name="signup">Sign Up</button>
                        <button role="button" type="submit" class="btn btn-primary" name="login">Login</button>
                    </div>
                </div>
                <div class="col-10 col-sm-5 mt-sm-0">
                    <div class="font-italic text-danger ml-sm-auto">
                        <?php 
                            if (!empty($error)) {
                                echo $error;
                            }
                        ?>
                    </div>
                </div>
            </form>
			<h2 id="popular">Popular</h2>
            <div class="row">
                <div class="column">
                    <img class="images" src="img/hxh.png" alt="Lost in Translation">
                </div>
                <div class="column">
                    <img class="images" src="img/full_metal.png" alt="Full Metal Alchemist">
                </div>
                <div class="column">
                    <img class="images" src="img/gintama.png" alt="Gintama">
                </div>
                <div class="column">
                    <img class="images" src="img/aot.png" alt="Attack on Titan">
                </div>
                <div class="column">
                    <img class="images" src="img/steins_gate.png" alt="Steins Gate">
                </div>
            </div>
			<h2 id="movies">Movies</h2>
            <div class="row">
                <div class="column">
                    <img class="images" src="img/voice.png" alt="Koe no Katachi">
                </div>
                <div class="column">
                    <img class="images" src="img/kimi.png" alt="Kimi no Na wa">
                </div>
                <div class="column">
                    <img class="images" src="img/howl.png" alt="Howl's Moving Castle">
                </div>
                <div class="column">
                    <img class="images" src="img/suzume.png" alt="Suzume no Tojimari">
                </div>
                <div class="column">
                    <img class="images" src="img/spirited.png" alt="Spirited Away">
                </div>
            </div>
			<h2 id="quotes">Quotes</h2>
			<p>He turns his back towards the light without hesitation. That figure that charges into the abyss at a dignified pace. How beautiful and foolish. - Sebastian Michaelis (Kuroshitsuji)</p>

			<p>Music speaks louder than words - Kousei Arima (Shigatsu Wa Kimi No Uso)</p>

            <p>It is the path you have chosen. Take pride in it. - Kotomine Kirei (Fate/stay night)</p>

		</div> 
        </div>


</body>
</html>
