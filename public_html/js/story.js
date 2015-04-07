var updateStoryService = {
	initialize : function() {
	},
	setStoryActivityForm : function(activity,index) {
		formId = "#activity-" + index ;
		buttonId = "#activity-button-"+index ;
		textId = "#textarea-"+index ;
		$(formId).append("<textarea id='storyText'></textarea>") ;
		$(formId).append("<button class='ui-btn ui-mini' id='activity-button-"+index+"'>提交</button>") ; 
		$(formId).trigger("create") ;
	    $(buttonId).click(function() {
	    		updateStoryService.submitStory($(formId),activity) ;
	    		return false ;
	    }) ;
	},
    submitStory : function(form,activity) {
		userGuid = personifyModel.getUserId() ;
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
				alert("Error:"+xhr.status + "  " + thrownError);
				alert("Error:"+xhr.responseText) ;
		    }			
		}).done(function(result) {
			updateStoryService.afterStorySubmission(result) ;
		});
	    	return false ;
	},
	afterStorySubmission : function(result) {
		alert("submit complete:"+data.message);
	},
} ; // end of update story service 
