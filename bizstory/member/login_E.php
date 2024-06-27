<?
/*
	수정 : 2012.11.15
	위치 : 로그인
*/
	include "../common/setting.php";
	include $local_path . "/include/header.php";

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
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.login.ready.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.sliderkit.1.5.1.js"></script>
<title>BIZSTORY Login</title>
</head>

<body id="login_layout">

	<div id="loading">로딩중입니다...</div>
	<div id="header">
		<a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/'" title="홈으로 이동 합니다." class="logo">
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

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>

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

						<div class="login_app">
							<ul>
								<li><a href="https://play.google.com/store/apps/details?id=com.ubstory.bizstory" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/bizstory/images/common/android_qr.png" alt="안드로이드 app" /></a></li>
								<li><a href="itms://itunes.apple.com/us/app/bizstory/id533048755?l=ko&amp;ls=1&amp;mt=8" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/bizstory/images/common/apple_qr.png" alt="아이폰 app" /></a></li>
							</ul>
						</div>

					<!-- 메인 visual -->
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

						<form id="loginform" name="loginform" class="loginform" method="post" action="<?=$this_page;?>" onsubmit="return check_login()">
							<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
							<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
							<fieldset>
								<legend class="blind">로그인 폼</legend>
								<div class="login_head">
									<div class="login_title">
										<strong class="hanName"></strong> 로그인 E형 로그인화면
									</div>
								</div>
								<div class="login_body">
									<div>
										<label for="login_mem_id" class="label">메일주소</label>
										<input type="text" name="param[mem_id]" id="login_mem_id" size="20" maxlength="100" value="메일주소를 입력하세요." title="메일주소를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
									</div>
									<div>
										<label for="login_mem_pwd" class="label">비밀번호</label>
										<input type="password" name="param[mem_pwd]" id="login_mem_pwd" size="20" maxlength="20" title="비밀번호를 입력하세요." class="type_text" />
									</div>
									<div>
										<div class="login_left"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /><label for="login_mem_id_chk">아이디 저장</label></div>
										<div class="login_right"><span class="btn_big"><input type="submit" value="로그인" /></span></div>
									</div>
								</div>
								<div class="login_foot">
									<ul>
										<li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/reg_form.php')"><strong>서비스신청</strong></a></li>
										<li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/find_id.php')">아이디 찾기</a></li>
										<li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/find_pwd.php')">비밀번호 찾기</a></li>
									</ul>
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

					<!-- Service Counter -->
					<?
						$agent_list = agent_data_data('page', '');
						$total_agent = $agent_list['total_num'];
						$total_agent_len = strlen($total_agent);
						$total_agent_remain = 5 - $total_agent_len;

						$chk_num = 0;
						for ($i = 0; $i < 5; $i++)
						{
							if ($i < $total_agent_remain)
							{
								$agent_str[$i] = 0;
							}
							else
							{
								$agent_str[$i] = substr($total_agent, $chk_num, 1);
								$chk_num++;
							}
						}
					?>
						<div id="service_counter">
							<div class="counter_num">
								<div class="mask"></div>
								<div id="box1" class="box_bg"><?=$agent_str[0];?></div>
								<div id="box2" class="box_bg"><?=$agent_str[1];?></div>
								<div id="box3" class="box_bg"><?=$agent_str[2];?></div>
								<div id="box4" class="box_bg"><?=$agent_str[3];?></div>
								<div id="box5" class="box_bg"><?=$agent_str[4];?></div>
							</div>
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
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>

<script type="text/javascript">
//<![CDATA[
	var link_ok   = '<?=$local_dir;?>/bizstory/member/regist_ok.php';
	var total_url = '<?=$total_url;?>';
//]]>
</script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_login.js" charset="utf-8"></script>
</body>
</html>