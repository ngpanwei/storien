<div data-role="page" id="pgSignIn" data-title="登入">
  <div data-role="header" data-position="fixed" data-theme="a">
    <h1>登入</h1>
  </div>
  <div data-role="main" class="ui-content">
      <div data-role='content' id="response"></div>
    <form id="signInForm" class="ui-body ui-body-a ui-corner-all">
        <label class='error' id='message'></label>
        <div data-role='fieldcontain'>
          <label for="email">邮箱:</label>
          <input name="email" id="email" value="" type="text"></input>
        </div>
        <div data-role='fieldcontain'>
          <label for="password">密码:</label>
          <input name="password" id="password" value="" type="password"></input>
        </div>
	    <button type="submit" data-theme="a" name="submit" 
	            value="signin">登入</button>
      </form>
	        <div class="ui-block-a">
	          <a id='register' href="#pgRegister" data-role="button">注册</a></div>
	        <div class="ui-block-b">
	          <a id='forget' href="#pgForgetPassword" data-role="button">修复密码</a></div>
    </div>

<div data-role="popup" id="signinDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:500px; width:470px;">
<div data-role="header" data-theme="a">
<h1 id="signinTitle">登录</h1>
</div>
<div role="main" class="ui-content">
    <h3 class="ui-title" id="signinText">正在登录中，请稍后。。。</h3>
    <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" 
        data-rel="back" data-transition="flow">关闭</a>
</div>
</div>
<div data-role="page" data-title="注册确认" id="pgConfirmation">
  <div data-role="header" data-position="fixed" data-theme="a">
    <h1>登录确认</h1>
  </div>
  <div data-role="main" class="ui-content">
  谢谢确认。
  <a id='signinConfirmation' data-role="button">确认</a></div>
  </div>

</div>
<div data-role="page" data-title="注册" id="pgRegister">
  <div data-role="header" data-position="fixed" data-theme="a">
    <h1>注册团成员</h1>
  </div>
  <div data-role="main" class="ui-content">
      <div data-role='content' id="response"></div>
      <form id="registrationForm" class="ui-body ui-body-a ui-corner-all">
        <label>让我们彼此认识</label>
        <label class='error' id='message'></label>
        <div data-role='fieldcontain'>
          <label for="teamname">团队名称（请管理员提供）</label>
          <input name="teamname" id="teamname" value="" type="text"></input>
        </div>
        <div data-role='fieldcontain'>
          <label for="username">个人名称</label>
          <input name="username" id="username" value="" type="text"></input>
        </div>
        <div data-role='fieldcontain'>
          <label for="email">邮箱</label>
          <input name="email" id="email" value="" type="text"></input>
        </div>
        <div data-role='fieldcontain'>
          <label for="password">密码</label>
          <input name="password" id="password" value="" type="password"></input>
        </div>
        <div data-role='fieldcontain'>
          <label for="cpassword">确认密码:</label>
          <input name="cpassword" id="cpassword" value="" type="password"></input>
        </div>
      <button type="submit" data-theme="a" name="submit" 
            value="submit-value">提交</button>
       <button type="reset" data-theme="a" name="reset" 
       		value="reset-value">重置</button>
    </form>
  </div>
  <div data-role="popup" id="registerDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
    <div data-role="header" data-theme="a">
    <h1 id="registerTitle">注册</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title" id="registerText">正在注册中，请稍后。。。</h3>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-b" 
        		data-rel="back" data-transition="flow">关闭</a>
    </div>
</div>
<div data-role="page" data-title="Teamify" id="pgConfirmation">
  <div data-role="header" data-position="fixed" data-theme="a">
    <h1>注册确认</h1>
  </div>
  <div data-role="main" class="ui-content">
  谢谢确认。
	<a id='registerConfirmation' data-role="button">确认</a></div>
  </div>
</div>


