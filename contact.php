
<?php





function safeEscapeString($string) {
 if (get_magic_quotes_gpc()) {
   return $string;
 } else {
   return mysql_real_escape_string($string);
 }
}

/* $v is validation routine
  empty means no validation (default) */
function sanitize($s, $v = '') {
  if( isset( $_GET[$s] ) ) {
    switch($v) {
      case 'email':
        // check if it's a real email
      break;
      default:
        return stripslashes( $_GET[$s] );
      break;
    }
  } else if( isset( $_POST[$s] ) ) {
    switch($v) {
      case 'email':
        // check if it's a real email
      break;
      default:
        return stripslashes( $_POST[$s] );
      break;
    }
  } else {
    return '';
  }
}



$op = sanitize("op");

switch ($op) {

	case "contact":
		echo sendContact();
	break;

	default:
		echo contactForm();
	break;

}


function contactForm() {

    ?>

        <a href="/">back</a>

    <?php

}



function sendContact() {


    $busname = sanitize("busname");
    $fullname = sanitize("fullname");
    $emailaddress = sanitize("emailaddress");

	$validemail = "false";
    $msg = "";

    if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $emailaddress)) {
        $msg = 'email is not valid';
        $validemail = "false";
    } else {
        $validemail = "true";
    }


    $phonenumber = sanitize("phonenumber");
    $description = sanitize("description");



$body =<<<HEREDOC

------------------------------------------------
41 Prospects Contact Form
------------------------------------------------

Business Name : $busname
Full Name     : $fullname
Email Address : $emailaddress
Phone Number  : $phonenumber

Description
-------------------------------
$description



HEREDOC;

 	$email_to = "brentsears@41prospects.com";
    $email_from = $emailaddress." |  41 Prospects Contact Form ";


	$body .=  "\n BROSWER:".getenv("HTTP_USER_AGENT")."\n\n REMOTE ADDRESS: ".getenv("REMOTE_ADDR")."\n\n REMOTE ADDRESS: ".gethostbyaddr($_SERVER['REMOTE_ADDR']);

    $subject_email = "41 Prospect Contact Form";
    $headers = "From: ".$emailaddress." |  41 Prospects Contact Form \r\n" .
    			  "Reply-To: ".$emailaddress." \r\n" .
    			   "X-Mailer: PHP/" . phpversion();
    $headers .= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";


    $sendme = mail($email_to, $subject_email, $body, $headers);

    if ($sendme) {
    	echo "Your email has been submitted. We’ll get back to you shortly.";
    }


}





?>

