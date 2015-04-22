<div data-role="page" id="pgEmbeddedActivityTemplate" data-title="活动模版">
  <div data-role="header" data-position="fixed" data-theme="b">
    <h1>活动模版</h1>
  </div>
  <div data-role="main" class="ui-content" id="activityTemplates">
	<div id="activityTemplate">
		<div id='$activityId' >
		   <div class="ui-body ui-body-a ui-corner-all">
		    <h3 class="st-highlight">$activityTitle
		    <a id="$activityBtnId" 
		    			class="ui-btn ui-btn-right ui-icon-grid ui-corner-all ui-btn-icon-notext">##</a>
		    </h3>
			$activityText
			<div id='$activityContentId'></div>
			</div>
			<br/>
		</div>
	</div>
  <div id="StoryFormTemplate">
	<form id='$activityForm'>
	    <textarea id='storyText'></textarea>
	    <div data-role="controlgroup" data-type="horizontal">
	    <button class='ui-btn ui-mini ui-btn-inline' id='$activityButton'>提交</button>
	</div></form>
  </div>
  <div id="StoryContentTemplate">
  </div>
  </div>
</div>
