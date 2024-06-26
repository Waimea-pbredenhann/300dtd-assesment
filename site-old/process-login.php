<?php
include '../site/css/styles.css';
require_once '_session.php';
require_once 'utils.php';

consoleLog($_POST, 'Form Data');

session_start();

$user = $_POST['user'];
$pass = $_POST['pass'];

$db = connectToDB();
$query = 'SELECT * FROM users WHERE username = ?';
$stmt = $db->prepare($query);
$stmt->execute([$user]);
$userData = $stmt->fetch();

consoleLog($userData, 'DB data');
if ($userData) {
    if (password_verify($pass, $userData['hash'])) {
        // We got here, so user and pass ok :>

        // Save user info for later use
        $_SESSION['user']['loggedIn'] = true;
        $_SESSION['user']['admin'] = $userData['admin'];
        $_SESSION['user']['forename'] = $userData['forename'];
        $_SESSION['user']['surname'] = $userData['surname'];
        // Heading over to homepage
        header('location: ../site/user-view.php');
        exit();
    } else {
        echo '<h2>Incorrect password</h2>';
        echo '<p>Try again or <a href="../site/form-signup.php">sign up</a>.</p>';
        echo '<form method="POST" action="">
                <input type="hidden" name="user" value="' . htmlspecialchars($user) . '">
                <label for="pass">Password:</label>
                <input type="password" name="pass" required>
                <input type="submit" value="Try Again">
              </form>';
    }
} else {
    echo '<h2>User account does not exist</h2>';
    echo '<p><a href="../site/form-signup.php">Sign up</a></p>';
}

echo '<p><a href="../site/index.php">Home</a></p>';
?>

<!-- The try again needs working on -->