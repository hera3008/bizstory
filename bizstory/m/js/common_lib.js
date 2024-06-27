	var local_dir = '../..';
	
//------------------------------------ 지사별 목록
	function part_information(code_part, select_class, field_id, field_value, select_type)
	{
		if (code_part == "") code_part = $('#post_part_idx').val();
		var shsgroup = $('#search_shsgroup').val();

		$.ajax({
			type: "post", cache: false, async: true, dataType : "json", url: local_dir + '/bizstory/comp_set/part_information.php',
			data: {
				"code_part" : code_part,
				"select_class" : select_class,
				"field_value" : field_value,
				"select_type" : select_type,
				"shsgroup" : shsgroup
			},
			success  : function(msg) {
				$('#' + field_id).empty();
				if (select_type == 'select')
				{
					$('#' + field_id).append('<option value="all">' + $('#' + field_id).attr('title') + '</option>');
				}
				else if (select_type == 'select_allno')
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}
				else
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}

				if (msg.success_chk == "Y")
				{
					$.each(msg.result_data, function() {

						var empty_str = ''
						for (var ii = 2; ii <= this.menu_dpeth; ii++)
						{
							empty_str = empty_str + '&nbsp;&nbsp;&nbsp;';
						}

						if (this.selected == 'Y')
						{
							$('#' + field_id).append('<option value= ' + this.idx + ' selected="selected">' + empty_str + this.name + '</option>');
						}
						else
						{
							$('#' + field_id).append('<option value= ' + this.idx + '>' + empty_str + this.name + '</option>');
						}
					});
				}
				else
				{
					if (msg.result_data != '')
					{
						check_auth_popup(msg.result_data);
					}
				}
			}
		});
	}
//------------------------------------ 문서 미리보기 결과
//success -> 변환 상태에 대한 성공여부를 나타낸다. ‘Y' 성공 / 'N' 실패
//index_url -> 변환된 결과를 인덱스한 html 파일의 url 값을 나타낸다.
//image_url -> 변환한 이미지에 저장되어 있는 위치의 url 값을 나타낸다.
//page_count -> 총 생성되는 전체 이미지 수를 나타낸다.
	function file_preview_result(agent_code, user_id, pre_idx, file_name)
	{
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$('html, body').animate({scrollTop:0}, 500);
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://121.88.4.88:8080/convert_result.php', jsonp : 'callback',
			data: { 'job_id' : 'demo', 'agent_code' : agent_code, 'user_id' : user_id, 'key' : pre_idx },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				if (msg.success == 'Y')
				{
					file_preview_html(msg.index_url, msg.image_url, msg.page_count, file_name);
				}
				else
				{
					alert(msg.err_desc + '(' + msg.err_code + ')');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}
	

//------------------------------------ 문서 미리보기 페이지구성
	function file_preview_html(index_url, image_url, page_count, file_name)
	{
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_html.php',
			data: {
				'index_url' : index_url,
				'image_url' : image_url,
				'page_count' : page_count,
				'file_name' : file_name },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('#popup_file_preview').html(msg);
				$('#document_preview a').lightBox();
				if (page_count > 0)
				{
					$('#image_1').click();
				}
				else
				{
					alert('아직 제공되지 않습니다.');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 이미지 미리보기
	function file_preview_images(f_class, idx, file_num)
	{
	// Loading - document
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_images.php',
			data: { 'idx' : idx, 'f_class' : f_class },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('#popup_file_preview').html(msg);
				//alert(msg);
				$('#images_preview a').lightBox();
				if (file_num > 0)
				{
					$('#img_image_1').click();
				}
				else
				{
					alert('이미지파일이 없습니다.');
				}
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

//------------------------------------ 문서 미리보기 - 변환하지 않는
	function file_preview_other(f_class, idx, file_ext)
	{
	// Loading - document
		$("#loading2").fadeIn('slow');
		$(window).resize(function(){
			$('#loading2').css({
				position:'absolute',
				left: ($(window).width() - $('#loading2').outerWidth())/2,
				top: ($(window).height() - $('#loading2').outerHeight())/2
			});
		});
		$(window).resize();

		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/include/file_preview_other.php',
			data: { 'idx' : idx, 'f_class' : f_class, 'file_ext' : file_ext },
			beforeSend: function(){ $("#loading2").fadeIn('slow'); },
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			},
			complete: function(){
				$("#loading2").fadeOut('slow');
			}
		});
	}







