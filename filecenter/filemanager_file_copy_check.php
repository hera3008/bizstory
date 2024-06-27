<?
/*
	생성 : 2013.04.03
	수정 : 2013.04.03
	위치 : 파일센터 > 파일관리 - 파일 복사, 이동 - 중복확인
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$chk_fi_idx = $_POST['chk_fi_idx'];

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($sub_type == 'file_copy')
	{
		$file_title  = "복사";
	}
	else
	{
		$file_title  = "이동";
	}

// 확인할 파일위치
	$chk_where = " and fi.fi_idx = '" . $up_idx . "'";
	$chk_data = filecenter_info_data('view', $chk_where);
	
	$dir_auth  = filecenter_folder_auth($up_idx); // 권한확인
	$dir_write_auth = $dir_auth['dir_write_auth'];

	if ($chk_data['dir_depth'] == '1') $up_fi_idx = $up_idx;
	else $up_fi_idx = $chk_data['up_fi_idx'] . ',' .  $up_idx;

	$chk_num = 1;
	if (is_array($chk_fi_idx))
	{
		foreach ($chk_fi_idx as $k => $v)
		{
			$where = " and fi.fi_idx = '" . $v . "'";
			$data = filecenter_info_data('view', $where);

			$file_name = $data['file_name'];
			$file_size = $data['file_size'];

			//업로드 권한이 있을 경우
			if ($dir_write_auth == 'Y') {
				$chk_where = " and fi.up_fi_idx = '" . $up_fi_idx . "' and fi.file_name = '" . $file_name . "'";
				$chk_page = filecenter_info_data('page', $chk_where);
				if ($chk_page['total_num'] == 0)
				{
					$chk_type = '2';
				}
				else // 중복일 경우
				{
					$chk_type = '1';
				}
			}
			else {
				//업로드 권한이 없는 경우
				$chk_type = '3';
			}
			$chk_data_file[$chk_type][$chk_num]['fi_idx']    = $v;
			$chk_data_file[$chk_type][$chk_num]['file_name'] = $file_name;
			$chk_data_file[$chk_type][$chk_num]['file_size'] = $file_size;
			$chk_num++;
		}
		ksort($chk_data_file);
	}
?>
	<fieldset>
		<legend class="blind">파일 <?=$file_title;?> 폼</legend>

		<table class="tinytable write" summary="<?=$file_title;?>할 파일목록입니다.">
		<caption><?=$file_title;?>할 파일목록</caption>
		<colgroup>
			<col width="30px" />
			<col />
			<col width="250px" />
		</colgroup>
		<thead>
			<tr>
				<th class="nosort"><input type="checkbox" name="copyfiidx" onclick="check_all('copyfiidx', this);" /></th>
				<th class="nosort"><h3>파일명(파일크기)</h3></th>
				<th class="nosort"><h3>비고</h3></th>
			</tr>
		</thead>
		<tbody>
<?
	$chk_num = 1;
	if (is_array($chk_data_file['2']))
	{
		foreach ($chk_data_file['2'] as $k => $v)
		{
			$fi_idx    = $v['fi_idx'];
			$file_name = $v['file_name'];
			$file_size = $v['file_size'];
			$file_size = byte_replace($file_size);
?>
			<tr>
				<td><input type="checkbox" id="copyfiidx_<?=$chk_num;?>" name="chk_fi_idx[]" value="<?=$fi_idx;?>" title="선택" checked="checked" /></td>
				<td>
					<div class="left">
						<?=$file_name;?>(<?=$file_size;?>)
					</div>
				</td>
				<td>
					<div class="left">
						<?=$file_title;?> 가능합니다.
					</div>
				</td>
			</tr>
<?
			$chk_num++;
		}
	}
	
	if (is_array($chk_data_file['1']))
	{
		foreach ($chk_data_file['1'] as $k => $v)
		{
			$fi_idx    = $v['fi_idx'];
			$file_name = $v['file_name'];
			$file_size = $v['file_size'];
			$file_size = byte_replace($file_size);
?>
			<tr>
				<td><input type="checkbox" title="선택" disabled="disabled" /></td>
				<td>
					<div class="left">
						<?=$file_name;?>(<?=$file_size;?>)
						<input type="hidden" id="copyfiidx_<?=$chk_num;?>" name="chk_fi_idx[]" value="<?=$fi_idx;?>" />
					</div>
				</td>
				<td>
					<div class="left">
						중복된 파일입니다. <?=$file_title;?> 할 수 없습니다.
					</div>
				</td>
			</tr>
<?
			$chk_num++;
		}
	}
	
	if (is_array($chk_data_file['3']))
	{
		foreach ($chk_data_file['3'] as $k => $v)
		{
			$fi_idx    = $v['fi_idx'];
			$file_name = $v['file_name'];
			$file_size = $v['file_size'];
			$file_size = byte_replace($file_size);
?>
			<tr>
				<td><input type="checkbox" title="선택" disabled="disabled" /></td>
				<td>
					<div class="left">
						<?=$file_name;?>(<?=$file_size;?>)
						<input type="hidden" id="copyfiidx_<?=$chk_num;?>" name="chk_fi_idx[]" value="<?=$fi_idx;?>" />
					</div>
				</td>
				<td>
					<div class="left">
						업로드 권한이 없어 <?=$file_title;?> 할 수 없습니다.
					</div>
				</td>
			</tr>
<?
			$chk_num++;
		}
	}
?>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
				<span class="btn_big_green"><input type="button" value="<?=$file_title;?>" onclick="check_copy_move()" /></span>
				<span class="btn_big_gray"><input type="button" value="취소" onclick="popup_file_close()" /></span>
			</div>
		</div>
	</fieldset>
<?
	db_close();
?>	
