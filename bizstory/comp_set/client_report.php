<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ci_idx    = $org_idx;

	$where = " and ci.ci_idx = '" . $ci_idx . "'";
	$client_data = client_info_data("view", $where);

	$link_ok     = $local_dir . "/bizstory/comp_set/client_report_ok.php";   // 저장
	$link_form   = $local_dir . "/bizstory/comp_set/client_report_form.php"; // 등록
	$link_modify = $local_dir . "/bizstory/comp_set/client_report_modify.php"; // 수정

	$where = " rr.del_yn = 'N' and rr.comp_idx = '" . $code_comp . "' and rr.ci_idx = '" . $ci_idx . "'";
	$data_sql['query_page'] = "
		select
			count(rr.rr_idx)
		from
			receipt_report rr
		where
			" . $where . "
	";
	$data_sql['query_string'] = "
		select
			rr.*
		from
			receipt_report rr
		where
			" . $where . "
		group by
			rr.rr_code
		order by
			rr.rr_code
	";
	$data_sql['page_size'] = $page_size;
	$data_sql['page_num']  = $page_num;
	$list = query_list($data_sql);

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_frame">
			<span><strong><?=$client_data['client_name'];?></strong>보고서를 관리합니다.</span>
		</div>

		<div id="tableheader">
			<div class="search">
				<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
					<?=$form_default;?>
					<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
						<option value=""><?=$search_column;?></option>
						<option value="ri.end_date"<?=selected($swhere, 'ri.end_date');?>>완료일</option>
						<option value="ri.reg_date"<?=selected($swhere, 'ri.reg_date');?>>등록일</option>
					</select>
					<input type="text" id="search_stext1" name="stext1" class="type_text datepicker" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" />
					~
					<input type="text" id="search_stext2" name="stext2" class="type_text datepicker" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" />
					<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
					<div class="etc_bottom"><a href="javascript:void(0);" onclick="popupsub_form('<?=$ci_idx;?>', '', '<?=$link_form;?>')" class="btn_sml fr"><span>보고서생성</span></a></div>
				</form>
			</div>
		</div>

		<table class="tinytable">
			<colgroup>
				<col />
				<col width="100px" />
				<col width="170px" />
				<col width="50px" />
				<col width="110px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>아이디</h3></th>
					<th class="nosort"><h3>이름</h3></th>
					<th class="nosort"><h3>연락처</th>
					<th class="nosort"><h3>로그인</h3></th>
					<th class="nosort"><h3>관리</h3></th>
				</tr>
			</thead>
			<tbody>
		<?
			$i = 0;
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
						$rr_idx = $data['rr_idx'];

						$btn_modify = "user_form('" . $ci_idx . "', '" . $rr_idx . "')";
						$btn_delete = "check_form_delete('" . $ci_idx . "', '" . $rr_idx . "')";
		?>
				<tr>
					<td><?=$data['mem_id'];?></td>
					<td><?=$data['mem_name'];?></td>
					<td>
						<div class="left"><?=$data['tel_num'];?></div>
						<div class="left"><?=$data['mem_email'];?></div>
					</td>
					<td><img src="bizstory/images/icon/<?=$data['login_yn'];?>.gif" alt="<?=$data['login_yn'];?>" class="pointer" onclick="<?=$btn_login;?>" /></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con"><span>수정</span></a>
						<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con"><span>삭제</span></a>
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

		<div class="section">
			<div class="fr">
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 저장하기
	function check_form(idx)
	{
		$("#popup_notice_view").hide();
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (idx == '')
		{
			chk_value = $('#post_mem_id').val();
			chk_title = $('#post_mem_id').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}

			chk_value = $('#post_mem_id_chk').val();
			chk_title = $('#post_mem_id_chk').attr('title');
			if (chk_value == 'N')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}

			chk_value = $('#post_mem_pwd').val();
			chk_title = $('#post_mem_pwd').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}

		if (action_num == 0)
		{
			$('#other_sub_type').val('post');
			$.ajax({
				type    : "post", dataType : 'json', url : '<?=$link_ok;?>',
				data    : $('#otherform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						user_list('<?=$ci_idx;?>');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

	$(".datepicker").datepicker( {
		showOn: "button",
		buttonImage: "<?=$local_dir;?>/bizstory/images/btn/calendar.jpg",
		dateFormat:"yy-mm-dd",
		buttonImageOnly: true,
	});
//]]>
</script>