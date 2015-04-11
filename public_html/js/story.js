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
	setStoryActivityEmptyForm : function(activity,index) {
		html = $("#StoryFormTemplate").html() ;
		html = html.replace("$activityIndex","activity-"+index) ;
		html = html.replace("$activityButton","activity-button-"+index) ;
		activityContentId = "#"+activity.creation+"-content" ;
		$(activityContentId).append(html) ;
		$(activityContentId).trigger("create") ;
		buttonId = "#activity-button-"+index ;
	    $(buttonId).click(function() {
	    		updateStoryService.submitStory($(formId),activity) ;
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
		alert(userGuid + ":" + activityGuid + ":" + storyText) ;
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
			updateStoryService.afterStorySubmission(result) ;
		});
	    	return false ;
	},
	afterStorySubmission : function(result) {
		alert("submit complete:"+data.message);
	},
} ; // end of update story service 
