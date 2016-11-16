(function(){

	// Error Message Directive
	angular.module("app")
		.directive("errorMessage",function(){
			return {
				restrict : "E",
				template : "<span class='text-danger pull-right'>{{trigger}}<span>",
				scope    : {
					trigger : "@"
				}
			}
		});

})();