<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>c2dm test web</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>
$(document).ready(function) {
	// js start
	console.log("function ready...");
	var docWidth = $(document).width();
	var docHeight = $(document).height() - 30;
	$("thread-container").css("height", docHeight+"px");
	$("#new-thread").click(openThread);
	$("#connect").click(function) {
		login();
	//	if (!HomeCloudClient.conn) login();
	//	else logout();
	});

	function login() {
		console.log('function start(login)...');
		$("#login").show();
		$("#query").show();
	}

	function showquery() {
		console.log('function start(showquery)...');
		var url = location.href;
		location.href = url + 'query/userlist';
	}

	function daemon() {
		console.log('function start(daemon)...');
		var url = location.href;
		location.href = url + 'daemon/'+$("#userid").val();
	}
</script>
</head>

<body>
<div>
	<header>
		<h1>apns test web</h1>
	</header>
	<nav>
		<p>
		<a href="/">Home</a>
		</p>
	</nav>

	<div id="login">
		<form action="/c2dm" method="post">
			<label>device Token: <input id="deviceToker" name="deviceToken" type="text" value="" size="100" /></label></br>
			<label>notification Text: <input id="notificationText" name="notificationText" type="text" value="" size="100" /></label></br>
			<label>collapse_key: <input id="collapse_key" name="collapse_key" type="text" value="1" size="100" /></label></br>
			<button type="submit">OK</button>
		</form>
	</div>

	<div id="query">
		<button onclick="showquery(); return true;">query test</button>
		<button onclick="daemon(); return true;">daemon test</button>
	</div>
	<footer>
		<p>
		Express API
		</p>
	</footer>
</div>

</body>
</html>
