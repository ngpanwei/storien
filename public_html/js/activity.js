var activityDb = $.localStorage ;
var ActivityModel = {
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
	syncActivityList : function() {
		alert("Sync111") ;
		$.ajax({
			type: "POST",
			url: "app/sync/Sync.php" ,
			dataType : "json",
			data: {
				userGuid : ActivityModel.getUserGuid() ,
				lastUpdate : ActivityModel.getLastUpdate() , 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr + thrownError) ; 
			},
		}).done(function(result) {
			alert("done") ; 
		});		
		
	},
} ;
var activityService = {
	initialize : function() {
	}
} ;