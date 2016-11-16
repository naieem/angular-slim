(function(){

	// User Create Controller
	angular.module('app')
		.controller('UsersCreateCtrl',UsersCreateCtrl);

	// Initialize Controller
	UsersCreateCtrl.$inject = ['$scope','UserService','$timeout','$location'];
	function UsersCreateCtrl($scope,UserService,$timeout,$location){

		// User Object
		$scope.user = {};

		// Save User
		$scope.saveUser = function(){
			UserService.saveUser($scope.user,function(response){

				if(response.data.status==true){
					alert("User Saved!");
					$timeout(function(){
						$location.path("/users");
					},500);

				}else{
					$scope.errors = response.data.errors;
				}

			});
		}
	}

})();