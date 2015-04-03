<div data-role="page" id="pgTeamAdmin" data-title="团队管理">
	<div data-role="header" data-position="fixed" data-theme="a">
		<h1>团队管理</h1>
	</div>
	<div data-role="main" class="ui-content">
		<div>
			<ul id="teamList" data-role="listview">
				<li><a id='user' href="#pgUpload"> <img id="userIcon"
						src="./assets/member-photo.png" width='150' height='150' />
						<h2 id='username'>Me</h2>
				</a></li>
			</ul>
		</div>
		<div>
			<form action="" method="post" id="createTeam">
				<label class='error' id='message'></label>
				<div data-role='fieldcontain'>
					<label for="teamname">团队名称:</label> <input name="teamname"
						id="teamname" value="" type="text"></input>
				</div>
				<button type="submit" data-theme="a" name="submit" value="signin">创建团队</button>
			</form>
		</div>
	</div>
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#pgTeamAdmin" data-prefetch="true"
					data-transition="flip">团队管理</a></li>
				<li><a href="#pgTeamCreate" data-prefetch="true"
					data-transition="flip">团队创建</a></li>
			</ul>
		</div>
	</div>
</div>
<div data-role="page" id="pgTeamCreate" data-title="团队管理">
	<div data-role="footer" data-position="fixed" data-theme="a">
		<div data-role="navbar">
			<ul>
				<li><a href="#pgTeamAdmin" data-prefetch="true"
					data-transition="flip">团队管理</a></li>
				<li><a href="#pgTeamCreate" data-prefetch="true"
					data-transition="flip">团队创建</a></li>
			</ul>
		</div>
	</div>
</div>