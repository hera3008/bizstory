<?
	include "../bizstory/common/setting.php";
	include $local_path . "/sole/common/member_chk.php";

	if ($fmode == '') $fmode = 'company';
	if ($smode == '') $smode = 'company';
	if ($fmode == 'company')
	{
		$first_navi_name = '총판관리';
		if ($smode == 'company')
		{
			$navi_name = '업체목록';
		}
	}
	include $local_path . "/sole/include/top.php";

	if ($fmode == '' || $smode == '')
	{ }
	else
	{
?>
	<div id="popup_result_msg" title="처리결과"></div>
<?
		$link_file = $local_path . '/sole/' . $fmode . '/' . $smode . '.php';

		if (is_file($link_file)) include $link_file;
		else echo "파일이 없습니다.";
?>
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
<?
	}
	include $local_path . "/sole/include/tail.php";
?>