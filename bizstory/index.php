<?
	include "bizstory/common/setting.php";
	include $local_path . "/bizstory/common/member_chk.php";
	include $local_path . "/include/top.php";

	if ($fmode == '' || $smode == '')
	{
		if ($_SESSION[$sess_str . '_ubstory_level'] == '1')
		{
			include $local_path . "/include/main_highest.php";
		}
		else
		{
			if ($company_set_data['main_type'] == 'A')
			{
				include $local_path . "/include/main.php";
			}
			else
			{
				if (file_exists($local_path . "/include/main_" . $company_set_data['main_type'] . ".php") == true)
				{
					include $local_path . "/include/main_" . $company_set_data['main_type'] . ".php";
				}
				else
				{
					include $local_path . "/include/main.php";
				}
			}
		}
?>
<script type="text/javascript">
//<![CDATA[
// SlideShow
	$('#ad_banner').cycle({
		fx: 'scrollUp', speed: 500, timeout:3000,
		pager: '#ad_counter'
	});
//]]>
</script>
<?
	}
	else
	{
?>
	<div class="ui-widget" id="popup_notice_view" style="display:none">
		<div class="ui-state-highlight ui-corner-all">
			<p>
				<span class="ui-icon ui-icon-info"></span>
				<span id="popup_notice_memo">
					<strong>주의</strong> 주의사항 입력
				</span>
			</p>
		</div>
	</div>

	<div id="popup_result_msg" title="처리결과"></div>
<?
		$link_file = $local_path . '/bizstory/' . $fmode . '/' . $smode . '.php';

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
	include $local_path . "/include/tail.php";
?>