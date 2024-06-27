<?
	//$set_file_class    = 'OUT';
	//$set_filecenter_yn = '0';

	//$set_file_class    = 'OUT';
	//$set_filecenter_yn = '1'; // 파일센터사용

	//$set_file_class    = 'IN';
	
	switch($fmode) {
		case 'project':
		case 'work':
			
		if (strlen(stristr($mybrowser_val_val, 'IE')) > 0)
		{
?>
<script type="text/javascript" for="CHXFile" event="ServerReply(chk_idx)">
	$('#project_idx_common').val(chk_idx);
	filecenter_complete(chk_idx);
	popupform_close2();
</script>
<?
		}
?>
<script type="text/javascript">
//<![CDATA[
<?
		
// 프로젝트관련
	if ($smode == 'project')
	{
		$file_comp = $_SESSION[$sess_str . '_comp_idx'];
		$file_part = $_SESSION[$sess_str . '_part_idx'];
		$file_mem  = $_SESSION[$sess_str . '_mem_idx'];

		if ($set_file_class == 'OUT') // 외부서버일 경우
		{
			if ($set_filecenter_yn == '1') // 파일센터 사용할 경우
			{
?>
// 외부서버, 파일센터
	function project_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'post_filecenter'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'pro_idx':pro_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", async: false, dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'project_file', 'table_idx':pro_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						close_data_form();
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}

// 업무등록 - 외부서버, 파일센터
	function project_work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'work_filecenter'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'wi_idx':wi_idx, 'pro_idx':pro_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", async: false, dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						work_insert_close();
						class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
			}
			else
			{
?>
// 외부서버
	function project_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'post_out'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'pro_idx':pro_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", async: false, dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'project_file', 'table_idx':pro_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						close_data_form();
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}

// 업무등록 - 외부서버
	function project_work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'work_out'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'wi_idx':wi_idx, 'pro_idx':pro_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", async: false, dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						work_insert_close();
						class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
			}
		}
		else
		{
?>
// 내부서버
	function project_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'post', async: false, dataType: 'json', url: '<?=$local_dir;?>/bizstory/project/project_ok.php',
			data: { 'sub_type':'post_file', 'idx_common':idx_common, 'pro_idx':pro_idx },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'project_file', 'table_idx':pro_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						close_data_form();
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}

// 업무등록
	function project_work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var pro_idx    = $('#project_info_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'post', async: false, dataType: 'json', url: '<?=$local_dir;?>/bizstory/project/project_view_work_ok.php',
			data: {
				'sub_type':'work_file'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx, 'wi_idx':wi_idx, 'pro_idx':pro_idx },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						work_insert_close();
						class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
		}

	} 
	// 업무 관련
	else if ($smode == 'work')
	{
		$file_comp = $_SESSION[$sess_str . '_comp_idx'];
		$file_part = $_SESSION[$sess_str . '_part_idx'];
		$file_mem  = $_SESSION[$sess_str . '_mem_idx'];

		if ($set_file_class == 'OUT') // 외부서버일 경우
		{
			if ($set_filecenter_yn == '1') // 파일센터 사용할 경우
			{
?>
// 업무등록 - 외부서버, 파일센터
	function work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();
		//alert(idx_common + "\n" + wi_idx + "\n" + table_name + "\n" + table_idx);
		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/work_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'work_filecenter'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'wi_idx':wi_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						//work_insert_close();
						//class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
			}
			else
			{
?>
// 업무등록 - 외부서버
	function work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type':'work_out'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx
				, 'wi_idx':wi_idx, 'comp_idx':'<?=$file_comp;?>', 'part_idx':'<?=$file_part;?>', 'mem_idx':'<?=$file_mem;?>' },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						//work_insert_close();
						//class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
			}
		}
		else
		{
?>

// 업무등록
	function work_file_check()
	{
		var idx_common = $('#project_idx_common').val();
		var wi_idx     = $('#project_wi_idx').val();
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'post', async: false, dataType: 'json', url: '<?=$local_dir;?>/bizstory/work/work_view_work_ok.php',
			data: {
				'sub_type':'post_file'
				, 'idx_common':idx_common, 'table_name':table_name, 'table_idx':table_idx, 'wi_idx':wi_idx },
			success: function(msg) {
			// 파일미리보기
				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
					data: {
						'table_name':'work_file', 'table_idx':wi_idx, 'code_comp':'<?=$file_comp;?>', 'code_part':'<?=$file_part;?>', 'code_mem':'<?=$file_mem;?>' },
					success: function(msg) {
						$("#preview_file_result").html(msg);
						//work_insert_close();
						//class_list_data('');
					},
					complete: function(){
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				});
			}
		});
	}
<?
		}

	}
?>
//------------------------------------ 파일저장이후
	function filecenter_complete(chk_idx)
	{
		var table_name = $('#filecenter_table_name').val();
		var table_idx  = $('#filecenter_table_idx').val();

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'post', async: false, dataType: 'json', url: '<?=$local_dir;?>/bizstory/filecenter/biz/filecenter_complete.php',
			data: { 'idx_common':chk_idx, 'table_name':table_name, 'table_idx':table_idx },
			async: false,
			success: function(json) {
				
				if (json.success_chk == 'Y') {
					var html = '';
					
					$.each(json.file_list, function(idx, item) {
						
						html += set_file_frame(idx, item);						
					});
					
					$("#file_fname_add_view").html(html);
					
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			},
			complete: function(){
			
			}
		});
	}
	
	function set_file_frame(idx, item) {
		var html = '';
		html += '<li>' + item.img_fname + '(' + number_format(item.img_size) + 'Byte)\n'
			+ '<a href="javascript:void(0)" class="btn_con" onclick="filecenter_delete(\'' + item.idx + '\')"><span>삭제</span></a>'
			+ '<input type="hidden" name="filecenter_' + idx + '_save_name" value="' + item.img_sname + '"/>'
			+ '<input type="hidden" name="filecenter_' + idx + '_file_name" value="' + item.img_fname + '"/>'
			+ '<input type="hidden" name="filecenter_' + idx + '_file_size" value="' + item.img_size + '"/>'
			+ '<input type="hidden" name="filecenter_' + idx + '_file_type" value="' + item.img_type + '"/>'
			+ '<input type="hidden" name="filecenter_' + idx + '_file_ext" value="' + item.img_ext + '"/>'
			+ '</li>';
		return html;
	}

//------------------------------------ 선택파일삭제
	function filecenter_delete(idx)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			var table_name = $('#filecenter_table_name').val();
			var table_idx  = $('#filecenter_table_idx').val();

			$.ajax({
				type: 'post', async: false, dataType: 'json', url:'<?=$local_dir;?>/bizstory/filecenter/biz/filecenter_delete.php',
				data:{'table_name':table_name, 'table_idx':table_idx, 'idx':idx},
				async: false,
				success:function(json) {
				
					if (json.success_chk == 'Y') {
						var html = '';
						
						$.each(json.file_list, function(idx, item) {
							
							html += set_file_frame(item);						
						});
						
						$("#file_fname_add_view").html(html);
						
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").fadeOut("slow");
					}
				},
				complete: function(){
				}
			});
		}
		return false;
	}

//]]>
</script>
<?
	break;
	
	default:
		break;
	}
?>
