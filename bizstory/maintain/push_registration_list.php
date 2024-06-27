<?
/*
	생성 : 2013.02.13
	수정 : 2013.02.13
	위치 : 설정폴더(총관리자용) > 푸쉬관리 > 등록 ID - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'push.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = push_member_data('list', $where, $orderby, $page_num, $page_size);
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
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="110px" />
		<col width="80px" />
		<col width="110px" />
		<col width="40px" />
		<col />
		<col width="130px" />
		<col width="80px" />
		<col width="60px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('업체명', 'comp.comp_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('직원명', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록아이디', 'mem.mem_id');?></h3></th>
			<th class="nosort"><h3>종류</h3></th>
			<th class="nosort"><h3><?=field_sort('등록ID', 'push.push_registration_id');?></h3></th>
			<th class="nosort"><h3><?=field_sort('디바이스번호', 'push.push_device_unique_id');?></h3></th>
			<th class="nosort">
				<h3><?=field_sort('등록일', 'push.reg_date');?></h3>
				<h3><?=field_sort('수정일', 'push.mod_date');?></h3>
			</th>
			<th class="nosort"><h3>삭제</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="9">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data["push_idx"] . "')";
				else $btn_delete = "check_auth('delete');";

				$regis_chk = 33;
				$regis_id  = $data["push_registration_id"];
				$regis_len = ceil(strlen($regis_id) / $regis_chk);
				$regis_str = '';
				$start_len = 0;
				for ($a = 1; $a <= $regis_len; $a++)
				{
					if ($a > 1) $regis_str .= '<br />';
					$regis_str .= substr($regis_id, $start_len, $regis_chk);
					$start_len = $a * $regis_chk;
				}

				$device_chk = 16;
				$device_id  = $data["push_device_unique_id"];
				$device_len = ceil(strlen($device_id) / $device_chk);
				$device_str = '';
				$start_len = 0;
				for ($a = 1; $a <= $device_len; $a++)
				{
					if ($a > 1) $device_str .= '<br />';
					$device_str .= substr($device_id, $start_len, $device_chk);
					$start_len = $a * $device_chk;
				}
?>
		<tr>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$data["comp_name"];?></td>
			<td><?=$data["mem_name"];?></td>
			<td><?=$data["mem_id"];?></td>
			<td><?=$data["push_device_type"];?></td>
			<td><div class="left"><span class="eng"><?=$regis_str;?></span></div></td>
			<td><div class="left"><span class="eng"><?=$device_str;?></span></div></td>
			<td>
				<span class="eng"><?=date_replace($data['reg_date'], 'Y-m-d');?></span><br />
				<span class="eng"><?=date_replace($data['mod_date'], 'Y-m-d');?></span>
			</td>
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
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>