<?
	$session_time = 60 * 60 * 6; // 6hours
	ini_set("session.cache_expire", $session_time);
	ini_set("session.gc_maxlifetime", $session_time);

	session_start();

	header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
	header("Content-type: text/html; charset=UTF-8");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");

///////////////////////////////////////////////////////////////////////////////
	$sess_str     = 'bizstory';
	$ip_address   = $_SERVER['REMOTE_ADDR'];
	$this_url     = $_SERVER['REQUEST_URI'];
	$this_host    = $_SERVER['HTTP_HOST'];
	$this_string  = $_SERVER['QUERY_STRING'];
	$this_page    = $_SERVER['PHP_SELF'];
	$this_page_ok = basename($this_page, '.php') . '_ok.php';
	if (stristr($this_page, '_ok')) $this_page = str_replace('_ok', '', $this_page);

	if ($move_url == "") $move_url = urlencode($this_url);
	else $move_url = urlencode($move_url);

///////////////////////////////////////////////////////////////////////////////
// 기본 경로
	$site_url = $_SERVER['SERVER_NAME'];
	$root_dir = $_SERVER['DOCUMENT_ROOT'];

	$local_dir1 = '';
	$local_dir  = '/bizstory/mobile';
	$local_path = $root_dir . $local_dir;

///////////////////////////////////////////////////////////////////////////////
	define(db_host, '183.111.148.115');  //접속 호스트
	define(db_name, 'bizstory');   //접속 DB
	define(db_user, 'root');       //접속 아이디
	define(db_pass, 'uBpass4862'); //접속 비밀번호

	include $root_dir . '/bizstory/common/code_data.php';
	include $root_dir . '/bizstory/function/database.php';
	include $root_dir . '/bizstory/function/function.php'; // 기본
	include $root_dir . '/bizstory/function/func_data.php';
	include $root_dir . '/bizstory/function/data_class.php';
	include $root_dir . '/bizstory/function/func_receipt.php'; // 접수
	include $root_dir . '/bizstory/function/func_work.php';    // 업무
	include $root_dir . '/bizstory/function/func_member.php';  // 회원, 직원
	include $root_dir . '/bizstory/function/func_board.php';   // 게시판
///////////////////////////////////////////////////////////////////////////////

/* 기본정보 */
	$tel_num = "02-1544-7325";
	$sms_num = "010-8925-4862";
	$mobile_eng  = "BIZSTORY";
	//$mobile_site = "http://www.bizstory.co.kr";
	$mobile_name = "비즈스토리";

/* 상단 기타버튼 */
	//$btn_location = '<a class="button" href="javascript:void(0)" onclick="window.location.href=\'' . $local_dir . '/map.php\'">location</a>';
	$btn_reload   = '<a class="right_b" href="javascript:void(0)" onclick="window.location.reload()">refresh</a>';
	//$btn_sms      = '<a href="sms:' . $sms_num . '" class="sms" target="_blank">sms</a>';
	//$btn_tel      = '<a href="tel:' . $tel_num . '" class="right_b">tel</a>';
	$btn_back     = '<a class="back" href="javascript:history.go(-1)">go back</a>';
	$btn_menu     = '<a class="button menu" href="javascript:void(0);">menu</a>';

	$Write = "<a href=\"javascript:void(0)\" onclick=\"window.location.href='" . $local_dir . "memo_write.php'\" class=\"right_b\"><em>Write</em></a>";

/* 하단 아이콘 네비게이션 및 카피라이트 */
	$address = '
		<div id="footer">
			<div class="navi">
				<div>
					<a href="javascript:void(0)" onclick="window.location.href=\'' . $local_dir . '/index.php\'" class="icon4"><span>홈</span></a>
					<a href="javascript:void(0)" onclick="alert(\'준비중입니다.\')" class="icon2"><span>글작성</span></a>
					<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
					<a href="javascript:void(0)" onclick="window.location.href=\'' . $local_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
				</div>
			</div>
			<address>
				<em>Copyright &copy; 2011</em>
				<strong>' . $mobile_eng . '.</strong>
				<span>All Rights Reserved.</span>
			</address>
		</div>
		<div id="popup_result_msg" title="처리결과"></div>
<script type="text/javascript">
//<![CDATA[
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() {$(this).dialog("close");}
		}
	});
//]]>
</script>
	';

	$bottom_btn = array(
		  "1"=>'<a href="javascript:void(0)" onclick="window.location.href=\'' . $local_dir . '/index.php\'" class="icon4"><span>홈</span></a>'
		, "2"=>'<a href="javascript:void(0)" onclick="alert(\'준비중입니다.\')" class="icon2"><span>글작성</span></a>'
		, "3"=>'<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>'
		, "4"=>'<a href="javascript:void(0)" onclick="window.location.href=\'' . $local_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>'
	);

/* 쇼셜네트워크 */
	$SNS = "
		<ul class=\"sns\">
			<li><a href=\"javascript:void(0)\" onclick=\"SNSScrap('twitter','게시물 제목','게시물 주소');\" class=\"twitter\"><span>Twitter</span></a></li>
			<li><a href=\"javascript:void(0)\" onclick=\"SNSScrap('facebook','게시물 제목','게시물 주소');\" class=\"facebook\"><span>Facebook</span></a></li>
			<li><a href=\"javascript:void(0)\" onclick=\"SNSScrap('me2day','게시물 제목','게시물 주소');\" class=\"me2day\"><span>me2day</span></a></li>
		</ul>
	";

///////////////////////////////////////////////////////////////////////////////
// 변수관련
	$arr_request = $_REQUEST;
	if (is_array($arr_request))
	{
		foreach($arr_request as $k => $v)
		{
			$key  = $k;
			$key1 = 'recv_' . $k;
			$key2 = 'send_' . $k;

			if (is_array($v)) { }
			else
			{
				$$key1 = urldecode($v);
				$$key2 = chk_input($$key1);
				$$key  = string_input($v);
			}
		}
		unset($_REQUEST);
	}

// 일반페이지관련
	if ($page_num == '') $page_num = 1;  // 페이지
	$recv_page_num = urldecode($page_num);
	$send_page_num = chk_input($recv_page_num);
	$page_num      = string_input($page_num);

	if ($page_size == '') $page_size  = 10; // 페이지당 게시물
	$recv_page_size = urldecode($page_size);
	$send_page_size = chk_input($recv_page_size);
	$page_size      = string_input($page_size);

	if ($block_size == '') $block_size = 10; // 블럭당 페이지수
	$recv_block_size = urldecode($block_size);
	$send_block_size = chk_input($recv_block_size);
	$block_size      = string_input($block_size);

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

// 업체정보
	$company_info_where = " and comp.comp_idx = '" . $code_comp . "'";
	$company_info_data  = company_info_data('view', $company_info_where);
	$company_id = $company_info_data['comp_idx'];

// 업체설정
	$company_set_where = " and cs.comp_idx = '" . $code_comp . "'";
	$company_set_data  = company_set_data('view', $company_set_where);

// 회원정보
	$member_info_where = " and mem.comp_idx = '" . $code_comp . "' and mem.mem_idx = '" . $code_part . "'";
	$member_info_data  = member_info_data('view', $member_info_where);

	$comp_path = $root_dir . '/data/company'; // 업체별
	$comp_dir  = '/data/company';

	$comp_company_path = $comp_path . '/' . $company_id . '/company'; // 업체
	$comp_company_dir  = $comp_dir  . '/' . $company_id . '/company';

	$comp_member_path  = $comp_path . '/' . $company_id . '/member'; // 회원
	$comp_member_dir   = $comp_dir  . '/' . $company_id . '/member';

	$comp_msg_path     = $comp_path . '/' . $company_id . '/message'; // 쪽지
	$comp_msg_dir      = $comp_dir  . '/' . $company_id . '/message';

	$comp_receipt_path = $comp_path . '/' . $company_id . '/receipt'; // 접수
	$comp_receipt_dir  = $comp_dir  . '/' . $company_id . '/receipt';

	$comp_work_path    = $comp_path . '/' . $company_id . '/work'; // 업무
	$comp_work_dir     = $comp_dir  . '/' . $company_id . '/work';

	$comp_pro_board_path = $comp_path . '/' . $company_id . '/board_project'; // 프로젝트
	$comp_pro_board_dir  = $comp_dir  . '/' . $company_id . '/board_project';

	$comp_board_path  = $comp_path . '/' . $company_id . '/board'; // 게시판
	$comp_board_dir   = $comp_dir  . '/' . $company_id . '/board';

	$comp_bnotice_path = $comp_path . '/' . $company_id . '/bnotice'; // 알림게시판
	$comp_bnotice_dir  = $comp_dir  . '/' . $company_id . '/bnotice';

	$comp_client_path = $comp_path . '/' . $company_id . '/client'; // 거래처파일
	$comp_client_dir  = $comp_dir  . '/' . $company_id . '/client';
?>