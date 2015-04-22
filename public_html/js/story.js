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
		storyText = $(form).find("#storyText").val() ;
        $.ajax({
			type: "POST",
			url: api.postStory ,
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
			postStoryService.afterStoryPosting(result.data) ;
		});
	    	return false ;
	},
	afterStoryPosting : function(newActivity) {
		activityListService.updateActivityList(newActivity) ;
	},
}; // 
var updateStoryService = {
	initialize : function() {
	},
	setStoryActivityEmptyForm : function(activity,index) {
		formId = "activity-form-"+activity.creation ;
		html = $("#StoryFormTemplate").html() ;
		html = html.replace("$activityForm",formId) ;
		html = html.replace("$activityButton","activity-button-"+activity.creation) ;
		activityContentId = "#"+activity.creation+"-content" ;
		$(activityContentId).append(html) ;
		$(activityContentId).trigger("create") ;
		buttonId = "#activity-button-"+activity.creation ;
	    $(buttonId).click(function() {
	    		form = $(this).closest('form');
	    		updateStoryService.submitStory(form,activity) ;
	    		return false ;
	    }) ;
	},
	setStoryActivityContent : function(activity,index) {
		html = "<p>" + activity.story + "</p>" ;
		activityContentId = "#"+activity.creation+"-content" ;
		$(activityContentId).append(html) ;
		$(activityContentId).trigger("create") ;
	},
	setStoryActivityForm : function(activity,index) {
		if(activity.story!=null&&activity.story!="") {
			this.setStoryActivityContent(activity,index) ;
		} else {
			this.setStoryActivityEmptyForm(activity,index) ;
		}
	},
    submitStory : function(form,activity) {
		userGuid = personifyModel.getUserId() ;
		activityGuid = activity.creation;
		storyText = $(form).find("#storyText").val() ;
        $.ajax({
			type: "POST",
			url: api.updateStory ,
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
			updateStoryService.afterStorySubmission(result.data) ;
		});
	    	return false ;
	},
	afterStorySubmission : function(newActivity) {
		activityListService.updateActivityList(newActivity) ;
	},
} ; // end of update story service 
