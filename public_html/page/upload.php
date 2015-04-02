<script src="js/upload.js"></script>
<style>
	.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
	.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
	.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
<div data-role="page" id="pgUpload" date-title="Upload Image">
	<div data-role="header" data-position="fixed" data-theme="a">
		<h1>演示PHP上传文件</h1>
	</div><!-- /header -->
	<div data-role="content" class="ui-content">
		<form action="" method="post" id="uploadForm" class="ui-body ui-body-a ui-corner-all" enctype="multipart/form-data" >
		<label for="file">文件名称</label>
		<input type="file" name="file" id="file" class="required" value="" accept="image/*" /> 
		<br />
		<div class="progress">
	        <div class="bar"></div >
	        <div class="percent">0%</div >
	    </div>
	    <input type="submit" id="ajaxSubmit" name="submit" data-theme="a" value="上传！" />
	    <!-- <div id="status"></div> -->
		<!-- <button type="submit" id="ajaxSubmit" data-theme="a" name="submit" 
	            value="upload">上传</button> -->
		</form>
	</div>

<div data-role="popup" id="uploadDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
    <div data-role="header" data-theme="a">
    <h1 id="uploadTitle">上传图像</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title" id="uploadText">正在上传中，请稍后。。。</h3>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" 
        		data-rel="back" data-transition="flow">关闭</a>
    </div>
</div>

</div>