<?
/*
	생성 : 2012.12.20
	수정 : 2013.04.09
	위치 : 업무폴더 > 프로젝트관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];
	$set_part_work_yn = 'N';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and pro.comp_idx = '" . $code_comp . "'";
	if ($set_part_work_yn == 'Y')
	{ }
	else
	{
		if ($set_part_yn == 'N') $where .= " and pro.part_idx = '" . $code_part . "'";
	}
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '' || $sorder2 == '')
	{
		$today_date = date('Y-m-d');
		$orderby = "
			 if (pros.code_value = 'PS90'
				, if (pro.end_date = '0000-00-00', '9999-12-31', pro.end_date)
				, if (pros.code_value = 'PS80'
					, '9000-12-31'
					, if (pros.code_value = 'PS01'
						, '9001-12-31'
						, '9002-12-31'
					)
				)
			) desc
			, if (datediff('" . $today_date . "', if (pro.deadline_date = '0000-00-00', '9999-12-31', pro.deadline_date)) < 0
				, 0
				, datediff('" . $today_date . "', if (pro.deadline_date = '0000-00-00', '9999-12-31', pro.deadline_date))
			) desc
			, pro.reg_date desc
		";
	}
	else
	{
		$orderby = $sorder1 . ' ' . $sorder2;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = project_info_data('list', $where, $orderby, $page_num, $page_size);
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

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>해당 프로젝트에 대한 업무를 등록할 수 있습니다.</li>
	</ul>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="100px" />
		<col width="110px" />
		<col />
		<col width="80px" />
		<col width="100px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="proidx" onclick="check_all('proidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('기한일', 'pro.deadline_date');?></h3></th>
			<th class="nosort"><h3>상태/완료일</h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'pro.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('책임자', 'apply.mem_name');?></h3></th>
			<th class="nosort"><h3>담당자</h3></th>
			<th class="nosort"><h3><?=field_sort('등록자', 'reg.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록일', 'pro.reg_date');?></h3></th>
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
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data['pro_idx'] . "')";
				else $btn_view = "check_auth_popup('view')";

				$list_data = project_list_data($data, $data['pro_idx']);
?>
		<tr>
			<td><input type="checkbox" id="proidx_<?=$i;?>" name="chk_pro_idx[]" value="<?=$data['pro_idx'];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><span class="num"><?=$list_data['deadline_date_str'];?></span></td>
			<td><?=$list_data['end_date_str'];?></td>
			<td>
				<div class="left">
					<img src="<?=$local_dir;?>/bizstory/images/icon/icon_project.gif" alt="프로젝트" />
					<span class="pro_code">[<?=$list_data['project_code'];?>]</span>
					<?=$list_data['subject_url'];?>
					<?=$list_data['open_img'];?>
					<?=$list_data['file_str'];?>
					<?=$list_data['new_img'];?>
				</div>
			</td>
			<td><?=$list_data['apply_name'];?></td>
			<td><div class="left"><?=$list_data['charge_str'];?></div></td>
			<td><?=$list_data['reg_name'];?></td>
			<td><span class="num"><?=date_replace($list_data['reg_date'], 'Y-m-d');?></span></td>
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