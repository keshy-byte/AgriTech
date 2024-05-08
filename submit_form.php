<?php

$host = 'localhost';
$dbname = 'contact_form';
$username = 'MarySQL';
$password = '123123';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(stripslashes(trim($_POST["name"])));
    $email = htmlspecialchars(stripslashes(trim($_POST["email"])));
    $message = htmlspecialchars(stripslashes(trim($_POST["message"])));

    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill all the fields!";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
        } else {

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
                
                $sql = "INSERT INTO submissions (name, email, message) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $email, $message]);

                echo "Thank you for contacting us, $name. We will get back to you shortly.";
            } catch (PDOException $e) {
                die("Could not connect to the database $dbname :" . $e->getMessage());
            }
        }
    }
} else {
    echo "Form submission is only allowed through POST method.";
}
?>
