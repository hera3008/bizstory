<?
	include $local_path . "header.php";
?>
<script type="text/javascript" src="./js/jquery.alsEN-1.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$("#lista1").als({
			visible_items: 3,
			scrolling_items: 3,
			orientation: "horizontal",
			circular: "yes",
			autoscroll: "no",
			interval: 5000,
			direction: "right"
		});
	});
</script>

<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">

				<article>
					<div class="navTab">
						<ul class="list_navTab">
							<li><a href="work_list.php" class="link_navTab"><img src="./images/icon1.png" width="124" height="124" alt="업무" />업무<em class="push">2</em></a></li>
							<li><a href="./" class="link_navTab"><img src="./images/icon2.png" width="124" height="124" alt="알림" />알림<em class="push">24</em></a></li>
							<li><a href="message_list.php" class="link_navTab"><img src="./images/icon3.png" width="124" height="124" alt="쪽지" />쪽지<em class="push">134</em></a></li>
							<li><a href="receipt_list.php" class="link_navTab"><img src="./images/icon4.png" width="124" height="124" alt="접수" />접수<em class="push">125</em></a></li>
						</ul>
					</div>
					<section class="navGnb">
						<div>
							<ul>
								<li><img src="./images/nav_m1.png" alt="업무관리" />업무관리</li>
								<li><img src="./images/nav_m2.png" alt="고객관리" />고객관리</li>
								<li><img src="./images/nav_m3.png" alt="게시판" />파일센터</li>
							</ul>
						</div>
					</section>

					<!-- section id="nav_list">
						<div id="lista1" class="als-container">
							<span class="als-prev"><img src="./images/arrow_l.png" alt="prev" title="previous" /></span>
							<div class="als-viewport">
								<ul class="als-wrapper">
									<li class="als-item"><img src="./images/nav_m1.png" alt="업무관리" />업무관리</li>
									<li class="als-item"><img src="./images/nav_m2.png" alt="고객관리" />고객관리</li>
									<li class="als-item"><img src="./images/nav_m3.png" alt="게시판" />게시판</li>
									<li class="als-item"><img src="./images/nav_m1.png" alt="파일센터" />파일센터</li>
									<li class="als-item"><img src="./images/nav_m2.png" alt="설정관리" />설정관리</li>
									<li class="als-item"><img src="./images/nav_m3.png" alt="테스트" />테스트</li>
								</ul>
							</div>
							<span class="als-next"><img src="./images/arrow_r.png" alt="next" title="next" /></span>
						</div>
					</section -->

					<section class="notice">
						<strong>NOTICE</strong>
						<div><marquee behavior="scroll" direction="left" scrollamount="2">금일(2013.05.03 금요일) PM 9시부터 ~ 토요일(AM 3시)까지 비즈스토리 DB서버확장으로 인한 서비스가 일시 중지됩니다. 더욱 원활한 서비스를 위함이니 많은 양해 부탁드립니다. 감사합니다.</marquee></div>
					</section>
				</article>

				<div class="list_work">
					<ul>
						<li>
							<a href="memo_list.php" class="title ico_memo">
								메모보기
								<span class="btn">15<img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>

						<li>
							<a href="#" class="title ico_advice">
								나의상담
								<span class="btn" href="./">3<img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>

						<li>
							<a href="#" class="title ico_exchange">
								비즈스토리 만남의 광장
								<span class="btn" href="./">135<img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>

<?
	include $local_path . "footer.php";
?>