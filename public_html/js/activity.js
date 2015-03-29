var activityDb = $.localStorage ;
var activityModel = {
	getUserGuid : function() {
		return activityDb.get("user.userId") ;
	},
	getLastUpdate : function() {
		if(activityDb.isSet("activity.lastUpdate")!=false) {
			return activityDb.get("activity.lastUpdate") ;
		}
		if(activityDb.isSet("user.creation")!=false) {
			return activityDb.get("user.creation") ;
		}
		return "unknown" ;
	} ,
	updateActivityList : function(newActivityList) {
		if(newActivityList==null) {
			alert("activities not defined") ;
		}
		activityDb.set("activity.activities",newActivityList) ;
	},
	getActivityList : function() {
		return activityDb.get("activity.activities") ;
	},
	getActivityByCreation : function(creation) {
		activityList = this.getActivityList() ;
		for(i=0;i<activityList.length;i++) {
			activity = activityList[i] ;
			if(activity.creation==creation)
				return activity;
		}
		return null;
	}
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
		window.location.hash = "pgActivity" ;
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
		}).done(function(result) {
			alert(result) ;
		});
	},
	refreshActivityList : function() {
		$("#personActivity").empty() ;
		newActivityList = activityModel.getActivityList() ;
		for(i=0;i<newActivityList.length;i++) {
			activity = newActivityList[i] ;
			this.addActivityItem(activity,i) ;
		}		
	},
	syncActivityList : function() {
		$.ajax({
			type: "POST",
			url: "app/sync/Sync.php" ,
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
var activityService = {
	initialize : function() {
	}
} ;