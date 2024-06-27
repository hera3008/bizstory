<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";

	$ci_where = " and ci.ci_idx = '" . $idx_client . "'";
	$ci_data = client_info_data('view', $ci_where);
	$charge_idx = $ci_data['mem_idx'];

	$mem_where = " and mem.mem_idx = '" . $charge_idx . "'";
	$mem_data = member_info_data('view', $mem_where);

	$mf_where = " and mf.comp_idx = '" . $idx_comp . "' and mf.mem_idx = '" . $charge_idx . "' and mf.sort = 1";
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
	<!-- 담당자보기 -->
	<div class="charge_wrap">
		<strong class="title">담당자보기</strong>
		<div class="charge">
			<?=$mem_image;?>
			<ul class="charge_info">
				<li class="info_name"><span>이름</span> : <?=$mem_data['mem_name'];?> <?=$mem_data['duty_name'];?></li>
				<li class="info_email"><span>E-mail</span> : <?=$mem_data['mem_email'];?></li>
				<li class="info_tel"><span>연락처</span> : <strong><?=$mem_data['hp_num'];?></strong></li>
			</ul>
		</div>
		<div class="career">
			<strong>이력사항</strong>
			<div class="career_textarea content1">
				<?=nl2br($mem_data['remark2']);?>
			</div>
		</div>
	</div>
	<!-- //담당자보기 -->

<script type="text/javascript">
//<![CDATA[
	$(".content1").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
//]]>
</script>