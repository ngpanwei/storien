var changePasswordService = {
	initialize : function() {
        this.validateChangePassword() ;
	},
	validateChangePassword : function() {
        $("#changePasswordForm").validate({
            rules: {
                opassword: {
                    required: true,
                    minlength: 8
                },
                password: {
                    required: true,
                    minlength: 8
                },
                cpassword: {
                    required: true,
                    minlength: 8
//                    equalTo:"#password"
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		changePasswordService.submitNewPassword(form) ;
            }            
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
				cpassword : cpassword 
			}
		}).done(function(result) {
			changePasswordService.afterChangePassword(result);
		});		
		return false ;
	},
	afterChangePassword : function(result) {
		if(result.resultCode=="failed") {
			$("#changePasswordDialog").popup("open") ;
			$("#changePasswordTitle").text("不好意思，修改没法完成") ;
			$("#changePasswordText").text(result.message) ;
			return ;
		}
        
        $("#changePasswordDialog").popup("open") ;
        $("#changePasswordTitle").text("个人密码修改成功") ;
        $("#changePasswordText").text(result.message) ;
        
        //延时2秒跳转刷新
        setTimeout(function () {
            appController.changePasswordSuccessful(result.data) ;
        }, 2000);           
	}
} ; // end of changePasswordService 
var changeEmailService = {
	initialize : function() {
        this.validateChangeEmail() ;
	},
	validateChangeEmail : function() {
        $("#changeEmailForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		changeEmailService.submitChangeEmail(form) ;
            }         
        }); 
    },
    submitChangeEmail : function(form) {
		userGuid = personifyModel.getUserId() ;
		email = $(form).find("#email").val() ;
		$.ajax({
			type: "POST",
			url: "app/user/ChangeEmail.php" ,
			dataType : "json",
			data: {
				userGuid : userGuid ,
				email : email  
			}
		}).done(function(result) {
			changeEmailService.afterChangeEmail(result);
		});		
		return false ;
	},
	afterChangeEmail : function(result) {
		if(result.resultCode=="failed") {
            $("#changeEmailDialog").popup("open") ;
			$("#changeEmailTitle").text("不好意思，修改没法完成") ;
			$("#changeEmailText").text(result.message) ;
			return ;
		}
        
        $("#changeEmailDialog").popup("open") ;
        $("#changeEmailTitle").text("邮箱修改成功") ;
        $("#changeEmailText").text(result.message) ;
        
        //延时2秒跳转刷新
        setTimeout(function () {
            appController.changeEmailSuccessful(result.data) ;
        }, 2000);        
        
	}
} ; // end of changeEmailService 
var changeUsernameService = {
	initialize : function() {
        this.validateChangeUsername() ;
	},
	validateChangeUsername : function() {
        $("#changeUsernameForm").validate({
            rules: {
                username: {
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            	changeUsernameService.submitChangeUsername(form) ;
            }        
        }); 
    },
	submitChangeUsername : function(form) {
		userGuid = personifyModel.getUserId() ;
		username = $(form).find("#username").val() ;
		$.ajax({
			type: "POST",
			url: "app/user/ChangeUsername.php" ,
			dataType : "json",
			data: { 
				userGuid : userGuid , 
				username : username 
			}
		}).done(function(result) {
			changeUsernameService.afterChangeUsername(result);
		});		
		return false ;
	},
	afterChangeUsername : function(result) {
		if(result.resultCode=="failed") {
            $("#changeUsernameDialog").popup("open") ;
			$("#changeUsernameTitle").text("不好意思，修改没法完成") ;
			$("#changeUsernameText").text(result.message) ;
			return ;
		}
        
        $("#changeUsernameDialog").popup("open") ;
        $("#changeUsernameTitle").text("用户名修改成功") ;
        $("#changeUsernameText").text(result.message) ;
        
        //延时2秒跳转刷新
        setTimeout(function () {
            appController.changePasswordSuccessful(result.data) ;
        }, 2000);     
	}
} ; // end of changeUsernameService
