var changePasswordService = {
	initialize : function() {
        this.validateSignIn() ;
	},
	validateSignIn : function() {
        $("#changePasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8
                },
                cpassword: {
                    required: true,
                    minlength: 8
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		changePasswordService.submitNewPassword(form) ;
            },            
        }); 
    },
	submitNewPassword : function(form) {
		userGuid = personifyModel.getUserId() ;
		opassword = $(form).find("#opassword").val() ;
		password = $(form).find("#password").val() ;
		cpassword = $(form).find("#cpassword").val() ;
		$.ajax({
			type: "POST",
			url: "app/user/ChangePassword.php" ,
			dataType : "json",
			data: { 
				userGuid : userGuid ,
				opassword : opassword ,
				password : password ,
				cpassword : cpassword ,
			}
		}).done(function(result) {
			changePasswordService.afterChangePassword(result);
		});		
		return false ;
	},
	afterChangePassword : function(result) {
		if(result.resultCode=="failed") {
			alert("OK") ;
			return ;
		}
	},
} ; // end of changePasswordService 
var changeEmailService = {
	initialize : function() {
        this.validateSignIn() ;
	},
	validateSignIn : function(id) {
        $("#changeEmailForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		changeEmailService.submitEmail(form) ;
            },            
        }); 
    },
    submitEmail : function(form) {
		userGuid = personifyModel.getUserId() ;
		email = $(form).find("#email").val() ;
		$.ajax({
			type: "POST",
			url: "app/user/ChangeEmail.php" ,
			dataType : "json",
			data: {
				userGuid : userGuid ,
				email : email , 
			}
		}).done(function(result) {
			changeEmailService.afterChangeEmail(result);
		});		
		return false ;
	},
	afterChangeEmail : function(result) {
		if(result.resultCode=="failed") {
		}
	},
} ; // end of changeEmailService 
var changeUsernameService = {
	initialize : function() {
        this.validateSignIn() ;
	},
	validateSignIn : function() {
        $("#changeUsernameForm").validate({
            rules: {
                username: {
                    required: true,
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		changeUsernameService.submitSignIn(form) ;
            },            
        }); 
    },
	submitSignIn : function(form) {
		userGuid = personifyModel.getUserId() ;
		username = $(form).find("#username").val() ;
		$.ajax({
			type: "POST",
			url: "app/user/ChangeUsername.php" ,
			dataType : "json",
			data: { 
				userGuid : userGuid , 
				username : username ,
			}
		}).done(function(result) {
			changeUsernameService.afterSignIn(result);
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
} ; // end of changeUsernameService