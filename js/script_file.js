
/*
	위치 : 업로드파일 관련 자바스크립트
	수정 : 1차 : 2013.01.22 
		  2차 : 2023.08.08
*/


	$(document).ready(function() {
	// File Style
		$(".type_basic").filestyle({
			image: "/common/upload/file_submit.gif",
			imagewidth : 82,
			imageheight : 29
		});
	});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 업로드파일설정

	// 파일 리스트 번호
    var index = 0;
    // 등록할 전체 파일 사이즈
    var total_file_size = 0;
    // 파일 리스트
    var file_list = new Array();
    // 파일 사이즈 리스트
    var file_size_list = new Array();
    // 등록 가능한 파일 사이즈 MB
    var upload_size = 10;
    // 등록 가능한 총 파일 사이즈 MB
    var maxupload_size = 500;
	
	var file_name = '';
	var upload_file_name = new Array();
	var upload_file_add_name = new Array();
	var upload_file_ext = new Array();
	var upload_file_sort = new Array();
	var upload_file_total = 0;
  
  
	function file_setting(input_upload_name, input_add_name, sort, max_size, upload_ext)
	{	
		if(!input_upload_name || input_upload_name == '' || input_upload_name == undefined){
			return false;
		}
		
		file_name = input_upload_name;
		upload_file_name[file_name] = input_upload_name;
		upload_file_add_name[file_name] = input_add_name;
		
		upload_file_ext[file_name] = upload_ext;
		maxupload_size[file_name] = max_size;
		upload_file_sort[file_name] = parseInt(sort);

		if(sort == 1){
			file_upload_setting(input_upload_name);			
		}else{
			mutil_file_upload_setting(input_upload_name);
		}

	}
	
	
	function file_upload_setting(upload_name){
		
		upload_file_total = 0;

		$('#'+upload_name).css('display', 'none');

		var html = "<style>.fileDrop{border:1px #ccc solid;margin:5px 0px;padding:0;min-height: 23px;width: 50%; background-color:#fff;}</style>";
			html += "<div style='display: flex;padding-bottom: 10px;width:100%;'>";
			html += "<div style='padding-right: 5px;'><a href='javascript:void(0);' id='btn_file_select_" + upload_name+ "' class='btn_big_green'><span>파일업로드</span></a></div>";
			html += "<div id='fileDrop_" + upload_name+ "' data='" + upload_name+ "' class='fileDrop'></div>";
			html += "</div>";
		
		$('#'+upload_name).after(html);

		$('#btn_file_select_'+upload_name).on('click',function (e) {
			e.preventDefault();
			$('#'+upload_name).click();
		});

		
		$('#'+upload_name).on('change', function(){
			 select_file($('#'+upload_name)[0].files); 
			 file_name = $(this).attr('id');
		});
	}

	function mutil_file_upload_setting(upload_name){
		
		var file_upload_num = parseInt($('#file_upload_num').val());
		if(!file_upload_num || file_upload_num == '' || file_upload_num == undefined) file_upload_num = 0;

		upload_file_total += file_upload_num;
		index = file_upload_num;

		$('#'+upload_name).css('display', 'none');

		var html = "<style>.mfileDrop{border:1px #ccc solid;margin:5px 0px;padding:0;min-height: 80px;width: 100%; background-color:#fff;} .mfileDrop #file_drop_tip{text-align: center;margin-top: 25px;font-size: 12px;}</style>";
			html += "<div id='fileDrop_" + upload_name+ "' class='mfileDrop' data='" + upload_name+ "'><p id='file_drop_tip'>첨부하실 파일을 여기에 끌어 놓으세요.</p></div>";
			html += "<a href='javascript:void(0);' id='btn_file_select_" + upload_name+ "' class='btn_big_green'><span>파일업로드</span></a>";

		$('#'+upload_name).after(html);

		$('#btn_file_select_'+upload_name).on('click',function (e) {
			e.preventDefault();
			$('#'+upload_name).click();
		});

		
		$('#'+upload_name).on('change', function(){
			 select_file($('#'+upload_name)[0].files); 
			 file_name = $(this).attr('id');
		});

		file_drop_down(upload_name);
	}

	// 파일 드롭 다운
    function file_drop_down(fname){
        var dropZone = $("#fileDrop_"+fname);
		
        //Drag기능
        dropZone.on('dragenter',function(e){
            e.stopPropagation();
            e.preventDefault();

            // 드롭다운 영역 css
            dropZone.css('background-color','#E3F2FC');
        });
        dropZone.on('dragleave',function(e){
            e.stopPropagation();
            e.preventDefault();			
            // 드롭다운 영역 css
            dropZone.css('background-color','#FFFFFF');
        });
        dropZone.on('dragover',function(e){
            e.stopPropagation();
            e.preventDefault();
            // 드롭다운 영역 css
            dropZone.css('background-color','#E3F2FC');
        });
        dropZone.on('drop',function(e){
            e.preventDefault();
            // 드롭다운 영역 css
            dropZone.css('background-color','#FFFFFF');
            
            var files = e.originalEvent.dataTransfer.files;
            if(files != null){
                if(files.length < 1){
                    check_auth_popup("폴더 업로드 불가 합니다.");
                    return;
                }				
				file_name = dropZone.attr('data');
                select_file(files)
            }else{
                check_auth_popup("ERROR");
            }
        });
    }
  
    // 파일 선택시
    function select_file(file_object){
		$("#loading").fadeIn('slow');

        var files = null;
  
        if(file_object != null){
            // 파일 Drag 이용하여 등록시
            files = file_object;
        }else{
			files = $('#'+file_name)[0].files;
        }
		
		

        // 다중파일 등록
        if(files != null){
			 var formData = new FormData();
				formData.append('folder', '/data/tmp');
				formData.append('upload_name', upload_file_name[file_name]);
				formData.append('add_name', upload_file_add_name[file_name]);
				formData.append('file_max', maxupload_size[file_name]);
				formData.append('upload_ext', upload_file_ext[file_name]);
			
            for(var i = 0; i < files.length; i++){
				
                // 파일 이름
                var file_info_name = files[i].name;
                var file_info_name_arr = file_info_name.split("\.");
                // 확장자
                var ext = file_info_name_arr[file_info_name_arr.length - 1];
                // 파일 사이즈(단위 :MB)
                //var fileSize = files[i].size / 1024 / 1024;
				var file_size = files[i].size / 1024 / 1024 / 1024;
                 
                if($.inArray(ext, ['exe', 'bat', 'sh', 'java', 'jsp', 'html', 'js', 'css', 'xml']) >= 0){
                    // 확장자 체크
					$("#loading").fadeOut('slow');
                    check_auth_popup("등록 불가 확장자 입니다.");
                    break;
                }else if(file_size > upload_size){
                    // 파일 사이즈 체크
					$("#loading").fadeOut('slow');
                    check_auth_popup("업로드 가능 용량은 " + upload_size + " MB 입니다.");
                    break;				
                }else{
					
					var filedata = files[i];
					formData.append('filedata', filedata);

					$.ajax({
						type: "POST",
						dataType: 'json',
						enctype: 'multipart/form-data',
						url: "/common/upload/upload_temp_ok.php",
						data: formData,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 600000,
						success: function (data) {
							//console.log(data);
							if(data.success_chk = 'Y')
							{
								var file_info = data.file_info;
								 // 전체 파일 사이즈
								total_file_size += file_size;
								 
								// 파일 배열에 넣기
								file_list[index] = files[i];
								 
								// 파일 사이즈 배열에 넣기
								file_size_list[index] = file_size;
			  
								// 업로드 파일 목록 생성
								if(upload_file_sort[file_name] == 1){
									add_file_list(index, file_info);
								}else{
									mutil_add_file_list(index, file_info);
								}
			  
								// 파일 번호 증가
								index++;
							}
						},
						error: function (e) {
							check_auth_popup("파일 업로드를 실패하였습니다.");
						}
					});
                }
            }

			if($('#'+upload_file_name).val() !=''){
				$('#file_fname').val('');
			}

			$("#loading").fadeOut('slow');

        }else{
            check_auth_popup("ERROR");
        }

    }
  
    // 업로드 파일 목록 생성
    function add_file_list(fIndex, file_info){

		var file_info_array = file_info.split('|');
		if (file_info_array[0] == 'N')
		{
			check_auth_popup("지원하지 않는 확장자입니다.");
		}
		else
		{	
			var trIndex = 1;
			var set_time_out = 0;
			if($("input[name='"+file_name+"_save_name']").length > 0){
				delete_file(trIndex, file_info_array[2], file_name);
				set_time_out = 500;
			}
			
			var fup_name = file_name;

			setTimeout( () => {
				var html = "";
					html += "<div id='fileTr_" + file_name +"_"+ trIndex + "' style='padding-left: 5px;'>";
					html += "    <p style='font-size:12px;'>";
					html +=         file_info_array[0] + " (" + file_info_array[1] + "Byte) ";
					html += '		<a href="javascript:void(0);" onclick="select_delete_file(' + trIndex + ', \'' + file_info_array[2] + '\', \''+file_name+'\'); return false;" class="btn_con_red"><span>삭제</span></a>';
					html += '		<input type="hidden" name="' + fup_name + '_save_name" value="' + file_info_array[2] + '" />';
					html += '		<input type="hidden" name="' + fup_name + '_file_name" value="' + file_info_array[0] + '" />';
					html += '		<input type="hidden" name="' + fup_name + '_file_size" value="' + file_info_array[1] + '" />';
					html += '		<input type="hidden" name="' + fup_name + '_file_type" value="' + file_info_array[3] + '" />';
					html += '		<input type="hidden" name="' + fup_name + '_file_ext"  value="' + file_info_array[4] + '" />';
					html += "    </p>"
					html += "</div>"
				
				
				$('#fileDrop_'+file_name).append(html);
				upload_file_total++;
			}, set_time_out);
			
		}
    }

	// 업로드 파일 목록 생성
    function mutil_add_file_list(fIndex, file_info){

		var file_info_array = file_info.split('|');
		if (file_info_array[0] == 'N')
		{
			check_auth_popup("지원하지 않는 확장자입니다.");
		}
		else
		{
			if($("#fileDrop_"+file_name+" #file_drop_tip").length > 0) $("#fileDrop_"+file_name+" #file_drop_tip").hide();

			var fup_name = file_name + (fIndex+1);
			var trIndex = fIndex;

			var html = "";
				html += "<div id='fileTr_" + file_name +"_"+ trIndex + "' style='display: contents;'>";
				html += "    <p style='padding: 2px 5px;font-size:12px;'>";
				html +=         file_info_array[0] + " (" + file_info_array[1] + "Byte) ";
				html += '		<a href="javascript:void(0);" onclick="select_delete_file(' + trIndex + ', \'' + file_info_array[2] + '\', \''+file_name+'\'); return false;" class="btn_con_red"><span>삭제</span></a>';
				html += '		<input type="hidden" name="' + fup_name + '_save_name" value="' + file_info_array[2] + '" />';
				html += '		<input type="hidden" name="' + fup_name + '_file_name" value="' + file_info_array[0] + '" />';
				html += '		<input type="hidden" name="' + fup_name + '_file_size" value="' + file_info_array[1] + '" />';
				html += '		<input type="hidden" name="' + fup_name + '_file_type" value="' + file_info_array[3] + '" />';
				html += '		<input type="hidden" name="' + fup_name + '_file_ext"  value="' + file_info_array[4] + '" />';
				html += "    </p>"
				html += "</div>"
			
			
			$('#fileDrop_'+file_name).append(html);
			$('#file_upload_num').val(fIndex+1);
			upload_file_total++;
		}
    }
	
	// 업로드 선택 파일 삭제
    function delete_file(fIndex, file_save_name, input_file_name){
		
		$.ajax({
			type: 'post', dataType: 'json', url:'/common/upload/upload_temp_delete.php',
			data:{'upload_name':input_file_name,'save_name':file_save_name},
			success:function(data) {
				if (data.success_chk == "Y")
				{
					// 전체 파일 사이즈 수정
					total_file_size -= file_size_list[fIndex];
					 
					// 파일 배열에서 삭제
					delete file_list[fIndex];
					 
					// 파일 사이즈 배열 삭제
					delete file_size_list[fIndex];
					 
					// 업로드 파일 테이블 목록에서 삭제
					$("#fileTr_" +input_file_name + "_" + fIndex).remove();

					if($('#fileDrop_'+input_file_name).find('div').length <1)
					{
						  if($('#fileDrop_'+input_file_name+" #file_drop_tip").length > 0) $('#fileDrop_'+input_file_name+' #file_drop_tip').show();
					}
					
					upload_file_total--;
					return true;
				}
				else 
				{
					check_auth_popup('업로드 파일 삭제 실패하였습니다.');
					return false;
				}
			}
		});

        
    }

    // 업로드 선택 파일 삭제
    function select_delete_file(trIndex, file_save_name, input_file_name){
		
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$("#loading").fadeIn('slow');			
			delete_file(trIndex, file_save_name, input_file_name);
		}
    }

//--------------------------------폼에서 파일삭제
	function file_form_delete(idx, sort)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: {'sub_type':'file_delete', 'idx':idx, 'sort':sort},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						//check_auth_popup('정상적으로 처리되었습니다.');
						$('#file_fname' + sort + '_view').html('');
						upload_file_total--;
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

//------------------------------------ 파일추가
	function file_form_add(add_name, file_max, file_ext)
	{
		var chk_num = parseInt(file_chk + 1);

		var string_input  = '<tr>';
			string_input += '	<th><label for="file_subject' + chk_num + '">추가파일</label></th>';
			string_input += '	<td>';
			string_input += '		<div class="left file">';
			string_input += '			<input type="text" name="file_subject' + chk_num + '" id="file_subject' + chk_num + '" class="type_text" placeholder="추가파일제목을 입력하세요." style="width:59%" />';
			string_input += '			<input type="file" name="file_fname' + chk_num + '" id="file_fname' + chk_num + '" class="type_text type_file type_multi" title="추가파일 선택하기" />';
			string_input += '		</div>';
			string_input += '		<div class="left file" id="file_fname' + chk_num + '_view"></div>';
			string_input += '	</td>';
			string_input += '	<td>&nbsp;</td>';
			string_input += '</tr>';

		$("#file_table").append(string_input);
		$('#file_upload_num').val(chk_num);
		file_setting('file_fname' + chk_num, add_name, 1, file_max, file_ext);
	}