var appDb = $.localStorage ;
var activityModel = {
	getUserGuid : function() {
		userDetail = appDb.get("user.detail") ;
		return userDetail.guid ;
	},
	getLastUpdate : function() {
		if(appDb.isSet("activity.lastUpdate")!=false) {
			return appDb.get("activity.lastUpdate") ;
		}
		if(appDb.isSet("user.creation")!=false) {
			return appDb.get("user.creation") ;
		}
		return "unknown" ;
	} ,
	updateActivityList : function(newActivityList) {
		if(newActivityList==null) {
			alert("activities not defined") ;
		}
		appDb.set("activity.activities",newActivityList) ;
	},
	getActivityList : function() {
		return appDb.get("activity.activities") ;
	},
	getActivityByCreation : function(creation) {
		activityList = this.getActivityList() ;
		for(i=0;i<activityList.length;i++) {
			activity = activityList[i] ;
			if(activity.creation==creation)
				return activity;
		}
		return null;
	},
	getCurrentActivity : function() {
		return appDb.get("activity.currentActivity") ;
	},
	setCurrentActivity : function(activity) {
		return appDb.set("activity.currentActivity",activity) ;
	},
} ;
var syncService = {
	initialize : function() {
		$("#syncBtn").click(function() {
			$("#activityPopupMenu").popup("close") ;
			syncService.sync() ;
		});
		$("#signifyBtn").click(function() {
			$("#activityItemPopupMenu").popup("close") ;
		});
	},
	sync : function() {
		activityListService.syncActivityList() ;
	},
};
var activityService = {
	initialize : function(content) {
		activity = activityModel.getCurrentActivity() ;
		$("#activityContent"+activity.kind).empty() ;
		$("#activityContent"+activity.kind).append(content) ;
		eval("activityService.initialize"+activity.kind+"();") ;
	},
	initializeInfo : function() {
		alert("initializeInfo") ;
	}
};
var activityListService = {
	initialize : function() {
	},
	setInfoActivityForm : function(activity,Index) {
		return ;
	},
	setStoryActivityForm : function(activity,index) {
		updateStoryService.setStoryActivityForm(activity,index) ;
	},
	updateActivityList : function(newActivity) {
		activities = activityModel.getActivityList() ;
		for (i=0;i<activities.length;i++) {
			activity = activities[i] ;
			if(activity.creation==newActivity.creation) {
				activities[i] = newActivity ;
				this.updateActivityItem(activities[i],i) ;
				return ;
			}
		}
		activities.push(newActivity) ;
		this.addActivityItem(newActivity,activities.length) ;
	},
	getActivityHtml : function(activity,index) {
		html = $("#activityTemplate").html() ;
		html = html.replace("$activityId",activity.creation) ;
		html = html.replace("$activityContentId",activity.creation+"-content") ;
		html = html.replace("$activityTitle",activity.title) ;
		html = html.replace("$activityText",activity.content) ;
		html = html.replace("$activityBtnId",index+"-btn") ;
		html = html.replace("$activityItemPopupMenu",index+"-menu") ;
		html = html.replace("$activityItemPopupMenu",index+"-menu") ;
//		html = html.replace("$signifyBtn",activity.creation+"-signify") ;
//		html = html.replace("$deleteBtn",activity.creation+"-delete") ;
		return html ;
	},
	showPopup : function(activity,index) {
		$('#activityItemPopupMenu').popup("open", {positionTo: detailBtn});
	},
	setActivityHtmlDetail : function(activity,index) {
		detailBtn = "#" + index+"-btn" ;
		alert(detailBtn) ;
		$(detailBtn).click(function() {
			activityListService.showPopup(activity,index) ;
		});
		menu = "#" + index+"-menu" ;
//		signifyBtn = "#" + activity.creation+"-signify" ;
//		deleteBtn = "#" + activity.creation+"-delete" ;
//		$(signifyBtn).click(function(){
//		    $(menu).popup("close") ;
//		}) ;
//		$(deleteBtn).click(function(){
//		    $(menu).popup("close") ;
//		}) ;
		try {
			eval("this.set"+activity.kind+"ActivityForm(activity,index)") ;
		} catch (err) {
			alert(err.message) ;
		}
	},
	updateActivityItem : function(activity,index) {
		html = this.getActivityHtml(activity,index) ;
		div = "#" + activity.creation ;
		$(div).replaceWith(html) ;
		$(div).trigger("create") ;
		this.setActivityHtmlDetail(activity,index) ;
	},
	addActivityItem : function(activity,index) {
		html = this.getActivityHtml(activity,index) ;
		$("#activities").append(html) ;
		$("#activities").trigger("create") ;
		this.setActivityHtmlDetail(activity,index) ;
	},
	activityItemClick : function(element) {
		creation = $(element).attr("activity-creation") ;
		activity = activityModel.getActivityByCreation(creation) ;
		activityModel.setCurrentActivity(activity) ;
		window.location.hash = "pgActivity" + activity.kind ;
		this.fetchContent(activity) ;
	},
	fetchContent : function(activity) {
		$.ajax({
			type: "POST",
			url: api.fetchContent ,
			data: {
				userGuid : activityModel.getUserGuid() ,
				activity : activity , 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr + thrownError) ; 
			},
		}).done(function(result) {
			activityService.initialize(result) ;
		});
	},
	refreshActivityList : function() {
		$("#activities").empty() ;
		newActivityList = activityModel.getActivityList() ;
		for(i=0;i<newActivityList.length;i++) {
			activity = newActivityList[i] ;
			this.addActivityItem(activity,i);
		}		
	},
	syncActivityList : function() {
		$.ajax({
			type: "POST",
			url: api.syncActivityList ,
			dataType : "json",
			data: {
				userGuid : activityModel.getUserGuid() ,
				lastUpdate : activityModel.getLastUpdate() , 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr + thrownError) ; 
			},
		}).done(function(result) {
			if(result.resultCode=="failed") {
				alert(result.message) ;
				return ;
			}
			activityModel.updateActivityList(result.data.activities) ;
			activityListService.refreshActivityList() ;
		});		
	},
} ;
