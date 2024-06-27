<?
/*
	수정 : 2012.08.27
	위치 : 고객관리 > 거래처목록 - 보기 - 메모목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$where = " and cim.ci_idx = '" . $ci_idx . "'";
	$list = client_memo_data('list', $where, '', $m_page_num, $m_page_size);

	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 등록자 이미지
			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir);
?>
<div class="comment" id="memo_list_<?=$data['cim_idx'];?>">
	<div class="comment_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user"><a class="name_ui" id="camp_member_243560"><?=$data['mem_name'];?></a></span>
		<span class="date">
<?
			$chk_date = date_replace($data['reg_date'], 'Y-m-d');
			if ($chk_date == date('Y-m-d'))
			{
				echo '<strong>', $data['reg_date'] , '</strong>';
			}
			else
			{
				echo $data['reg_date'];
			}
?>
		</span>
<?
	if ($data['mem_idx'] == $code_mem || $code_level <= '11') {
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="memo_modify_form('open', '<?=$data['cim_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="memo_delete('<?=$data['cim_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div class="comment_wrap" id="memo_view_<?=$data['cim_idx'];?>">
		<div class="comment_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and cimf.cim_idx = '" . $data['cim_idx'] . "'";
	$file_list = client_memo_file_data('list', $file_where, '', '', '');

	if ($file_list['total_num'] > 0)
	{
?>
				<ul>
<?
		foreach ($file_list as $file_k => $file_data)
		{
			if (is_array($file_data))
			{
				$fsize = $file_data['img_size'];
				$fsize = byte_replace($fsize);

				$btn_str = preview_file($comp_client_dir, $file_data['cimf_idx'], 'client_memo');
?>
					<li>
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/bizstory/work/client_view_memo_download.php?cimf_idx=<?=$file_data['cimf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
					</li>
<?
			}
		}
?>
				</ul>
<?
		$btn_img = preview_images($data['cim_idx'], 'client_memo');
		if ($btn_img != '')
		{
			echo '
				<div>' . $btn_img . '</div>
			';
		}
	}
?>
			</div>
		</div>
	</div>
</div>
<div id="memo_modify_<?=$data['cim_idx'];?>" title="댓글수정"></div>
<?
		}
	}
?>
<input type="hidden" id="memo_new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div class="tablefooter_m">
	<?=page_view_comment($m_page_size, $m_page_num, $list['total_page'], 'memo');?>
</div>
<div class ="clear mb01"></div>
<hr />
