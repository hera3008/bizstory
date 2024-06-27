<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 로그인
*/
	include "../bizstory/common/setting.php";
	include $local_path . "/sole/include/header.php";

	$move_url = urldecode($move_url);
	$move_url_arr = explode('&', $move_url);
	$total_url = '';
	foreach ($move_url_arr as $k => $v)
	{
		if ($k == 0)
		{
			$total_url = $v;
		}
		else
		{
			$total_url .= '&' . $v;
		}
	}
?>
<script type="text/javascript" src="<?=$local_dir;?>/common/js/jquery.cycle.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/sole/js/jquery.login.ready.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.sliderkit.1.5.1.js"></script>
<title>BIZSTORY 총판 Login</title>
</head>

<body id="login_layout">

	<div id="loading">로딩중입니다...</div>
	<div id="header">
		<a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/sole/'" title="홈으로 이동 합니다." class="logo">
			<img src="<?=$local_dir;?>/bizstory/images/common/logo.jpg" width="198px" height="39px" alt="home" />
		</a>
		<div class="srevice_menu">
			<ul>
				<li class="icon1"><a href="<?=$local_dir;?>/">홈</a></li>
				<li class="icon2"><a href="<?=$local_dir;?>/bizstory/include/page_view.php?pidx=service_guide">서비스안내</a></li>
				<li class="icon3"><a href="http://www.ubstory.net/" target="_blank" title="새 창으로 이동">유비스토리 바로가기</a></li>
			</ul>
		</div>
	</div>

	<div id="popup_result_msg" title="처리결과"></div>

	<table id="layout_table">
		<colgroup>
			<col />
		</colgroup>
		<tr>
			<td id="container">

				<div id="login_frame">
					<div class="login_box">

						<div class="login_logo">
							<p><strong>나만의 비즈니스홈</strong> - 쉽고 빠르게 접수하고 신속하게 처리되는 <span class="engName"></span> 시스템 언제든지 신청해주시면 신속정확하게 처리해 드리겠습니다.</p>
						</div>

						<div class="sliderkit gall-img">
							<img src="./images/common/bizstory_img.png" alt="" />
						</div>

						<!-- 메인 visual
						<div class="sliderkit gall-img">
							<div class="sliderkit-nav">
								<div class="sliderkit-nav-clip">
									<ul>
										<li>
											<img src="/bizstory/images/common/bizstory_img.png" alt="" />
										</li>
										<li>
											<img src="/bizstory/images/common/bizstory_img2.png" alt="" />
										</li>
									</ul>
								</div>
								<div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-prev"><a href="#" title="Scroll to the left"><img src="/bizstory/images/btn/btn_sliderkit_prev.png" alt="이전" /></a></div>
								<div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-next"><a href="#" title="Scroll to the right"><img src="/bizstory/images/btn/btn_sliderkit_next.png" alt="다음" /></a></div>
							</div>
						</div>
						<!-- //메인 visual -->

						<form id="loginform" name="loginform" class="loginform" method="post" action="<?=$this_page;?>" onsubmit="return check_login()">
							<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
							<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
							<fieldset>
								<legend class="blind">로그인 폼</legend>
								<div class="login_head">
									<div class="login_title">
										<strong class="hanName"></strong> 로그인
									</div>
								</div>
								<div class="login_body">
									<div>
										<label for="login_sole_id" class="label">아이디</label>
										<input type="text" name="param[sole_id]" id="login_sole_id" size="20" maxlength="100" value="아이디를 입력하세요." title="아이디를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
									</div>
									<div>
										<label for="login_sole_pwd" class="label">비밀번호</label>
										<input type="password" name="param[sole_pwd]" id="login_sole_pwd" size="20" maxlength="20" title="비밀번호를 입력하세요." class="type_text" />
									</div>
									<div>
										<div class="login_left"><input type="checkbox" name="login_sole_id_chk" id="login_sole_id_chk" value="Y" onclick="check_login_save()" /><label for="login_sole_id_chk">아이디 저장</label></div>
										<div class="login_right"><span class="btn_big"><input type="submit" value="로그인" /></span></div>
									</div>
								</div>
							</fieldset>
						</form>

					<!-- Notice Ticker -->
					<?
						$notice_where = " and ni.notice_type = '2' and ni.view_yn = 'Y' and ni.comp_all = 'Y'";
						$notice_list = notice_info_data('list', $notice_where, '', '', '');
					?>
						<div id="login_notice" class="ticker">
							<div class="ticker_frame">
								<marquee behavior="scroll" direction="left" scrollamount="2"><p>
					<?
						if ($notice_list['total_num'] == 0)
						{
					?>
									<span style="padding-right:20px;">&nbsp;</span>
					<?
						}
						else
						{
							foreach ($notice_list as $notice_k => $notice_data)
							{
								if (is_array($notice_data))
								{
									$import_type = $notice_data['import_type'];
									$link_url    = $notice_data['link_url'];

								// 중요도
									if ($notice_data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
									else if ($notice_data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
									else if ($notice_data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
									else $important_span = '';

									if ($link_url == '')
									{
										$subject = $notice_data['content'];
									}
									else
									{
										$subject = '<a href="http://' . $link_url . '" target="_blank">' . $notice_data['content'] . '</a>';
									}
					?>
									<span style="padding-right:20px;">ㆍ <?=$subject;?><?=$important_span;?></span>
					<?
								}
							}
						}
					?>
								</p></marquee>
							</div>
						</div>

					<!-- Ad Banner -->
					<?
					// 배너목록
						$banner_where = " and bi.view_yn = 'Y' and bi.banner_type = '2' and bi.comp_all = 'Y'";
						$banner_list = banner_info_data('list', $banner_where, '', '', '');
					?>
						<div id="login_banner" class="ad_banner">
							<div id="ad_banner">
					<?
						if ($banner_list['total_num'] == 0)
						{
					?>
								<div>
									<a href="./" title="배너제목"><img src="./data/ad_banner01.gif" alt="배너내 텍스트 내용" width="257px" height="125px" /></a>
								</div>
					<?
						}
						else
						{
							foreach ($banner_list as $banner_k => $banner_data)
							{
								if (is_array($banner_data))
								{
									if ($banner_data["img_sname1"] != '')
									{
										$img_str = '<img src="' . $banner_dir . '/' . $banner_data["img_sname1"] . '" alt="' . $banner_data["content"] . '" width="257px" height="125px" />';
									}
									else
									{
										$img_str = '';
									}
					?>
								<div>
									<a href="<?=$banner_data['link_url'];?>" target="_blank" title="<?=$banner_data["content"];?>"><?=$img_str;?></a>
								</div>
					<?
								}
							}
						}
					?>
							</div>
							<div id="ad_counter"></div>
						</div>

					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="login_footer">
				<div id="footer"></div>
			</td>
		</tr>
	</table>
	<div id="backgroundPopup"></div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 로그인
	function check_login()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#login_sole_id').val();
		chk_title = $('#login_sole_id').attr('title');
		if (chk_value == '' || chk_value == chk_title)
		{
			chk_total = chk_total + chk_title + '<br />';
			$('#login_sole_id').val(chk_title);
			action_num++;
		}

		chk_value = $('#login_sole_pwd').val();
		chk_title = $('#login_sole_pwd').attr('title');
		if (chk_value == '' || chk_value == chk_title)
		{
			chk_total = chk_total + chk_title + '<br />';
			$('#login_sole_pwd').val('');
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: '/sole/login_ok.php',
				data: $('#loginform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_login_save();
						location.href = '/sole/';
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 아이디저장
	function check_login_save()
	{
		var chk_value = $('#login_sole_id').val();
		var chk_title = $('#login_sole_id').attr('title');
		if (chk_value == chk_title) chk_value = '';

		if ($('#login_sole_id_chk').attr('checked') == 'checked' || $('#login_sole_id_chk').attr('checked') == true)
		{
			$.cookie('login_sole_id_save', chk_value, { expires: 7 });
		}
		else $.cookie('login_sole_id_save', null, { expires: 7 });
	}

//------------------------------------ ID Save
	if ($.cookie('login_sole_id_save') == null || $.cookie('login_sole_id_save') == '')
	{
		$('#login_sole_id').val($('#login_sole_id').attr('title'));
	}
	else
	{
		$('#login_sole_id').val($.cookie('login_sole_id_save'));
		$('#login_sole_id_chk').attr('checked', 'checked');
	}

//------------------------------------ 에러부분
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() { $(this).dialog("close"); }
		}
	});

//------------------------------------ 배경클릭시
	$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기
//]]>
</script>
</body>
</html>