<?
/*
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 등록, 수정
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";
   // require_once $local_path ."/include/header.php";

	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part      = search_company_part($code_part);
    $code_part      = "";
	$set_client_cnt = $comp_set_data['client_cnt'];
	$set_tax_yn     = $comp_set_data['tax_yn'];
	$cr_idx         = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);


	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$form_chk = 'N';
	if($auth_menu['int'] == 'Y' && $ci_idx == '')
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && ($ci_idx != '' || $comp_client_idx != '')) // 수정권한
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
				$(".modal-backdrop").fadeOut("fade");
			//]]>
			</script>';
		exit;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		$where = " and comp.comp_idx = '" . $code_comp . "'";
		$comp_info = company_info_data("view", $where);
		$comp_class = $comp_info['comp_class'];
        
        if($comp_data['comp_class'] == "1")  //학교
        {
            $where = "cr.comp_client_code = '{$code_comp}'";
        }
        else //기업
        {
            $where = "cr.comp_client_code = '{$code_comp}'";
        }
         
        $query_string ="
                SELECT
                    cr.*
                    , ci.comp_name AS comp_name, ci.tel_num AS comp_tel_num, ci.comp_code AS comp_code, ci.address as comp_address
                    , cci.comp_name AS client_comp_name, cci.tel_num AS client_comp_tel_num 
                    , cci.comp_code AS client_comp_code, cci.sc_code, cci.sc_name, cci.schul_code, cci.schul_name, cci.tel_num, cci.address
                from
                    client_request_data cr
                    left join company_info AS ci ON cr.comp_idx = ci.comp_idx
                    left join company_info AS cci ON cr.comp_client_idx = cci.comp_idx
                WHERE 
                    cr.cr_idx = '{$cr_idx}' 
        ";
       
        //$sql_data['query_string'] = $query_string;
        $data = query_view($query_string);

       //print_r($data);

		
?>

                                                    <form id="postform" name="postform" method="post" class="form" action="<?=$this_page;?>" onsubmit="return check_form()">
														<?=$form_all;?>
                                                        <div class="modal-body">
                                                            <?if($cr_idx == ""){?>
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_menu_depth"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">도교육청</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <select  name="param[sc_code]" title="시도교육청 선택하세요."  class="form-select form-select-sm" onchange="school_info_data($(this).val(), '', '')">
                                                                        <option value="">시도교육청선택</option>
                                                                        <? foreach($set_sc_code as $key => $val){?>
                                                                        <option value="<?=$key?>"> <?=$val?></option>
                                                                        <?}?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="chk_menu1"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">학교검색</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <input type="text" name="search_text" id="search_text" value="" >
                                                                </div>
                                                            </div>
                                                            <?}?>
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_group_name"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">시도교육청</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                <input type="hidden" name="param[comp_client_idx]" id="post_comp_client_idx"  value="<?=$data['comp_client_idx'];?>">
                                                                <input type="hidden" name="param[comp_client_code]" id="post_comp_client_code"  value="<?=$data['comp_client_code'];?>">
                                                                <input type="hidden" name="param[sc_code]" id="post_sc_code" title="시도교육청 코드를 입력하세요." value="<?=$data['sc_code'];?>">
                                                                    <input type="text" name="param[sc_name]" id="post_sc_name" title="시도교육청을 입력하세요."
                                                                        class="form-control form-control-sm maxlength" placeholder="시도교육청을 입력하세요." maxlength="25"
                                                                        value="<?=$data['sc_name'];?>">
                                                                </div>
                                                            </div>
                                                          
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="post_group_name"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">학교명</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                    <input type="hidden" name="param[schul_code]" id="post_schul_code" title="학교코드를 입력하세요." value="<?=$data['schul_code'];?>">
                                                                    <input type="text" name="param[schul_name]" id="post_schul_name" title="학교명을 입력하세요."
                                                                        class="form-control form-control-sm maxlength" placeholder="학교명을 입력하세요." maxlength="25"
                                                                        value="<?=$data['schul_name'];?>">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="chk_menu1"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">연락처</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[tel_num]" id="post_tel_num" title="연락처를 입력하세요."
                                                                        class="form-control form-control-sm maxlength" placeholder="연락처를 입력하세요." maxlength="25"
                                                                        value="<?=$data['tel_num'];?>">
                                                                </div>
                                                            </div>

                                                            <div class="row py-2">
                                                                <div class="col-3">
                                                                    <label for="chk_menu1"
                                                                        class="fs-7 fw-semibold my-3">
                                                                        <span class="required">주소</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 fv-row fv-plugins-icon-container">
                                                                <input type="text" name="param[address]" id="post_address" title="주소를 입력하세요."
                                                                            class="form-control form-control-sm maxlength" placeholder="주소를 입력하세요." maxlength="25"
                                                                            value="<?=$data['address'];?>">
                                                                </div>
                                                            </div>
                                                       
                                                        
                                                        <div class="modal-footer">
														    <button type="button" class="btn btn-sm btn-secondary d-print-none" data-bs-dismiss="modal">
															    <i class="ki-outline ki-arrows-circle fs-6"></i> 취소
                                                        </button>

                                                        
														<?
														if ($cr_idx == "") {
														?>
                                                            <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 등록
                                                            </button>
															<input type="hidden" name="sub_type" value="post" />
														<?}else{?>	
															 <button type="button" class="btn btn-sm btn-warning d-print-none"  onclick="check_form()">
																<i class="ki-outline ki-pencil fs-6"></i> 수정
                                                            </button>
															<input type="hidden" name="sub_type" value="modify" />
															<input type="hidden" name="cr_idx" value="<?=$cr_idx;?>" />
														<?}?>
                                                        </div>
                                                        
                                                    </form>


<?
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('#search_text').autocomplete({
        
	source : function(request, response) { //source: 입력시 보일 목록
	     $.ajax({
	           url : local_dir+"/bizstory/comp_set/company_school_info.php"   
	         , type : "POST"
	         , dataType: "JSON"
	         , data : {sub_type :'view', 'sc_code' : $('#post_sc_code').val(), search_text: request.term}	// 검색 키워드
	         , success : function(data){ 	// 성공
                console.log(data);
                var resultList = data;
                var info = "";
                console.log(resultList);
	             response(
	                 $.map(resultList, function(item) {
                        info = item.sc_code + '/'+ item.sc_name;
                        info += '/'+ item.org_code + '/'+ item.ogr_name;
                        info += '/'+ item.schul_code + '/'+ item.schul_name;
                        info += '/'+ item.tel_num + '/' + item.address;
                        info += '/'+ item.comp_idx;
                       
	                     return {
	                    	     label : item.schul_name    	// 목록에 표시되는 값
	                           , value : item.schul_name		// 선택 시 input창에 표시되는 값
	                           , info : info // index
	                     };
	                 })
	             );    //response
	         }
	         ,error : function(){ //실패
	             alert("오류가 발생했습니다.");
	         }
	     });
	}
	,focus : function(event, ui) { // 방향키로 자동완성단어 선택 가능하게 만들어줌	
			return false;
	}
	,minLength: 2// 최소 글자수
	,autoFocus : true // true == 첫 번째 항목에 자동으로 초점이 맞춰짐
	,delay: 100	//autocomplete 딜레이 시간(ms)
	,select : function(evt, ui) { 
      	// 아이템 선택시 실행 ui.item 이 선택된 항목을 나타내는 객체, lavel/value/idx를 가짐
       
			console.log(ui.item.label);
			console.log(ui.item.info);
            const info = ui.item.info.split('/');

            $('#post_sc_code').val(info[0]);
            $('#post_sc_name').val(info[1]);
            $('#post_schul_code').val(info[4]);
            $('#post_schul_name').val(info[5]);
            $('#post_tel_num').val(info[6]);
            $('#post_address').val(info[7]);
            $('#post_comp_client_idx').val(info[8]);
            $('#post_comp_client_code').val(info[0]+info[4]);
	 }
});

</script>