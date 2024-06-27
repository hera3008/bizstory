<?
/*
	생성 : 2012.07.23
	수정 : 2013.04.24
	위치 : 직원정보
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$chk_idx_arr = explode('_', $chk_idx);
	$mem_idx     = $chk_idx_arr[1];

	$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
	$mem_data = member_info_data('view', $mem_where);

	$mem_img = member_img_view($mem_idx, $comp_member_dir);

	$charge_name = '<strong style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</strong> : ' . $mem_data['group_name'];
?>
<div class="layerProfile layerSet">
	<div class="profile_top">
		<a class="btnClose" href="javascript:void(0)" onclick="show_staff('<?=$chk_idx;?>');"title="닫기"></a>
		<p class="name"><?=$mem_data['mem_name'];?><span><?=$mem_data['duty_name'];?></span></p>
	</div>
	<div class="data">
		<?=$mem_img['img_53'];?>
		<ul class="option">
			<li><a class="memo"    href="javascript:void(0)" onclick="popup_msg('<?=$mem_idx;?>')"><span>쪽지</span></a></li>
			<li><a class="request" href="javascript:void(0)"><span>요청</span></a></li>
			<li><a class="sms"     href="javascript:void(0)" onclick="popup_sms('<?=$mem_idx;?>')"><span>SMS</span></a></li>
			<li><a class="email"   href="javascript:void(0)"><span>E-Mail</span></a></li>
		</ul>
		<ul class="profile">
			<li><?=$charge_name;?></li>
			<li><a class="mail" href="mailto:<?=$mem_data['mem_email'];?>"><?=$mem_data['mem_email'];?></a></li>
			<li class="mobile"><span><?=$mem_data['hp_num'];?></span></li>
			<li class="access"><span>최종접속 : <?=$mem_data['last_date'];?></span></li>
		</ul>
	</div>
	<span class="arrow" style="left: 90%;"></span>
</div>
