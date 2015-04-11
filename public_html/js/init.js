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
		postStoryService.initialize() ;
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
        return appController.getUrlVars()[name];
    },
	start : function() { 
        var code = appController.getUrlVar('code');
        if(code != undefined && code == personifyModel.getUserId()){
            userGuid = personifyModel.getUserId() ;
            $.ajax({
                type: "POST",
                url: "app/user/ConfirmUser.php" ,
                dataType : "json",
                data: { 
                    userGuid : userGuid 
                }
            }).done(function(result) {
                if(result.resultCode=="success") {
                    //延时2秒跳转刷新
                    setTimeout(function () {
                        window.location.hash = "pgWelcomeConfirmation";
                        userInfoService.refresh() ;
                        activityListService.refreshActivityList() ;
                    }, 2000); 
                }               
            });		
            return false ;           
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
	appController.initialize() ;
	appController.start() ;
}) ;
