<?php

$file1 = '/PHPMailer/Exception.php';
$file2 = '/PHPMailer/PHPMailer.php';
$file3 = '/PHPMailer/SMTP.php';

$basePath = $_SERVER['DOCUMENT_ROOT'];

$fullPath1 = $basePath . $file1;
$fullPath2 = $basePath . $file2;
$fullPath3 = $basePath . $file3;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $fullPath1;
require $fullPath2;
require $fullPath3;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $riskLevel = $_POST["risk-level"];
  $totalScore = $_POST["result"];
  $Number = $_POST['phone'];

  $servername = "localhost";
  $username = "id20813073_paularge";
  $password = "@Liverpool23";
  $database = "id20813073_stushield";

  // Create a connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the INSERT statement
  $stmt = $conn->prepare("INSERT INTO stushield (name, email, risk_level, total_score, phone) VALUES (?, ?, ?, ?, ?)");

  // Bind the parameters and execute the statement
  $stmt->bind_param("sssis", $name, $email, $riskLevel, $totalScore, $Number);
  $stmt->execute();

  // Check if the insert was successful
  if ($stmt->affected_rows > 0) {
    echo "Data inserted successfully.";
  } else {
    echo "Failed to insert data.";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();

  try {
    $mail = new PHPMailer(true);

    // Set SMTP server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'stushield@outlook.com';
    $mail->Password = '@Liverpool23';
    $mail->SMTPSecure = 'STARTTLS';
    $mail->Port = 587;

    $mail->setFrom('stushield@outlook.com', 'Diabetes risk assessment');
    $mail->addAddress($email, $name);
    $mail->addReplyTo('stushield@outlook.com', 'Diabetes risk assessment');
    $mail->isHTML(true);

    // Prepare email message
    $subject = "Diabetes Risk Assessment Result";
    $message = "Hello $name,<br><br>\n\nYour diabetes risk assessment result:<br><br>\n\nRisk Level: $riskLevel<br><br>\n\n Total Score: $totalScore.<br><br>\n\nThank you for participating in the diabetes risk assessment.<br><br>\n\nKind regards,<br><br>\n\nStushield";

    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();

    echo 'Confirmation email sent successfully!';
  } catch (Exception $e) {
    echo 'Email could not be sent. Error: ', $e->getMessage();
  }
}
?>
