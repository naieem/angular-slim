(function(){

	// Init App Module
	var app = angular.module("app",['ngRoute','ngCookies']);

	// Initialize Back End Constant
	app.constant('backend_url','http://localhost/ang-slim/api');

	// Nav Controller
	app.controller("NavController",NavController);

	NavController.$inject = ['$rootScope','$scope'];
	function NavController($rootScope,$scope){
		$rootScope.$on('loginSuccess',function(event,args){
			$scope.isLoggedIn = true;
		});

		$rootScope.$on('logoutSuccess',function(event,args){
			$rootScope.isLoggedIn = false;
		});
	}

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
				ridirectAuth : true
			})
			.when('/users',{
				templateUrl : 'angular/users/listing.html',
				controller  : 'UsersListingCtrl',
				controllerAs : 'ul',
				resolve     : {
					users : function(UserService){
						return UserService.getAllUsers();
					}
				},
				requireAuth : true
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
		$rootScope.$on('$routeChangeStart',function(event,next){
			if(next.requireAuth===true){
				if($rootScope.isLoggedIn===true){
					console.log("Is logged in detected");
				}else{
					console.log("Still not");
					$location.path('/users/login');
				}
			}else if(next.ridirectAuth==true){
				if($rootScope.isLoggedIn===true){
					$location.path('/users');
				}
			}
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