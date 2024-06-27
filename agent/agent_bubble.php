<?
	//require_once "./include/class.FileLog.php";

	//$log_file = "log/_trace_agent_bubble.".date("Ymd").".log";
	//$log = new FileLog("agent_bubble");
	//$log->log_file = $log_file;
	//$log->logging = true;

	//$log->blank_line();
	//$log->log("** ".date("Ymd-His"));
	//$log->log("client_code=".$_REQUEST['client_code'].", macaddress=".$_REQUEST['macaddress']);

	include "../bizstory/common/setting.php";
	include $local_path . "/agent/include/agent_chk.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BizStory Bubble</title>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/agent/css/agent2.css" media="all" />
<style>
	body{margin:0px;padding:0px;}
</style>
</head>

<body>
<div id="bubble_wrap">
	<div class="bubble_box">
		<div class="client_str">
			<?=$client_name;?>
		</div>
		<div class="receipt_str">
			요청하신 접수가
<?
	$history_where = "
		and ri.ci_idx = '" . $client_idx . "' and ri.macaddress = '" . $macaddress . "'
		and rsh.read_yn = 'N'
		and (code.code_value = '2' or code.code_value = '99')";
	//$history_list = receipt_status_history_data('list', $history_where, 'rsh.reg_date desc', '', '');

	//$log->log("total_num=".$history_list['total_num']);

	//$code_value = $history_list[0]['code_value'];
	$code_value = 0;

	if ($code_value == '2') // 접수승인
	{
		echo '<strong>[승인]</strong>';
	}
	else if ($code_value == '99') // 완료
	{
		echo '<strong>[완료]</strong>';
	}
	else
	{
		echo '';
	}
?>
			되었습니다.
		</div>
	</div>
</div>
</body>
</html>