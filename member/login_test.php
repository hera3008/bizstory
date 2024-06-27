<?
/*
	수정 : 2013.05.20
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

<body id="login_layout" class="login">

	<div id="loading">로딩중입니다...</div>
	
	<? include $local_path . "/include/template_inchargeLayer.php"; ?>
	
	<div id="header">
		<div class="top_nav">
			<div>
				<h2 class="blind">공통 네비게이션 영역</h2>
				<ul class="top_group">
					<li><a href="http://www.ubstory.net" target="_blank" title="새창으로 이동">유비스토리</a></li>
					<li><a href="http://homestory.ubstory.net" target="_blank" title="새창으로 이동">홈스토리</a></li>
					<li><a href="<?=$local_dir;?>/" class="on">비즈스토리</a></li>
					<li><a href="http://viewstory.ubstory.net" target="_blank" title="새창으로 이동">뷰스토리</a></li>
				</ul>
				<ul class="top_gnb">
					<li><a href="<?=$local_dir;?>/" title="메인으로" class="home"><span class="axi axi-ion-home"></span> 메인으로</a></li>
					<li><a href="<?=$local_dir;?>/" title="로그인" class="login"><span class="axi axi-lock-outline"></span> 로그인</a></li>
				</ul>		
			</div>
		</div>
		<div id="gnb_wrap">
			<div>
				<h1><a href="<?=$local_dir;?>/" class="logo"><img src="<?=$local_dir;?>/bizstory/images/common/logo.png" alt="홈스토리"></a></h1>
				<h2 class="blind">메인메뉴 영역</h2>
				<div id="gnb">
					<ul>
						<li class="menu1"><a href="<?=$local_dir;?>/contents/service_info.php">서비스 소개</a>
						<li class="menu2"><a href="<?=$local_dir;?>/contents/service_price.php">서비스 가격</a>
						<li class="menu3"><a href="<?=$local_dir;?>/contents/service_case.php">성공사례</a>
						<li class="menu4"><a href="<?=$local_dir;?>/contents/service_partner.php">협력사 </a>                       
						<li class="menu5"><a href="<?=$local_dir;?>/contents/service_notice.php">고객센터</a>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="popup_result_msg" title="처리결과"></div>

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>

	<h2 class="blind">메인 컨텐츠 영역</h2>
	<div id="container">
		<article class="m_tit">
			<h3>사람의 가능성을 <span>현실의 가치</span>로 만드는<br />스마트 업무공간 <strong>비즈스토리</strong></h3>
			<p>
				비즈스토리는 승승장구 하는 강소기업의 비법을 그대로 담은 업무중심 협업솔루션입니다.<br />
				중소기업 조직의 힘은 빠르고, 열린의사결정, 효율적인 업무소통에 있습니다. <br />
				조직원이 가지고 있는 힘을 모두 한곳에 모이게 하여 최고의 팀으로 성과를 낼 수 있습니다. <br />
				협업의 제약적이고 소모적인 환경에 다른 방법을 찾고 계신다면, 많이 다른 협업솔루션의 비법으로 해결하세요.
			</p>
		</article>
		<article class="m_loginbox">
			<div>
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
								<label for="login_mem_id" class="label">메일주소</label>
								<input type="text" name="param[mem_id]" id="login_mem_id" size="20" maxlength="100" value="" title="메일주소를 입력하세요." placeholder="메일주소를 입력하세요." class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
							</div>
							<div>
								<label for="login_mem_pwd" class="label">비밀번호</label>
								<input type="password" name="param[mem_pwd]" id="login_mem_pwd" size="20" maxlength="20" title="비밀번호를 입력하세요." placeholder="비밀번호를 입력하세요." class="type_text" />
							</div>
							<div>
								<div class="login_right"><span class="btn_big"><input type="submit" value="로그인" /></span></div>
								<div class="login_left"><input type="checkbox" name="login_mem_id_chk" id="login_mem_id_chk" value="Y" onclick="check_login_save()" /><label for="login_mem_id_chk">아이디 저장</label></div>
							</div>
						</div>
						<div class="login_foot">
							<ul>
								<!-- li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/demo_form.php')"><strong>데모신청</strong></a></li -->
								<li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/find_id.php')">아이디 찾기</a></li>
								<li><a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/find_pwd.php')">비밀번호 찾기</a></li>
							</ul>
						</div>
					</fieldset>
				</form>
			</div>
			<!-- Service Counter -->
			<?
				$agent_where = " and comp.del_yn = 'N' and part.del_yn = 'N' and ci.del_yn = 'N'";
				$agent_list = agent_data_data('page', $agent_where);
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
				<h3><span>Bizstory Agent</span> 배포현황</h3>
				<div class="counter_num">
					<div class="mask"></div>
					<div id="box1" class="box_bg"><?=$agent_str[0];?></div>
					<div id="box2" class="box_bg"><?=$agent_str[1];?></div>
					<div id="box3" class="box_bg"><?=$agent_str[2];?></div>
					<div id="box4" class="box_bg"><?=$agent_str[3];?></div>
					<div id="box5" class="box_bg"><?=$agent_str[4];?></div>
				</div>
				<p>명이 비즈스토리와 함께 합니다.</p>
			</div>
			<!-- //Service Counter -->
			<!-- 메인 visual -->
			<div class="sliderkit gall-img">
				<div class="sliderkit-nav">
					<div class="sliderkit-nav-clip">
						<ul>
							<li>
								<a href="http://www.ubstory.net/bbs/boardView.do?id=9&bIdx=11&page=1&menuId=56" target="_blank" title="새창으로 이동"><img src="/bizstory/images/common/popup_podcast2.jpg" alt="" /></a>
							</li>
							<li>
								<a href="http://homestory.ubstory.net" target="_blank" title="새창으로 이동"><img src="/bizstory/images/common/popup_homestory.jpg" alt="" /></a>
							</li>
							<li>
								<a href="http://www.ubstory.net/bbs/boardView.do?id=9&bIdx=11&page=1&menuId=56" target="_blank" title="새창으로 이동"><img src="/bizstory/images/common/popup_podcast.jpg" alt="" /></a>
							</li>
							<li>
								<a href="http://www.ubstory.net/bbs/boardView.do?id=9&bIdx=2&page=1&menuId=56" target="_blank" title="새창으로 이동"><img src="/bizstory/images/common/popup_news.jpg" alt="" /></a>
							</li>
							<li>
								<a href="http://homestory.viewstory.net" target="_blank" title="새창으로 이동"><img src="/bizstory/images/common/popup_viewstory.jpg" alt="" /></a>
							</li>
						</ul>
					</div>
					<div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-prev"><a href="#" title="Scroll to the left">이전</a></div>
					<div class="sliderkit-btn sliderkit-nav-btn sliderkit-nav-next"><a href="#" title="Scroll to the right">다음</a></div>
				</div>
			</div>
			<!-- //메인 visual -->
		</article>	
		<!-- Notice Ticker -->
		<?
			$notice_where = " and ni.notice_type = '2' and ni.view_yn = 'Y' and ni.comp_all = 'Y'";
			$notice_list = notice_info_data('list', $notice_where, '', '', '');
		?>
			<div id="login_notice" class="ticker">
				<div class="ticker_frame">
					<h3><span class="axi axi-volume"></span>&nbsp;&nbsp;중 요 알 림</h3>
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
		
		<article class="m_bizstory">
			<div class="m_biz_area">
				<div>
					<h3>Bizstroy</h3>
					<h4>Smart Work Hybrid- Cloud System, IP Core Management</h4>
					<ul class="info">
						<li>효율적인<br />업무소통</li>
						<li>열린의사<br />결정</li>
						<li>소모적인 이메일<br />업무 최소화</li>
					</ul>
					<p class="txt"><span>강소기업</span>의 비법!!<br /><strong>많이 다른 협업</strong>이 있습니다.</p>
				</div>
				<ul class="type">
					<li>
						<a href="<?=$local_dir;?>/contents/service_info01.php">
							<dl>
								<dt>업무형</dt>
								<dd>팀의 모든 업무를 한곳에서<br />쓰고, 모으고, 찾고, 공유하세요!!</dd>
							</dl>
						</a>
					</li>
					<li>
						<a href="<?=$local_dir;?>/contents/service_info02.php">
							<dl>
								<dt>유지보수형</dt>
								<dd>스마트한 접수!<br />비용은 다운~ 생산성은 업!!</dd>
							</dl>
						</a>
					</li>
					<li>
						<a href="<?=$local_dir;?>/contents/service_info03.php">
							<dl>
								<dt>문서보안형</dt>
								<dd>문서관리 기능으로 <br />보안, 권한, 체계적인 관리까지!!</dd>
							</dl>
						</a>
					</li>
				</ul>
			</div>
		</article>
		
		<article class="m_app">
			<div>
				<h3>
					작은 조직의 힘을 증명하는 협업 솔루션
					<strong><span>비즈스토리</span>  모바일 APP 다운로드</strong>
				</h3>
				<ul>
					<li><a href="https://play.google.com/store/apps/details?id=com.ubstory.bizstory" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/bizstory/images/common/app_android.gif" alt="안드로이드 app" /></a></li>
					<li><a href="https://itunes.apple.com/kr/app/bizstory/id533048755" target="_blank" title="새창으로 이동"><img src="<?=$local_dir;?>/bizstory/images/common/app_ios.gif" alt="아이폰 app" /></a></li>
				</ul>
			</div>
		</article>
		
		<article class="m_info">
			<h3>
				<strong><span>스마트워크</span>비즈스토리</strong>
				<span class="axi axi-ring-volume"></span> 문의전화 : 1544-7325 (내선2번)
			</h3>
			
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
			<div class="m_demo">
				<a href="javascript:void(0);" onclick="login_popup_view('<?=$local_dir;?>/bizstory/member/demo_form.php')">
					<h3>데모신청</h3>
					<p>스마트 업무를 언제 어디서나<br />직접 서비스를 체험할 수 있습니다.</p>
				</a>
			</div>
			<div class="m_inquiry">
				<a href="/contents/service_price.php">
					<h3>서비스가격</h3>
					<p>스마트한 업무 비즈스토리의<br />서비스 가격을 안내해드립니다.</p>
					<span>자세히보기</span>
				</a>
			</div>
			<div class="m_notice">
				<div class="tab_board board1">
					<h3><a href="#" class="tab1 on"><span>UBSTORY</span> 새로운 소식 </a></h3>
					<div>
						<ul style="display: block;">
							<li>
								<dl>
									<dt><a href="http://www.ubstory.net/bbs/boardView.do?id=9&bIdx=11&page=1&menuId=56" target="_blank" title="새창으로 이동">비즈스토리가 팟캐스트 광고를 시작합니다.</a></dt>
									<dd>
										<div><img width="360" src="http://www.ubstory.net/ubstory/include/img/ad/ad_8949_conference.jpg" alt="" /></div>
										스마트 협업 시스템 비즈스토리를 어떻게 고객들에게 쉽고 명쾌하게 전달할 것인가에 대한 다양한 아이디어를 모아모아 스팟광고의 결실을 만들어냈습니다.<br />삼시두끼 특유의 유머로 탄생한 스마트 협업 시스템 ‘비즈스토리’’~!!<br />즐겁게 광고를 들어보시고 평가해주시면 더욱 나은 광고로 보답하겠습니다.
									</dd> 
								</dl>
							</li>
							<li class="more"><a href="http://www.ubstory.net/bbs/board.do?id=9&menuId=56#1" target="_blank" title="새창으로 이동">새로운 소식 더보기</a></li>
						</ul>
					</div>
				</div>
			</div>
		</article>

	</div>


	<div class="login_footer">
		<div class="address">
			<h3>Contact US</h3>
			<address>
				<p>431-815 경기도 안양시 동안구 시민대로 248번길 25 (부림동 1591-9) 안양창조산업센터 305호  &nbsp;&nbsp; T. 1544-7325 &nbsp;&nbsp; F. 0505-719-7325 &nbsp;&nbsp; E. ubsns@ubstory.net</p>
			</address>
		</div>	
		<div id="footer"></div>
	</div>


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