<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use PHPMailer\PHPMailer\PHPMailer;
 
require __DIR__ . '/../vendor/autoload.php';
 



require '../src/config/conn.php';



/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->post('/hello/add', function (Request $request, Response $response, $args) {
   $params = (array)$request->getParsedBody();
   $foo = $params['name'];
$sql="INSERT INTO `email_verify`(`author`, `email`, `enquiry`, `code`) VALUES (:name,:email,:enquiry,:code)";

$random = substr(md5(mt_rand()), 0, 7);
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $params['name']);
       $stmt->bindParam(':email',$params['email']);
        $stmt->bindParam(':enquiry',$params['enquiry']);
       // $stmt->bindParam(':des',$params['des']);
		$stmt->bindParam(':code',$random);
        $stmt->execute();


$phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    //$phpmailer->SMTPDebug  = 1;
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
      $msg='{"result":200,"text":"success"}';

$response->getBody()->write($msg);

 return $response;
    }
    else
    {	$msg='{"result":200,"text":"success"}';

$response->getBody()->write($msg);

 return $response;
    }






    
   
});

require '../src/routes/enquiry.php';

// Run app
$app->run();