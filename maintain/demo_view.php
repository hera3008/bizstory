<?
/*
	생성 : 2012.12.17
	수정 : 2012.12.17
	위치 : 설정폴더(관리자) > 설정관리 > 데모신청 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$demo_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
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
	if (($auth_menu['int'] == 'Y' && $demo_idx == '') || ($auth_menu['mod'] == 'Y' && $demo_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and demo.demo_idx = '" . $demo_idx . "'";
		$data = demo_info_data("view", $where);

		$tel_num = $data["tel_num"];
		$tel_num_str = substr($tel_num, 0, 1);
		if ($tel_num == '-' || $tel_num == '--')
		{
			$tel_num = '';
		}
		else if ($tel_num_str == '-')
		{
			$tel_num = substr($tel_num, 1, strlen($tel_num));
		}
		$data['address'] = str_replace('||', ' ', $data['address']);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<fieldset>
			<legend class="blind">데모신청 정보</legend>

			<table class="tinytable write" summary="이메일, 핸드폰번호등 기본 가입양식을 입력합니다.">
				<caption>데모신청</caption>
				<colgroup>
					<col width="120px" />
					<col />
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>상호명</th>
						<td colspan="3">
							<div class="left"><?=$data['comp_name'];?></div>
						</td>
					</tr>
					<tr>
						<th>대표자명</th>
						<td>
							<div class="left"><?=$data['boss_name'];?></div>
						</td>
						<th>사업자등록번호</th>
						<td>
							<div class="left"><?=$data['comp_num'];?></div>
						</td>
					</tr>
					<tr>
						<th>사업장주소</th>
						<td colspan="3">
							<div class="left"><?=$data['zip_code'];?></div>
							<div class="left mt"><?=$data['address'];?></div>
						</td>
					</tr>
					<tr>
						<th>이메일</th>
						<td colspan="3">
							<div class="left"><?=$data['comp_email'];?></div>
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
						<th>업종</th>
						<td>
							<div class="left"><?=$data['upjong'];?></div>
						</td>
						<th>업태</th>
						<td>
							<div class="left"><?=$data['uptae'];?></div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
				</div>
			</div>

		</fieldset>
	</div>
</div>
<?
	}
?>