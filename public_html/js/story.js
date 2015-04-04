var updateStoryService = {
	initialize : function() {
        this.validateUpdateStory() ;
	},
	validateUpdateStory : function() {
        $("#storyForm").validate({
            rules: {
            		storyText: {
                    required: true,
                },
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            },
            submitHandler: function(form) {
            		updateStoryService.submitStory(form) ;
            },
        }); 
    },
    submitStory : function(form) {
		userGuid = personifyModel.getUserId() ;
		activity = activityModel.getCurrentActivity() ;
		activityGuid = activity.creation;
		storyText = $(form).find("#storyText").val() ;
		alert(userGuid + ":" + activityGuid + ":" + storyText) ;
        $.ajax({
			type: "POST",
			url: "app/activity/UpdateStory.php" ,
			dataType : "json",
			data: { 
				userGuid : userGuid , 
				activityGuid : activityGuid ,
				storyText : storyText ,
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText) ;
		    }			
		}).done(function(result) {
			updateStoryService.afterStorySubmission(result) ;
		});
	    	return false ;
	},
	afterStorySubmission : function(data) {
		alert(data.message);
	},
} ; // end of update story service 
