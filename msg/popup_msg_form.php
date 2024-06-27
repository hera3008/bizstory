<?
/*
	수정 : 2023.03.26
	위치 : 팝업 메세시 보내기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
    $code_part = "";
	$mem_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $send_page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$page_where = " and mem.mem_idx = '" . $mem_idx . "'";
	$page_data = member_info_data('view', $page_where);
   
	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $mem_idx != "" && $mem_data['total_num'] < 0) // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else
	{
        $form_chk   = 'Y';
        /*
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
                $(".modal-backdrop").fadeOut("fade");
			//]]>
			</script>';
		exit;
        */
	}

	if ($form_chk == 'Y')
	{
        $charge_str = staff_layer_form($mem_idx, '', 'N', $set_color_list2, 'stafflist', $page_data['mem_idx'], '');

        $mf_where = " and mf.mem_idx = '" . $mem_idx. "' and mf.sort = 1";
		$mf_data  = member_file_data('view', $mf_where);
        
        $mem_img_blank = '<span class="symbol-label fs-2x fw-semibold text-warning bg-light-warning">' . strtoupper(substr($page_data['mem_id'], 0, 1)) .'</span>';
        $mem_img = '';
        if ($data['mem_img'] != '')  $mem_img = $page_data['mem_img']; 
        else if ($mf_data['img_sname'] != '') $mem_img =  $mem_dir . "/" . $mf_data['mem_idx'] . "/" . $mf_data['img_sname'];

?>

            <div class="card w-100 rounded-0">
				<div class="card-header pe-5">
					<div class="card-title">
						<div class="d-flex justify-content-center flex-column me-3">
							<strong class="fs-4 fw-bold text-gray-900 me-1 lh-1">
                                <?=$page_data['group_name'] ? "[". $page_data['group_name'] . "]" : ""?> 
                                <?=$page_data['mem_name']?> <?=$page_data['duty_name']?>
                            </strong> 
						</div>
					</div>
					<div class="card-toolbar">
						<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_user_close">
							<i class="ki-outline ki-cross fs-3x"></i>
						</div>
					</div>
				</div>
				<div class="card-body hover-scroll-overlay-y text-start">
					<div class="d-flex align-items-center py-1">
						<div class="symbol symbol-circle symbol-75px me-5">
                            <?
                            if($mem_img == ''){ echo  $mem_img_blank; } else {?>
							<img src="<?=$mem_img?>" alt="<?=$page_data['mem_name']?>">
                            <?}?>
                            
						</div>
						<div class="d-flex flex-column align-items-start justify-content-center lh-sm">
							<span class="d-flex align-items-center text-gray-800 fs-6 fw-semibold my-1"> 
                                <?=$page_data['comp_name']?> <?=$page_data['group_name'] ? " : ". $page_data['group_name'] : ""?>
                            </span>
							<span class="text-muted fs-7 my-1">이메일: <a href="mailto:<?=$page_data['mem_email']?>" class="text-gray-800 text-hover-primary"><?=$page_data['mem_email']?></a></span>
							<span class="text-muted fs-7 my-1">연락처: <?=$page_data['hp_num']?></span> 
							<span class="text-muted fs-7 my-1">최종접속: <?=$page_data['last_date']?></span>
						</div>
					</div>
					<div class="my-5">
						<div class="d-grid">
							<ul class="nav nav-tabs flex-nowrap text-nowrap">
								<li class="nav-item">
									<a class="nav-link btn btn-active-light-primary btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active" data-bs-toggle="tab" href="#kt_tab_pane_1">쪽지보내기</a>
								</li>
								<!--li class="nav-item">
									<a class="nav-link btn btn-active-light-primary btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" href="#kt_tab_pane_2">문자보내기</a>
								</li-->
							</ul>
						</div>
					</div>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                            <form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
                                <input type="hidden" name="sub_type" id="post_receive_idx" value="post">
                                <input type="hidden" name="receive_idx[]" id="post_receive_idx" value="<?=$mem_idx?>">
                                
                                <?=$form_all;?>
								<div class="row">
									<div class="col-xl-2">
										<div class="fs-7 fw-semibold my-3">
											받는자
										</div>
									</div>
									<div class="col-xl-10 fv-row fv-plugins-icon-container fs-7 my-3">
										<span class="text-success">
                                            [<?=$page_data['comp_name']?> <?=$page_data['group_name'] ? " :". $page_data['group_name'] : ""?>]
                                        </span> 
                                        <span class="text-warning"><?=$page_data['mem_name']?> <?=$page_data['duty_name']?> </span>
									</div>
								</div>
								<div class="row py-4">
									<div class="col-xl-2">
										<label for="post_remark" class="fs-7 fw-semibold my-3">
											<span class="required">내용</span>
										</label>
									</div>
									<div class="col-xl-10 fv-row fv-plugins-icon-container">
										<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." class="form-control maxlength form-control-sm" rows="7" placeholder="300자까지 가능합니다." maxlength="300"></textarea>
									</div>
								</div>
								<div class="separator separator-dashed mb-6 mb-lg-8"></div>
								<div class="row mb-8 mb-lg-10">
									<div class="col-6">
										<button type="button" class="btn btn-sm btn-flex btn-secondary">
											<i class="ki-outline ki-burger-menu fs-6"></i> 취소
										</button>
									</div>
									<div class="col-6 text-end">
										<button type="submit" class="btn btn-sm btn-flex btn-warning">
											<i class="ki-outline ki-pencil fs-6"></i> 등록
										</button>
									</div>
								</div>
							</form>
						</div>

                        <!-- 문자보내기 -->
						<!--div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
							<form action="#" class="form" method="post">
								<div class="row">
									<div class="col-xl-2">
										<div class="fs-7 fw-semibold my-3">
											받는자
										</div>
									</div>
									<div class="col-xl-10 fv-row fv-plugins-icon-container fs-7 my-3">
										<span class="text-success">[(주)유비스토리:경영지원]</span> <span class="text-warning">서경원</span>
									</div>
								</div>
								<div class="row py-4">
									<div class="col-xl-2">
										<label for="sms_contents" class="fs-7 fw-semibold my-3">
											<span class="required">문구</span>
										</label>
									</div>
									<div class="col-xl-10 fv-row fv-plugins-icon-container">
										<input type="text" name="param[sms]" id="sms_contents" class="form-control maxlength form-control-sm" placeholder="30자까지 가능합니다. " size="30" maxlength="30">
									</div>
								</div>
								<div class="separator separator-dashed mb-6 mb-lg-8"></div>
								<div class="row mb-8 mb-lg-10">
									<div class="col-6">
										<button type="button" class="btn btn-sm btn-flex btn-secondary">
											<i class="ki-outline ki-burger-menu fs-6"></i> 취소
										</button>
									</div>
									<div class="col-6 text-end">
										<button type="submit" class="btn btn-sm btn-flex btn-warning">
											<i class="ki-outline ki-pencil fs-6"></i> 등록
										</button>
									</div>
								</div>
							</form>
						</div-->
                        <!-- // 문자보내기 -->

					</div>
				</div>
			</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

	// 받는사람확인
		var mem_idx  = document.getElementsByName('receive_idx[]');
		
		if (mem_idx == '')
		{
			chk_total = chk_total + '받는자를 선택하세요.<br />';
			action_num++;
		}

		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/msg/msg_ok.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						alert("쪽지를 전송하였습니다.");
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					//$("#backgroundPopup").fadeOut("slow");
                    $(".modal-backdrop").fadeOut("fade");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//]]>
</script>
<?
	}
?>
