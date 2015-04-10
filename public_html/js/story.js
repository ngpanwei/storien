var postStoryService = {
	initialize : function() {
		$("#postBtn").click(function() {
			$("#activityPopupMenu").popup("close") ;
			$("#postActivity").toggle() ;
		});
		$("#closePostBtn").click(function() {
			$("#postActivity").toggle() ;
			return false ;
		}) ;
		$("#postStoryBtn").click(function() {
			postStoryService.postStory($("#postStoryForm")) ;
			return false ;
		}) ;
	},
    postStory : function(form) {
		userGuid = personifyModel.getUserId() ;
		storyText = $(form).find("#postText").val() ;
		alert(userGuid + ":" + storyText) ;
        $.ajax({
			type: "POST",
			url: "app/activity/PostStory.php" ,
			dataType : "json",
			data: { 
				userGuid : userGuid , 
				storyText : storyText ,
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert("Error:"+xhr.status + "  " + thrownError);
				alert("Error:"+xhr.responseText) ;
		    }			
		}).done(function(result) {
			$("#postActivity").toggle() ;
			postStoryService.afterStoryPosting(result) ;
		});
	    	return false ;
	},
	afterStoryPosting : function(result) {
		alert("posting complete:"+data.message);
	},
}; // 
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
