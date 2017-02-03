<?php

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if image file is a actual document or fake document
if(isset($_POST["submit"])) {
  $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    $error_message .= "File is must be a PDF or Word Document. ";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  $error_message .= "File already exists. ";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 1000000) {
  $error_message .= "Sorry, your file is too large. ";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf") {
  $error_message .= "Only DOC, DOCX & PDF files are allowed. ";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $error_message .= "Sorry, your file was not uploaded. ";
  // if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. ";
  } else {
    $error_message .= "Sorry, there was an error uploading your file. ";
  }
}

// Print out error messages (If there are any).
echo $error_message;

  //Email Template. Send if no errors.
  if(!isset($error_message))
  {
    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";

    // Get the file and file name
    $file  =  $target_file;
    $filename = basename( $_FILES["fileToUpload"]["name"]);

    $to = "jonathanedempsey@gmail.com";
    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));
    $subject = "File Test";

    $message .= "Hello!\n";
    $message .= "\n";

    // Checks if box is ticked and puts it in the message
    // IMPORTANT: Not used for sending the attachment,
    // just for checkboxes with different names.
    if (isset($_POST['cat'])) {
      $message .= "cat \n";
    }
    if (isset($_POST['dog'])) {
      $message .= "dog \n";
    }
    if (isset($_POST['mouse'])) {
      $message .= "mouse \n";
    }

    // Required headers to send attachment
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;

    // message
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $message . $eol;

    // attachment
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol;
    $body .= $content . $eol;
    $body .= "--" . $separator . "--";

    mail($to,$subject,$body,$headers);
  }

?>
