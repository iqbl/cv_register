<?php
include_once("../server.php");

$file_name=$_GET['fn'];
$id64dec=$_GET['id'];
$strcod=explode('*',$id64dec);
$id= $strcod[0];
$vid=$strcod[1];
$res=mysql_query("select * from users where username='$id'");
$data=mysql_fetch_array($res);
$email_from=$data['email'];
$usersid = $data['id'];
$client_name    = $data['username'];
if($file_name){
    $link=$vid;
    mysql_query("update vacancies set notify_email = '".mysql_real_escape_string($_GET['email_target'])."' where added_by = '$usersid'");
    $res=mysql_query("select * from vacancies where id='$vid'");
    $data=mysql_fetch_array($res);
    $jobtitle=$data['jobtitle'];
    $id_en=base64_encode($id64dec);
    $qedit_register=mysql_query("insert into edit_register(vacancyid, status, submitdate) values('$link', 'register',now())");
    sendmail($id_en,$file_name, $_GET['email_target'],$id,$jobtitle,$link, $client_name);
    sleep(5);

    header("location:activesearch.php?id=$id_en&konf_email=sukses&file=$file_name");
    //echo $id." - ".$client_ref;

}
function sendmail($id_en,$file_name, $email_target, $id,$jobtitle,$link, $client_name) {
include_once('class.phpmailer.php');
//include_once('sendgrid.php');

$mail             = new PHPMailer(); // defaults to using php "mail()"
$body             = '
<span style="font-size: 12px;color: #000000; ">
Hi '.$client_name.',<br/><br/>
 We are currently updating the emailing system as some clients are having problems with their email forwarding systems. 
We will be continuing to upload the Cvs into your account but will also email you the Cvs each morning to make sure you have them on your systems. 
We apologise for any inconvenience. 


<br><br>

<br>
<br>
Thank You

<p align="left"><font face="Arial" color="#000080">
<span style="font-size: 12px">
<img src="http://ourteam.org.uk/images/maillogo.gif" width="307" height="38"> </span></font></p>
<p align="left">
<span style="FONT-SIZE: 12px">
<font face="Arial" color="#00007d">t +44 (0) 20 7431 6329 &nbsp;<br></font>
<font color="#00007e" face="Arial">f +44 (0) 20 7900 6259<br>
www.ourteam.org.uk<br>
</font>
<font face="Verdana, Helvetica, Arial"><br>
</font>
<font face="Arial" color="#00007e">OurTeam is registered in the UK: 
Carltone House, 307 Finchley Road, London NW3 6EH.
</font></span>
<span style="font-size: 9.0pt; font-family: Arial; color: #00007F">Company no: 6420516</span>
<font color="#00007e" face="Arial"><span style="FONT-SIZE: 12px">
<br>
The opinions expressed within this email represent solely those of the author. 
The information in this Internet email is confidential and may be legally 
privileged. It is intended solely for the addressee. Access to this internet 
email by anyone else is unauthorised. If you are not the intended recipient, any 
disclosure, copying, distribution or any action taken or omitted to be taken in 
reliance on it, is prohibited and may be unlawful.<br>
&nbsp;<br>
This message has been scanned for viruses.<br>
&nbsp;</span></font></p>

</span>
';
$body             = eregi_replace("[\]",'',$body);

$url = 'https://api.sendgrid.com/';
$user = 'iqbal.ifeni';
$pass = 'polban352';

$fileName = $file_name;
//$filePath = dirname("../upl_cv/");
$filePath = dirname("https://otclient.com/upl_cv");
$params = array(

    'api_user'  => $user,
    'api_key'   => $pass,
    'to'        => $email_target,
    'subject'   => 'CV ',
    'html'      => $body,
   // 'text'      => 'hai',
    'from'      => 'enquiries@ourteam.org.uk', 
    //'bcc'     => 'muhamad.iqbal.ifeny@gmail.com',

    //'files['.$fileName.']' => $filePath.'/'.$fileName
    //'files['.$fileName.']' => $filePath.'/'.$fileName

    'files['.$fileName.']' => '@'.$filePath.'/'.$fileName,
  );

print_r($params);

$request =  $url.'api/mail.send.json';

// Generate curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

// print everything out
print_r($response);

// print everything out
// print_r($response);
}


//-------------------------------------

?>


