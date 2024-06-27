<?php
/*
	생성 : 2012.05.24
	위치 : 안드로이드 - 푸시

	사용법
	1. class.c2dm.php 를 include 한다.
	2. C2DM 클래스로 객체를 만든다. 이때 전달하는 값은 로그파일명이 된다.
	3. push_send() 함수를 호출한다.
	   $sender      : 전송구분자 (bizstorycokr@gmail.com)
	   $comp_idx    :
	   $part_idx    :
	   $mem_idx		: 수신자 idx
	   $receiver    : 수신자 ID, push_member 에 등록된 사용자 검색에 사용된다.
	                  push_member 에서 찾으면, $comp_idx, $part_idx, $mem_idx 를
					  push_member 에 등록된 값으로 설정한다.
	   $msg_type    : 전송 알림메시지 구분, 수신자가 받겠다고 설정한 알림종류만 전송된다.
	                  message - 쪽지알림
					  receipt - 접수알림
					  work    - 업무알림
					  notice  - 공지알림
	   $message		: 전송메시지, 최대 길이는 1024 바이트 (메시지는 최대한 짧게 만든다.)

	   결과값       : 1-전송성공이고, 0-전송실패
*/

require_once "class.c2dm.php";

/*
$receiver = "ipos21@naver.com";
$msg_type = "message";
$message = "apns 전송";
*/

$c2dm = new C2DM("apple_push");
$result = $c2dm->push_send($sender, $comp_idx, $part_idx, $mem_idx, $receiver, $msg_type, $message);

echo $result;

?>
