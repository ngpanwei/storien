<div data-role="page" class="ui-page-theme-a" id="pgPersonHome" data-title="个人">
	<div data-role="header" data-position="fixed" data-theme="a">
		<h1 >我的活动</h1>
		<a href="#activityPopupMenu" data-rel="popup" data-role="button" data-icon="info"
			class="ui-btn ui-corner-all ui-btn-right ui-icon-grid ui-btn-icon-notext">处理</a>
	</div>
	<div data-role="popup" id="activityPopupMenu" data-theme="a">
		<ul data-role="listview" data-inset="true" style="min-width: 100px;">
			<li><a id="syncBtn">刷新</a></li>
			<li><a id="postBtn">分享</a></li>
		</ul>
	</div>
	<div data-role="main" class="ui-content">
	    <div id="postActivity" style="display:none">
	    		<form id="postStoryForm">
	    			<textarea id="storyText"></textarea>
	    		</form>
	    			<button class='ui-btn ui-mini' id='postStoryBtn'>提交</button>
	    			<button class='ui-btn ui-mini' id='closePostBtn'>取消</button>
	    		</div>
		<div id="others"></div>
		<div id="activities"></div>
	</div>
	<!-- footer -->
</div>
