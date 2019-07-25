<?php
use App\Etemplate;
use App\GeneralSettings;

if (! function_exists('send_email')) {

    function send_email( $to, $name, $subject, $message)
    {
        $temp = Etemplate::first();
        $gnl = GeneralSettings::first();
        $template = $temp->emessage;
        $from = $temp->esender;
		if($gnl->email_notification == 1)
		{
			$headers = "From: $gnl->title <$from> \r\n";
			$headers .= "Reply-To: $gnl->title <$from> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$mm = str_replace("{{name}}",$name,$template);
			$message = str_replace("{{message}}",$message,$mm);

			mail($to, $subject, $message, $headers);
		}
    }
}


if (! function_exists('send_sms'))
{

    function send_sms( $to, $message)
    {
        $temp = Etemplate::first();
        $gnl = GeneralSettings::first();
        if($gnl->sms_notification == 1)
        {
            $sendtext = urlencode($message);
            $appi = $temp->smsapi;
            $appi = str_replace("{{number}}",$to,$appi);
            $appi = str_replace("{{message}}",$sendtext,$appi);
            $result = file_get_contents($appi);
        }
    }
}


if (! function_exists('notify'))
{
    function notify( $user, $subject, $message)
    {
        send_email($user->email, $user->name, $subject, $message);
        send_sms($user->mobile, strip_tags($message));
    }
}

function Replace($data) {
    $data = str_replace("'", "", $data);
    $data = str_replace("!", "", $data);
    $data = str_replace("@", "", $data);
    $data = str_replace("#", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("*", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("+", "", $data);
    $data = str_replace("=", "", $data);
    $data = str_replace(",", "", $data);
    $data = str_replace(":", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace("?", "", $data);
    $data = str_replace("  ", "_", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace(".", "-", $data);
    $data = strtolower(str_replace("  ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace(" ", "-", $data));
    $data = strtolower(str_replace("__", "-", $data));
    return str_replace("_", "-", $data);
}