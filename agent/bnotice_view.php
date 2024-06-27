<?
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

	$abn_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
		<input type="hidden" name="part_idx"    value="' . $client_part . '" />
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
		<input type="hidden" name="client_code" value="' . $client_code . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$where = " and abn.abn_idx = '" . $abn_idx . "'";
	$chk_data = agent_bnotice_data('view', $where);

	$data = agent_bnotice_list_data($chk_data, $chk_data['abn_idx']);

// 읽음으로 표시 - 자기것은 제외
	$cc_data['chk_ci']  = $client_idx;
	$cc_data['chk_ccg'] = $client_ccg_idx;
	$cc_data['chk_mac'] = $macaddress;
	$cc_data['abn_idx'] = $data['abn_idx'];
	agent_bnotice_data_read($cc_data);

// 파일목록
	$file_where = " and abnf.abn_idx = '" . $abn_idx . "'";
	$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');
?>

<div class="ajax_write">
	<div class="ajax_frame">

		<fieldset>
			<legend class="blind">알림 상세보기</legend>
			<table class="tinytable view" summary="알림 상세하게 봅니다.">
			<caption>알림</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>분류</th>
					<td>
						<div class="left">
				<?
					$bnotice_class = $data['class_str']['code_name'];
					if (is_array($bnotice_class))
					{
						foreach ($bnotice_class as $k => $v)
						{
							if ($k == 1) echo $v;
							else echo ' &gt; ', $v;
						}
					}
				?>
						</div>
					</td>
				</tr>
				<tr>
					<th>등록일</th>
					<td><div class="left"><?=$data['reg_date'];?></div></td>
				</tr>
				<tr>
					<th>제목</th>
					<td>
						<div class="left">
							<strong><?=$data['subject'];?></strong>
							<?=$important_span;?>
						</div>
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td>
						<div class="left">
							<p class="memo">
								<?=$data['remark'];?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>파일</th>
					<td>
						<div class="left file">
				<?
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

								$btn_str = preview_file($bnotice_dir, $file_data['abnf_idx'], 'bnotice');
				?>
								<li>
									<?=$btn_str;?>
									<a href="<?=$local_dir;?>/agent/bnotice_download.php?abnf_idx=<?=$file_data['abnf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								</li>
				<?
							}
						}
						$btn_img = preview_images($abn_idx, 'bnotice');
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
		</fieldset>

		<div class="section">
			<div class="fr">
				<span class="btn_big_violet"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
