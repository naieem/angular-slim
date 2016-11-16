(function(){

	// User Listing Controller
	angular.module('app')
		.controller('UsersListingCtrl',UsersListingCtrl);

	// Initialize Controller
	UsersListingCtrl.$inject = ['$scope','users','UserService'];
	function UsersListingCtrl($scope,users,UserService){
		var ul = this;
		ul.users = users;

		$scope.selectedUser = {};

		// Set User to be updated
		$scope.editUser = function(user){
			angular.copy(user,$scope.selectedUser);
		}

		// Update User and Update User Lists
		$scope.updateUser = function(){
			UserService.updateUser($scope.selectedUser,function(response){
				if(response.data.status==true){
					alert("User Updated");
					UserService.getAllUsers()
						.then(function(response){
							ul.users = response;
						});
					$scope.errors = {};
					$scope.selectedUser = {};
					$("#editModal").modal('hide');
				}else{
					$scope.errors = response.data.errors;
				}
			});
		}

		// Delete User
		$scope.deleteUser = function(user,index){
			UserService.deleteUser(user,function(response){
				if(response.status==true){
					ul.users.splice(index,1);
				}else{
					
				}

			});
		}
	}

})();