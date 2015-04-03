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
				value="submit-value">修复密码</button>
		</form>
	</div>
</div>