<?
/*
	수정 : 2012.08.27
	위치 : 고객관리 > 거래처목록 - 보기 - 메모등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	
	if($cim_idx != ""){
		$where = " and cim.cim_idx = '" . $cim_idx . "'";
		$data = client_memo_data('view', $where);

		$file_where = " and cimf.cim_idx = '" . $cim_idx . "'";
		$file_list = client_memo_file_data('list', $file_where, '', '', '');

		$file_chk = query_view("select max(sort) as sort from client_memo_file where cim_idx = '" . $cim_idx . "'");
		$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

		$file_upload_num = $file_sort;
		$file_chk_num    = $file_upload_num + 1;
	}else{
		$file_list = array();
		$file_upload_num = 0;
		$file_chk_num    = 1;
	}
?>
<div class="new_report">
	<form name="memoform" id="memoform" method="post" action="<?=$this_page;?>" onsubmit="return check_memo_form('<?=$cim_idx;?>')">
		<input type="hidden" name="comp_idx" value="<?=$code_comp;?>" />
		<input type="hidden" name="part_idx" value="<?=$code_part;?>" />
		<input type="hidden" name="ci_idx"   value="<?=$ci_idx;?>" />
		<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />

		<div class="form">
			<textarea name="param[remark]" id="memopost_remark" cols="50" rows="10" title="메모내용을 입력하세요."><?=$data['remark'];?></textarea>

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
					<li id="file_fname_<?=$file_chk;?>_view" class="org_file">
						<a href="<?=$local_diir;?>/bizstory/work/client_view_memo_download.php?cimf_idx=<?=$file_data['cimf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
						<a href="javascript:void(0);" class="btn_con" onclick="memo_file_delete('<?=$file_data['cimf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
					</li>
<?
		}
	}
?>
					</ul>
				</div>
			</div>

		</div>
		<div class="action">
	<?
		if ($cim_idx == '') {
	?>
			<span class="btn_big_green"><input type="submit" value="등록" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="memo_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn_big_blue"><input type="submit" value="수정" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="memo_modify_form('close', '<?=$cim_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="cim_idx"  value="<?=$cim_idx;?>" />
	<?
		}
	?>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	file_chk_num = <?=$file_chk_num;?>;
	//file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'client_memo', '');
	file_setting('file_fname', 'client_memo', '', '<?=$file_multi_size;?>', '');

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_memo,
		elPlaceHolder: "memopost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {
			bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});
//]]>
</script>