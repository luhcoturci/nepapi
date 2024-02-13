<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Factory\AppFactory;

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
//$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
// Define app routes






// Get All Customers




$app->get('/api/costumer', function (Request $request, Response $response) {
    //$name = $args['name'];
    //$response->getBody()->write("Hello");
    //return $response;
	
	// Get All Customers
//$app->get('/api/customers', function(Request $request, Response $response){
    $sql = "SELECT * FROM costumer";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
       // echo json_encode($customers);
		
		$response->getBody()->write(json_encode($customers));
		return $response;
		
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }

	
});

// Get All sigle
$app->get('/api/costumer/{id}', function (Request $request, Response $response,$args) {
    
	$id = $request->getAttribute('id');

    $sql = "SELECT * FROM costumer WHERE id = $id";

	
//$app->get('/api/customers', function(Request $request, Response $response){
    //$sql = "SELECT * FROM costumer";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		//$row = $sth1->fetch(PDO::FETCH_ASSOC);
if ($customers) {
	
	$customers=array('code' => 200, 'data' => $customers);
	
	$response->getBody()->write(json_encode($customers));
}else{
	$customers='null';
	$customers=array('code' => 201, 'data' => $customers);
	$response->getBody()->write(json_encode($customers));
		//return $response;
}
        $db = null;
       // echo json_encode($customers);
		
		
		return $response;
		
    } catch(PDOException $e){
        $customers='{"error": {"text": '.$e->getMessage().'}';
		$response->getBody()->write($customers);
		return $response;
    }

	
});



// Add Customer
	$app->post('/api/costumer/add', function(Request $request, Response $response){
   //$name = $request->post('name');
    //  $contact = $request->post('contact');
       //$address = $request->post('address');
	   
	//   $paramValue = $app->request()->post('paramName');
	
	$params = (array)$request->getParsedBody();
	
	//$response->getBody()->write(json_encode($params['address']));
   // return $response;

   // $sql = "INSERT INTO costumer (name,contact,address) VALUES ('dasdasd','sdasd','dasdasd')";
$sql="INSERT INTO `costumer`(`name`, `contact`, `address`, `code`) VALUES (:name,:contact,:address,:code)";
    try{
        // Get DB Object
		$random = substr(md5(mt_rand()), 0, 7);
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $params['name']);
       $stmt->bindParam(':contact',$params['contact']);
        $stmt->bindParam(':address',$params['address']);
        $stmt->bindParam(':code',$random);

        $stmt->execute();
		
		$customers='{"notice": {"text": "Customer Added"}';
$response->getBody()->write($customers);
		return $response;
     //   echo '{"notice": {"text": "Customer Added"}';

    } catch(PDOException $e){
        $customers='{"error": {"text": '.$e->getMessage().'}';
		$response->getBody()->write($customers);
		return $response;
    }
	
});

// Add Customer
	$app->post('/api/enquiry/add', function(Request $request, Response $response){
   //$name = $request->post('name');
    //  $contact = $request->post('contact');
       //$address = $request->post('address');
	   
	//   $paramValue = $app->request()->post('paramName');
	
	$params = (array)$request->getParsedBody();
	
	//$response->getBody()->write(json_encode($params['address']));
   // return $response;

   // $sql = "INSERT INTO costumer (name,contact,address) VALUES ('dasdasd','sdasd','dasdasd')";
$sql="INSERT INTO `enquiry`(`autor`, `email`, `enquiry`) VALUES (:autor,:email,:enquiry)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':autor', $params['name']);
       $stmt->bindParam(':email',$params['email']);
        $stmt->bindParam(':enquiry',$params['enquiry']);
       

        $stmt->execute();
		
		$customers='{"notice": {"text": "enquiry created"}';
$response->getBody()->write($customers);
		return $response;
     //   echo '{"notice": {"text": "Customer Added"}';

    } catch(PDOException $e){
        $customers='{"error": {"text": '.$e->getMessage().'}';
		$response->getBody()->write($customers);
		return $response;
    }
	
});


// Delete Customer
$app->delete('/api/costumer/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM costumer WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        $customers='{"notice": {"text": "Customer Deleted"}';
		$response->getBody()->write($customers);
		return $response;
    } catch(PDOException $e){
       $customers='{"error": {"text": '.$e->getMessage().'}';
		$response->getBody()->write($customers);
		return $response;
    }
});


// Update Customer
$app->put('/api/costumer/update/{id}', function(Request $request, Response $response, $args){
   // $id = $request->getAttribute('id');
	 $id=$args['id'];
	
	//$sth1 = $this->db->prepare("SELECT * FROM tasks WHERE id=:id");
//$sth1->bindParam("id", $args['id']);

	//$params = (array)$request->getParam('name');
	//$name =$request->PUT('name');
	
	  //$response->getBody()->write($params);
		//return $response;
		$params = $request->getParsedBody();
	
    //$name = $request->getParam('name');
    //$contact = $request->getParam('contact');
    //$address = $request->getParam('address');
        $sql = "UPDATE costumer SET
				name 	= :name,
				contact		= :contact,
                address 	= :address
              
			WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
		
		$stmt->bindParam(':name', $params['name']);
      $stmt->bindParam(':contact',$params['contact']);
        $stmt->bindParam(':address',$params['address']);

       // $stmt->bindParam(':name', $name);
        
       //$stmt->bindParam(':contact',      $contact);
       
       // $stmt->bindParam(':address',    $address);
        

        $stmt->execute();

      $customers='{"notice": {"text": "Customer Updated"}';
	  $response->getBody()->write($customers);
		return $response;

    } catch(PDOException $e){
      $customers='{"error": {"text": '.$e->getMessage().'}';
	  $response->getBody()->write($customers);
		return $response;
    }
});
