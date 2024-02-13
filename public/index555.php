<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use PHPMailer\PHPMailer\PHPMailer;
 
require __DIR__ . '/../vendor/autoload.php';
 
$app = AppFactory::create();
//$app->setBasePath("/public");
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/send', function (Request $request, Response $response, $args) {
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
$phpmailer->SMTPDebug  = 1;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
//$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username = 'neptlnacional@gmail.com'; // https://mailmug.net/ create an account
    $phpmailer->Password = 'rsbb rvvl ooea uvoj';
    $phpmailer->setFrom('neptlnacional@gmail.com', 'Mailer');
    $phpmailer->addAddress('lverdial@ibi-worldwide.com', 'Example');     //Add a recipient
 
 
    $phpmailer->isHTML(true);                                  //Set email format to HTML
 
    $phpmailer->Subject = 'Here is the subject';
    $phpmailer->Body    = 'This is the HTML message body <b>in bold!</b>';
    $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
 
    
 if($phpmailer->send())
    {
      $response->getBody()->write("email sent");
    return $response;
    }
    else
    {	$response->getBody()->write("email not sent");
    return $response;
    }


 
    
});
 
$app->run();