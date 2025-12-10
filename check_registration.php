<?php

$page_title = "Check registration";

include 'mysqli_connect.php';

include 'includes/header.html';

include 'includes/navbar.html';

if (isset ($_SESSION['username'])){
	header('location: index.php');
}
else{
	
    $username = $_POST['username'];
    $password = $_POST['pass'];


    $sql1 = "SELECT users_username, users_password FROM users WHERE users_username = ? AND users_password = ?";
	
    $sql2 = "INSERT INTO users (users_username, users_password) VALUES (:username, :password)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
	    ':username' => $username,
	    ':password' => $password
	]);

    if ($stmt = mysqli_prepare($connection, $sql1)) {
	    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
	    mysqli_stmt_execute($stmt);
    	$result1 = mysqli_stmt_get_result($stmt);

	    if ($row = mysqli_fetch_assoc($result1)) {
	        echo "Utilisateur : " . $row['users_username'];
	    } else {
	        echo "Erreur d'identifiants";
	    }
	
	    mysqli_stmt_close($stmt);
	} else {
	    die(mysqli_error($connection));
	}

    if (mysqli_num_rows ($result1) == 0){
        if (mysqli_query ($connection, $sql2)){
            include 'includes/new_registration.php';
        }
        else{
            include 'includes/error.php';
            
        }

    }
    else{
        include 'includes/notregistered.php';
    }
}

mysqli_close ($connection);

include 'includes/footer.html';

?>
