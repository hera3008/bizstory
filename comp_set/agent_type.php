<?
	require_once "../common/setting.php";
	require_once "../common/member_chk.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

	$where = " and part.part_idx = '" . $part_idx . "'";
	$data = company_part_data("view", $where);

	$part_agent_type = $data['agent_type'];
	$agent_type_arr  = explode(',', $part_agent_type);

// 지사에 해당되는 agent를 보여준다.
	$i = 0;
?>
		<p>에이젼트 타입</p>
		<div class="agent_type">
<?
	foreach ($agent_type_arr as $k => $v)
	{
		if ($agent_type == '')
		{
			if ($k == 0)
			{
				$agent_type = $v;
			}
		}

		if ($agent_type == $v) $class_str = ' class="select"';
		else $class_str = '';
?>
			<a href="javascript:void(0);" id="agent_type_<?=$v;?>" onclick="agent_type('<?=$part_idx;?>', '<?=$v;?>')"<?=$class_str;?>>[<?=$v;?>] 타입</a>
<?
		if ($i < count($agent_type_arr)-1) echo '<span>|</span>';
		$i++;
	}
?>
		</div>

	<input type="hidden" id="select_agent_type" name="agent_type" value="<?=$agent_type;?>" />