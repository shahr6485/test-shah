<?php
// session_start();

include '_dbcon.php';

require("vendor/autoload.php");
require_once("mailer/PHPMailer.php");
require_once("mailer/SMTP.php");
require_once("mailer/Exception.php");
use PHPMailer\PHPMailer\PHPMailer;

?>

<?php

$s = "SELECT * FROM `test` WHERE active = '1' ";
$run = mysqli_query($conn, $s);

while($r = mysqli_fetch_assoc($run)){
    $email = $r['email'];
	$token = $r['token'];
	$initial_page = "https://test-shah.herokuapp.com/index.php";
	// $email = $_SESSION['email'];
	// $email = $r['email'];
    
	$random = rand(0, 1000);
	$api    = 'http://xkcd.com/' . $random . '/info.0.json';
	$json = file_get_contents($api);
	$data = json_decode($json);
	$title = 'Latest comics' . $data->safe_title;
	$name = $data->title;
	$img = $data->img;
	$subject = "$data->title";
	$unsubscribe_url = "https://test-shah.herokuapp.com/unsubscribecron.php?email=$email&token=$token";
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Host = "smtp.gmail.com";
	$mail->Port = "587";
	$mail->Username = "phprtcamp@gmail.com";
	$mail->Password = "php@123$";
	$mail->setFrom("phprtcamp@gmail.com");
	$mail->addAddress($email);
	$mail->isHTML(true);
	$mail->Subject = "Collection renewed.";
	$mail->Body = '
					<p>Hola amigos!</p>
					Let the humor amaze you.
					<h3>' . $data->safe_title . "</h3>
					<img src='" . $data->img . "' alt='some data hehe'/>
				<br />
				Enjoy the Comic : <a target='_blank' href='https://xkcd.com/" . $data->num . "'>Comic</a><br /> 
				Unsubscribe the service : <a target='_blank' href='" . $unsubscribe_url . "'>Unsubscribe</a><br />";
	$mail->addStringAttachment(file_get_contents($img), "$subject.jpg");
	$mail->send();
}

