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
	} ,
	getActivityList : function() {
		return activityDb.get("activity.activities") ;
	}
} ;
var SyncService = {
	initialize : function() {
		$("#syncBtn").click(function() {
			SyncService.sync() ;
		});
	},
	sync : function() {
		activityListService.syncActivityList() ;
	},
} ;
var activityListService = {
	initialize : function() {
	},
	addActivityItem : function(activity,index) {
		html = "<li class='ui-li-has-thumb'>"
		+ "<a id='activity" + index +"' class='ui-btn ui-btn-icon-right'>" 
		+ "<img src='./_assets/member-photo.png' width='150' height='150'/>"
		+ "<h2>" + activity.title + "</h2>"
		+ "<p>" + activity.text + "</p>"
		+ "</a></li>" ;
		$("#personActivity").append(html) ;
		$("#personActivity").trigger("create") ;
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