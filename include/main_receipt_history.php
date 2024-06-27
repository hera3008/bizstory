<?
/*
	생성 : 2012.10.25
	위치 : 메인화면 > 접수이력
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn = $comp_set_data['part_yn'];

	$receipt_where = " and rsh.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $receipt_where .= " and rsh.part_idx = '" . $code_part . "'";
	$receipt_where .= " and ri.del_yn = 'N' and rsh.mem_idx > 0";
	$receipt_order = "rsh.reg_date desc";
	$receipt_list = receipt_status_history_data('list', $receipt_where, $receipt_order, 1, 20);
?>
	<ul>
<?
	if ($receipt_list['total_num'] > 0)
	{
		foreach ($receipt_list as $receipt_k => $receipt_data)
		{
			if (is_array($receipt_data))
			{
			// 담당자 이미지
				$mem_img = member_img_view($receipt_data['mem_idx'], $comp_member_dir);

				$list_data = receipt_list_data($receipt_data['ri_idx'], $receipt_data);
?>
		<li class="line">
			<ul class="li_l">
				<li class="li_subject">[<strong><?=$list_data['client_name'];?></strong>]</li>
				<li class="li_subject">
					<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'"><strong><?=$receipt_data['subject'];?></strong></a>
					<?=$list_data['important_img'];?>
				</li>
				<li class="li_memo">
					<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'"><?=$receipt_data['status_memo'];?></a>
				</li>
			</ul>
			<ul class="li_r">
				<li class="li_date">[<?=date_replace($receipt_data['reg_date'], 'Y-m-d H:i');?>]</li>
				<li class="li_mem"><span><?=$mem_img['img_26'];?></span></li>
			</ul>
		</li>
<?
			}
		}
	}
	else
	{
?>
		<li style="height:200px; text-align:center; padding-top:120px;">등록된 내용이 없습니다.</li>
<?
	}
    
    db_close();
?>
	</ul>