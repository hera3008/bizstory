<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 퇴사직원 - 실행
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mem_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shgroup"  value="' . $send_shgroup . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y' && $mem_idx != '') // 보기권한
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
		$where = " and mem.mem_idx = '" . $mem_idx . "'";
		$data = member_info_data("view", $where, '', '', '', 2);

		$data['address']    = str_replace('||', ' ', $data['address']);
		$data['enter_date'] = date_replace($data['enter_date'], 'Y-m-d');
		$data['end_date']   = date_replace($data['end_date'], 'Y-m-d');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">직원정보 폼</legend>
				<table class="tinytable write" summary="직원정보에 대한 상세한 내용을 봅니다.">
				<caption>직원정보</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>지사</th>
						<td colspan="3">
							<div class="left"><?=$data['part_name'];?></div>
						</td>
					</tr>
					<tr>
						<th>이름</th>
						<td colspan="3">
							<div class="left"><?=$data['mem_name'];?></div>
						</td>
					</tr>
					<tr>
						<th>이메일</th>
						<td colspan="3">
							<div class="left"><?=$data['mem_email'];?></div>
						</td>
					</tr>
					<tr>
						<th>직책</th>
						<td>
							<div class="left"><?=$data['part_name'];?></div>
						</td>
						<th>직원그룹</th>
						<td>
							<div class="left">
							<div class="left"><?=$data['group_name'];?></div>
						</td>
					</tr>
					<tr>
						<th>주소</th>
						<td colspan="3">
							<div class="left"><?=$data['zip_code'];?></div>
							<div class="left mt"><?=$data['address'];?></div>
						</td>
					</tr>
					<tr>
						<th>전화번호</th>
						<td>
							<div class="left"><?=$data['tel_num'];?></div>
						</td>
						<th>핸드폰 번호</th>
						<td>
							<div class="left"><?=$data['hp_num'];?></div>
						</td>
					</tr>
					<tr>
						<th>스마트폰여부</th>
						<td>
							<div class="left"><?=$data['smartphone_yn'];?></div>
						</td>
						<th>스마트폰종류</th>
						<td>
							<div class="left"><?=$data['smartphone_class'];?></div>
						</td>
					</tr>
					<tr>
						<th>로그인여부</th>
						<td>
							<div class="left"><?=$data['login_yn'];?></div>
						</td>
						<th>관리자여부</th>
						<td>
							<div class="left"><?=$data['ubstory_yn'];?></div>
						</td>
					</tr>
					<tr>
						<th>입사일</th>
						<td>
							<div class="left"><?=$data['enter_date'];?></div>
						</td>
						<th>퇴사일</th>
						<td>
							<div class="left"><?=$data['end_date'];?></div>
						</td>
					</tr>
					<tr>
						<th>메모</th>
						<td colspan="3">
							<div class="left"><?=nl2br($data['remark']);?></div>
						</td>
					</tr>
				</tbody>
				</table>
			</fieldset>
<?
// 사진
	$photo_where = " and mf.comp_idx = '" . $code_comp . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort = '1'";
	$photo_data = member_file_data('view', $photo_where);

	$file_where = " and mf.comp_idx = '" . $code_comp . "' and mf.mem_idx = '" . $mem_idx . "' and mf.sort != '1'";
	$file_list = member_file_data('list', $file_where, '', '', '');
?>
			<div class="sub_frame"><h4>파일관리</h4></div>

			<fieldset>
				<legend class="blind">직원파일 폼</legend>
				<table class="tinytable write" summary="등록된 직원파일목록입니다.">
				<caption>직원파일</caption>
				<colgroup>
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="file_fname1">사진</label></th>
						<td colspan="2">
							<div class="filewrap">
				<?
					if ($photo_data['total_num'] > 0)
					{
						$img_size = $photo_data['img_size'];
						if ($img_size > 0) $img_size = number_format($img_size/1024);
						else $img_size = 0;

						$photo_img = '
							<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="80px" alt="' . $data['mem_name'] . '" />';
					}
				?>
								<?=$photo_img;?>
							</div>
						</td>
					</tr>
				<?
					if ($file_list['total_num'] > 0)
					{
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
								$img_size = $file_data['img_size'];
								if ($img_size > 0) $img_size = number_format($img_size/1024);
								else $img_size = 0;
				?>
					<tr>
						<th><?=$file_data['subject'];?></th>
						<td>
							<div class="left file">
								<a href="<?=$local_dir;?>/bizstory/comp_set/staff_download.php?mf_idx=<?=$file_data['mf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>(<?=$img_size;?>KByte)
							</div>
						</td>
					</tr>
				<?
							}
						}
					}
				?>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?
	}
?>