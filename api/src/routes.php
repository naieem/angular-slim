<?php
// Routes
spl_autoload_register(function ($classname) {
    require ("libs/" . $classname . ".php");
});

/**
 * Get All Users
 */
$app->get('/users',function($request,$response,$args){
	$stmt = $this->pdo_database->query("SELECT * FROM users");
	$stmt->execute();
	$result = array();
	while($row = $stmt->fetch()){
		$result[] = $row;
	}
	$response->withJSON($result);
	return $response;
});




/**
 * Get Single User
 */
$app->get('/users/{id}',function($request,$response,$args){
	$stmt = $this->pdo_database->prepare("SELECT * FROM users WHERE id=:id");
	$stmt->bindParam("id",$args['id']);
	$stmt->execute();
	$response->withJSON($stmt->fetch());
	return $response;
});

/**
 * Login User
 */
$app->post('/users/login',function($request,$response,$args){
	$post = $request->getParsedBody();
	$stmt = $this->pdo_database->prepare("SELECT * FROM users WHERE username=:username AND userpassword=:password");
	$stmt->bindParam("username",$post['username']);
	$stmt->bindParam("password",$post['password']);
	$stmt->execute();

	if($stmt->rowCount() > 0){
		$response->withJSON(array(
			"status" => TRUE,
			"user"   => $stmt->fetch()
		));
	}else{
		$response->withJSON(array(
			"status" => FALSE
		));
	}	
	return $response;

});

/**
 * Create New User
 */
$app->post('/users',function($request,$response,$args){

	// GET Post Body
	$post = $request->getParsedBody();
		
	// Initialize Validator
	$validator = new MyGUMP($this->pdo_database);

	// Validation Rules
	$rules = array(
		"username" => "required|is_unique,users",
		"password" => "required"
	);
	
	// Run Validation
	if($validator->validate($post,$rules)===TRUE){
		$stmt = $this->pdo_database->prepare("INSERT INTO users(`username`,`userpassword`) VALUES(:username,:password) ");

		$stmt->bindParam("username",$post['username']);
		$stmt->bindParam("password",$post['password']);
		$stmt->execute();
		$response->withJSON(array(
			"id" => $this->pdo_database->lastInsertId(),
			"status" => TRUE
		));
	}else{
		$response->withJSON(array(
			"status" => FALSE,
			"errors" => $validator->get_errors_array()
		));
	}

	// Return JSON
	return $response;
});


/**
 * Update A User
 */
$app->put('/users/{id}',function($request,$response,$args){

	$id = $args['id'];
	// GET Post Body
	$post = $request->getParsedBody();
		
	// Initialize Validator
	$validator = new MyGUMP($this->pdo_database);

	// Validation Rules
	$rules = array(
		"username" => "required|is_unique,users ".$id,
		"userpassword" => "required"
	);
	
	// Run Validation
	if($validator->validate($post,$rules)===TRUE){
		$stmt = $this->pdo_database->prepare("UPDATE users SET username=:username,userpassword =:password WHERE id=:id");
		$stmt->bindParam("username",$post['username']);
		$stmt->bindParam("password",$post['userpassword']);
		$stmt->bindParam("id",$id);
		$stmt->execute();
		$response->withJSON(array(
			"status" => TRUE,
			"id" => $id
		));
	}else{
		$response->withJSON(array(
			"status" => FALSE,
			"errors" => $validator->get_errors_array()
		));
	}
	return $response;
});

/**
 * Delete User
 */
$app->delete('/users/{id}',function($request,$response,$args){
	$id = $args['id'];
	$stmt = $this->pdo_database->prepare("DELETE FROM users WHERE id=:id");
	$stmt->bindParam("id",$id);
	$stmt->execute();
	$response->withJSON(array(
		"status" => TRUE
	));
	return $response;
});


