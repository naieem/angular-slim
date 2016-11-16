(function(){

	// Init App Module
	var app = angular.module("app",['ngRoute','ngCookies']);

	// Initialize Back End Constant
	app.constant('backend_url','http://localhost/ang-slim/api');

	// Init Config
	app.config(config);
	config.$inject = ['$routeProvider'];
	function config($routeProvider){
		$routeProvider
			.when('/',{
				template : 'Hello World'
			})
			.when('/users/create',{
				templateUrl : 'angular/users/create.html',
				controller  : 'UsersCreateCtrl',
				controllerAs : 'uc',
			})
			.when('/users/login',{
				templateUrl : 'angular/users/login.html',
				controller  : 'UsersLoginCtrl',
				controllerAs : 'ul',
			})
			.when('/users',{
				templateUrl : 'angular/users/listing.html',
				controller  : 'UsersListingCtrl',
				controllerAs : 'ul',
				resolve     : {
					users : function(UserService){
						return UserService.getAllUsers();
					}
				}
			})
			.otherwise({
				redirectTo : '/'
			});
	}

	// Init Run
	app.run(run);
	run.$inject = ['$rootScope','$location','$cookies'];
	function run($rootScope,$location,$cookies){
		$rootScope.globals = $cookies.get('globals') || {};
			
		/**
		 * Check Globals
		 */
		if(!jQuery.isEmptyObject($rootScope.globals)){
			$rootScope.isLoggedIn = true;
		}
		

		// Monitor Route Changes
		$rootScope.$on('$locationChangeStart',function(event,next,current){
			console.log($rootScope.globals);
		});

		/**
		 * Logout User
		 */
		$rootScope.logout = function(){
			$rootScope.globals 		= {};
			$rootScope.isLoggedIn   = false;
			$cookies.remove('globals');
		}

	}

})();