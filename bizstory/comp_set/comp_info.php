<?
/*
	수정 : 2013.05.02
	위치 : 설정관리 > 회사관리 > 회사소개
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_ok = $local_dir . "/bizstory/comp_set/comp_info_ok.php"; // 저장

	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$data = company_info_data('view', $where);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" value="modify" />

		<fieldset>
			<legend class="blind">회사소개</legend>
			<table class="tinytable write" summary="회사소개를 수정합니다.">
			<caption>회사소개</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_slogan1">슬로건1</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[slogan1]" id="post_slogan1" class="type_text" title="슬로건1을 입력하세요." size="50" value="<?=$data['slogan1'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_slogan2">슬로건2</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[slogan2]" id="post_slogan2" class="type_text" title="슬로건2를 입력하세요." size="50" value="<?=$data['slogan2'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="left textarea_span">
							<textarea name="param[comp_remark]" id="post_comp_remark" title="내용을 입력하세요." class="none"><?=$data['comp_remark'];?></textarea>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="sub_frame"><h4>파일관리</h4></div>

			<table class="tinytable write" summary="회사파일을 관리합니다.">
			<caption>회사파일</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="file_fname1">로고이미지</label></th>
					<td>
						<div class="left">
							<input type="file" name="file_fname1" id="file_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
							<span>* 180*50 (.jpg, .gif, .png 만 가능) </span>
						</div>
						<div class="filewrap">
							<div class="file" id="file_fname1_view">
			<?
				$file_where = " and cf.comp_idx = '" . $code_comp . "' and cf.file_class = 'logo'";
				$file_data = company_file_data('view', $file_where);

				if ($file_data["img_sname"] != '')
				{
					$img_str = '<img src="' . $comp_company_dir . '/' . $file_data["img_sname"] . '" alt="' . $file_data["subject"] . '" width="180" height="50" />';

					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
								<?=$img_str;?>
								<a href="<?=$local_diir;?>/bizstory/comp_set/company_download.php?cf_idx=<?=$file_data['cf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['cf_idx'];?>', '1')"><span>삭제</span></a>
			<?
				}
			?>
							</div>
						</div>
					</td>
					<th><label for="file_fname2">도장이미지</label></th>
					<td>
						<div class="left">
							<input type="file" name="file_fname2" id="file_fname2" class="type_text type_file type_multi" title="파일 선택하기" />
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
						<div class="filewrap">
							<div class="file" id="file_fname2_view">
			<?
				$file_where = " and cf.comp_idx = '" . $code_comp . "' and cf.file_class = 'stamp'";
				$file_data = company_file_data('view', $file_where);

				if ($file_data["img_sname"] != '')
				{
					$img_str = '<img src="' . $comp_company_dir . '/' . $file_data["img_sname"] . '" alt="' . $file_data["subject"] . '" width="100px />';

					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
								<?=$img_str;?>
								<a href="<?=$local_diir;?>/bizstory/comp_set/company_download.php?cf_idx=<?=$file_data['cf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['cf_idx'];?>', '2')"><span>삭제</span></a>
			<?
				}
			?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname3">사업자등록증</label></th>
					<td>
						<div class="left">
							<input type="file" name="file_fname3" id="file_fname3" class="type_text type_file type_multi" title="파일 선택하기" />
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
						<div class="filewrap">
							<div class="file" id="file_fname3_view">
			<?
				$file_where = " and cf.comp_idx = '" . $code_comp . "' and cf.file_class = 'license'";
				$file_data = company_file_data('view', $file_where);

				if ($file_data["img_sname"] != '')
				{
					$img_str = '<img src="' . $comp_company_dir . '/' . $file_data["img_sname"] . '" alt="' . $file_data["subject"] . '" width="100px" />';

					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
								<?=$img_str;?>
								<a href="<?=$local_diir;?>/bizstory/comp_set/company_download.php?cf_idx=<?=$file_data['cf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['cf_idx'];?>', '3')"><span>삭제</span></a>
			<?
				}
			?>
							</div>
						</div>
					</td>
					<th><label for="file_fname4">통장사본</label></th>
					<td>
						<div class="left">
							<input type="file" name="file_fname4" id="file_fname4" class="type_text type_file type_multi" title="파일 선택하기" />
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
						<div class="filewrap">
							<div class="file" id="file_fname4_view">
			<?
				$file_where = " and cf.comp_idx = '" . $code_comp . "' and cf.file_class = 'bankbook'";
				$file_data = company_file_data('view', $file_where);

				if ($file_data["img_sname"] != '')
				{
					$img_str = '<img src="' . $comp_company_dir . '/' . $file_data["img_sname"] . '" alt="' . $file_data["subject"] . '" width="100px" />';

					$fsize = $file_data['img_size'];
					$fsize = byte_replace($fsize);
			?>
								<?=$img_str;?>
								<a href="<?=$local_diir;?>/bizstory/comp_set/company_download.php?cf_idx=<?=$file_data['cf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$file_data['cf_idx'];?>', '4')"><span>삭제</span></a>
			<?
				}
			?>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
	$btn_img = preview_images($code_comp, 'company_cert');
?>
			<div class="sub_frame">
				<h4>인증서
					<a href="javascript:void(0);" onclick="file_add_form('file_table');" class="btn_sml_violet"><span>추가</span></a></h4>
			</div>
			<div class="right">
				<?=$btn_img;?>
				<div id="loading2">문서 미리보기 로딩중입니다...</div>
			</div>

			<table class="tinytable write" summary="인증서를 관리합니다." id="file_table">
			<caption>인증서이미지</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
<?
	$file_where = " and cf.comp_idx = '" . $code_comp . "' and cf.file_class = 'certificate'";
	$file_list = company_file_data('list', $file_where, '', '', '');
	$cert_num = 5;
	foreach ($file_list as $file_k => $file_data)
	{
		if (is_array($file_data))
		{
?>
				<tr>
					<th><label for="file_fname<?=$cert_num;?>">인증서파일</label></th>
					<td colspan="3">
						<div class="left">
							인증서 제목 : <input type="text" name="file_subject<?=$cert_num;?>" id="file_subject<?=$cert_num;?>" class="type_text" title="인증서제목" value="<?=$file_data['subject'];?>" size="40" />
						</div>
						<div class="filewrap">
							<div class="file" id="file_fname<?=$cert_num;?>_view">
		<?
			if ($file_data["img_sname"] != '')
			{
				$img_str = '<img src="' . $comp_company_dir . '/' . $file_data["img_sname"] . '" alt="' . $file_data["subject"] . '" width="100px" />';

				$fsize = $file_data['img_size'];
				$fsize = byte_replace($fsize);
		?>
								<?=$img_str;?>
								<a href="<?=$local_diir;?>/bizstory/comp_set/company_download.php?cf_idx=<?=$file_data['cf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								<a href="javascript:void(0);" class="btn_con" onclick="file_delete_chk('<?=$file_data['cf_idx'];?>', '<?=$cert_num;?>')"><span>삭제</span></a>
		<?
			}
		?>
							</div>
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
					</td>
				</tr>
<?
			$cert_num++;
		}
	}
?>
				<tr>
					<th><label for="file_subject<?=$cert_num;?>">인증서제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="file_subject<?=$cert_num;?>" id="file_subject<?=$cert_num;?>" class="type_text" title="인증서제목" value="" size="40" />
							<input type="hidden" name="file_class<?=$cert_num;?>" id="file_class<?=$cert_num;?>" value="certificate" />
						</div>
					</td>
					<th><label for="file_fname<?=$cert_num;?>">인증서파일</label></th>
					<td>
						<div class="filewrap">
							<div class="file" id="file_fname<?=$cert_num;?>_view">
								<input type="file" name="file_fname<?=$cert_num;?>" id="file_fname<?=$cert_num;?>" class="type_text type_file type_multi" title="파일 선택하기" />
							</div>
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
			<input type="hidden" id="upload_fnum" name="upload_fnum" value="<?=$cert_num;?>" />

			<div class="section">
				<div class="fr">
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>
				</div>
			</div>
		</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_comp_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	file_setting('file_fname1', 'company', '1', '<?=$file_multi_size;?>', '');
	file_setting('file_fname2', 'company', '2', '<?=$file_multi_size;?>', '');
	file_setting('file_fname3', 'company', '3', '<?=$file_multi_size;?>', '');
	file_setting('file_fname4', 'company', '4', '<?=$file_multi_size;?>', '');
	file_setting('file_fname<?=$cert_num;?>', 'company', '<?=$cert_num;?>', '<?=$file_multi_size;?>', '');

//------------------------------------ 파일추가
	function file_add_form(chk_name)
	{
		var sort = $('#upload_fnum').val();
		var chk_num = parseInt(sort) + 1;

		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$lcoal_dir;?>/bizstory/comp_set/company_add.php',
			data: {'sort':chk_num},
			success: function(msg) {
				$("#" + chk_name).append(msg);
				$('#upload_fnum').val(chk_num);
				file_setting('file_fname' + chk_num, 'company', chk_num, '<?=$file_multi_size;?>', '');
			}
		});
	}

//------------------------------------ Save
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		oEditors.getById["post_comp_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_comp_remark').val(); // 내용
		chk_title = $('#post_comp_remark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type : 'post', dataType: 'json', url: "<?=$link_ok;?>",
				data : $('#postform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y") check_auth_popup('정상적으로 처리되었습니다.');
					else check_auth_popup(msg.error_string);
				}
			});
		}
		return false;
	}
//]]>
</script>