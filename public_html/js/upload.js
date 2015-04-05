var uploadDb = $.localStorage ;
var uploadModel = {
    getUserGuid : function() {
		return uploadDb.get("user.userId") ;
    }
}
var uploadService = {
	initialize : function(formId){
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');

		$("#uploadForm").ajaxForm({
		url: 'app/user/upload.php',
    	type:'post',
        dataType:'json', 
        data: {
            guid : uploadModel.getUserGuid()
        },

	    beforeSend: function() {
	    	status.empty();
	        var percentVal = '0%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	        
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    success: function(result) {
	    	if(result.resultCode=="failed") {
	    		$("#uploadDialog").popup("open") ;
				$("#uploadTitle").text("上传没法完成") ;
				$("#uploadText").text(result.message) ;

				var percentVal = '0%';
		        bar.width(percentVal)
		        percent.html(percentVal);

				return ;
			}else{
                var percentVal = '100%';
                bar.width(percentVal)
                percent.html(percentVal);
                appController.uploadSuccessful() ;
            }
	    }	
	});
	}		 
};