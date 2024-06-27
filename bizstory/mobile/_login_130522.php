<?
	include $local_path . "header.php";
?>

<header>
	<h1>
		<a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a>
		
	</h1>
	<a class="button" href="javascript:void(0)" onclick="login_out()"><span class="ico_logout">로그아웃</span></a>
</header>
<div id="wrapper" class="login">
	<div id="scroller">
		<section id="login_notice">
			<div>
				<h2>NOTICE</h2>
				<ul>
					<li><a href="./">공지내용11111111111</a></li>
					<li><a href="./">공지내용222222222222222</a></li>
					<li><a href="./">공지내용11111111111</a></li>
				</ul>
			</div>
		</section>
		<article class="login_section">
			<div>
				<section>
				<p>아이디 및 비밀번호가 기억나지 않으세요?<br />웹에서 비즈스토리에 연결하시면 아이디찾기 및 비밀번호 찾기를 하실 수 있습니다.</p>
				<ul>
					<li>
						<label for="login_mem_id">아이디</label>
						<input type="text" name="param[mem_id]" placeholder="아이디를 입력하세요." id="login_mem_id" value="" />
					</li>
					<li>
						<label for="login_mem_pwd">비밀번호</label>
						<input type="password" name="param[mem_pwd]" placeholder="비밀번호를 입력하세요." id="login_mem_pwd" value="" />
					</li>
					<li>
						<label for="login_auto_check" class="small">아이디저장</label>
						<span class="toggle"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /></span>
					</li>
				</ul>
				<span class="auto_login">
					<input name="login_auto_check" id="login_auto_check" value="1" type="checkbox" /><label for="login_auto_check" class="small">자동로그인</label>
				</span>
				</section>
			</div>
		</article>
		<!-- 로그인버튼 -->
		<section class="login_btn">
			<div class="btn1 ta_c">
				<a href="./index.php" onclick="check_login();" data-transition="slideup">회원 로그인</a>
			</div>
		</section>
	</div>
</div>