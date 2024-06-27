<?
	include $local_path . "header.php";
?>

<header>
	<h1>
		<a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a>
		
	</h1>
	<a class="button" href="javascript:void(0)" onclick="login_out()"><span class="ico_logout">로그아웃</span></a>
</header>
<article>
	<ul class="navTab navTab_top col4">
		<li><!-- span class="push"><em>5</em></span --><a class="work" href="./">업무</a></li>
		<li><a class="notifi" href="./">알림</a></li>
		<li><a class="note" href="./">쪽지</a></li>
		<li><a class="receipt" href="./">접수</a></li>
	</ul>
	<!-- section class="navGnb">
		<div>
			<추후 다시 바꿔야함 
			<ul id="mycarousel" class="jcarousel-skin-tango">
				<li><img src="http://static.flickr.com/66/199481236_dc98b5abb3_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/75/199481072_b4a0d09597_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/57/199481087_33ae73a8de_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/77/199481108_4359e6b971_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/58/199481143_3c148d9dd3_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/72/199481203_ad4cdcf109_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/58/199481218_264ce20da0_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/69/199481255_fdfe885f87_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/60/199480111_87d4cb3e38_s.jpg" width="75" height="75" alt="" /></li>
				<li><img src="http://static.flickr.com/70/229228324_08223b70fa_s.jpg" width="75" height="75" alt="" /></li>
			</ul>>
		</div>
	</section-->
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
		<ul class="m_list">
			<li><a href="./">업무관리</a><em></em></li>
			<li><a href="./">접수미처리현황</a><em></em></li>
			<li><a href="./">업무이력</a><em></em></li>
		</ul>
	</div>
</div>

<script src="./js/main.js"></script>
<?
	include $local_path . "footer.php";
?>