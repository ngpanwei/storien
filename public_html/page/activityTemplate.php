<div data-role="page" id="pgEmbeddedActivityTemplate" data-title="活动模版">
  <div data-role="header" data-position="fixed" data-theme="b">
    <h1>活动模版</h1>
  </div>
  <div data-role="main" class="ui-content" id="activityTemplates">
	<div id="activityTemplate">
		<div id='$activityId'>
		    <h2>$activityTitle</h2>
			$activityText
			<div id='$activityContentId'></div>
			<hr/>
		</div>
	</div>
  <div id="StoryFormTemplate">
	<form id='$activityIndex'>
	    <textarea id='storyText'></textarea>
	    <button class='ui-btn ui-mini' id='$activityButton'>提交</button>
	</form>
  </div>
  <div id="StoryContentTemplate">
  </div>
  </div>
</div>
