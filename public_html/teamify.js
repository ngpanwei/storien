var teamDb = $.localStorage ;
var teamifyModel = {
	isLoggedIn : function() {
		if(teamDb.isSet("teamify.userId")==false) {
			return false ;
		}
		return true ;
	},	
} ; // end of teamifyModel
var teamService = {		
} ; // end of signInService 
var signInService = {
	initialize : function(formId) {
        this.validateSignIn(formId) ;
	},
	validateSignIn : function(id) {
        $( id ).validate({
            rules: {
                email: {
                    required: true
                },
                password: {
                    required: true
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
			registerService.afterRegistration(result) ;
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
                    required: true
                },
                password: {
                    required: true
                },
                cpassword: {
                    required: true
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
		alert(result.message);
		alert(result.data.username) ;
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
                    required: true
                },
                password: {
                    required: true
                },
                cpassword: {
                    required: true
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
	submitResetPassword : function() {
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
			resetPasswordService.afterRegistration(result) ;
		});
	    	return false ;
	},
	afterResetPassword : function(data) {
		alert(data.message);
	},
} ; // end of forget password service 
var teamifyController = {
	initialize : function() {
		signInService.initialize("#signInForm") ;
		registerService.initialize("#registrationForm") ;
		resetPasswordService.initialize("#resetPasswordForm") ;
	},
	start : function() {
		if(teamifyModel.isLoggedIn()==true) {
			window.location.hash = "pgTeamHome";
		} else {
			window.location.hash = "pgSignIn";
		}
	},	
} ; // end of teamifyController 
$(document).ready(function() {
	teamifyController.initialize() ;
	teamifyController.start() ;
}) ;

