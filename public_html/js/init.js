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
    changeEmailSuccessful : function(userVO) {
		personifyModel.setUserId(userVO) ;
		userInfoService.refresh() ;
		window.location.hash = "pgPersonSettings";
		activityListService.syncActivityList()  ;
	},
    changePasswordSuccessful : function(userVO) {
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
        var code = $.getUrlVar('code');
        if(code == personifyModel.getUserId()){
            window.location.hash = "pgWelcomeConfirmation";
            userInfoService.refresh() ;
			activityListService.refreshActivityList() ;
            return ;
        }
        
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
    //jQuery 获取URL请求参数
    $.extend({
        getUrlVars: function(){
          var vars = [], hash;
          var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
          for(var i = 0; i < hashes.length; i++)
          {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
          }
          return vars;
        },
        getUrlVar: function(name){
          return $.getUrlVars()[name];
        }
     });
    
	appController.initialize() ;
	appController.start() ;
}) ;
