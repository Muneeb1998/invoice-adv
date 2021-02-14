<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Libraries\PdfGenerate;

/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
if (!function_exists('sendEmail')) {
	function sendEmail(array $a = [])
	{
		$helper = model('App\Models\HelperModel');
		$session   = \Config\Services::session();
		$aSettingBuilder = $helper->getBuilder('SmtpSettings');
		$aSettingBuilder->skipValidation();
		$aSettingBuilder->select([
			'smtpHost',
			'smtpUsername',
			'smtpPass',
			'smtpPort',
			'bcc',
			'replyTo',
			'setFromEmail',
			'setFromName',
			'SMTPSecure',
			'auth',
		]);
        if(isset($a['user_id']) && $a['user_id'] != ''){
            $aSettingBuilder->where('admin_id', $a['user_id']);
        }else{
            $aSettingBuilder->where('admin_id', $session->get('userId'));
        }
		$aSettingData = $aSettingBuilder->get()->getResult();
		$aSetting = (array)$aSettingData[0];
		// $aSetting = array(
		// 	'smtpHost' => 'mail.pickyour.online',
		// 	'smtpUsername' => 'muneebmansoor@pickyour.online',
		// 	'smtpPass' => ')]d8uAHoIO014l',
		// 	'smtpPort' => '587',
		// 	'bcc' => '',
		// 	'replyTo' =>  'muneebmansoor@pickyour.online',
		// 	'setFromEmail' => 'muneebmansoor@pickyour.online',
		// 	'setFromName' => MAIL_NAME,
		// 	'SMTPSecure' => 'tls',
		// 	'auth' => true,
		// );
		// $oAdmin = $helper->getBuilder('admin');
		// $oAdmin->skipValidation();
		// $oAdmin->select([
		// 	'email',
		// ])->where('id', 1);
		// $aAdminMail = $oAdmin->get()->getResult();
		// $aAdminMail = (array)$aAdminMail[0];
		// $adminMail = $aAdminMail['email'];
		if ((isset($a['bcc'])) && (!empty($a['bcc'])) && $a['bcc'] != '') {
		    $a['bcc'] = $aSetting['bcc'] . ','.$a['bcc'];
		    $a['bcc'] = explode(',',$a['bcc']);
		    $aSetting['bcc'] = $a['bcc'];
		}else{
            $aSetting['bcc'] = array($aSetting['bcc']);
        }
		$a['aConf'] = $aSetting;
		// ciMail($a);
		phpMaillerSender($a);
	}
}

function ciMail(array $a)
{
	$host = $a['aConf']['smtpHost'];
	$smtpUser = $a['aConf']['smtpUsername'];
	$pass = $a['aConf']['smtpPass'];
	$port = $a['aConf']['smtpPort'];
	$bcc = $a['aConf']['bcc'];
	$replyTo = $a['aConf']['replyTo'];
	$from = $a['aConf']['setFromEmail'];
	$fromName = $a['aConf']['setFromName'];
	$SMTPSecure = $a['aConf']['SMTPSecure'];
	// $auth = $a['aConf']['auth'];
	// $config['SMTPAuth'] = 0;
	$config['SMTPHost'] = $host;
	$config['SMTPUser'] = $smtpUser;
	$config['SMTPPass']  = $pass;
	$config['SMTPPort'] = $port;
	$config['SMTPCrypto'] = $SMTPSecure;
	$config['fromEmail'] = $from;
	$config['fromName'] = $fromName;
	$config['protocol'] = 'mail';
	$email = \Config\Services::email();
	$email->initialize($config);
	$email->setFrom($from, $fromName);
	if (!empty($bcc)) {
		$email->setBCC($bcc);
	}
	$email->setReplyTo($replyTo, $fromName);
	if (!isset($a['subject'])) {
		$email->setSubject('Online Account Opening Form');
	}
	$email->setTo($a['to']);
	$aMailProp = setMailProp($a);
	$email->setSubject($aMailProp['subject']);
	$email->setMessage($aMailProp['body']);
	if (!$email->send()) {
		//throw new \Exception('Error: Unable to send email');
		throw new \Exception($email->printDebugger(['subject']));
	}
}

function phpMaillerSender(array $a)
{
	$host = $a['aConf']['smtpHost'];
	$smtpUser = $a['aConf']['smtpUsername'];
	$pass = $a['aConf']['smtpPass'];
	$port = $a['aConf']['smtpPort'];
	$aBcc = $a['aConf']['bcc'];
	$replyTo = $a['aConf']['replyTo'];
	$from = $a['aConf']['setFromEmail'];
	$fromName = $a['aConf']['setFromName'];
	$SMTPSecure = $a['aConf']['SMTPSecure'];
	$auth = $a['aConf']['auth'];
	$config['SMTPAuth'] = 0;
	$mail = new PHPMailer(true);

	try {
		//Server settings
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host       = $host;                    // Set the SMTP server to send through
		$mail->SMTPAuth   = $auth;                                   // Enable SMTP authentication
		$mail->Username   = $smtpUser;                     // SMTP username
		$mail->Password   = $pass;                               // SMTP password
		$mail->SMTPSecure = ($SMTPSecure == 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS);         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = $port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		//Recipients
		$mail->setFrom($from, $fromName);
		$mail->addAddress($a['to']);     // Add a recipient
		$mail->addReplyTo($replyTo);
		if (!empty($aBcc)) {
			foreach ($aBcc as $k => $v) {
				$mail->addBCC($v);
			}
		}
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		// Content
		$mail->isHTML(true);
		$aMailProp = setMailProp($a);
		if (isset($a['hash'])) {
			$pdfString = new PdfGenerate([
				'sHash' => $a['hash'],
				'output' => true
			]);
			$pdfString = $pdfString->generate();
			$mail->addStringAttachment($pdfString, $a['invoice_no'] . '.pdf');
		}
		$mail->Subject = $aMailProp['subject'];
		$mail->Body    = $aMailProp['body'];
		$mail->send();
    // print_r($aMailProp);
    // die();
		// echo 'Message has been sent';
	} catch (Exception $e) {
		// throw new \Exception('Error: Unable to send email');
		throw new \Exception($e->getMessage());
	}
}

function setMailProp(array $ar)
{
	$a = [];
	switch ($ar['e']) {
		case 'test':
			$a['subject'] = 'Here is your info Subject';
			$a['body'] = '<p>Hi <b>User</b> Here is your test email</p>'; // via smtphost:' . $ar['aConf']['smtpHost'] . '.<p>';
			break;
		case 'invoiceSend':
			$a['subject'] = 'Invoice ' . $ar['invoice_no'] . ' from ' . MAIL_NAME;
			// $a['body'] = '<div class="card">Hi ' .ucwords($ar['client_name']) . ',<br><br>
			// 			  A new invoice has been generated for you by ' . MAIL_NAME . '. Here a quick summary:<br><br>
			// 			  Invoice Details: ' . $ar['invoice_no'] . '<br><br>
			// 			  Total Invoice Amount: ' . $ar['amount'] . '<br><br>
			// 			  Due Date: ' . $ar['due_date'] . '<br><br>
			// 			  You can view the invoice of it from the following link:<br><br>
			// 			  ' . site_url() . 'clientinvoice/' . $ar['hash'] . '<br><br>
			// 			  Best regards,<br><br>
			// 			  ' . MAIL_NAME . '</div>';
			$a['body'] = '<div class="card">' . str_replace('{Due Date}', $ar['due_date'], $ar['msg']) . '<br><br>
						  You can view the invoice of it from the following link:<br><br>
						  ' . site_url() . 'clientinvoice/' . $ar['hash'] . '<br><br>
						  Best regards,<br><br>
						  ' . MAIL_NAME . '</div>';
			break;
		case 'invoiceView':
			$a['subject'] = 'Statement Viewed ' . $ar['invoice_no'];
			$a['body'] = '<div class="card">Hello ' . ucwords($ar['admin_name']) . ',<br><br>
						  This is the notification that statement ' . $ar['invoice_no'] . ' has just been viewed by it\'s recipient.<br><br>
						  You can view the details of this statement by following this link:<br><br>
						  ' . site_url() . 'clientinvoice/' . $ar['access_hash'] . '<br><br>
						  This is automatically generted notification';
			break;
			case 'invoicePaidAdmin':
			$a['subject'] = 'New Payment for Invoive '.$ar['invoice_no'].'';
			$a['body'] = 'Hello ' . ucwords($ar['company']) . ',<br><br>
                          This is notification that a payment of ' . $ar['total'] . ' has been added to invoice ' . $ar['invoice_no'] . ' using the following payment. Mark as Paid.<br><br>
                          You can view details of this payment by logging into the control panel at the following link:<br><br>
                          '.site_url().'<br><br>
                          Best regards,<br><br>
                          '. $ar['company'] .'';
			break;
            
			case 'invoicePaidClient':
			$a['subject'] = 'Invoive '.$ar['invoice_no'].' Paid';
			$a['body'] = 'Hello ' . ucwords($ar['client_name']) . ',<br><br>
                          Thank you for your payment of ' . $ar['total'] . ' on ' . $ar['pay_date'] . '<br><br>
                          You can view the payment receipt or download a PDF copy of it by click on the following link:<br><br>
						  ' . site_url() . 'pdf/' . $ar['access_hash'] . '<br><br>
                          Best regards,<br><br>
                          '. $ar['company'] .'';
			break;
		case 'reject':
			$a['subject'] = 'Form Reject';
			$a['body'] = 'Your Form Rejected';
			break;
	}
	return $a;
}
