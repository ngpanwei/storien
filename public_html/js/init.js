var appController = {
	initialize : function() {
		// debug，只验证不提交表单
		// validatorService.initialize();
		signInService.initialize("#signInForm") ;
		registerService.initialize("#registrationForm") ;
		resetPasswordService.initialize("#resetPasswordForm") ;
		personService.initialize() ;
		syncService.initialize() ;
	},
	registrationSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		window.location.hash = "pgPersonHome";
		activityListService.syncActivityList()  ;
	},
	signinSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		window.location.hash = "pgPersonHome";
		activityListService.syncActivityList()  ;
	},
	start : function() {
		if(personifyModel.isLoggedIn()==true) {
			window.location.hash = "pgPersonHome";
			activityListService.refreshActivityList() ;
		} else {
			window.location.hash = "pgSignIn";
		}			
	}
} ; // end of appController 
$(document).ready(function() {
	appController.initialize() ;
	appController.start() ;
}) ;
