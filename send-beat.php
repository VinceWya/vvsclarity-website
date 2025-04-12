<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Capture the buyer's email address
  $buyer_email = $_POST['buyer_email'];

  // Assuming payment was successful, send the download link
  $subject = "Your Purchased Beat - Download Link";
  $message = "Thank you for your purchase! You can download your beat from the link below:\n\n";
  $message .= "Download Link: https://vincewya.github.io/vvsclarity-website/store.html/beats/Rexv2 x tdf type beat @vvsclarity @rexv2 148 bpm.mp3\n\n";
  $message .= "Enjoy making music!\n\nBest Regards,\nVVSCLARITY Store";
  
  // Send the email (you may want to use a more secure way to send emails, like PHPMailer)
  mail($buyer_email, $subject, $message, "From: vincentreyes2007@gmail.com");
  
  echo "Thank you for your purchase! The download link has been sent to your email.";
}
?>
