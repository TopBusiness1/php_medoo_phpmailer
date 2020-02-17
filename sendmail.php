<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//include the database
include 'functions.php';

$datamail = $testuser->select('usersmail',['id','name','email','timefrom','timeto','callfrom','callto','callduration','talkduration','status','sented']);


foreach ($datamail as $mailrow) {
$daysfrom = array(
  'Day'=>"",
  'Week'=>"Sunday",
  'Month'=>"last day of",
  'Year'=>""
);
$daysto = array(
  'Day'=>"",
  'Week'=>"Saturday ",
  'Month'=>"",
  'Year'=>""
);
  $timefrom = date("Y-m-d h:i:s", strtotime($daysfrom[$mailrow['timefrom']]." -1 ".$mailrow['timefrom'].' 00:00:00')) ;
  $timeto = date("Y-m-d h:i:s", strtotime($daysto[$mailrow['timefrom']].' This '.$mailrow['timefrom'].' '.$mailrow['timeto']));

	$datacdr = $database->select("cdr", ["datetime","clid","dst","billable","duration","disposition"], [
    "datetime[<>]" =>  [$timefrom, $timeto],
    "clid[~]" => ["AND" => [$mailrow['callfrom'], ""]],
    "dst[~]" => ["AND" => [$mailrow['callto'], ""]],
    "billable[~]" => ["AND" => [$mailrow['callduration'], ""]],
    "duration[>=]" => $mailrow['talkduration'],
    "disposition[~]" => ["AND" => [($mailrow['status']=='All'?'':$mailrow['status']), ""]]
	]);
    $fp = fopen('Report.csv', 'w');
	if ($datacdr > 0) {
	 $csvdata = ['Time', 'Call From', 'Call To', 'Call Duration(s)', 'Talk Duration(s)', 'Status'];
    fputcsv($fp, $csvdata, ',', "\t");

    $mailer=0;
    // output data of each row
    foreach($datacdr as $cdrrow) {
        $date =  new DateTime();
        $sented = new DateTime($mailrow['sented']);
        $sendtime = date('Y-m-d h:i:s', strtotime($mailrow['sented'].$mailrow['timeto']));
        // $adfadsf = date_format($sented, "Y-m-d H:i:s");

        if($sendtime <= $timefrom && new DateTime($timeto) <= $date){
          $testuser->update("usersmail", [
            "sented" => date_format($date, 'Y-m-d')
          ], [
            "id" => $mailrow['id']
          ]);
          fputcsv($fp, $cdrrow, ",", "\t");
          $mailer = 1;
        }
    }
    if($mailer == 1){
      // Instantiation and passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
          //Server settings
          $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
          $mail->isSMTP();                                            // Send using SMTP
          $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
          $mail->Username   = 'admin@gmail.com';              // SMTP username
          $mail->Password   = 'admin';                            // SMTP password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
          $mail->Port       = 587;                                    // TCP port to connect to

          //Recipients
          $mail->setFrom('adminp@gmail.com', 'Admin');
          // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
          $mail->addAddress($mailrow['email']);               // Name is optional
          // $mail->addReplyTo('info@example.com', 'Information');
          // $mail->addCC('cc@example.com');
          // $mail->addBCC('bcc@example.com');

          // Attachments
          $mail->addAttachment('./Report.csv');    // Optional name

          // Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'Receiver message Report!';
          $mail->Body    = 'This message is your search schedule <b>Report!</b>';
          $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

          $mail->send();
          echo 'Message has been sent';
      } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
  } else {
      echo "0 results";
  }
  fclose($fp);
}
// echo json_encode($data);

?>
