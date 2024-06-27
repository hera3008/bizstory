<?
/*
	수정 : 2012.10.31
	위치 : 설정관리 > 에이전트관리 > 타입관리
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_agent = search_agent_type($code_part, $code_agent);

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
	$link_list  = $local_dir . "/bizstory/care_con/care_con_list.php"; // 목록
	$link_form  = $local_dir . "/bizstory/care_con/care_con_form.php"; // 등록
	$link_ok    = $local_dir . "/bizstory/care_con/care_con_ok.php";   // 저장
	$link_type = $local_dir . "/bizstory/care_con/care_con_type.php"; // 에이전트

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// x
?>
                    <!-- Content wrapper -->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!-- Content -->
                        <div id="kt_app_content" class="app-content app-content-fit-mobile flex-column-fluid">
                            <!-- Content container -->
                            <div id="kt_content_container"
                                class="app-container app-container-fit-mobile container-fluid">
                                <div class="card card-flush">
                                    <div
                                        class="card-header align-items-center min-h-50px mt-4 mt-lg-5 ls-n2 py-0 px-6 px-lg-8 gap-2 gap-md-4">
                                        <div class="card-title">
                                            <h4 class="fs-1">Care Con </h4>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <ol
                                                class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
                                                <li class="breadcrumb-item">홈</li>
                                                <li class="breadcrumb-item">설정관리</li>
                                                <li class="breadcrumb-item text-gray-700">거래처관리</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card-body px-6 px-lg-9 py-2 py-lg-3">

										 <!-- 글목록 -->
										 <?php
											// 카테고리
											echo part_cate_tab($code_comp);
										?>

										<?
										if ($sub_type == 'postform' || $sub_type == 'modifyform' || $comp_client_idx != '' )
										{
											echo '<div id="work_form_view">';
											include $local_path . "/bizstory/comp_set/client_form.php";
											echo '</div>';
										}
										else
										{
											echo "<!-- 글목록 -->";
											echo '<div id="data_list">';
											include $local_path."/bizstory/comp_set/client_list.php";
											echo '</div>';
											echo '<!-- // 글목록 -->';
										}
										?>


                                    </div>
                                </div>
                            </div>
                            <!--// Content container -->
                        </div>
                        <!--// Content -->
                    </div>
                    <!--// Content wrapper -->

<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<div class="agentarea" id="agent_menu"></div>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_code_agent" name="code_agent" value="<?=$code_agent;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list  = '<?=$link_list;?>';
	var link_form  = '<?=$link_form;?>';
	var link_ok    = '<?=$link_ok;?>';
	var link_agent = '<?=$link_agent;?>';

	agent_type('<?=$code_part;?>', '<?=$code_agent;?>');
//]]>
</script>