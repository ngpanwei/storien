<div data-role="page" id="pgChangePassword" data-title="修改密码">
	<div data-role="header" data-position="fixed" data-theme="a">
		<h1>修改密码</h1>
	</div>
	<div data-role="main" class="ui-content">
		<div data-role='content' id="response"></div>
		<form id="changePasswordForm" class="ui-body ui-body-a ui-corner-all">
			<div data-role='fieldcontain'>
				<label for="opassword">老密码</label> <input name="opassword"
					id="opassword" value="" type="password"></input>
			</div>
		    <div data-role='fieldcontain'>
				<label for="password">新密码</label> <input name="password"
					id="password" value="" type="password"></input>
			</div>
			<div data-role='fieldcontain'>
				<label for="cpassword">确认密码:</label> <input name="cpassword"
					id="cpassword" value="" type="password"></input>
			</div>
			<button type="submit" data-theme="a" name="submit"
				value="submit-value">修改密码</button>
		</form>
	</div>
    <div data-role="popup" id="changePasswordDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:500px; width:470px;">
    <div data-role="header" data-theme="a">
    <h1 id="changePasswordTitle">修改密码</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title" id="changePasswordText">正在修改中，请稍后...</h3>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" 
            data-rel="back" data-transition="flow">关闭</a>
    </div>
    </div>
</div>