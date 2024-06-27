<?
	$session_time = 60 * 60 * 5; // 5 hours 1시간을 5시간으로 설정(2013.05.22)
	ini_set("session.cache_expire", $session_time);
	ini_set("session.gc_maxlifetime", $session_time);
	error_reporting(E_ERROR | E_WARNING);

	session_start();

	header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
	header("Content-type: text/html; charset=UTF-8");
	header("Pragma: no-cache");
	header("Cache-Control: no-cache, must-revalidate");

	define('db_host', '183.111.148.116');  //접속 호스트
	define('db_name', 'bizstory');   //접속 DB
	define('db_user', 'root');       //접속 아이디
	define('db_pass', 'uBpass4862'); //접속 비밀번호	

	
///////////////////////////////////////////////////////////////////////////////
// 기본 경로
	$site_url = 'www.bizstory.co.kr';//$_SERVER['SERVER_NAME'];
	$root_dir = $_SERVER['DOCUMENT_ROOT'];	

    $local_dir  = '';
    $mobile_dir  = '/bizstory/m';
	$local_path = $root_dir . $local_dir;
    $mobile_path = $root_dir . $mobile_dir;

	$query_path = $local_path . '/data/error'; // SQL ERROR

	$temp_path   = $local_path . '/bizstory/template'; // 템플릿
	$temp_dir    = $local_dir  . '/bizstory/template';

	$tmp_path = $local_path . '/data/tmp'; // 기본저장소
	$tmp_dir  = $local_dir  . '/data/tmp';

	$banner_path = $local_path . '/data/banner'; // 배너
	$banner_dir  = $local_dir  . '/data/banner';

	$popup_path = $local_path . '/data/popup'; // 팝업창
	$popup_dir  = $local_dir  . '/data/popup';

	$bbs_path = $local_path . '/data/bbs'; // 게시판
	$bbs_dir  = $local_dir  . '/data/bbs';

	$sole_path  = $local_path . '/data/sole'; // 총판
	$sole_dir   = $local_dir  . '/data/sole';

	$temp_path  = $local_path . '/bizstory/template'; // 스킨
	$temp_dir   = $local_dir  . '/bizstory/template';
	
// 파일미리보기
	$set_preview_url    = 'http://view.ubstory.net';
	$preview_agent_code = 'bizstory'; // 문서 미리보기
	$preview_user_id    = 'bizstory';

///////////////////////////////////////////////////////////////////////////////
	$sess_str    = 'bizstory';
	$ip_address  = $_SERVER['REMOTE_ADDR'];
	$this_url    = $_SERVER['REQUEST_URI'];
	$this_host   = $_SERVER['HTTP_HOST'];
	$this_string = $_SERVER['QUERY_STRING'];
	$this_page   = $_SERVER['PHP_SELF'];
	$this_page_ok    = basename($this_page, '.php') . '_ok.php';
	$this_page_print = basename($this_page, '.php') . '_print.php';
	$this_page_excel = basename($this_page, '.php') . '_excel.php';
	if (stristr($this_page, '_ok')) $this_page = str_replace('_ok', '', $this_page);

	if ($move_url == "") $move_url = urlencode($this_url);
	else $move_url = urlencode($move_url);
	
	include $local_path . '/bizstory/common/code_data.php';
    include $local_path . '/bizstory/function/db_lib.php';
	include $local_path . '/bizstory/function/function.php';        // 기본
	include $local_path . '/bizstory/function/func_data.php';	
	include $local_path . '/bizstory/function/data_class.php';
	include $local_path . '/bizstory/function/func_receipt.php';    // 접수	
	include $local_path . '/bizstory/function/func_work.php';       // 업무
	include $local_path . '/bizstory/function/func_board.php';      // 게시판		
	include $local_path . '/bizstory/function/func_member.php';     // 직원
	include $local_path . '/bizstory/function/func_preview.php';    // 미리보기
	include $local_path . '/bizstory/function/func_expert.php';     // 전문가
	include $local_path . '/bizstory/function/func_filecenter.php'; // 파일센터
		   
	include $local_path . '/bizstory/push/class.c2dm.php'; // 안드로이드 푸시
	include $local_path . '/bizstory/push/class.apns.php'; // ios 푸시
	include $local_path . '/bizstory/push/class.push.php'; // 푸시   

    include $local_path . '/bizstory/common/menu_title.php';    //메뉴명
	include $local_path . '/bizstory/function/func_carecon.php';     // 케어콘

///////////////////////////////////////////////////////////////////////////////
// 변수관련

	db_connect();
	
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
	
	if ($page_size == '') // 페이지당 게시물
	{
		if ($fmode == 'work' && $smode == 'work') $page_size = 30;
		else if ($fmode == 'filecenter' && $smode == 'filemanager') $page_size = 50;
		else if ($fmode == 'expert' && $smode == 'client_search') $page_size = 200;
		else $page_size = 20;
	}
	$recv_page_size = urldecode($page_size);
	$send_page_size = chk_input($recv_page_size);
	$page_size      = string_input($page_size);

	if ($block_size == '') $block_size = 10; // 블럭당 페이지수
	$recv_block_size = urldecode($block_size);
	$send_block_size = chk_input($recv_block_size);
	$block_size      = string_input($block_size);

// 댓글페이지관련
	if ($m_page_num == '') $m_page_num = 1;  // 페이지
	$recv_m_page_num = urldecode($m_page_num);
	$send_m_page_num = chk_input($recv_m_page_num);
	$m_page_num      = string_input($m_page_num);

	if ($m_page_size == '') $m_page_size  = 10; // 페이지당 게시물
	$recv_m_page_size = urldecode($m_page_size);
	$send_m_page_size = chk_input($recv_m_page_size);
	$m_page_size      = string_input($m_page_size);

	if ($m_block_size == '') $m_block_size = 10; // 블럭당 페이지수
	$recv_m_block_size = urldecode($m_block_size);
	$send_m_block_size = chk_input($recv_m_block_size);
	$m_block_size      = string_input($m_block_size);


    ///////////////////////////////////////////////////////////////////////////////
// 업체정보 - 사이트용
	if ($_SESSION[$sess_str . '_comp_idx'] != '')
	{ 
		$company_info_where = " and comp.comp_idx = '" . $_SESSION[$sess_str . '_comp_idx'] . "'";
		$company_info_data  = company_info_data('view', $company_info_where);
		$company_id = $company_info_data['comp_idx'];
	}
// 업체정보 - 에이전트용
	else
	{
		$company_info_where = " and comp.comp_idx = '" . $_SESSION['agent_client_comp'] . "'";
		$company_info_data  = company_info_data('view', $company_info_where);
		$company_id = $company_info_data['comp_idx'];
		
	}

// 업체설정
	$comp_set_where = " and cs.comp_idx = '" . $company_id . "'";
	$comp_set_data  = company_setting_data('view', $comp_set_where);

	$set_viewer_yn      = $comp_set_data['viewer_yn'];     // 파일 미리보기여부
	$set_part_yn        = $comp_set_data['part_yn'];       // 지사통합여부
	$set_part_work_yn   = $comp_set_data['part_work_yn'];  // 업무지사통합여부
	$set_file_class     = $comp_set_data['file_class'];    // 파일 내부-외부
	$set_filecenter_yn  = $comp_set_data['filecenter_yn']; // 파일센터사용여부
	if ($set_file_class == 'OUT')
	{
		$set_filecneter_url = 'http://' . $comp_set_data['file_out_url'] . '/filecenter'; // 파일센터 주소
		$set_upload_url     = 'http://' . $comp_set_data['file_out_url']; // 파일센터 주소
	}
	else
	{
		$set_filecneter_url = 'http://' . getenv("HTTP_HOST") . '/bizstory/filecenter'; // 파일센터 주소
		$set_upload_url     = 'http://' . getenv("HTTP_HOST");//$local_dir; // 파일센터 주소
	}

///////////////////////////////////////////////////////////////////////////////
	$file_multi_size     = 150 * 1024 * 1024;
	$upload_file_num_max = 5;

// 새로 파일업로드할 용도
	$file_max_file  = 1000 * 1024 * 1024 * 1024;  // 개당파일크기-20G
	$file_max_size  = 1000 * 1024 * 1024 * 1024; // 총 파일크기-100G
	$file_max_cnt   = 1000;      // 총개수
	$file_max_file1 = 100 . 'G';   // 개당파일크기
	$file_max_size1 = 1 . 'T'; // 총 파일크기
	$file_max_cnt1  = 1000;      // 총개수

// 브라우저 정보
	$mybrowser     = getenv('HTTP_USER_AGENT');
	$mybrowser_arr = explode(';', $mybrowser);

	if (count($mybrowser_arr) > 3 && !strpos($mybrowser, 'MSIE') ) {
	
		if (stripos($mybrowser_arr[2], 'Trident')) {
			$mybrowser_val_val = 'MSIE11';
		} else {
			$mybrowser_val = trim($mybrowser_arr[1]);
			$mybrowser_val_arr = explode(' ', $mybrowser_val);
			$mybrowser_val_val = trim($mybrowser_val_arr[0]);	
		}
	} else {
		$mybrowser_val = trim($mybrowser_arr[1]);
		$mybrowser_val_arr = explode(' ', $mybrowser_val);
		$mybrowser_val_val = trim($mybrowser_val_arr[0]);	
	}

///////////////////////////////////////////////////////////////////////////////
// 업체관련 경로
	$comp_path = $local_path . '/data/company'; // 업체별
	$comp_dir  = $local_dir  . '/data/company';

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

	$comp_bbs_path     = $comp_path . '/' . $company_id . '/bbs'; // 게시판
	$comp_bbs_dir      = $comp_dir  . '/' . $company_id . '/bbs';

	$comp_bnotice_path = $comp_path . '/' . $company_id . '/bnotice'; // 알림게시판
	$comp_bnotice_dir  = $comp_dir  . '/' . $company_id . '/bnotice';

	$comp_client_path  = $comp_path . '/' . $company_id . '/client'; // 거래처파일
	$comp_client_dir   = $comp_dir  . '/' . $company_id . '/client';

	$comp_consult_path = $comp_path . '/' . $company_id . '/consult'; // 상담게시판
	$comp_consult_dir  = $comp_dir  . '/' . $company_id . '/consult';

	$comp_account_path = $comp_path . '/' . $company_id . '/account'; // 회계
	$comp_account_dir  = $comp_dir  . '/' . $company_id . '/account';

	$comp_project_path = $comp_path . '/' . $company_id . '/project'; // 프로젝트
	$comp_project_dir  = $comp_dir  . '/' . $company_id . '/project';




	$comp_schedule_path = $comp_path . '/' . $company_id . '/schedule'; // 일정
	$comp_schedule_dir  = $comp_dir  . '/' . $company_id . '/schedule';

	$comp_banner_path = $comp_path . '/' . $company_id . '/banner'; // 배너
	$comp_banner_dir  = $comp_dir  . '/' . $company_id . '/banner';

	$comp_popup_path = $comp_path . '/' . $company_id . '/popup'; // 팝업창
	$comp_popup_dir  = $comp_dir  . '/' . $company_id . '/popup';
	
?>