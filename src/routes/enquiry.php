<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Factory\AppFactory;
use PHPMailer\PHPMailer\PHPMailer;
//require '../vendor/autoload.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
 
 
//$app = AppFactory::create();
//$app->setBasePath("/slimapp");
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
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

// Define app routes
// Add enquiry
	$app->post('/api/enquiry/add', function(Request $request, Response $response){
   
	
	$params = (array)$request->getParsedBody();
	
$sql="INSERT INTO `email_verify`(`author`, `email`, `enquiry`, `code`) VALUES (:name,:email,:enquiry,:code)";
    try{
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
		
		$sended=sendVerificationEmail($params['email'],$params['name'],$random);
		
		if($sended==="True"){
		
		$msg='{"result":200,"text":"success"}';
		
		$response->getBody()->write($msg);

		return $response;
		
		
		}else{
		$msg='{"result":500,"text":"Failed"}';
		
		$response->getBody()->write($msg);

		return $response;
		}
    

    } catch(PDOException $e){
        $msg='{"result":"400","text": '.$e->getMessage().'}';
		$response->getBody()->write($msg);
		return $response;
    }
	
});


//Function to send mail, 
function sendVerificationEmail($email,$id,$code)
{      
    $mail = new PHPMailer;

   // $mail->SMTPDebug=0;
    $mail->isSMTP();

    $mail->Host="smtp.gmail.com";
    $mail->Port=587;
    $mail->SMTPSecure="tls";
    $mail->SMTPAuth=true;
    $mail->Username="neptlnacional@gmail.com";
    $mail->Password="rsbb rvvl ooea uvoj";

    $mail->addAddress($email,$id);
    $mail->Subject="National Enquiry Point Verification Code";
   // $mail->isHTML();
    $mail->Body=" Karu ".$id.",

Obrigadu ba submete ona ita nia pedidu ba Portal Inkéritu Nasional Autoridade Aduaneira. Atu asegura autentisidade husi ita nia pedidu no atu ajuda mantein seguransa ba ita nia sistema, ami husu ita atu verifika ita nia enderesu email liuhusi uza kódigu konfirmasaun tuir mai:

Kódigu Konfirmasaun: ".$code."

Iha ne’e mak oinsa ita atu kontinua:
1. Fila ba pajina submisaun inkéritu.
2. Hatama kódigu konfirmasaun iha area designadu.
3. Submete kódigu hodi finaliza ita nia inkéritu. 

Kódigu ida ne’e validu ba minutu 10 husi tempu email ida ne’e. karik kódigu espira, favór submete inkéritu foun liuhusi vizita Portal Komérsiu Alfândega (https://customs.gov.tl/enquiry-point). 

Kumprimentus,

Ekipa Portal Inkéritu Nasional
Autoridade Aduaneira Timor-Leste

-----------------------------------------

Dear ".$id.",
Thank you for submitting your enquiry to the Customs Authority National Enquiry Point. To ensure the authenticity of your request and to help maintain the security of our system, we require you to verify your email address by using the following confirmation code:

Confirmation Code: ".$code."

Here is how you can proceed:
1. Return to the enquiry submission page.
2. Enter the confirmation code in the designated field.
3. Submit the code to finalize your enquiry.

This code is valid for 10 minutes from the time of this email. If this code expires, please submit a new enquiry by visiting the Customs Trade Portal (https://customs.gov.tl/enquiry-point).

Regards,

National Enquiry Point Team
Timor-Leste Customs Authority
";

    $mail->From="neptlnacional@gmail.com";
    $mail->FromName="NEP";

    if($mail->send())
    {
       
	  $sended="True";
	  return $sended;
    }
    else
    {	
	    $sended="False";
		return $sended;
    }


}


// Get All sigle



$app->get('/api/enquiry/{id}', function (Request $request, Response $response,$args) {  
	

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
	$code = $request->getAttribute('id');

       // $stmt = $db->query($sql1);
 	$stmt = $db->prepare("SELECT * FROM `email_verify` where `code` =:id");
	 $stmt->bindParam(':id', $code);
	 $stmt->execute();

	
	// $stmt->execute();



        $enquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
 if ($enquiries) {
	$sql="INSERT INTO `enquiry`(`author`, `email`, `enquiry`, `created_on`) VALUES (:name,:email,:enquiry,:created_on)";
	
	foreach($enquiries as $row) {
        $id = $row['id'];
        $autor = $row['author'];
	$email = $row['email'];
	$enquiry = $row['enquiry'];
	$date = new DateTime();
        $ts = $date->getTimestamp();
		$db = new db();
        // Connect
        $db = $db->connect();
	
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $autor);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':enquiry',$enquiry);
        $stmt->bindParam(':created_on',$ts);
		 //$stmt->bindParam(':views',$v);

        $stmt->execute();
	}
	//$enquiries=array('code' => 200, 'data' => $id);
	
	 $sql2 = "DELETE FROM `email_verify` WHERE `id` =:id";
	 $db = new db();
        // Connect
     $db1 = $db->connect();
     $stmt1 = $db1->prepare($sql2);
	 $stmt1->bindParam(':id', $id);
	 $stmt1->execute();
	
	
	//send email notification
	$sended=sendsuccessfullEmail($email,$autor,$enquiry,$id);
	
	if($sended==="True"){
		
		$msg='{"result":200,"text":"success"}';
		
		$response->getBody()->write($msg);

		return $response;
		
		
		}else{
		$msg='{"result":500,"text":"Failed"}';
		
		$response->getBody()->write($msg);

		return $response;
		}
	
	//$response->getBody()->write(json_encode($autor));
	
	
	}else{
		$msg='{"result":500,"text":"Failed"}';
		
		$response->getBody()->write($msg);

		return $response;
		
	}
        	$db = null;
      		return $response;
		
    } catch(PDOException $e){
        $msg='{"result":"400","text": '.$e->getMessage().'}';
		$response->getBody()->write($msg);
		return $response;
    }

	
});



function sendsuccessfullEmail($email,$autor,$enquiry,$code)
{      
    $mail = new PHPMailer;

   // $mail->SMTPDebug=0;
    $mail->isSMTP();

    $mail->Host="smtp.gmail.com";
    $mail->Port=587;
    $mail->SMTPSecure="tls";
    $mail->SMTPAuth=true;
    $mail->Username="neptlnacional@gmail.com";
    $mail->Password="rsbb rvvl ooea uvoj";

    $mail->addAddress($email,$autor);
    $mail->Subject="National Enquiry Point Email Confirmation";
    
    $mail->Body=" Karu ".$autor.",

Ita nia inkéritu iha okos submete ona ba Portal Inkéritu Nasionál Autoridade Aduaneira ho susesu. Favór haree kópia husi ita nia pedidu iha okos:

Id Inkéritu: ".$code."

Ita nia Inkéritu:  ".$enquiry."


Ita nia inkéritu ne’e importante mai ami no ami sei responde iha loron 5 nia laran iha loron servisu nian. 

Kumprimentus,

Ekipa Portal Inkéritu Nasionál 
Autoridade Aduaneira Timor-Leste
-----------------------------------------

Dear ".$autor.",

Your enquiry below has been successfully submitted to the Customs Authority National Enquiry Point. Please find a copy of your request below:

Enquiry id: ".$code."

Your Enquiry:  ".$enquiry."

Your enquiry is important to us and we will aim to reply within 5 working days. 

Regards,

National Enquiry Point Team
Timor-Leste Customs Authority


";
    $mail->From="neptlnacional@gmail.com";
   // $mail->isHTML(true);
//$mail->AltBody = $body;
    $mail->FromName="NEP";

    if($mail->send())
    {
       
	  $sended="True";
	  return $sended;
    }
    else
    {	
	    $sended="False";
		return $sended;
    }


}
