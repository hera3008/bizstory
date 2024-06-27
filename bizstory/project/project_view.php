<?
/*
	생성 : 2012.12.20
	수정 : 2013.05.03
	위치 : 업무폴더 > 프로젝트관리 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$code_part    = search_company_part($code_part);
	$code_mem     = $_SESSION[$sess_str . '_mem_idx'];
	$code_level   = $_SESSION[$sess_str . '_ubstory_level'];
	$code_ubstory = $_SESSION[$sess_str . '_ubstory_yn'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];
	$pro_idx          = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
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
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup("");
		//]]>
		</script>
		';
	}

	if ($form_chk == 'Y')
	{
		$where = " and pro.pro_idx = '" .  $pro_idx . "'";
		$data = project_list_info('view', $where);
		$data = project_list_data($data, $pro_idx);

		$file_where = " and prof.pro_idx = '" . $pro_idx . "'";
		$file_list = project_file_data('list', $file_where, '', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="pro_idx"  id="view_pro_idx"  value="<?=$pro_idx;?>" />
			<input type="hidden" name="force_yn" id="view_force_yn" value="<?=$data['force_yn']?>" />

			<fieldset>
				<legend class="blind">프로젝트정보</legend>
				<table class="tinytable view" summary="등록한 업무에 대한 상세정보입니다.">
				<caption>프로젝트정보</caption>
				<colgroup>
					<col width="80px" />
					<col />
					<col width="100px" />
					<col width="100px" />
					<col width="60px" />
					<col width="200px" />
				</colgroup>
				<tbody>
					<tr>
						<th>제목</th>
						<td colspan="5">
							<div class="left">
								<span class="pro_code">[
                        <?if ($data['menu1'] == '') {?><?=$data['project_code'];?><?}else{
                            
                           echo $data['menu1'] . '-';
                           
                           if ($data['menu2'] != '') echo $data['menu2'] . '-';
                           
                           echo $data['project_code'];
                         
                        }?>     
                                ]</span>&nbsp;<strong><?=$data['subject'];?></strong>
								<?=$data['open_img'];?>
								<?=$data['file_str'];?>
							</div>
						</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td>
							<div class="left"><?=$data['total_charge_str'];?></div>
						</td>
						<th>프로젝트 책임자</th>
						<td>
							<div class="left"><?=$data['apply_name'];?></div>
						</td>
						<th>등록자</th>
						<td>
							<div class="left"><?=$data['reg_name_view'];?>(<?=$data['reg_date'];?>)</div>
						</td>
					</tr>
					<tr>
						<th>기한</th>
						<td colspan="5">
							<div class="left"><?=$data['start_date_str'];?> ~ <?=$data['deadline_date_str'];?></div>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td colspan="5">
							<div class="left">
								<p class="memo">
									<?=$data['remark'];?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td colspan="5">
							<div class="left file">
					<?
						if ($file_list['total_num'] > 0) {
					?>
								<ul>
					<?
							foreach ($file_list as $file_k => $file_data)
							{
								if (is_array($file_data))
								{
									$img_path = $file_data['img_path'];
									$in_out   = $file_data['in_out'];
									$fsize    = $file_data['img_size'];
									$fsize    = byte_replace($fsize);

									$btn_str = preview_files($file_data['prof_idx'], 'project');

									if ($in_out == 'CENTER')
									{
										$down_url = $set_filecneter_url . '/biz/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}
									else if ($in_out == 'OUT')
									{
										$down_url = $set_filecneter_url . '/biz/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}
									else
									{
										$down_url = $local_dir . '/bizstory/project/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}

									if ($img_path != '')
									{
										$file_url = substr($img_path, 1, strlen($img_path)) . '/<strong>' . $file_data['img_fname'] . '</strong>';
									}
									else
									{
										$file_url = '<strong>' . $file_data['img_fname'] . '</strong>';
									}
					?>
									<li>
										<?=$btn_str;?>
										<a href="<?=$down_url;?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_url;?> (<?=$fsize;?>)</a>
									</li>
					<?
								}
							}
							$btn_img = preview_images($pro_idx, 'project');
							if ($btn_img != '')
							{
								echo '
									<li>' . $btn_img . '</li>
								';
							}
					?>
								</ul>
					<?
						}
					?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
	<?
	// 등록자, 책임자 가능하다.
		if ($data['reg_id'] == $code_mem || $data['apply_idx'] == $code_mem || ($code_ubstory == 'Y' && $code_level <= '11'))
		{
			$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="open_data_form(\'' . $pro_idx . '\')" /></span>';
			$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $pro_idx . '\')" /></span>';
			$btn_ps90   = '<span class="btn_big_violet"><input type="button" value="완료" onclick="check_pro_status(\'PS90\')" /></span>';
            
            if ($data['pro_status'] == 'PS90') {
                $btn_return = '<span class="btn_big_violet"><input type="button" value="반려" onclick="check_pro_status(\'PS70\')" /></span>';
            }
		}

	// 강제완료
		if ($data['apply_idx'] == $code_mem || ($code_ubstory == 'Y' && $code_level <= '11'))
		{
			$btn_force_ps90 = '<span class="btn_big_violet"><input type="button" value="강제완료" onclick="check_pro_status(\'PS90\', \'Y\')" /></span>';
			$btn_ps90       = '<span class="btn_big_violet"><input type="button" value="완료" onclick="check_pro_status(\'PS90\')" /></span>';
		}

	// 완료일 경우 수정, 삭제가 안됨
		if ($data['pro_status'] == 'PS90')
		{
			$btn_modify = '';
			$btn_delete = '';
			$btn_ps90   = '';
			$btn_force_ps90 = '';
		}

	// 분류 공정률확인
		$class_where = " and proc.pro_idx = '" . $pro_idx . "'";
		$class_list = project_class_data('list', $class_where, '', '', '');
		$total_end = 0; $total_work = 0;
		if ($class_list['total_num'] > 0)
		{
			foreach ($class_list as $class_k => $class_data)
			{
				if (is_array($class_data))
				{
				// 총업무
					$work_where = " and wi.pro_idx = '" . $class_data['pro_idx'] . "' and wi.proc_idx = '" . $class_data['proc_idx'] . "'";
					$work_page = work_info_data('page', $work_where);

				// 완료된 업무 - 취소
					$end_where = " and wi.pro_idx = '" . $class_data['pro_idx'] . "' and wi.proc_idx = '" . $class_data['proc_idx'] . "'
						and (wi.work_status = 'WS90' or wi.work_status = 'WS99' or wi.work_status = 'WS60')";
					$end_page = work_info_data('page', $end_where);

					$total_work += $work_page['total_num'];
					$total_end += $end_page['total_num'];
				}
			}
			if ($total_work == 0)
			{
				$persent_val = 0;
			}
			else
			{
				$persent_val = $total_end / $total_work * 100;
			}
		}
		else
		{
			$persent_val = 0;
		}
		if ($persent_val < 100)
		{
			$btn_ps90 = '';
		}
        
        $section_str = project_status_view($data); // 진행상태
	?>
				<div class="section">
					<div class="fl">
                        <div class="status_box_<?=$section_str['status_title_bg'];?>"></div>
                        <div class="status_box">
                            <div class="status_top">
                                <p class="count">
                                    <?=$section_str['status_title'];?>
                                </p>
                            </div>
                        <?
                            if ($section_str['status_comment'] != '')
                            {
                        ?>
                            <div class="status">
                                <div class="status_info">
                                    <?=$section_str['status_comment'];?>
                                </div>
                            </div>
                        <?
                            }
                        ?>
                        </div>
                    </div>
					<div class="fr">
					    <?=$btn_return?>
						<?=$btn_force_ps90;?>
						<?=$btn_ps90;?>
						<?=$btn_modify;?>
						<?=$btn_delete;?>
					</div>
				</div>
			</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 업무분류
?>
		<div class="dotted"></div>
		<div id="task_class" class="report_box">
			<div class="report_top">
				<p class="count">프로젝트 업무분류</p>
				<div class="new" id="class_new_btn">
			<?
				if ($data['pro_status'] != 'PS90')
				{
			?>
					<img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_classify.png" alt="업무분류쓰기" class="pointer" onclick="class_insert_form('open')" />
			<?
				}
			?>
				</div>
			</div>
			<div id="new_class" title="업무분류쓰기"></div>
			<div id="nwe_class_work" title="업무등록"></div>
			<form id="classlistform" name="classlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="classlist_sub_type" name="sub_type"  value="" />
				<input type="hidden" id="classlist_pro_idx"  name="pro_idx"  value="<?=$pro_idx;?>" />
				<input type="hidden" id="classlist_proc_idx" name="proc_idx" value="" />
				<?=$form_page;?>
				<div id="class_list_data"></div>
			</form>
		</div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 로그기록
?>
		<div class="dotted2"></div>
		<div id="task_history" class="update_box">
			<div class="update_top">
				<p class="count">
					<a id="history_gate" class="btn_i_minus" title="로그기록" onclick="history_view()"></a> 로그기록
				</p>
				<div class="new"></div>
			</div>
			<form id="historylistform" name="historylistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="historylist_pro_idx" name="pro_idx" value="<?=$pro_idx;?>" />
				<?=$form_page;?>
				<div id="history_list_data"></div>
			</form>
		</div>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
    var now_folder_id = 'authsubmenu_<?=$chk_up;?>';

    var fsidebar = document.getElementById("type_folder_navi");
    if (fsidebar)
    {
        this.flistItem = function(li){
            if(li.getElementsByTagName("ul").length > 0)
            {
                var li_a = li.getElementsByTagName("span")[0];
                var ul = li.getElementsByTagName("ul")[0];
                var ul_id = $(ul).attr('id');

                ul.style.display = "none";
                var span = li.getElementsByTagName("span")[0];
                var strong = li.getElementsByTagName("strong")[0];
                strong.className = "collapsed";
                span.onclick = function(){
                    ul.style.display = (ul.style.display == "none") ? "block" : "none";
                    strong.className = (ul.style.display == "none") ? "collapsed" : "expanded";
                };

                var sub_menu_arr = now_folder_id.split('_');
                var left_str = 'authsubmenu';
                for (var left_num = 1; left_num < 10; left_num++)
                {
                    if (sub_menu_arr[left_num] != undefined)
                    {
                        left_str = left_str + '_' + sub_menu_arr[left_num];
                        $("#" + left_str).css({"display": "block"});
                        if (ul_id == left_str)
                        {
                            strong.className = "expanded";
                        }
                    }
                }
                li_a.appendChild(strong);
            };
        };
        var items = fsidebar.getElementsByTagName("li");
        for(var i = 0; i < items.length; i++)
        {
            flistItem(items[i]);
        };
    };
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 상태변경
	function check_pro_status(idx)
	{
	    var force_yn = arguments[1];
	    if (force_yn != null) {
	        $("#view_force_yn").val(force_yn);
	    } else {
	        $("#view_force_yn").val('N');
	    }
	    
		var action_url = ''; action_str = '';
		if (idx == 'PS90') // 프로젝트완료
		{
			action_str = '90';
		} else if (idx == 'PS70') {
		    action_str = '70';
		}
		action_url = local_dir + '/bizstory/project/pro_status_' + action_str + '.php';

		if (action_url != '')
		{
			$.ajax({
				type: "post", dataType: 'html', url: action_url,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('html, body').animate({scrollTop:0}, 500 );
					var maskHeight = $(document).height() + 20;
					var maskWidth  = $(window).width();
					var boxHeight  = $(window).height() / 2 - 100;;
					$("#sub_popupform").slideDown("slow");
					$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
					$('.sub_popupform').css('top',  boxHeight);
					$('.sub_popupform').css('left', maskWidth/2-($('.sub_popupform').width()/2));
					$("#sub_popupform").html(msg);
				},
				complete: function(){ }
			});
		}
		return false;
	}

//------------------------------------ 팝업폼 닫기
	function close_pro_status()
	{
		$("#popup_notice_view").hide();
		$("#sub_popupform").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 작업 관련
	var class_list = '<?=$local_dir;?>/bizstory/project/project_view_class_list.php';
	var class_form = '<?=$local_dir;?>/bizstory/project/project_view_class_form.php';
	var class_ok   = '<?=$local_dir;?>/bizstory/project/project_view_class_ok.php';

//------------------------------------ 작업 등록
	function class_insert_form(form_type)
	{
		class_list_data('');
		$('#nwe_class_work').html('');
        $('.class_modify').html('');
        $('#classlist_proc_idx').val('');
        
        $('#list_sub_type').val('');
        $('#new_class').html('');
        $('#class_new_btn').show();
		
		if (form_type != 'close')
		{
			$('#list_sub_type').val('project_work');
			$.ajax({
				type: "post", dataType: 'html', url: class_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#class_new_btn').hide();
					//$("#new_class").slideUp("slow");
					//$("#new_class").slideDown("slow");
					$("#new_class").html(msg);
					
					$('#classpost_subject').focus();
				}
			});
		}
	}

//------------------------------------ 작업 목록
	function class_list_data(idx)
	{
		$('#list_sub_type').val('');
		$('#classlist_proc_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: class_list,
			data: $('#classlistform').serialize(),
			success: function(msg) {
				$('#class_list_data').html(msg);
			}
		});
	}
	class_list_data('');

//------------------------------------로그 관련
	var history_list = '<?=$local_dir;?>/bizstory/project/project_view_history_list.php';

//------------------------------------로그 목록
	function history_list_data()
	{
		$.ajax({
			type: "post", dataType: 'html', url: history_list,
			data: $('#historylistform').serialize(),
			success: function(msg) {
				$('#history_list_data').html(msg);
			}
		});
	}

//------------------------------------로그 열기/닫기
	var history_chk_val = 'close';
	function history_view()
	{
		if (history_chk_val == 'close')
		{
			history_chk_val = 'open';
			$('#history_list_data').html('');
			$("#history_gate").removeClass('btn_i_minus');
			$("#history_gate").addClass('btn_i_plus');
		}
		else
		{
			history_chk_val = 'close';
			history_list_data();
			$("#history_gate").removeClass('btn_i_plus');
			$("#history_gate").addClass('btn_i_minus');
		}
	}
	history_view();
	
	$(function() {
	    /*
        $("body").delegate('.class_list', 'click', function() {
            var class_list = $(this).attr('id').replace('_btn');
            $("#" + class_list).toggle();
        });
        */
	});
//]]>
</script>
<?
	}
?>