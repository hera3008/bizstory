<?
/*
	수정 : 2013.05.02
	위치 : 고객관리 > 접수목록 - 등록, 수정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem = $_SESSION[$sess_str . '_mem_idx'];
	$ri_idx    = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
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

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $ri_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ri_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{	
		if($ri_idx != ""){
			$receipt_info = new receipt_info();
			$receipt_info->ri_idx = $ri_idx;
			$receipt_info->data_path = $comp_receipt_path;
			$receipt_info->data_dir = $comp_receipt_dir;

			$data = $receipt_info->receipt_info_view();
			$file_list = $receipt_info->receipt_file();

			if ($data['part_idx'] == '' || $data['part_idx'] == '0') $data['part_idx'] = $code_part;
			if ($data['part_name'] == '')
			{
				$sub_where = " and part.part_idx = '" . $code_part . "'";
				$sub_data = company_part_data('view', $sub_where);

				$data['part_name'] = $sub_data['part_name'];
			}

		// 직원정보
			$mem_where = " and mem.mem_idx = '" . $code_mem . "'";
			$mem_data = member_info_data('view', $mem_where);

			if ($data['writer'] == '') $data['writer'] = $mem_data['mem_name'];
			if ($data['tel_num'] == '') $data['tel_num'] = $mem_data['hp_num'];
			if ($data['important'] == '') $data['important'] = 'RI01';

		// 접수파일
			$file_query = "select max(sort) as sort from receipt_file where ri_idx = '" . $ri_idx . "'";
			$file_chk = query_view($file_query);
			$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

			$file_upload_num = $file_chk['sort'];
			$file_chk_num    = $file_upload_num + 1;
		}else{
			$file_list = array();
			$file_upload_num = 0;
			$file_chk_num    = 1;
		}
?>
<div class="info_text">
	<ul>
		<li>파일 업로드시 파일선택을 "쉬프트키"와 함께 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">접수정보 폼</legend>
				<table class="tinytable write" summary="접수정보를 등록/수정합니다.">
				<caption>접수정보</caption>
				<colgroup>
					<col width="90px" />
					<col width="300px" />
					<col width="90px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td>
							<div class="left">
					<?
					// 거래처, 접수분류
						$str_script = "part_information(this.value, 'client_info', 'post_ci_idx', '" . $data['ci_idx'] . "', ''); part_information(this.value, 'receipt_class', 'post_receipt_class', '" . $data['receipt_class'] . "', '');";
					?>
								<?=company_part_select($data['part_idx'], ' onchange="' . $str_script . '"');?>
							</div>
						</td>
						<th><label for="post_ci_idx">거래처명</label></th>
						<td>
							<div class="left">
								<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요" onchange="check_client_info(this.value)">
									<option value="">거래처를 선택하세요</option>
								</select>
								<input type="hidden" name="param[client_code]" id="post_client_code" value="<?=$data['client_code'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_writer">작성자</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[writer]" id="post_writer" class="type_text" title="작성자를 입력하세요." size="30" value="<?=$data['writer'];?>" />
							</div>
						</td>
						<th><label for="post_tel_num">연락처(위치)</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[tel_num]" id="post_tel_num" class="type_text" title="전화번호를 입력하세요." size="15" value="<?=$data['tel_num'];?>" />&nbsp;<span style="color:#ee6666">개인정보 유출방지를 위해 개인 휴대폰 번호는 가급적 삼가해주시고 사무실번호 또는 위치정보(예:00시청 00과)형태로 입력해 주시기바랍니다.</span>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_receipt_class">분류</label></th>
						<td>
							<div class="left">
								<select name="param[receipt_class]" id="post_receipt_class" title="분류를 선택하세요">
									<option value="">분류를 선택하세요</option>
								</select>
							</div>
						</td>
						<th>중요도</th>
						<td>
							<div class="left">
								<?=code_radio($set_receipt_important, 'param[important]', 'post_important', $data['important']);?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_subject">제목</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_remark">내용</label></th>
						<td colspan="3">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요."><?=$data['remark'];?></textarea>
						</td>
					</tr>
					<tr>
						<th><label for="file_fname">파일</label></th>
						<td colspan="3">
							<div class="filewrap">
								<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
								<div class="file">
									<ul id="file_fname_view">
				<?
					foreach ($file_list as $file_k => $file_data)
					{
						if (is_array($file_data))
						{
							$file_chk = $file_data['sort'];
							$fsize = $file_data['img_size'];
							$fsize = byte_replace($fsize);
				?>
										<li id="file_fname<?=$file_chk;?>_view" class="org_file">
											<a href="<?=$local_diir;?>/bizstory/receipt/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
											<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['rf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
										</li>
				<?
						}
					}
				?>
									</ul>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
				<input type="hidden" id="upload_fnum" name="upload_fnum" value="<?=$upload_file_num_max;?>" />

				<div class="section">
					<div class="fr">
				<?
					if ($ri_idx == '') {
				?>
						<span class="btn_big_green"><input type="button" value="등록" onclick="check_form()" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="ri_idx"   value="<?=$ri_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	//file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'receipt', '');
	file_setting('file_fname', 'receipt', '', '<?=$file_multi_size;?>', '');

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	part_information('<?=$data['part_idx'];?>', 'client_info', 'post_ci_idx', '<?=$data['ci_idx'];?>', '');
	part_information('<?=$data['part_idx'];?>', 'receipt_class', 'post_receipt_class', '<?=$data['receipt_class'];?>', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		var file_chk = $('#upload_select_num').val();
		if (file_chk > 0)
		{
			chk_total = chk_total + '먼저 선택한 파일을 올리세요.<br />';
			action_num++;
		}

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_ci_idx').val(); // 거래처
		chk_title = $('#post_ci_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_receipt_class').val(); // 접수분류
		chk_title = $('#post_receipt_class').attr('title');
		if (chk_value == '' || chk_value == '0')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
						$("#work_form_view").html('');
						list_data();
				<?
					$f_default1 = str_replace('&amp;', '&', $f_default);
				?>
						//location.href = '?<?=$f_default1;?>';
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 거래처를 선택하면 기본데이타보여주기
	function check_client_info(idx)
	{
		$.ajax({
			type: 'post', dataType: 'json', url : '<?=$local_dir;?>/bizstory/comp_set/client_information.php',
			data : {"idx": idx},
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					$('#post_client_code').val(msg.client_code);
				}
			}
		});
	}
//]]>
</script>

<?
	}
?>
