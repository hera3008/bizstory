<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ms_idx    = $idx;

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
		$where = " and ms.ms_idx = '" . $ms_idx . "'";
		$data = message_send_data('view', $where);

		$receive_where = " and mr.ms_idx = '" . $ms_idx . "'";
		$receive_list = message_receive_data('list', $receive_where, 'mr.mem_name asc', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<table class="tinytable view" summary="보낸쪽지 내용을 보여줍니다.">
		<caption>보낸쪽지</caption>
		<colgroup>
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th>보낸일</th>
				<td><div class="left"><?=date_replace($data['reg_date'], 'Y-m-d H:i:s');?></div></td>
			</tr>
			<tr>
				<th>내용</th>
				<td><div class="left"><p class="memo"><?=$data['remark'];?></p></div></td>
			</tr>
			<tr>
				<th>받은사람</th>
				<td>
					<div class="left">
	<?
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
			<tr>
				<th>첨부파일</th>
				<td colspan="3">
					<div class="left file">
			<?
				$file_where = " and msgf.ms_idx = '" . $ms_idx . "'";
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
					$btn_img = preview_images($ms_idx, 'message');
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
				<span class="btn_big fl"><input type="button" value="삭제" onclick="check_send_delete('<?=$ms_idx;?>')" /></span>
				<span class="btn_big fl"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<?
	}
?>
