<!-- user-scalable : 확대/축소 가능여부
 initial-scale : 최초 확대 비율 ex)100% -> 1.0
 maximum-scale : 스케일 변경시 비율 최대값
 minimum-scale : 스케일 변경시 비율 최소값
 width : 가로 길이 px, 보통은 device-width
 height : 세로 길이 px, 보통은 device-height -->

<!DOCTYPE html>
<html>
	<head>
		<title>jQuery Mobile</title>
		<meta charset="utf-8" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no," />
<meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
<meta name="apple-mobile-web-app-capable" content="yes" />
		<!--프레임, 스크립트>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" /
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.css" />
		<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>-->

		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	</head>

	<body>
		<!-- 1page -->
		<div data-role="page" id="first_page">
			<div data-role="header">
				<h1>ing......</h1>
				<div data-role="navbar">
					<ul>
						<li><a href="#" data-icon="delete">menu1</a></li>
						<li><a href="#" data-icon="grid">menu2</a></li>
						<li><a href="#" data-icon="home">menu3</a></li>
						<li><a href="#" data-icon="search">menu4</a></li>
					</ul>
				</div>
			</div>
			<div data-role="contents">
				<ul data-role="listview">
					<li data-role="list-divider">listview item</li>
					<li><a href="jquerymobile2.php">list item1</a></li>
					<li><a href="#second_page">list item2</a></li>
					<li><a href="#second_page">list item3</a></li>
					<li><a href="#second_page" data-transition="slideup">list item4</a></li>
					<li data-role="list-divider">listview item2</li>
					<li><a href="#second_page" data-transition="pop">list item5</a></li>
					<li><a href="#second_page" data-transition="slidedown">list item6</a></li>
					<li><a href="#second_page" data-transition="flip">list item7</a></li>
				</ul>
				<input type="password" name="pw" id="name" value="" />
				<input type="text" id="yid" name="id" autocomplete="off" autocorrect="off" placeholder="아이디를 입력하세요." value="" aria-required="true" required />
			</div>
			<div data-role="footer" data-position="fixed">
				<div data-role="navbar">
					<ul>
						<li><a href="#">f_menu1</a></li>
						<li><a href="#">f_menu2</a></li>
						<li><a href="#">f_menu3</a></li>
						<li><a href="#">f_menu4</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- 2page -->
		<div data-role="page" id="second_page" data-add-back-btn="ture">
			<div data-role="header">
				<h1>ing_sub......</h1>
			</div>
			<div data-role="contents">
				<p>sub 내용 들어오는 곳 </p>
			</div>
		</div>
	</body>
</html>