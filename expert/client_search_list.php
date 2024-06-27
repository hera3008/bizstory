<?
/*
	생성 : 2013.01.16
	수정 : 2013.01.17
	위치 : 전문가코너 > 거래처검색관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	if ($scomp != '') $where .= " and ci.comp_idx = '" . $scomp . "'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = expert_client_info_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;scomp=' . $send_scomp;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scomp"  value="' . $send_scomp . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="150px" />
		<col />
		<col width="60px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="aiidx" onclick="check_all('aiidx', this);" /></th>
			<th class="nosort"><h3>업체명</h3></th>
			<th class="nosort"><h3>거래처명</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>설정여부</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="6">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$ci_idx  = $data['ci_idx'];
				$ecs_idx = $data['ecs_idx'];

				if ($ecs_idx == '') $chk_view_yn = 'N';
				else $chk_view_yn = 'Y';

				if ($data["view_yn"] == '') $data["view_yn"] = 'Y';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $ci_idx . "', '" . $data["view_yn"] . "')";
					$btn_modify = "open_data_form('" . $ci_idx . "')";
				}
				else
				{
					$btn_view   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
				}
?>
		<tr>
			<td><input type="checkbox" id="aiidx_<?=$i;?>" name="chk_ci_idx[]" value="<?=$data["ci_idx"];?>" /></td>
			<td><?=$data['comp_name'];?></td>
			<td><div class="left"><?=$data['client_name'];?></div></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$chk_view_yn;?>.gif" alt="<?=$chk_view_yn;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
			</td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />
