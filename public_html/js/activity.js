var appDb = $.localStorage ;
var activityModel = {
	getUserGuid : function() {
		return appDb.get("user.userId") ;
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
			syncService.sync() ;
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
	addActivityItem : function(activity,index) {
		html = "<li class='ui-li-has-thumb'>"
		+ "<a id='activity" + index +"' class='ui-btn ui-btn-icon-right'" 
		+ " activity-creation='"+ activity.creation +"'>"
		+ "<img src='./assets/icon/" + activity.kind + ".png' width='150' height='150'/>"
		+ "<h2>" + activity.title + "</h2>"
		+ "<p>" + activity.text + "</p>"
		+ "</a></li>" ;
		$("#personActivity").append(html) ;
		$("#personActivity").trigger("create") ;
		$("#activity"+index).click(function() {
			activityListService.activityItemClick(this) ;
		});
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
			url: "app/content/FetchContent.php" ,
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
		$("#personActivity").empty() ;
		newActivityList = activityModel.getActivityList() ;
		for(i=0;i<newActivityList.length;i++) {
			activity = newActivityList[i] ;
			this.addActivityItem(activity,i);
		}		
	},
	syncActivityList : function() {
		$.ajax({
			type: "POST",
			url: "app/activity/SyncActivityList.php" ,
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
