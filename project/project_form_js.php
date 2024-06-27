<?
$link_up   = $local_dir . "/bizstory/comp_set/project_class_up.php";   // 상위메뉴 저장
?>
<script type="text/javascript">
//<![CDATA[
	var oEditors = []; // 에디터관련
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});
    
    $(function() {
        up_menu_change('3', '');
        
        try {
           $(".datepicker").datepicker(); // 날짜
        } catch(e) {}
        
    });
    

//------------------------------------ 메뉴단계변경
    function up_menu_change(menu_depth, idx)
    {
        $.ajax({
            type    : 'post', dataType: 'html', url: '<?=$link_up?>',
            data : {
                "menu_depth" : menu_depth,
                "code_idx" : idx
            },
            success : function(msg) {
                $('#up_menu_list').html(msg);
            }
        });
    }

//------------------------------------ 선택된 메뉴 하위메뉴
    function down_menu_change(menu_depth, sel_depth, idx, is_disabled)
    {
        $.ajax({
            type    : 'post', dataType: 'html', url: '<?=$link_up?>',
            data     : {
                "menu_depth" : menu_depth,
                "sel_depth" : sel_depth,
                "code_idx" : idx
            },
            success : function(msg) {
                $('#up_menu_list').html(msg);
                if ($('#menu2_code').val() != '') {
                    $("#chk_menu2").val( $('#menu2_code').val() );
                }
                if (is_disabled == true) {
                    $("#chk_menu1").prop('disabled', true).css('background', '#afafaf');
                    $("#chk_menu2").prop('disabled', true).css('background', '#afafaf');
                    $("#post_project_code").prop('readonly', true).css('background', '#afafaf').attr('onblur', '');
                }
            }
        });
    }

// 담당자 - 선택
	function select_member()
	{
		var mem_idx  = document.getElementsByName('project_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = '';

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx = mem_idx[i].value;
				}
				else
				{
					total_member_idx += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}

		var charge_idx = $('#post_old_charge_idx').val();
		if (charge_idx != '')
		{
			total_member_idx = charge_idx + ',' + total_member_idx;
		}
		$('#post_charge_idx').val(total_member_idx);
	}

<?
	echo $charge_view['change_script'];
?>

// 등록, 수정
    var code_chk = false;
    
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 오늘날짜 이전은 안됨
<?
	if ($pro_idx == '') {
?>
        if ($("#post_project_code").val() != '' && code_chk == false) {
            chk_total += '프로젝트 코드가 중복됩니다.<br />';
            action_num++;
        }
        
		chk_value = $('#post_deadline_date').val();
		chk_value = chk_value.replace('-', '');
		chk_value = chk_value.replace('-', '');
		if (chk_value < <?=date('Ymd');?>)
		{
			chk_total = chk_total + '이전 날짜는 선택하실 수 없습니다.<br />';
			action_num++;
		}
<?
	}
?>
	// 종료일이 시작일보다 크도록
		var chk_start = $('#post_start_date').val();
		chk_start = chk_start.replace('-', '');
		chk_start = chk_start.replace('-', '');

		var chk_end = $('#post_deadline_date').val();
		chk_end = chk_end.replace('-', '');
		chk_end = chk_end.replace('-', '');

		if (chk_start > chk_end)
		{
			chk_total = chk_total + '종료일은 시작일보다 작을 수 없습니다.<br />';
			action_num++;
		}

		chk_value = $('#post_apply_idx').val(); // 책임자
		chk_title = $('#post_apply_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		select_member();

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
		    $("#menu1").val($("#chk_menu1 option:selected").text());
            $("#menu2").val($("#chk_menu2 option:selected").text());
            if($("#project_info_idx").val() == '') {
                $("#project_sub_type").val('post');
            } else {
                $("#project_sub_type").val('modify');
            }
            
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#project_info_idx').val(msg.pro_idx);
						project_file_check();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}
	

    function project_code_check() {
        if ($("#post_project_code").val() == '') {
            code_chk = true;
        } else {
            $("#menu1_code").val($("#chk_menu1 option:selected").val());
            $("#menu2_code").val($("#chk_menu2 option:selected").val());
            
            $("#project_sub_type").val('project_code_check');
                        
            $.ajax({
                type: 'post', dataType: 'json', url: link_ok,
                data: $('#postform').serialize(),
                success: function(msg) {
                    if (msg.success_chk == 'Y') {
                        code_chk = true;
                    } else {
                        code_chk = false;
                        alert("프로젝트 코드가 중복됩니다.\n확인 후 다시 입력해 주십시오.");
                    }
                }
            });
        }
    }
//]]>
</script>