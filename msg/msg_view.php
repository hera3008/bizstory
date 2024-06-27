<?
/*
	수정 : 2013.05.14
	위치 : 업무관리 > 나의 업무 > 쪽지 - 쪽지보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		if ($post_value == 'receive')
		{
			$where = " and mr.mr_idx = '" . $idx . "'";
			$data = message_receive_data('view', $where);
		}
		else
		{
			$where = " and ms.ms_idx = '" . $idx . "'";
			$data = message_send_data('view', $where);
		}

	// 읽기체크
		if ($post_value == 'receive' && ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00"))
		{
			$query_str = "
				update message_receive set
					read_date = now()
				where del_yn = 'N' and mr_idx = '" . $idx . "'
			";
			db_query($query_str);
			query_history($query_str, 'message_receive', 'update');

			$data['read_date'] = date('Y-m-d H:i:s');
?>
			<script type="text/javascript">
			//<![CDATA[
				$.ajax({
					type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/msg/msg_receive_read.php',
					data: {"mr_idx":"<?=$idx;?>", "mem_idx":"<?=$data['reg_id'];?>"},
					success: function(msg) {
						if (msg.success_chk == "Y")
						{
							$('#msg_top_num').html('<em>' + msg.total_note + '</em>');
							$('#msg_mem_<?=$data['reg_id'];?>').html('<em>' + msg.mem_note + '</em>');
							$('#msg_view_a_<?=$idx2;?>').removeClass('no_read');
						}
						else check_auth_popup(msg.error_string);
					}
				});
			//]]>
			</script>
<?
		}
?>
	<div style="padding-top:10px; padding-left:25px;">
		<table class="tinytable view" summary="쪽지 내용을 보여줍니다.">
		<caption>쪽지 내용</caption>
		<colgroup>
			<col width="80px" />
			<col />
		</colgroup>
		<tbody>
	<?
		if ($post_value == 'receive')
		{
	?>
			<tr>
				<th>보낸사람</th>
				<td><div class="left"><?=$data['send_mem_name'];?>(<?=$data['send_mem_id'];?>)</div></td>
			</tr>
			<tr>
				<th>보낸일</th>
				<td><div class="left"><?=date_replace($data['reg_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
			<tr>
				<th>읽은일</th>
				<td colspan="3"><div class="left"><?=date_replace($data['read_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
	<?
		}
		else
		{
	?>
			<tr>
				<th>보낸일</th>
				<td colspan="3"><div class="left"><?=date_replace($data['reg_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
			<tr>
				<th>받은사람</th>
				<td colspan="3">
					<div class="left">
	<?
		$receive_where = " and mr.ms_idx = '" . $idx . "'";
		$receive_list = message_receive_data('list', $receive_where, 'mr.mem_name asc', '', '', 2);
		foreach ($receive_list as $receive_k => $receive_data)
		{
			if (is_array($receive_data))
			{
				echo $receive_data['mem_name'], '(', $receive_data['mem_id'], ')', ' - ', date_replace($receive_data['read_date'], 'Y-m-d H:i:s'), '<br />';
			}
		}
	?>
					</div>
				</td>
			</tr>
	<?
		}
	?>
			<tr>
				<th class="memo_remark">내용</th>
				<td colspan="3"><div class="left"><p class="memo"><?=$data['remark'];?></p></div></td>
			</tr>
			<tr>
				<th>첨부파일</th>
				<td colspan="3">
					<div class="left file">
			<?
				$file_where = " and msgf.ms_idx = '" . $data['ms_idx'] . "'";
				$file_list = message_file_data('list', $file_where, '', '', '');

				if ($file_list['total_num'] > 0) {
			?>
						<ul>
			<?
					foreach ($file_list as $file_k => $file_data)
					{
						if (is_array($file_data))
						{
							$fsize = $file_data['img_size'];
							$fsize = byte_replace($fsize);

							$btn_str = preview_file($comp_msg_dir, $file_data['msgf_idx'], 'message');
			?>
							<li>
								<?=$btn_str;?>
								<a href="<?=$local_dir;?>/bizstory/msg/msg_download.php?msgf_idx=<?=$file_data['msgf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
							</li>
			<?
						}
					}
					$btn_img = preview_images($data['ms_idx'], 'message');
					if ($btn_img != '')
					{
						echo '
							<li>' . $btn_img . '</li>
						';
					}
			?>
						</ul>
			<?
				}
			?>
					</div>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
				<span class="btn_big_red"><input type="button" value="삭제" onclick="check_msg_delete('<?=$post_value;?>', '<?=$idx;?>', '<?=$list_mem_idx;?>')" /></span>
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupview_close('<?=$idx2;?>')" /></span>
			</div>
		</div>
	</div>
<?
	}
?>
