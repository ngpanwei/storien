var uploadService = {
	initialize : function(){
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');

		$("#uploadForm").ajaxForm({
		url: api.upload ,
    	type:'post',
        dataType:'json', 
        data: {
            guid : personifyModel.getUserId
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
                
                $("#uploadDialog").popup("open") ;
				$("#uploadTitle").text("上传图像成功") ;
				$("#uploadText").text(result.message) ;
                
                //延时2秒跳转刷新
                setTimeout(function () {
                    appController.uploadSuccessful(result.data) ;
                }, 2000);
            }
	    }	
	});
	}		 
};