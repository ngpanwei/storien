var personDb = $.localStorage ;
var personifyModel = {
	// using https://github.com/julien-maurel/jQuery-Storage-API
	isLoggedIn : function() {
		if(personDb.isSet("user.userId")==false) {
			return false ;
		}
		return true ;
	},	
	logOut : function() {
		personDb.remove("user.userId") ;
	},
	getUserId : function() {
		return personDb.get("user.userId") ;
	},
	setUserId : function(userVO) {
		personDb.set("user.detail",userVO) ;
		// @deprecrated - will use userVO from henceforth
		personDb.set("user.userId",userVO.guid) ;
		personDb.set("user.username",userVO.username) ;
		personDb.set("user.creation",userVO.creation) ;
	}
} ; // end of personifyModel

var userInfoService = {
	refresh: function() {
		userVO = personDb.get("user.detail") ;
		if(userVO==null) {
			return ;
		}
		$("#settingUsername").text(userVO.username) ;
		d = new Date(); str = d.getTime() ;
		$("#settingUserIcon").attr("src","./"+userVO.photoPath+"?"+str) ;
		$("#settingUserPhoto").attr("src","./"+userVO.photoPath+"?"+str) ;
	}
		
};
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

var personService = {
	initialize : function() {
		$("#logout").click(function() {
			personService.signOut() ;
		});
	},
	signOut : function() {
		personifyModel.logOut() ;
		appController.start() ;
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
		$("#signinDialog").popup("open") ;
		$.ajax({
			type: "POST",
			url: "app/user/SignIn.php" ,
			dataType : "json",
			data: { 
				email : email , 
				password : password ,
			}
		}).done(function(result) {
			signInService.afterSignIn(result);
		});		
		return false ;
	},
	afterSignIn : function(result) {
		if(result.resultCode=="failed") {
			$("#signinTitle").text("不好意思，登录没法完成") ;
			$("#signinText").text(result.message) ;
			return ;
		}
		appController.signinSuccessful(result.data) ;
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
                    //equalTo:"#password"
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
		$("#registerDialog").popup("open") ;
//		photo = $(form).find("#photo").val() ;
	    	$.ajax({
			type: "POST",
			url: "app/user/Register.php" ,
			dataType : "json",
			data: { 
				teamname : teamname , 
				username : username , 
				email : email , 
				password : password , 
				cpassword : cpassword , 
			} ,
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText) ;
				$("#registerTitle").text("不好意思，系统有些毛病，请在试试") ;
				$("#registerText").text(xhr.status + "  " + thrownError);
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
		appController.registrationSuccessful(result.data) ;
	},		
} ; // end of registerService 
var forgetPasswordService = {
	initialize : function(formId) {
        this.validateForgetPassword(formId) ;
	},
	validateForgetPassword : function(formId) {
        $(formId).validate({
            rules: {
                email: {
                    required: true,
                    email:true
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		forgetPasswordService.submitResetPassword(form) ;
            },
        }); 
    },
	submitResetPassword : function(form) {
		email = $(form).find("#email").val() ;
		alert(email) ;
        $.ajax({
			type: "POST",
			url: "app/user/forgetPassword.php" ,
			dataType : "json",
			data: { 
				email : email , 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText) ;
		    }			
		}).done(function(result) {
			forgetPasswordService.afterResetPassword(result) ;
		});
	    	return false ;
	},
	afterResetPassword : function(data) {
		alert(data.message);
	},
} ; // end of forget password service 


