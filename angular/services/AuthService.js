(function(){
	
	/**
	 * Authentication Service
	 */
	angular.module("app")
		.factory("AuthService",AuthService);

	/**
	 * Initialize AuthService
	 */
	AuthService.$inject = ['$rootScope','$http','$cookies'];
	function AuthService($rootScope,$http,$cookies){

		// Initialize Service Object
		var service = {};

		/**
		 * Set User Credentials
		 * @param {Object} user 
		 */
		service.setCredentials = function(user){
			$cookies.put('globals',user);
			$rootScope.isLoggedIn = true;
		}	

		/**
		 * Logout User
		 */
		service.logout = function(){
			$cookie.remove('globals');
			$rootScope.globals = {};
			$rootScope.isLoggedIn = false;
		}

		// Return Service
		return service;
	}

})();