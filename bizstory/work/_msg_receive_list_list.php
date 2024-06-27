<?
/*
	수정 : 2012.04.27
	위치 : 업무폴더 > 나의 업무 > 쪽지 > 받은쪽지 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "and mr.comp_idx = '" . $code_comp . "' and mr.mem_idx = '" . $code_mem . "' and mr.recv_keep = 'N'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = message_receive_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<ul class="list_cmt">
<?
	$i = 1;
	$num = $list["total_num"] - ($page_num - 1) * $page_size;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$remark = strip_tags($data["remark"]);
			//$remark = string_cut($remark, 70);

			if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
			{
				$remark = '<span class="no_read">' . $remark . '</span>';
			}

		// 첨부파일
			$file_where = " and msgf.ms_idx = '" . $data["ms_idx"] . "'";
			$file_list = message_file_data('page', $file_where);
			$total_file = $file_list['total_num'];
			if ($total_file > 0) $file_str = '<span class="attach" title="첨부파일">' . number_format($total_file) . '</span>';
			else $file_str = '';
?>
	<li class="message_box">
		<div class="box_cmt">
			<span class="txt_cmt">
				<a href="javascript:void(0);" onclick="view_open('<?=$data["mr_idx"];?>')"><?=$remark;?></a>
				<?=$file_str;?>
				<span class="ico_cmt ico_cmt_left"></span>
			</span>
			<span class="desc">
				<span class="time">2012.11.18</span>
				<span class="link_name">문은지</span>
			</span>
		</div>
	</li>
<?
		}
	}
?>
	<li class="message_box">
		<div class="box_cmt">
			<span class="txt_cmt">
				내용블라블라브라~~~~~~~~내용블라블라브라~~~~~~~~내용블라블라브라~~~~~~~~내용블라블라브라~~~~~~~~내용블라블라브라~~~~~~~~
				<span class="ico_cmt ico_cmt_left"></span>
			</span>
			<span class="desc">
				<span class="time">2012.11.18</span>
				<span class="link_name">문은지</span>
			</span>
		</div>
	</li>
	<li class="message_box2">
		<div class="box_cmt">
			<span class="txt_cmt">
				내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~내용내용내용내용~~~
				<span class="ico_cmt ico_cmt_right"></span>
			</span>
			<span class="desc">
				<span class="time">2012.11.17</span>
				<span class="link_name">김경화</span>
			</span>
		</div>
	</li>
	<li class="message_box">
		<div class="box_cmt">
			<span class="txt_cmt">
				내용~~~~~~~~
				<span class="ico_cmt ico_cmt_left">ㅁㅁ</span>
			</span>
			<span class="desc">
				<span class="time">2012.11.16</span>
				<span class="link_name">문은지</span>
			</span>
		</div>
	</li>
</ul>

<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />

<!--table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="100px" />
		<col />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="mridx" onclick="check_all('mridx', this);" /></th>
			<th class="nosort"><h3>보낸사람</h3></th>
			<th class="nosort"><h3>내용</h3></th>
			<th class="nosort"><h3>보낸일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="4">등록된 데이타가 없습니다.</td>
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
				$remark = strip_tags($data["remark"]);
				$remark = string_cut($remark, 70);

				if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
				{
					$remark = '<span class="no_read">' . $remark . '</span>';
				}

			// 첨부파일
				$file_where = " and msgf.ms_idx = '" . $data["ms_idx"] . "'";
				$file_list = message_file_data('page', $file_where);
				$total_file = $file_list['total_num'];
				if ($total_file > 0) $file_str = '<span class="attach" title="첨부파일">' . number_format($total_file) . '</span>';
				else $file_str = '';
?>
		<tr>
			<td><input type="checkbox" id="mridx_<?=$i;?>" name="chk_mr_idx[]" value="<?=$data["mr_idx"];?>" /></td>
			<td><?=$data['send_mem_name'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$data["mr_idx"];?>')"><?=$remark;?></a>
					<?=$file_str;?>
				</div>
			</td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y.m.d');?></span></td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
	</tbody>
</table-->