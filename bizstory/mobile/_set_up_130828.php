<?
	include $local_path . "header.php";
?>

<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">
				
				<p class="alarm">알람을 켜고 끌수 있습니다.</p>
				<section class="setting_area">
					<ul class="setting">
						<li>
							<strong>알람켜기</strong>
							<div class="switch float_r">
								<input type="checkbox" checked />
								<label><i></i></label>
							</div>
						</li>
						<li>
							<strong>접수알람</strong>
							<div class="switch float_r">
								<input type="checkbox" />
								<label><i></i></label>
							</div>
						</li>
						<li>
							<strong>업무알람</strong>
							<div class="switch float_r">
								<input type="checkbox" />
								<label><i></i></label>
							</div>
						</li>
						<li>
							<strong>단문자알람</strong>
							<div class="switch float_r">
								<input type="checkbox" />
								<label><i></i></label>
							</div>
						</li>
						<li class="end">
							<strong>공지알람</strong>
							<div class="switch float_r">
								<input type="checkbox" />
								<label><i></i></label>
							</div>
						</li>
					</ul>
				</section>

			</div>
		</div>
	</div>

<?
	include $local_path . "footer.php";
?>