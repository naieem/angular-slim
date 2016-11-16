(function(){

	angular.module("app")
		.controller("UsersLoginCtrl",UsersLoginCtrl);

	UsersLoginCtrl.$inject = ['$scope','$rootScope','UserService','AuthService'];
	function UsersLoginCtrl($scope,$rootScope,UserService,AuthService){

		$scope.user = {};

		$scope.login = function(){
			UserService.login($scope.user,function(response){
				if(response.status==true){
					AuthService.setCredentials(response.user);
					$rootScope.isLoggedIn = true;
				}else{
					alert('Account not found');
				}
			});
		}


	}

})();