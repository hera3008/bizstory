<?
/*
	수정 : 2013.03.07
	수정 : 2013.03.07
	위치 : V-Center 로그인
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
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.login.neoarena.ready.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.sliderkit.1.5.1.js"></script>

<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/neoarena.css" media="all" />
<title>NEO ARENA Login</title>
</head>

<body id="login_layout">

	<div id="loading">로딩중입니다...</div>
	<div id="header">
		<a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/'" title="홈으로 이동 합니다." class="logo">
			<img src="<?=$local_dir;?>/bizstory/images/neoarena/logo_neoarena.jpg" width="198px" height="39px" alt="home" />
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

						<form id="loginform" name="loginform" class="loginform" method="post" action="<?=$this_page;?>" onsubmit="return check_login()">
							<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
							<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
							<fieldset>
								<legend class="blind">로그인 폼</legend>
								<div class="login_head">
									<div class="login_title">
										<!-- strong class="hanName"></strong> <strong>로그인 V-Center</strong-->
										<img src="<?=$local_dir;?>/bizstory/images/neoarena/login_txt.png" alt="네오아레나" class="txt" />
										<img src="<?=$local_dir;?>/bizstory/images/neoarena/login_txt2.png" alt="모두 함께 더 나은 미래를 향해 끊임없이 창조하고 발전하는 기업입니다." class="txt2" />
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
										<div class="login_right"><span class="btn_big2"><input type="submit" value="로그인" /></span></div>
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

					<!-- Notice Ticker 
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
						</div>-->

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