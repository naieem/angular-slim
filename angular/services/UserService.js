(function(){

	angular.module('app')	
		.factory("UserService",UserService);


	// Initialize UserService
	UserService.$inject = ['$http','backend_url'];
	function UserService($http,backend_url){

		// Initialize Object
		var service = {};

		// Get All Users
		service.getAllUsers = function(){
			return $http.get(backend_url+"/public/users")
					.then(function(response){
						return response.data;
					});
		}

		// Save User
		service.saveUser = function(user,callback){
			$http.post(backend_url+"/public/users",user)
				.then(callback);
		}

		// Update User
		service.updateUser = function(user,callback){
			$http.put(backend_url+"/public/users/"+user.id,user)
				.then(callback);
		}


		// Delete User
		service.deleteUser = function(user,callback){
			$http.delete(backend_url+"/public/users/"+user.id)
				.then(function(response){
					callback(response.data);
				});
		}

		// Login User
		service.login = function(user,callback){
			$http.post(backend_url+"/public/users/login",user)
				.then(function(response){
					callback(response.data);
				});
		}

		
		// Return Object	
		return service;
	}

})();