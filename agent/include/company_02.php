<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";

	$mem_where = " and mem.comp_idx = '" . $idx_comp . "' and mem.part_idx = '" . $idx_part . "' and mem.login_yn = 'Y'";
	$mem_order = "cpd.sort asc, mem.mem_name asc";
	$mem_list = member_info_data('list', $mem_where, $mem_order, '', '');
?>
	<!-- 직원보기 -->
	<div class="staff content1">
<?
	foreach ($mem_list as $mem_k => $mem_data)
	{
		if (is_array($mem_data))
		{
			$mf_where = " and mf.mem_idx = '" . $mem_data['mem_idx'] . "' and mf.sort = 1";
			$mf_data  = member_file_data('view', $mf_where);

			if ($mf_data['img_sname'] != '')
			{
				$mem_image = '<img class="photo" src="' . $staff_dir . '/' . $mf_data['mem_idx'] . '/' . $mf_data['img_sname'] . '" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
			}
			else
			{
				$mem_image = '<img class="photo" src="' . $local_dir . '/bizstory/images/tfuse-top-panel/no_member.jpg" alt="' . $mem_data['mem_name'] . '" width="80px" height="80px" />';
			}
?>
		<div class="staff_area">
			<?=$mem_image;?>
			<ul class="staff_info">
				<li class="info_name"><span>이름</span> : <?=$mem_data['mem_name'];?> <?=$mem_data['duty_name'];?></li>
				<li class="info_email"><span>E-mail</span> : <?=$mem_data['mem_email'];?></li>
				<li class="info_tel"><span>연락처</span> : <strong><?=$mem_data['hp_num'];?></strong></li>
			</ul>
		</div>
<?
		}
	}
?>
	</div>
	<!-- //직원보기 -->

<script type="text/javascript">
//<![CDATA[
	$(".content1").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
//]]>
</script>