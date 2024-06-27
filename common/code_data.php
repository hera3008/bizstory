<?
/*
	수정 : 2012.05.25
	위치 : 설정
*/
// 에러내용
	$set_error_message = array(
		'no_login' => array(
			  'error'   => '로그인페이지로 이동'
			, 'message' => '접속권한이 없습니다.<br />'
		)
		, 'no_authority' => array(
			  'error'   => '로그인페이지로 이동'
			, 'message' => '해당페이지 접속권한이 없습니다.<br />'
		)
		, 'no_direct' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '해당페이지 접속권한이 없습니다.<br />'
		)
		, 'need_sub_type' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => 'sub_type 명이 필요합니다.<br />'
		)
		, 'need_method' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => 'sub_type method 가 없습니다.<br />'
		)
		, 'no_match_id' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '일치하는 아이디가 없습니다. <br />확인 후 다시 입력해주세요.<br />'
		)
		, 'no_match_password' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '비밀번호가 일치하지 않습니다. <br />확인 후 다시 입력해주세요.<br />'
		)
		, 'no_company' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '업체정보가 없습니다.<br />'
		)
		, 'no_authority_company' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '승인된 업체가 아닙니다. <br />관리자에게 문의하여주세요.<br />'
		)
		, 'end_company' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '기간이 만료되었습니다. <br />관리자에게 문의하여주세요.<br />'
		)
		, 'no_authority_member' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '승인된 회원이 아닙니다. <br />관리자에게 문의하여주세요.<br />'
		)
		, 'no_authority_login' => array(
			  'error'   => '이전페이지로 이동'
			, 'message' => '로그인권한이 없습니다. <br />관리자에게 문의하여주세요.<br />'
		)
	);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 배열값
// 전화번호
	$set_telephone = array(
		  "02"   => "02"
		, "031"  => "031"
		, "032"  => "032"
		, "033"  => "033"
		, "041"  => "041"
		, "042"  => "042"
		, "043"  => "043"
		, "051"  => "051"
		, "052"  => "052"
		, "053"  => "053"
		, "054"  => "054"
		, "055"  => "055"
		, "061"  => "061"
		, "062"  => "062"
		, "063"  => "063"
		, "064"  => "064"
		, "0130" => "0130"
		, "0502" => "0502"
		, "0505" => "0505"
		, "070"  => "070"
		, "010"  => "010"
		, "011"  => "011"
		, "016"  => "016"
		, "017"  => "017"
		, "018"  => "018"
		, "019"  => "019"
	);
// 핸드폰번호
	$set_cellular = array(
		  "010" => "010"
		, "011" => "011"
		, "016" => "016"
		, "017" => "017"
		, "018" => "018"
		, "019" => "019"
	);
// 통신사
	$set_hp_company = array(
		  "SKT" => "SKT"
		, "KT"  => "KT"
		, "LGT" => "LGT"
	);
// 스마트폰종류
	$set_smart_class = array(
		  "iphone"  => "아이폰"
		, "android" => "안드로이드"
	);
// 메일도메인
	$set_email_domain = array(
		  "chol.com"     => "chol.com"
		, "daum.net"     => "daum.net"
		, "dreamwiz.com" => "dreamwiz.com"
		, "empal.com"    => "empal.com"
		, "freechal.com" => "freechal.com"
		, "gmail.com"    => "gmail.com"
		, "goe.go.kr"    => "goe.go.kr"
		, "hanafos.com"  => "hanafos.com"
		, "hanmail.net"  => "hanmail.net"
		, "hanmir.com"   => "hanmir.com"
		, "hotmail.com"  => "hotmail.com"
		, "korea.com"    => "korea.com"
		, "korea.kr"     => "korea.kr"
		, "lycos.co.kr"  => "lycos.co.kr"
		, "nate.com"     => "nate.com"
		, "naver.com"    => "naver.com"
		, "netian.com"   => "netian.com"
		, "paran.com"    => "paran.com"
		, "sayclub.com"  => "sayclub.com"
		, "yahoo.co.kr"  => "yahoo.co.kr"
		, "yahoo.com"    => "yahoo.com"
		, ""             => "직접입력"
	);
// 출력여부
	$set_view = array(
		  "Y" => "출력함"
		, "N" => "출력안함"
	);
// 사용여부
	$set_use = array(
		  "Y" => "사용함"
		, "N" => "사용안함"
	);
	$set_use_num = array(
		  "1" => "사용함"
		, "0" => "사용안함"
	);
// Agent Type
	$set_agent_type = array(
		  "A" => "A Type"
		, "B" => "B Type"
		, "C" => "C Type"
	);
// 메인화면타입
	$set_main_type = array(
		  "A" => "A Type(기본화면)"
		, "B" => "B Type"
		, "C" => "C Type"
		, "D" => "D Type"
		, "E" => "E Type"
		, "VCcenter" => "VCcenter Type"
	);
// 로그인화면
	$set_login_type = array(
		  "A" => "A Type(기본화면)"
		, "B" => "B Type"
		, "C" => "C Type"
		, "D" => "D Type"
		, "E" => "E Type"
		, "VCcenter" => "VCcenter Type"
		, "Neoarena" => "Neoarena Type"
	);
////////////////////////////////////////////
// 파일업로드금지
	$set_extension_no = array(
		  "perl", "pl", "cgi"
		, "php", "php3", "php4", "inc", "sql"
		, "asp", "asx"
		, "jsp", "java", "class"
		, "html", "htm", "phtml", "js"
		, "dll"
	);
	$set_extension_no_script = 'perl|pl|cgi|php|php3|php4|inc|sql|asp|asx|jsp|java|class|html|htm|phtml|js|dll';
// 미리보기파일
	$set_preview_ext = array(
		  "hwp"  => "hwp"
		, "pdf"  => "pdf"
		, "ppt"  => "ppt"
		, "pptx" => "pptx"
		, "doc"  => "doc"
		, "docx" => "docx"
		, "xls"  => "xls"
		, "xlsx" => "xlsx"
	);
	$set_preview_ext_str   = "hwp,pdf,ppt,pptx,doc,docx,xls,xlsx";
	$set_preview_ext_other = "txt";
	$set_preview_ext_img   = array("jpg", "jpeg", "gif", "png", "bmp", "tif");
	$set_preview_ext_img2  = "jpg,jpeg,gif,png,bmp,tif";

////////////////////////////////////////////
// 공휴일
	$set_holiday = array(
		  "S" => array(
			  "01-01" => "신정"
			, "03-01" => "삼일절"
			, "05-05" => "어린이날"
			, "06-06" => "현충일"
			, "08-15" => "광복절"
			, "10-03" => "개천절"
			, "10-09" => "한글날"
			, "12-25" => "성탄절"
		)
		, "L" => array(
			  "12-29" => ""
			, "01-01" => "설날(1.1)"
			, "01-02" => ""
			, "04-08" => "석가탄신일"
			, "08-14" => ""
			, "08-15" => "추석"
			, "08-16" => ""
		)
	);
// 일정구분
	$set_sche_type = array(
		  "personal" => "개인일정"
		, "team"     => "팀일정"
	);
// 반복설정
	$set_repeat_set = array(
		  "day"   => "매일"
		, "week"  => "매주"
		, "month" => "매월"
		, "year"  => "매년"
	);
// 공개여부
	$set_open_type = array(
		  "all"  => "모두공개"
		, "time" => "시간만 공개"
		, "N"    => "비공개"
	);
// 미리알림
	$set_notify_type = array(
		  "N"       => "없음"
		, "message" => "쪽지"
		, "sms"     => "문자"
		, "email"   => "메일"
	);
// 요일
	$set_week = array(
		  "1" => "월"
		, "2" => "화"
		, "3" => "수"
		, "4" => "목"
		, "5" => "금"
		, "6" => "토"
		, "7" => "일"
	);
// 요일2
	$set_week2 = array(
		  "1" => "월요일"
		, "2" => "화요일"
		, "3" => "수요일"
		, "4" => "목요일"
		, "5" => "금요일"
		, "6" => "토요일"
		, "7" => "일요일"
	);
// 시간
	$set_sche_time = array(
		  "00:00" => "오전 12:00"
		, "00:30" => "오전 12:30"
		, "01:00" => "오전 01:00"
		, "01:30" => "오전 01:30"
		, "02:00" => "오전 02:00"
		, "02:30" => "오전 02:30"
		, "03:00" => "오전 03:00"
		, "03:30" => "오전 03:30"
		, "04:00" => "오전 04:00"
		, "04:30" => "오전 04:30"
		, "05:00" => "오전 05:00"
		, "05:30" => "오전 05:30"
		, "06:00" => "오전 06:00"
		, "06:30" => "오전 06:30"
		, "07:00" => "오전 07:00"
		, "07:30" => "오전 07:30"
		, "08:00" => "오전 08:00"
		, "08:30" => "오전 08:30"
		, "09:00" => "오전 09:00"
		, "09:30" => "오전 09:30"
		, "10:00" => "오전 10:00"
		, "10:30" => "오전 10:30"
		, "11:00" => "오전 11:00"
		, "11:30" => "오전 11:30"
		, "12:00" => "오후 12:00"
		, "12:30" => "오후 12:30"
		, "13:00" => "오후 01:00"
		, "13:30" => "오후 01:30"
		, "14:00" => "오후 02:00"
		, "14:30" => "오후 02:30"
		, "15:00" => "오후 03:00"
		, "15:30" => "오후 03:30"
		, "16:00" => "오후 04:00"
		, "16:30" => "오후 04:30"
		, "17:00" => "오후 05:00"
		, "17:30" => "오후 05:30"
		, "18:00" => "오후 06:00"
		, "18:30" => "오후 06:30"
		, "19:00" => "오후 07:00"
		, "19:30" => "오후 07:30"
		, "20:00" => "오후 08:00"
		, "20:30" => "오후 08:30"
		, "21:00" => "오후 09:00"
		, "21:30" => "오후 09:30"
		, "22:00" => "오후 10:00"
		, "22:30" => "오후 10:30"
		, "23:00" => "오후 11:00"
		, "23:30" => "오후 11:30"
	);
// 시간
	$set_sche_repeat = array(
		  "0"  => "당일"
		, "1"  => "1일뒤"
		, "2"  => "2일뒤"
		, "3"  => "3일뒤"
		, "4"  => "4일뒤"
		, "5"  => "5일뒤"
		, "6"  => "6일뒤"
		, "7"  => "1주뒤"
		, "14" => "2주뒤"
	);
// 알림
	$set_sche_notify = array(
		  "0"     => "정시"
		, "5"     => "5분전"
		, "10"    => "10분전"
		, "15"    => "15분전"
		, "30"    => "30분전"
		, "60"    => "1시간"
		, "120"   => "2시간"
		, "1440"  => "1일(24시간)"
		, "2880"  => "2일(48시간)"
		, "10080" => "1주일(168시간)"
	);

////////////////////////////////////////////
// 보고서 상태
	$set_report_status = array(
		  "1" => "등록"
		, "2" => "완료"
		, "3" => "취소"
	);

// 입력사항
	$set_input_type = array(
		  "text"     => "텍스트박스"
		, "radio"    => "라디오버튼"
		, "checkbox" => "체크박스"
		, "select"   => "셀렉트박스"
		, "textarea" => "textarea"
	);

// color list
	$set_color_list = array(
		  "0" => array("FFFFFF","E5E4E4","D9D8D8","C0BDBD","A7A4A4","8E8A8B","827E7F","767173","5C585A","000000")
		, "1" => array("FEFCDF","FEF4C4","FEED9B","FEE573","FFED43","F6CC0B","E0B800","C9A601","AD8E00","8C7301")
		, "2" => array("FFDED3","FFC4B0","FF9D7D","FF7A4E","FF6600","E95D00","D15502","BA4B01","A44201","8D3901")
		, "3" => array("FFD2D0","FE9A95","FE9A95","FF7A73","FF483F","FE2419","F10B00","D40A00","940000","6D201B")
		, "4" => array("FFDAED","FFA1D1","FFA1D1","FF84C3","FF57AC","FD1289","EC0078","D6006D","BB005F","9B014F")
		, "5" => array("FCD6FE","F9A1FE","F9A1FE","F784FE","F564FE","F546FF","F328FF","D801E5","C001CB","8F0197")
		, "6" => array("E2F0FE","ADD5FE","ADD5FE","92C7FE","6EB5FF","48A2FF","2690FE","0162F4","013ADD","0021B0")
		, "7" => array("D3FDFF","7CFAFF","7CFAFF","4AF7FE","1DE6FE","01DEFF","00CDEC","01B6DE","00A0C2","0084A0")
		, "8" => array("EDFFCF","D1FD88","D1FD88","BEFA5A","A8F32A","8FD80A","79C101","3FA701","307F00","156200")
		, "9" => array("D4C89F","C49578","C49578","C2877E","AC8295","C0A5C4","969AC2","92B7D7","80ADAF","9CA53B")
	);
	$set_color_list2 = array('',"#0075c8","#009e25","#3a32c3","#7820b9","#ffaa00","#ffef00","#a6cf00","#009e25","#00b0a2","#0075c8");
	$set_color_list3 = array('',"#ccffcc","#66ff00","#00ffcc","#ffcc66","#cccc00","#66cccc","#33cc66","#00cc00","#cc99cc","#999966","#669900","#0099cc","#ff6666","#cc6600","#6666cc");
/*
			"#000000","#000033","#000066","#000099","#0000cc","#0000ff","#330000","#330033",
			"#330066","#330099","#3300cc","#3300ff","#660000","#660033","#660066","#660099",
			"#6600cc","#6600ff","#990000","#990033","#990066","#990099","#9900cc","#9900ff",
			"#cc0000","#cc0033","#cc0066","#cc0099","#cc00cc","#cc00ff","#ff0000","#ff0033",
			"#ff0066","#ff0099","#ff00cc","#ff00ff","#003300","#003333","#003366","#003399",
			"#0033cc","#0033ff","#333300","#333333","#333366","#333399","#3333cc","#3333ff",
			"#663300","#663333","#663366","#663399","#6633cc","#6633ff","#993300","#993333",
			"#993366","#993399","#9933cc","#9933ff","#cc3300","#cc3333","#cc3366","#cc3399",
			"#cc33cc","#cc33ff","#ff3300","#ff3333","#ff3366","#ff3399","#ff33cc","#ff33ff",
			"#006600","#006633","#006666","#006699","#0066cc","#0066ff","#336600","#336633",
			"#336666","#336699","#3366cc","#3366ff","#666600","#666633","#666666","#666699",
			"#6666cc","#6666ff","#996600","#996633","#996666","#996699","#9966cc","#9966ff",
			"#cc6600","#cc6633","#cc6666","#cc6699","#cc66cc","#cc66ff","#ff6600","#ff6633",
			"#ff6666","#ff6699","#ff66cc","#ff66ff","#009900","#009933","#009966","#009999",
			"#0099cc","#0099ff","#339900","#339933","#339966","#339999","#3399cc","#3399ff",
			"#669900","#669933","#669966","#669999","#6699cc","#6699ff","#999900","#999933",
			"#999966","#999999","#9999cc","#9999ff","#cc9900","#cc9933","#cc9966","#cc9999",
			"#cc99cc","#cc99ff","#ff9900","#ff9933","#ff9966","#ff9999","#ff99cc","#ff99ff",
			"#00cc00","#00cc33","#00cc66","#00cc99","#00cccc","#00ccff","#33cc00","#33cc33",
			"#33cc66","#33cc99","#33cccc","#33ccff","#66cc00","#66cc33","#66cc66","#66cc99",
			"#66cccc","#66ccff","#99cc00","#99cc33","#99cc66","#99cc99","#99cccc","#99ccff",
			"#cccc00","#cccc33","#cccc66","#cccc99","#cccccc","#ccccff","#ffcc00","#ffcc33",
			"#ffcc66","#ffcc99","#ffcccc","#ffccff","#00ff00","#00ff33","#00ff66","#00ff99",
			"#00ffcc","#00ffff","#33ff00","#33ff33","#33ff66","#33ff99","#33ffcc","#33ffff",
			"#66ff00","#66ff33","#66ff66","#66ff99","#66ffcc","#66ffff","#99ff00","#99ff33",
			"#99ff66","#99ff99","#99ffcc","#99ffff","#ccff00","#ccff33","#ccff66","#ccff99",
			"#ccffcc","#ccffff","#ffff00","#ffff33","#ffff66","#ffff99","#ffffcc","#ffffff"
*/

///////////////////////////////////////////////////////////////////////////////
// 필요한 쿼리문
	$create_query = array(
		"query_history" => "CREATE TABLE IF NOT EXISTS `[table_name]` (
			`idx` bigint(20) NOT NULL AUTO_INCREMENT,
			`comp_idx` int(11) NOT NULL DEFAULT '0' COMMENT '업체정보(company_info)',
			`part_idx` int(11) NOT NULL DEFAULT '0' COMMENT '지사정보(company_part)',
			`query_string` text NOT NULL COMMENT '쿼리내용',
			`table_name` varchar(30) NOT NULL DEFAULT 'no' COMMENT '테이블명',
			`query_type` varchar(10) NOT NULL DEFAULT 'no' COMMENT '명령어',
			`reg_ip` varchar(20) NOT NULL DEFAULT 'no' COMMENT '등록IP주소',
			`reg_id` varchar(200) NOT NULL DEFAULT 'system' COMMENT '등록자',
			`reg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',

			PRIMARY KEY (`idx`),
			KEY `table_name` (`comp_idx`, `table_name`),
			KEY `query_type` (`comp_idx`, `query_type`),
			KEY `comp_idx` (`comp_idx`,`part_idx`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='쿼리내역';"
	);

	$table_query = array(
		"board" => "CREATE TABLE IF NOT EXISTS `[table_name]` (
			`b_idx`      int(11)             NOT NULL AUTO_INCREMENT,
			`comp_idx`   int(11)             NOT NULL DEFAULT '0'    COMMENT 'company_info(업체정보)',
			`part_idx`   int(11)             NOT NULL DEFAULT '0'    COMMENT 'company_part(지사정보)',
			`bs_idx`     int(11)             NOT NULL DEFAULT '0'    COMMENT 'board_setting(게시판설정)',
			`bc_idx`     int(11)             NOT NULL DEFAULT '0'    COMMENT 'board_category(말머리)',
			`mem_idx`    int(11)             NOT NULL DEFAULT '0'    COMMENT 'member_info(회원)',
			`writer`     varchar(100)        NOT NULL DEFAULT ''     COMMENT '작성자',
			`subject`    varchar(255)        NOT NULL DEFAULT ''     COMMENT '제목',
			`remark`     longtext            NOT NULL DEFAULT ''     COMMENT '내용',
			`pwd`        varchar(255)        NOT NULL DEFAULT ''     COMMENT '비밀번호',
			`views`      int(11)             NOT NULL DEFAULT '0'    COMMENT '조회수',
			`secret_yn`  enum('Y','N')       NOT NULL DEFAULT 'N'    COMMENT '비밀글여부',
			`ip_addr`    varchar(20)                  DEFAULT NULL   COMMENT '등록시 IP Address',
			`write_date` datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '작성일',
			`order_idx`  int(11)             NOT NULL DEFAULT '0'    COMMENT '순서',
			`gno`        varchar(255)        NOT NULL DEFAULT '0'    COMMENT '답변해당 b_idx',
			`tgno`       int(11)             NOT NULL DEFAULT '0'    COMMENT '답변단계',

			`reg_id`   varchar(200)  NOT NULL DEFAULT 'system' COMMENT '등록자',
			`reg_date` datetime      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',
			`mod_id`   varchar(200)           DEFAULT NULL     COMMENT '수정자',
			`mod_date` datetime               DEFAULT NULL     COMMENT '수정일',
			`del_yn`   enum('Y','N') NOT NULL DEFAULT 'N'      COMMENT '삭제여부',
			`del_ip`   varchar(20)            DEFAULT NULL     COMMENT '삭제IP',
			`del_id`   varchar(200)           DEFAULT NULL     COMMENT '삭제자',
			`del_date` datetime               DEFAULT NULL     COMMENT '삭제일',

			PRIMARY KEY (`b_idx`),
			KEY `comp_idx` (`del_yn`,`comp_idx`),
			KEY `bs_idx`   (`del_yn`,`comp_idx`,`bs_idx`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[comment_str]';"
		,
		"pro_board" => "CREATE TABLE IF NOT EXISTS `[table_name]` (
			`b_idx`      int(11)             NOT NULL AUTO_INCREMENT,
			`comp_idx`   int(11)             NOT NULL DEFAULT '0'    COMMENT 'company_info(업체정보)',
			`part_idx`   int(11)             NOT NULL DEFAULT '0'    COMMENT 'company_part(지사정보)',
			`ci_idx`     int(11)             NOT NULL DEFAULT '0'    COMMENT 'client_info(거래처정보)',
			`bs_idx`     int(11)             NOT NULL DEFAULT '0'    COMMENT 'pro_board_set(게시판설정)',
			`bc_idx`     int(11)             NOT NULL DEFAULT '0'    COMMENT 'pro_board_category(말머리)',
			`mem_idx`    int(11)             NOT NULL DEFAULT '0'    COMMENT 'member_info(회원)',
			`writer`     varchar(100)        NOT NULL DEFAULT ''     COMMENT '작성자',
			`subject`    varchar(255)        NOT NULL DEFAULT ''     COMMENT '제목',
			`remark`     longtext            NOT NULL DEFAULT ''     COMMENT '내용',
			`pwd`        varchar(255)        NOT NULL DEFAULT ''     COMMENT '비밀번호',
			`views`      int(11)             NOT NULL DEFAULT '0'    COMMENT '조회수',
			`secret_yn`  enum('Y','N')       NOT NULL DEFAULT 'N'    COMMENT '비밀글여부',
			`ip_addr`    varchar(20)                  DEFAULT NULL   COMMENT '등록시 IP Address',
			`write_date` datetime            NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '작성일',
			`order_idx`  int(11)             NOT NULL DEFAULT '0'    COMMENT '순서',
			`gno`        varchar(255)        NOT NULL DEFAULT '0'    COMMENT '답변해당 b_idx',
			`tgno`       int(11)             NOT NULL DEFAULT '0'    COMMENT '답변단계',

			`reg_id`   varchar(200)  NOT NULL DEFAULT 'system' COMMENT '등록자',
			`reg_date` datetime      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '등록일',
			`mod_id`   varchar(200)           DEFAULT NULL     COMMENT '수정자',
			`mod_date` datetime               DEFAULT NULL     COMMENT '수정일',
			`del_yn`   enum('Y','N') NOT NULL DEFAULT 'N'      COMMENT '삭제여부',
			`del_ip`   varchar(20)            DEFAULT NULL     COMMENT '삭제IP',
			`del_id`   varchar(200)           DEFAULT NULL     COMMENT '삭제자',
			`del_date` datetime               DEFAULT NULL     COMMENT '삭제일',

			PRIMARY KEY (`b_idx`),
			KEY `comp_idx` (`del_yn`,`comp_idx`),
			KEY `ci_idx`   (`del_yn`,`comp_idx`,`ci_idx`),
			KEY `bs_idx`   (`del_yn`,`comp_idx`,`bs_idx`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='[comment_str]';"
	);

////////////////////////////////////////////
// 업무관련
// 공개여부
	$set_work_open = array(
		  "Y" => "공개"
		, "N" => "비공개"
	);
// 업무기한 덧붙이기
	$set_work_deadline_txt = array(
		  "WD01" => "가능한 빨리"
		, "WD02" => "오전까지"
		, "WD03" => "점심 전까지"
		, "WD04" => "오후까지"
		, "WD05" => "퇴근 전까지"
		, "WD06" => "-------------"
		, "select" => "직접입력하기"
	);
// 중요도
	$set_work_important = array(
		  "WI01" => "해당없음"
		, "WI02" => "상"
		, "WI03" => "중"
		, "WI04" => "하"
	);
// 업무상태
	$set_work_status = array(
		  "WS01"    => "대기"
		, "WS02"    => "진행"
		, "WS20"    => "승인대기"
		, "WS20_02" => "승인요청취소"
		, "WS20_70" => "승인요청반려"
		, "WS20_90" => "승인완료"
		, "WS30"    => "요청대기"
		, "WS30_02" => "완료요청취소"
		, "WS30_70" => "완료요청반려"
		, "WS30_90" => "요청완료"
		, "WS60"    => "취소"
		, "WS70"    => "반려"
		, "WS80"    => "보류"
		, "WS90"    => "완료"
		, "WS99"    => "종료"
	);
// 업무종류
	$set_work_type = array(
		  "WT01" => "본인"
		, "WT02" => "요청"
		, "WT03" => "승인"
		, "WT04" => "알림"
	);

////////////////////////////////////////////
// 계약관련
// 계약구분
	$set_contract_type = array(
		  "maintenance" => "유지보수"
		, "new" => "기타계약"
	);
////////////////////////////////////////////
// 푸시이력
// 전송상태
	$set_push_status = array(
		  "0" => "실패"
		, "1" => "성공"
	);
// 메세지구분
	$set_push_msg = array(
		  "message" => "쪽지"
		, "project" => "프로젝트"
		, "work"    => "업무"
		, "receipt" => "접수"
		, "sms"     => "단문자"
		, "consult" => "상담게시판"
		, "reg_ok"  => "업체신청"
	);
// 서비스구분
	$set_push_service = array(
		  "android" => "안드로이드폰"
		, "ios"     => "아이폰"
	);
// 푸쉬종류
	$set_sn_type = array(
		  "001" => "비즈스토리"
		, "002" => "우리사이"
	);

////////////////////////////////////////////
// 에이전트관련
// 중요도
	$set_agent_important = array(
		  "0" => "해당없음"
		, "1" => "상"
		, "2" => "중"
		, "3" => "하"
	);
// 버튼
	$set_agent_button = array(
		  "1" => "A/S접수내역"
		, "2" => "아이콘2"
		, "3" => "아이콘3"
		, "4" => "아이콘4"
	);
// 링크타입
	$set_agent_button_type = array(
		  "1" => "A/S접수"
		, "2" => "알림게시판"
		, "3" => "상담게시판"
		, "4" => "일반게시판"
		, "5" => "링크아이콘"
	);
// 링크타입 - 제공되는 주소
	$set_agent_button_url = array(
		  "1" => "receipt_request();"
		, "2" => "move_bnotice();"
		, "3" => "move_consult();"
		, "4" => "move_board();"
	);

////////////////////////////////////////////
// 접수관련
// 중요도
	$set_receipt_important = array(
		  "RI01" => "해당없음"
		, "RI02" => "상"
		, "RI03" => "중"
		, "RI04" => "하"
	);
// 접수상태
	$set_receipt_status = array(
		  "RS01"    => "접수등록"
		, "RS02"    => "접수승인"
		, "RS03"    => "작업진행"
		, "RS90"    => "완료처리"
		, "RS80"    => "보류처리"
		, "RS60"    => "취소처리"
	);

////////////////////////////////////////////
// 회사파일관련
	$set_comp_file = array(
		  "1" => array("logo","로고이미지")
		, "2" => array("stamp","도장이미지")
		, "3" => array("license","사업자등록증")
		, "4" => array("bankbook","통장사본")
		, "5" => array("certificate","인증서")
	);

////////////////////////////////////////////
// 상담게시판관련
// 중요도
	$set_consult_important = array(
		  "CI01" => "해당없음"
		, "CI02" => "상"
		, "CI03" => "중"
		, "CI04" => "하"
	);

////////////////////////////////////////////
// 알림게시판관련
// 중요도
	$set_bnotice_important = array(
		  "BNI01" => "해당없음"
		, "BNI02" => "상"
		, "BNI03" => "중"
		, "BNI04" => "하"
	);
// 거래처구분
	$set_client_type = array(
		  "1" => "거래처그룹"
		, "2" => "거래처전체"
		, "3" => "거래처개별"
	);

////////////////////////////////////////////
// 수출신고
// 수출자구분
	$set_export_section = array(
		  "A" => "A"
		, "B" => "B"
		, "C" => "C"
		, "D" => "D"
	);
// 신고구분
	$set_report_section = array(
		  "H" => "H"
		, "J" => "J"
		, "L" => "L"
		, "M" => "M"
		, "S" => "S"
		, "O" => "O"
	);
// 검사방법
	$set_test_how = array(
		  "A" => "수출신고시검사"
		, "B" => "적재전확인"
	);
// 물품상태
	$set_goods_state = array(
		  "N" => "신품"
		, "O" => "중고품"
	);
// 임시개청신청
	$set_openhouse = array(
		  "A" => "대상아님"
		, "B" => "대상임"
	);

////////////////////////////////////////////
// 회계
// 카드구분
	$set_card_gubun = array(
		  "1" => "신용카드"
		, "2" => "체크카드"
	);
// 사용구분
	$set_account_type = array(
		  "OUT" => "출금"
		, "IN"  => "입금"
	);
// 회계구분
	$set_account_gubun = array(
		  "cash" => "현금"
		, "card" => "카드"
		, "bank" => "계좌이체"
	);

////////////////////////////////////////////
// 프로젝트관련
// 공개여부
	$set_project_open = array(
		  "Y" => "공개"
		, "N" => "비공개"
	);
// 프로젝트상태
	$set_project_status = array(
		  "PS01" => "대기"
		, "PS02" => "진행"
		, "PS60" => "취소"
		, "PS70" => "반려"
		, "PS80" => "보류"
		, "PS90" => "완료"
	);

////////////////////////////////////////////
// 전문가코너
// 입력된 폼 셋팅
	$set_field_type = array(
		  "radio"    => "라디오버튼"
		, "checkbox" => "체크박스"
		, "select"   => "셀렉트박스"
	);
// 만족도
	$set_satisfy_code = array(
		  "1" => "상"
		, "2" => "중"
		, "3" => "하"
	);

////////////////////////////////////////////
// 파일센터
// 파일공간
	$set_filecenter_class = array(
		  "IN"  => "내부공간"
		, "OUT" => "외부공간"
	);

////////////////////////////////////////////
// 삭제해야할 데이타 테이블
	$delete_table = array(
		"1" => array("menu_company" , "지사별메뉴"),

		"2" => array("company_part_duty"   , "직책"),
		"3" => array("company_staff_group" , "직원그룹"),
		"4" => array("member_info"         , "회원"),
		"5" => array("member_file"         , "회원파일"),
		"6" => array("menu_auth_member"    , "회원별메뉴권한"),
		"7" => array("member_memo"         , "회원메모"),
		"8" => array("member_bookmark"     , "즐겨찾기"),
		"9" => array("message_sms"         , "단문자"),
		"10" => array("message_receive"    , "받은쪽지"),
		"11" => array("message_send"       , "보낸쪽지"),
		"12" => array("message_file"       , "쪽지파일"),

		"13" => array("company_client_group" , "거래처그룹"),
		"14" => array("client_code_info"     , "거래처코드"), // 값을 비운다. ci_idx = 0
		"15" => array("client_info"          , "거래처정보"),
		"16" => array("client_memo"          , "거래처메모"),
		"17" => array("client_memo_file"     , "거래처메모파일"),
		"18" => array("client_user"          , "거래처사용자"), // 거래처값으로 삭제를 한다.
		"19" => array("contract_info"        , "거래처계약정보"),
		"20" => array("expert_client_search" , "거래처검색조건"), // 전문가상담에 관련된 정보 거래처값으로 삭제를 한다.
		"21" => array("agenct_data"          , "에이전트에 들어온 정보"), // 거래처값으로 삭제를 한다.

		"22" => array("agent_button"        , "에어전트버튼"),
		"23" => array("agent_banner"        , "에이전트배너"),
		"24" => array("code_bnotice_class"  , "에이전트알림분류"),
		"25" => array("agent_bnotice"       , "에이전트알림"),
		"26" => array("agent_bnotice_file"  , "에이전트알림파일"),
		"27" => array("agent_bnotice_check" , "에이전트알림확인"),
		"28" => array("agent_notice"        , "에이전트공지"),

		"29" => array("code_receipt_class"     , "접수분류"),
		"30" => array("code_receipt_status"    , "접수상태"),
		"31" => array("receipt_info"           , "접수정보"),
		"32" => array("receipt_file"           , "접수파일"),
		"33" => array("receipt_info_detail"    , "접수정보상세"),
		"34" => array("receipt_comment"        , "접수코멘트"),
		"35" => array("receipt_comment_file"   , "접수코멘트파일"),
		"36" => array("receipt_end_file"       , "접수완료파일"),
		"37" => array("receipt_check"          , "접수확인"),
		"38" => array("receipt_status_history" , "접수이력"),

		"39" => array("code_report_class"     , "점검분류"),
		"40" => array("receipt_report"        , "점검정보"),
		"41" => array("receipt_report_detail" , "점검정보상세"),

		"42" => array("code_work_class"     , "업무분류"),
		"43" => array("code_work_status"    , "업무상태"),
		"44" => array("work_info"           , "업무정보"),
		"45" => array("work_file"           , "업무파일"),
		"46" => array("work_comment"        , "업무코멘트"),
		"47" => array("work_report"         , "업무보고"),
		"48" => array("work_report_file"    , "업무보고파일"),
		"49" => array("work_read"           , "업무읽기"),
		"50" => array("work_check"          , "업무확인"),
		"51" => array("work_status_history" , "업무이력"),

		"52" => array("code_project_status"    , "프로젝트상태"),
		"53" => array("project_info"           , "프로젝트정보"),
		"54" => array("project_file"           , "프러젝트정보파일"),
		"55" => array("project_class"          , "프로젝트분류"),
		"56" => array("project_status_history" , "프로젝트이력"),

		"57" => array("code_account_bank"  , "회계은행"),
		"58" => array("code_account_card"  , "회계카드"),
		"59" => array("code_account_class" , "회계계정"),
		"60" => array("code_account_gubun" , "회계구분"),
		"61" => array("account_info"       , "운영비"),

		"62" => array("bbs_setting"  , "게시판설정"), // part_add_idx : 있을 경우는 제외
		"63" => array("bbs_category" , "게시판말머리"),
		"64" => array("bbs_info"     , "게시판정보"),
		"65" => array("bbs_file"     , "게시판정보파일"),
		"66" => array("bbs_comment"  , "게시판정보코멘트"),
		"67" => array("bbs_link"     , "게시판정보링크"),
		"68" => array("bbs_notice"   , "게시판정보공지"),

		"69" => array("code_consult_class"     , "상담분류"),
		"70" => array("consult_info"           , "상담정보"),
		"71" => array("consult_file"           , "상담정보파일"),
		"72" => array("consult_comment"        , "상담코멘트"),
		"73" => array("consult_comment_file"   , "상담코멘트파일"),
		"74" => array("consult_status_history" , "상담이력"),
		"75" => array("consult_check"          , "상담확인"),

		"76" => array("filecenter_auth"           , "폴더권한"),
		"77" => array("filecenter_code_type"      , "폴더타입"),
		"78" => array("filecenter_code_type_auth" , "폴더타입권한"),
		"79" => array("filecenter_info"           , "파일정보"),
		"80" => array("filecenter_history"        , "파일이력"),

		"81" => array("code_sche_class" , "일정종류"),
		"82" => array("schedule_info"   , "일정정보"),

		"83" => array("code_dili_status"  , "근태상태"),
		"84" => array("diligence_set"     , "근태설정"),
		"85" => array("diligence_info"    , "근태정보"),
		"86" => array("diligence_comment" , "근태코멘트"),

		"87" => array("export_info"          , "수출신고"),
		"88" => array("agent_board_category" , "게시판말머리"),
		"89" => array("email_history"        , "이메일이력"),
		"91" => array("push_member"          , "푸쉬사용자")
	);



////////////////////////////////////////////
// 학교 관련 데이타
// 

// 교육청 코드 2024.03.20 김소령
	$set_sc_code = array (
		"B10" => "서울특별시교육청",
		"C10" => "부산광역시교육청",
		"D10" => "대구광역시교육청",
		"E10" => "인천광역시교육청",
		"F10" => "광주광역시교육청",
		"G10" => "대전광역시교육청",
		"H10" => "울산광역시교육청",
		"I10" => "세종특별자치시교육청",
		"J10" => "경기도교육청",
		"K10" => "강원특별자치도교육청",
		"M10" => "충청북도교육청",
		"N10" => "충청남도교육청",
		"P10" => "전라북도교육청",
		"Q10" => "전라남도교육청",
		"R10" => "경상북도교육청",
		"S10" => "경상남도교육청",
		"T10" => "제주특별자치도교육청"
	);

// 학교 분류 2024.03.20 김소령
	$set_schul_knd_sc_nm = array (
		"초등학교", "중학교", "고등학교", "고등기술학교", "고등공민학교", "공동실습소",
		"국제학교", "방송통신중학교", "방송통신고등학교", "외국인학교", "특수학교",
		"각종학교(초)", "각종학교(중)", "각종학교(고)", "각종학교(대안학교)",
		"평생학교(초)-3년6학기", "평생학교(초)-4년12학기", "평생학교(중)-2년6학기", "평생학교(중)-3년6학기", "평생학교(고)-2년6학기", "평생학교(고)-3년6학기"
	);
?>