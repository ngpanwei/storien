<div data-role="page" id="pgChangeEmail" data-title="修改邮箱">
	<div data-role="header" data-position="fixed" data-theme="a">
		<h1>修改邮箱</h1>
	</div>
	<div data-role="main" class="ui-content">
		<div data-role='content' id="response"></div>
		<form id="changeEmailForm" class="ui-body ui-body-a ui-corner-all">
			<div data-role='fieldcontain'>
				<label for="email">新邮箱:</label> <input name="email" id="email"
					value="" type="text"></input>
			</div>
			<button type="submit" data-theme="a" name="submit"
				value="submit-value">修改邮箱</button>
		</form>
	</div>
    <div data-role="popup" id="changeEmailDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:500px; width:470px;">
    <div data-role="header" data-theme="a">
    <h1 id="changeEmailTitle">修改邮箱</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title" id="changeEmailText">正在修改中，请稍后...</h3>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" 
            data-rel="back" data-transition="flow">关闭</a>
    </div>
    </div>
</div>