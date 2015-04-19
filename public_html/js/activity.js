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
	deleteActivity : function(activityGuid) {
		activityList = this.getActivityList() ;
		newActivityList = new Array() ;
		for(i=0;i<activityList.length;i++) {
			activity = activityList[i] ;
			if(activity.creation!=activityGuid) {
				newActivityList.push(activity) ;
			}
		}
		this.updateActivityList(newActivityList) ;
	},
} ;
var syncService = {
	initialize : function() {
		$("#syncBtn").click(function() {
			$("#activityPopupMenu").popup("close") ;
			syncService.sync() ;
		});
		$("#signifyBtn").click(function() {
			activity = activityModel.getCurrentActivity() ;
			$("#activityItemPopupMenu").popup("close") ;
		});
		$("#deleteBtn").click(function() {
			activity = activityModel.getCurrentActivity() ;
			$("#activityItemPopupMenu").popup("close") ;
			activityListService.requestDeleteActivity() ;
		});
	},
	sync : function() {
		activityListService.requestActivityList() ;
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
	deleteActivityFromList : function(activityGuid) {
		activityHash = "#" + activityGuid ;
		$(activityHash).remove() ;
	},
	getActivityHtml : function(activity,index) {
		html = $("#activityTemplate").html() ;
		html = html.replace("$activityId",activity.creation) ;
		html = html.replace("$activityContentId",activity.creation+"-content") ;
		html = html.replace("$activityTitle",activity.title) ;
		html = html.replace("$activityText",activity.content) ;
		html = html.replace("$activityBtnId",index+"-btn") ;
		html = html.replace("$activityItemPopupMenu",activity.creation+"-menu") ;
		html = html.replace("$activityItemPopupMenu",activity.creation+"-menu") ;
		return html ;
	},
	showActivityItemPopup : function(activity,index) {
		detailBtn = "#" + index+"-btn" ;
		activityModel.setCurrentActivity(activity) ;
		$('#activityItemPopupMenu').popup("open", {positionTo: detailBtn});
	},
	setActivityHtmlDetail : function(activity,index) {
		detailBtn = "#" + index+"-btn" ;
		$(detailBtn).click(function() {
			activityListService.showActivityItemPopup(activity,index) ;
		});
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
//	fetchContent : function(activity) {
//		$.ajax({
//			type: "POST",
//			url: api.fetchContent ,
//			data: {
//				userGuid : activityModel.getUserGuid() ,
//				activity : activity , 
//			},
//			error: function (xhr, ajaxOptions, thrownError) {
//				alert(xhr + thrownError) ; 
//			},
//		}).done(function(result) {
//			activityService.initialize(result) ;
//		});
//	},
	refreshActivityList : function() {
		$("#activities").empty() ;
		newActivityList = activityModel.getActivityList() ;
		for(i=0;i<newActivityList.length;i++) {
			activity = newActivityList[i] ;
			this.addActivityItem(activity,i);
		}		
	},
	requestDeleteActivity : function() {
		currentActivity = activityModel.getCurrentActivity() ;
		$.ajax({
			type: "POST",
			url: api.requestDeleteActivity ,
			dataType : "json",
			data: {
				userGuid : activityModel.getUserGuid() ,
				activityGuid : currentActivity.creation ,
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr + thrownError) ; 
			},
		}).done(function(result) {
			if(result.resultCode=="failed") {
				alert("failed: "+result.message) ;
				return ;
			}
			activityGuid = result.data ;
			activityModel.deleteActivity(activityGuid) ;
			activityListService.deleteActivityFromList(activityGuid) ;
		});		
	},
	requestActivityList : function() {
		$.ajax({
			type: "POST",
			url: api.requestActivityList ,
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
