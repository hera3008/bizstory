<?
/*
	수정 : 2013.04.15
	위치 : 업무관리 > 나의 업무 > 쪽지 > 쪽지목록 - 목록 - 해당쪽지
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$mem_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$query_string = "
		select
			mr.*
			, ms.remark, ms.send_date
		from
			message_receive mr
			left join message_send ms on ms.del_yn = 'N' and ms.ms_idx = mr.ms_idx
		where
			mr.comp_idx = '" . $code_comp . "'
			and (mr.mem_idx = '" . $code_mem . "' or mr.reg_id = '" . $code_mem . "')
			and if (mr.mem_idx = '" . $code_mem . "', mr.reg_id, mr.mem_idx) = '" . $mem_idx . "'
			and if (mr.mem_idx = '" . $code_mem . "', mr.del_yn, ms.send_del) = 'N'
		order by
			if (mr.mem_idx = '" . $code_mem . "', mr.reg_date, ms.reg_date) desc
	";
	$data_sql['query_page']   = $query_page;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = $page_size;
	$data_sql['page_num']     = $page_num;
	$list = query_list($data_sql);
	$page_num = $list['page_num'];

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
?>
<div class="ajax_write">
	<div class="ajax_frame">
<?
	if ($mem_idx == '')
	{
?>
		<ul class="list_cmt">
			<li class="message_box msg_no">
				왼쪽에서 직원을 선택하세요. 해당 쪽지를 볼 수 있습니다.
			</li>
		</ul>
<?
	}
	else
	{
		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
		if ($mem_data['del_yn'] == 'Y')
		{
			$mem_name = $mem_data['mem_name'] . ' (퇴사직원)';
		}
		else
		{
			$mem_name = $mem_data['mem_name'];
		}
?>
<div class="upload_title">
	<strong><?=$mem_name;?></strong> 님과의 쪽지내용입니다.
</div>

<ul class="list_cmt">
<?
	$i = 1;
	$num = $list["total_num"] - ($page_num - 1) * $page_size;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$remark = strip_tags($data["remark"]);
			$remark = str_replace('&nbsp;', ' ', $remark);
			$remark = string_cut($remark, 70);

		// 첨부파일
			$file_where = " and msgf.ms_idx = '" . $data['ms_idx'] . "'";
			$file_list = message_file_data('page', $file_where);
			$total_file = $file_list['total_num'];
			if ($total_file > 0) $file_str = '<span class="attach" title="첨부파일">' . number_format($total_file) . '</span>';
			else $file_str = '';

		// 구분별로 보여줄 내용
			if ($data['reg_id'] == $code_mem) // 보낸경우
			{
				$msg_class  = "message_box2";
				$msg_class2 = "ico_cmt_right";
				$msg_check  = '<input type="checkbox" id="msidx_' . $i . '" name="chk_ms_idx[]" value="' . $data["ms_idx"] . '" />';
				$msg_reg    = '보낸일 : ' . $data['send_date'];
				$msg_type   = 'send';
				$msg_idx    = $data["ms_idx"];
			}
			else
			{
				$msg_class  = "message_box";
				$msg_class2 = "ico_cmt_left";
				$msg_check  = '<input type="checkbox" id="mridx_' . $i . '" name="chk_mr_idx[]" value="' . $data["mr_idx"] . '" />';
				$msg_reg    = '받은일 : ' . $data['send_date'];
				$msg_type   = 'receive';
				$msg_idx    = $data["mr_idx"];

				if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
				{
					$remark = '<span class="no_read">' . $remark . '</span>';
				}
				else
				{
					$msg_reg .= ', 읽은일 : ' . $data['read_date'];
				}
			}
?>
	<li class="<?=$msg_class;?>">
		<div class="box_cmt">
			<span class="txt_photo"><img src="/bizstory/images/tfuse-top-panel/no_member.jpg" alt="" height="35px" width="35px"></span>
			<div class="txt_area">
				<span class="txt_cmt">
					<?=$msg_check;?>
					<a href="javascript:void(0);" onclick="popupview_open('<?=$i;?>', '<?=$mem_idx;?>', '<?=$msg_idx;?>', '<?=$msg_type;?>')"><?=$remark;?></a>
					<?=$file_str;?>
					<div id="msg_view_remark_<?=$i;?>" class="none"></div>
					<span class="ico_cmt <?=$msg_class2;?>"></span>
				</span>
				<span class="desc">
					<span class="time"><?=$msg_reg;?></span>
				</span>
			</div>
		</div>
	</li>
<?
			$i++;
		}
	}

	if ($list['total_num'] == 0)
	{
?>
	<li class="message_box msg_no">
		데이타가 없습니다.
	</li>
<?
	}
?>
</ul>

<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
<?
	$str = '
		<div id="tablenav">
			<ul>
				<li><a href="javascript:void(0);" onclick="page_move_other(\'first\', \'' . $mem_idx . '\')" class="first">First Page</a></li>
				<li><a href="javascript:void(0);" onclick="page_move_other(\'prev\', \'' . $mem_idx . '\')" class="previous">Previous Page</a></li>
				<li><a href="javascript:void(0);" onclick="page_move_other(\'next\', \'' . $mem_idx . '\')" class="next">Next Page</a></li>
				<li><a href="javascript:void(0);" onclick="page_move_other(\'last\', \'' . $mem_idx . '\')" class="last">Last Page</a></li>
				<li><a href="javascript:void(0);" onclick="page_move_other(\'all\', \'' . $mem_idx . '\')" class="showall">View All</a></li>
				<li>
					<select id="page_page_num" name="page_num" title="페이지 선택" onchange="page_move_other(this.value, \'' . $mem_idx . '\')">';
	for ($i = 1; $i <= $list['total_page']; $i++)
	{
		$str .= '
						<option value="' . $i . '"' . selected($page_num, $i) . '>' . $i . '</option>';
	}
	$str .= '
					</select>
				</li>
			</ul>
		</div>
		<div id="tablelocation">
			<select id="page_page_size" name="page_size" title="출력게시물수 선택" onchange="msg_list_data(\'' . $mem_idx . '\')">
				<option value="5"' . selected($page_size, '5') . '>5</option>
				<option value="10"' . selected($page_size, '10') . '>10</option>
				<option value="15"' . selected($page_size, '15') . '>15</option>
				<option value="20"' . selected($page_size, '20') . '>20</option>
				<option value="30"' . selected($page_size, '30') . '>30</option>
				<option value="40"' . selected($page_size, '40') . '>40</option>
				<option value="60"' . selected($page_size, '60') . '>60</option>
				<option value="80"' . selected($page_size, '80') . '>80</option>
				<option value="100"' . selected($page_size, '100') . '>100</option>';
	if ($page_size > 100)
	{
		$str .= '
				<option value="' . $page_size . '"' . selected($page_size, $page_size) . '>' . $page_size . '</option>';

	}
	$str .= '
			</select>
			<span>Entries Per Page</span> - Page ' . $page_num . '/' . $list['total_page'] . '
		</div>';

	echo $str;
?>
</div>
<hr />
<?
	}
?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 페이지이동
	function page_move_other(str, idx)
	{
		var total_page = $('#new_total_page').val();
		var page_num   = $('#page_page_num').val();

		if (str == 'first')
		{
			$('#page_page_num').val(1);
		}
		else if (str == 'last')
		{
			$('#page_page_num').val(total_page);
		}
		else if (str == 'prev')
		{
			page_num = parseInt(page_num) - 1;
			if (page_num < 1) page_num = 1;
			$('#page_page_num').val(page_num);
		}
		else if (str == 'next')
		{
			page_num = parseInt(page_num) + 1;
			if (page_num > total_page) page_num = total_page;
			$('#page_page_num').val(page_num);
		}
		else if (str == 'all')
		{
			$('#page_page_num').val(1);
			$('#page_page_size').append('<option value="1000">1000</option>');
			$('#page_page_size').val(1000);
		}
		else
		{
			$('#page_page_num').val(str);
		}
		msg_list_data(idx);
	}

//]]>
</script>
