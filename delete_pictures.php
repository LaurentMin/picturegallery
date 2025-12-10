<?php

$page_title = "Delete pictures";

include 'mysqli_connect.php';

include 'includes/header.html';

include 'includes/navbar.html';

if (!isset ($_SESSION ['username'])){
	header('location: index.php');
}
else{
    if (isset ($_POST['pictures_name'])){
        $pictures_name = $_POST['pictures_name'];
        
        $sql2 = "DELETE FROM pictures WHERE pictures_name = ?";
		mysqli_stmt_bind_param($stmt, "s", $pictures_name);
		if (mysqli_stmt_execute($stmt)) {
		    if (mysqli_stmt_affected_rows($stmt) > 0) {
		
		        // Répertoire autorisé
		        $baseDir  = realpath(__DIR__ . '/uploads');
		        // On retire d’éventuels chemins "../" et on force dans uploads
		        $fileName = basename($pictures_name);
		        $filePath = realpath($baseDir . DIRECTORY_SEPARATOR . $fileName);
		
		        // On vérifie que le fichier est bien dans le dossier uploads
		        if ($filePath !== false && strpos($filePath, $baseDir) === 0 && is_file($filePath)) {
					// nosemgrep: php.lang.security.unlink-use.unlink-use
		            if (unlink($filePath)) {
		                echo "Removed picture " . htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8') . "<br>";
		                echo "Removed picture " . htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8')
		                     . ", continue with <a href=''>deleting pictures</a>";
		            } else {
		                echo "Error while deleting picture.<br>";
		            }
		        } else {
		            echo "Invalid or unauthorized file path.<br>";
		        }
		
		        unset($filePath);
		    }
		}

		mysqli_stmt_close($stmt);
    }
    
    $sql1 = "SELECT users.users_username, pictures.pictures_name FROM pictures INNER JOIN users ON pictures.id_users = users.users_id";
    $result = mysqli_query ($connection, $sql1) or die (mysqli_error ($connection));
    
    echo "<form action='' method='POST'>";
    echo "<select name='pictures_name'>";
    if (mysqli_num_rows ($result) > 0){
        while ($row = mysqli_fetch_assoc ($result)){
            if($row['users_username'] == $_SESSION['username']){
                echo "<option value='" . $row['pictures_name'] . "'>" . $row['pictures_name'] . "</option>";
            }
        }
    }
    else {
        echo "Error 2";
    }
    echo "</select>";
    echo "<input type='submit' value='Delete picture'>";
    echo "</form>";

    include 'includes/footer.html';

    mysqli_close ($connection);
    unset($connection);
}

?>

