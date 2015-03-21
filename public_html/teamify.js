var teamDb = $.localStorage ;
var teamifyModel = {
	// using https://github.com/julien-maurel/jQuery-Storage-API
	isLoggedIn : function() {
		if(teamDb.isSet("teamify.userId")==false) {
			return false ;
		}
		return true ;
	},	
	logOut : function() {
		teamDb.remove("teamify.userId") ;
	},
	setUserId : function(userVO) {
		teamDb.set("teamify.userId",userVO.guid) ;
		teamDb.set("teamify.username",userVO.username) ;
	}
} ; // end of teamifyModel

//debug，只验证不提交表单
var validatorService = {
	initialize: function(){
		jQuery.validator.setDefaults({
		  debug: true,
		  success: "valid"
		});
	}
};
//end of validatorService

var teamService = {
	initialize : function() {
		$("#logout").click(function() {
			alert("logout") ;
		});
	},
	signOut : function() {
		teamifyModel.logOut() ;
	},
} ; // end of signInService 
var signInService = {
	initialize : function(formId) {
        this.validateSignIn(formId) ;
	},
	validateSignIn : function(id) {
        $( id ).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		signInService.submitSignIn(form) ;
            },            
        }); 
    },
	submitSignIn : function(form) {
		email = $(form).find("#email").val() ;
		password = $(form).find("#password").val() ;
		$.ajax({
			type: "POST",
			url: "app/teamify/SignIn.php" ,
			dataType : "json",
			data: { 
				email : email , 
				password : password ,
			}
		}).done(function(result) {
			// registerService.afterRegistration(result) ;
			signInService.afterSignIn(result);
		});		
		return false ;
	},
	afterSignIn : function(data) {
		alert(data.message);
	},
} ; // end of signInService 
var registerService = {
	initialize : function(formId) {
        this.validateRegistration(formId) ;
	},
	validateRegistration : function(formId) {
        $(formId).validate({
            rules: {
                teamname: {
                    required: true
                },
                username: {
                    required: true
                },
                email: {
                    required: true,
                    email:true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                cpassword: {
                    required: true,
                    minlength: 8,
//                    equalTo:"#password"
                },
                photo: {
//                    required: true,
//                    accept: "image/*"
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		registerService.submitRegistration(form) ;
            },
        }); 
    },
	submitRegistration : function(form) {
		teamname = $(form).find("#teamname").val() ;
		username = $(form).find("#username").val() ;
		email = $(form).find("#email").val() ;
		password = $(form).find("#password").val() ;
		cpassword = $(form).find("#cpassword").val() ;
		// @todo 这里弹出一个 popup dialog
		// 成功之后关闭popup
		$("#registerDialog").popup("open") ;
//		photo = $(form).find("#photo").val() ;
	    	$.ajax({
			type: "POST",
			url: "app/teamify/Register.php" ,
			dataType : "json",
			data: { 
				teamname : teamname , 
				username : username , 
				email : email , 
				password : password , 
				cpassword : cpassword , 
			} ,
			error: function (xhr, ajaxOptions, thrownError) {
		        alert(xhr.status + "  " + thrownError);
		    }			
		}).done(function(result) {
			registerService.afterRegistration(result) ;
		});
	    	return false ;
	},
	afterRegistration : function(result) {
		if(result.resultCode=="failed") {
			$("#registerTitle").text("不好意思，注册没法完成") ;
			$("#registerText").text(result.message) ;
			return ;
		}
		teamifyController.registrationSuccessful(result.data) ;
	},		
} ; // end of registerService 
var resetPasswordService = {
	initialize : function(formId) {
        this.validateResetPassword(formId) ;
	},
	validateResetPassword : function(formId) {
        $(formId).validate({
            rules: {
                email: {
                    required: true,
                    email:true
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                cpassword: {
                    required: true,
                    minlength: 8,
                    equalTo:"#password"
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            	resetPasswordService.submitResetPassword(form) ;
            },
        }); 
    },
	submitResetPassword : function(form) {
		email = $(form).find("#email").val() ;
		password = $(form).find("#password").val() ;
		cpassword = $(form).find("#cpassword").val() ;
    	$.ajax({
			type: "POST",
			url: "app/teamify/ResetPassword.php" ,
			dataType : "json",
			data: { 
				email : email , 
				password : password , 
				cpassword : cpassword , 
			}
		}).done(function(result) {
			resetPasswordService.afterResetPassword(result) ;
		});
	    	return false ;
	},
	afterResetPassword : function(data) {
		alert(data.message);
	},
} ; // end of forget password service 
var teamifyController = {
	initialize : function() {
		//debug，只验证不提交表单
		// validatorService.initialize();
		signInService.initialize("#signInForm") ;
		registerService.initialize("#registrationForm") ;
		resetPasswordService.initialize("#resetPasswordForm") ;
		teamService.initialize() ;
	},
	start : function() {
		if(teamifyModel.isLoggedIn()==true) {
			window.location.hash = "pgTeamHome";
		} else {
			window.location.hash = "pgSignIn";
		}
	},	
	registrationSuccessful : function(userVO) {
		teamifyModel.setUserId(userVO) ;
		window.location.hash = "pgTeamHome";
	},
} ; // end of teamifyController 
$(document).ready(function() {
	teamifyController.initialize() ;
	teamifyController.start() ;
}) ;

