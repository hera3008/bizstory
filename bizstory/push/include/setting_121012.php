<?php
	/* 경로설정 */
	$LocalDir="./";
	$DataDir="/bizstory/data/";

	if ($site_url == 'new.bizstory.co.kr')
	{
		define(db_host, 'localhost');    //접속 호스트
		define(db_name, 'bizstory_004'); //접속 DB
		define(db_user, 'root');         //접속 아이디
		define(db_pass, 'uBpass4862');   //접속 비밀번호

		$test_site_name = "테스트사이트접속중";
	}
	else
	{
		define(db_host, 'localhost');  //접속 호스트
		define(db_name, 'bizstory');   //접속 DB
		define(db_user, 'root');       //접속 아이디
		define(db_pass, 'uBpass4862'); //접속 비밀번호
	}


	?>
