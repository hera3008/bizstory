<?
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";

	$alarma  = $_POST['alarma'];
	$message = $_POST['message'];
	$receipt = $_POST['receipt'];
	$work    = $_POST['work'];
	$consult = $_POST['consult'];
	$sms     = $_POST['sms'];
	$notice  = $_POST['notice'];

	$command    = "update"; //명령어
	$table      = "push_member"; //테이블명
	$conditions = "comp_idx = '" . $code_comp . "' and mem_idx = '" . $code_mem . "'"; //조건

	if ($alarma  == '') $alarma  = 'N';
	if ($message == '') $message = 'N';
	if ($receipt == '') $receipt = 'N';
	if ($work    == '') $work    = 'N';
	if ($consult == '') $consult = 'N';
	if ($sms     == '') $sms     = 'N';
	if ($notice  == '') $notice  = 'N';

	$param['push_enable']  = $alarma;
	$param['push_message'] = $message;
	$param['push_receipt'] = $receipt;
	$param['push_work']    = $work;
	$param['push_consult'] = $consult;
	$param['push_sms']     = $sms;
	$param['push_notice']  = $notice;

	$param['mod_id']   = $code_mem;
	$param['mod_date'] = date("Y-m-d H:i:s");

	$query_str = make_sql($param, $command, $table, $conditions);
	db_query($query_str);
	query_history($query_str, $table, $command);

	$str = '{"success_chk" : "Y", "error_string":""}';
	echo $str;
	exit;
?>