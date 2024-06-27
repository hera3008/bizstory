<?
/*
	생성 : 2012.12.17
	수정 : 2013.05.20
	위치 : 설정폴더(관리자) > 설정관리 > 데모신청 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'demo.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$where .= " and (
				replace(demo.tel_num, '-', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'demo.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = demo_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

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
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom"><?=$btn_write;?></div>
</div>

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col />
		<col width="100px" />
		<col width="100px" />
		<col width="150px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="demoidx" onclick="check_all('demoidx', this);" /></th>
			<th class="nosort"><h3><?=field_sort('업체명', 'demo.comp_name');?></h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3><?=field_sort('대표자명', 'demo.boss_name');?></h3></th>
			<th class="nosort"><h3>이메일</h3></th>
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
				if ($auth_menu['mod'] == "Y")
				{
					$btn_view = "view_open('" . $data["demo_idx"] . "')";
				}
				else
				{
					$btn_view = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data["demo_idx"] . "')";
				else $btn_delete = "check_auth_popup('delete');";

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
?>
		<tr>
			<td><input type="checkbox" id="demoidx_<?=$i;?>" name="chk_demo_idx[]" value="<?=$data["demo_idx"];?>" /></td>
			<td>
				<div class="left"><?=$data["comp_name"];?></div>
			</td>
			<td><span class="eng"><?=$tel_num;?></span></td>
			<td><?=$data["boss_name"];?></td>
			<td><span class="eng"><?=$data["comp_email"];?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_view;?>" class="btn_con_violet"><span>보기</span></a>
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
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>