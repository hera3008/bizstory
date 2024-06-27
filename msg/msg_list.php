<?
/*
	수정 : 2013.05.14
	위치 : 업무관리 > 나의 업무 > 쪽지 > 목록 - 직원
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($auth_menu['del'] == "Y") // 삭제버튼
	{
		$btn_delete = '<a href="javascript:void(0);" onclick="select_delete()" class="btn_big_red"><span>선택삭제</span></a>';
	}
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}

	$query_string = "
		select
			mr.*
			, ms.mem_name as send_mem_name
			, if (mr.mem_idx = '" . $code_mem . "', ms.mem_name, mr.mem_name) as chk_name
			, if (mr.mem_idx = '" . $code_mem . "', mem1.del_yn, mem2.del_yn) as chk_del
			, if (mr.mem_idx = '" . $code_mem . "', mr.reg_date, ms.reg_date) as chk_reg
		from
			message_receive mr
			left join message_send ms on ms.del_yn = 'N' and ms.ms_idx = mr.ms_idx
			left join member_info mem1 on mem1.mem_idx = ms.mem_idx
			left join member_info mem2 on mem2.mem_idx = mr.mem_idx
		where
			mr.comp_idx = '" . $code_comp . "'
			and (mr.mem_idx = '" . $code_mem . "' or mr.reg_id = '" . $code_mem . "')
			and if (mr.mem_idx = '" . $code_mem . "', mr.del_yn, ms.send_del) = 'N'
		order by
			if (mr.mem_idx = '" . $code_mem . "', mr.reg_date, ms.reg_date) asc
	";
	$data_sql['query_page']   = $query_page;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';
	$list = query_list($data_sql);
	if ($list['total_num'] > 0)
	{
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				if ($data['mem_idx'] == $code_mem)
				{
					$chk_id = $data['reg_id'];
				}
				else
				{
					$chk_id = $data['mem_idx'];
				}

				$msg_data[$chk_id]['num']++;
				$msg_data[$chk_id]['idx']    = $chk_id;
				$msg_data[$chk_id]['name']   = $data['chk_name'];
				$msg_data[$chk_id]['del_yn'] = $data['chk_del'];

				$msg_reg[$chk_id] = $data['chk_reg'];
			}
		}
		arsort($msg_reg);
	}
?>
<div class="etc_bottom">
	<?=$btn_delete;?>
	<?=$btn_write;?>
</div>
<hr />

<div class="message_area">
	<div class="message_list">

		<table class="tinytable">
			<colgroup>
				<col width="50px"/>
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort">No</th>
					<th class="nosort"><h3>직원</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="2">등록된 데이타가 없습니다.</td>
				</tr>
<?
	}
	else
	{
		$i = 1;
		foreach ($msg_reg as $k => $data)
		{
			$mem_idx  = $msg_data[$k]['idx'];
			$mem_name = $msg_data[$k]['name'];
			$msg_num  = $msg_data[$k]['num'];

			$mem_img = member_img_view($mem_idx, $comp_member_dir); // 등록자 이미지
			$mem_string = staff_layer_form($mem_idx, '', $set_part_work_yn, $set_color_list2, 'msgstaff', $i, 'memlist');

		// 안 읽은 쪽지수 구하기
			$msg_ing_query = "
				select
					count(mr_idx)
				from
					message_receive
				where
					del_yn = 'N'
					and comp_idx = '" . $code_comp . "'
					and mem_idx = '" . $code_mem . "'
					and reg_id = '" . $mem_idx . "'
					and recv_keep = 'N'
					and date_format(read_date, '%Y-%m-%d') = '0000-00-00'
			";
			$msg_ing = query_page($msg_ing_query);
			$msg_ing = $msg_ing['total_num'];
			if ($msg_ing > 0)
			{
				$new_msg = '<em>' . $msg_ing . '</em>';
			}
			else
			{
				$new_msg = '';
			}
?>
				<tr>
					<td><span class="num"><?=$i;?></span></td>
					<td>
						<div class="left">
							<?=$mem_img['img_22'];?>
							<a href="javascript:void(0);" onclick="msg_list_data('<?=$mem_idx;?>')"><?=$mem_string;?> (<?=number_format($msg_num);?>)</a>
							<span class="num3" id="msg_mem_<?=$mem_idx;?>"><?=$new_msg;?></span>
						</div>
					</td>
				</tr>
<?
				$num--;
				$i++;
		}
	}
?>
			</tbody>
		</table>
	</div>

	<div class="message_comment">
		<div class="wrap_comment" id="msg_data_list"></div>
	</div>
</div>
<hr />
