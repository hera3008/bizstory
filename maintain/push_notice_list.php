<?
/*
	생성 : 2012.12.27
	수정 : 2012.12.27
	위치 : 설정폴더(총관리자용) > 푸쉬관리 > 푸쉬공지 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

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
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
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

	$where = " ";
	$list = sms_notice_data('list', $where, '', $page_num, $page_size);

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col />
		<col width="150px" />
		<col width="80px" />
		<col width="70px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>내용</h3></th>
			<th class="nosort"><h3>업체</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="5">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['sn_idx'] . "')";
				else $btn_delete = "check_auth_popup('delete');";

			// 업체
				if ($data['comp_all'] == 'Y')
				{
					$comp_view = '모든업체';
				}
				else
				{
					$comp_view = '';
					$comp_idx = $data['comp_idx'];
					$comp_idx_arr = explode(',', $comp_idx);
					foreach ($comp_idx_arr as $comp_k => $comp_v)
					{
						if ($comp_k > 0)
						{
							$comp_where = " and comp.comp_idx = '" . $comp_v . "'";
							$comp_data = company_info_data('view', $comp_where);

							if ($comp_k == 1)
							{
								$comp_view = $comp_data['comp_name'];
							}
							else
							{
								$comp_view .= ', ' . $comp_data['comp_name'];
							}
						}
					}
				}
?>
		<tr>
			<td><span class="num"><?=$num;?></span></td>
			<td><div class="left"><?=$data['contents'];?></div></td>
			<td><div class="left"><?=$comp_view;?></div></td>
			<td><span class="num"><?=date_replace($list_data['reg_date'], 'Y-m-d');?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
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
<hr />