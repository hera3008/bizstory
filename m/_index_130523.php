<?
	include $local_path . "header.php";
?>

<div data-role="page">
	<div data-role="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div data-role="content">
		<article>
			<div class="navTab">
				<ul class="list_navTab">
					<li><a href="./" class="link_navTab"><img src="./images/icon1.png" width="124" height="124" alt="업무" />업무<em class="push">2</em></a></li>
					<li><a href="./" class="link_navTab"><img src="./images/icon2.png" width="124" height="124" alt="알림" />알림<em class="push">24</em></a></li>
					<li><a href="./" class="link_navTab"><img src="./images/icon3.png" width="124" height="124" alt="쪽지" />쪽지<em class="push">134</em></a></li>
					<li><a href="./" class="link_navTab"><img src="./images/icon4.png" width="124" height="124" alt="접수" />접수<em class="push">125</em></a></li>
				</ul>
			</div>
			<section class="navGnb">
				<div>
					<ul>
						<li>업무관리</li>
						<li>고객관리</li>
						<li>파일센터</li>
					</ul>
				</div>
			</section>
			<section id="notice">
				<h2>NOTICE</h2>
				<ol>
					<li><a href="./">공지내용11111111111</a></li>
					<li><a href="./">공지내용222222222222222</a></li>
				</ol>
			</section>
		</article>
		<div id="wrapper" class="main">
			<div id="scroller">

				<div class="list_work">
					<ul>
						<li>
							<a href="#" class="title">메모보기</a><a class="btn" href="/">15<img src="./images/bul_arrow.png" alt="더보기" /></a>
						</li>

						<li>
							<a href="#" class="title">나의상담</a>
							<a class="btn" href="./">3<img src="./images/bul_arrow.png" alt="더보기" /></a>
						</li>

						<li>
							<a href="#" class="title">비즈스토리 만남의 광장</a>
							<a class="btn" href="./">135<img src="./images/bul_arrow.png" alt="더보기" /></a>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>

	<script src="./js/main.js"></script>

<?
	include $local_path . "footer.php";
?>