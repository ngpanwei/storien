var personDb = $.localStorage ;
var personifyModel = {
	// using https://github.com/julien-maurel/jQuery-Storage-API
	isLoggedIn : function() {
		if(personDb.isSet("personify.userId")==false) {
			return false ;
		}
		return true ;
	},	
	logOut : function() {
		personDb.remove("personify.userId") ;
	},
	setUserId : function(userVO) {
		personDb.set("personify.userId",userVO.guid) ;
		personDb.set("personify.username",userVO.username) ;
	}
} ; // end of personifyModel

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
		 // alert(result.message);
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
			url: "app/user/ResetPassword.php" ,
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


