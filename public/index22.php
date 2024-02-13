<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use PHPMailer\src\PHPMailer;
use PHPMailer\src\SMTP;
use PHPMailer\src\Exception;

require '../vendor/PHPMailer\src\PHPMailer.php';
require '../vendor/PHPMailer\src\SMTP.php';
require '../vendor/PHPMailer\src\Exception.php';

require '../vendor/autoload.php';
require '../src/config/conn.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
 
 
$app = AppFactory::create();
//$app->setBasePath("/nepapi");
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

//$app->addErrorMiddleware(true, true, true);
//With slim v4 you need to add this to be able to get the POST body with getParsedBody(). Else it will be null.
$app->addBodyParsingMiddleware();





// Define app routes





	

//include costume

require '../src/routes/enquiry.php';
//Add '/{routes:.+}' as last route. (Part of enabling CORS)


// Run app
$app->run();

