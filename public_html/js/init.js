var appController = {
	initialize : function() {
		// debug，只验证不提交表单
		// validatorService.initialize();
		signInService.initialize() ;
		registerService.initialize() ;
		forgetPasswordService.initialize() ;
		changeUsernameService.initialize() ;
		changePasswordService.initialize() ;
		changeEmailService.initialize() ;
		updateStoryService.initialize() ;
		uploadService.initialize();
		personService.initialize() ;
		syncService.initialize() ;
	},
	registrationSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		userInfoService.refresh() ;
		window.location.hash = "pgPersonHome";
		activityListService.syncActivityList()  ;
	},
	signinSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		userInfoService.refresh() ;
		window.location.hash = "pgPersonHome";
		activityListService.syncActivityList()  ;
	},
    changeUsernameSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		userInfoService.refresh() ;
		window.location.hash = "pgPersonSettings";
		activityListService.syncActivityList()  ;
	},
    uploadSuccessful : function() {
        personifyModel.setUserId(userVO) ;
		userInfoService.refresh() ;
		window.location.hash = "pgPersonSettings";
		activityListService.syncActivityList()  ;
	},
	start : function() {
		if(personifyModel.isLoggedIn()==true) {
			window.location.hash = "pgPersonHome";
			userInfoService.refresh() ;
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
