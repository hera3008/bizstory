<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mr_idx    = $idx;

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and mr.mr_idx = '" . $mr_idx . "'";
		$data = message_receive_data('view', $where);

		// 읽기체크
		if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
		{
			$query_str = "
				update message_receive set
					read_date = now()
				where del_yn = 'N' and mr_idx = '" . $mr_idx . "'
			";
			db_query($query_str);
			query_history($query_str, 'message_receive', 'update');

			$data['read_date'] = date('Y-m-d H:i:s');
?>
			<script type="text/javascript">
			//<![CDATA[
				$.ajax({
					type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/work/msg_receive_read.php',
					data: {"mr_idx":"mr_idx"},
					beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
					success: function(msg) {
						if (msg.success_chk == "Y")
						{
							list_data();
							$('#note_total_num').html(msg.total_note);
						}
						else check_auth_popup(msg.error_string);
					}
				});
			//]]>
			</script>
<?
		}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<table class="tinytable view" summary="받은쪽지 내용을 보여줍니다.">
		<caption>받은쪽지</caption>
		<colgroup>
			<col width="100px" />
			<col />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th>보낸사람</th>
				<td><div class="left"><?=$data['send_mem_name'];?>(<?=$data['send_mem_id'];?>)</div></td>
				<th>보낸일</th>
				<td><div class="left"><?=date_replace($data['reg_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
			<tr>
				<th>읽은일</th>
				<td colspan="3"><div class="left"><?=date_replace($data['read_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
			<tr>
				<th>내용</th>
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
								<a href="<?=$local_dir;?>/bizstory/work/msg_download.php?msgf_idx=<?=$file_data['msgf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
				<span class="btn_big fl"><input type="button" value="보관하기" onclick="message_store('<?=$mr_idx;?>')" /></span>
				<span class="btn_big fl"><input type="button" value="삭제" onclick="check_receive_delete('<?=$mr_idx;?>')" /></span>
				<span class="btn_big fl"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<?
	}
?>
