<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
	$send_fmode = "receipt";
	$send_smode = "receipt";
	$today_date     = date('Y-m-d');
	$set_part_yn = $comp_set_data['part_yn'];
	$search_company = $_POST["search_company"]; 
	
	if ($search_company != ''){
		$code_part = $search_company;
	}
	
	$code_part = search_company_part($code_part);

	//echo $send_fmode."</br>";
	//echo $send_smode."</br>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<script>
    var recommendedKeywordView = '검색할 단어 입력';
    var recommendedKeyword = '';

    $(document).ready(function()
    {
        $('#inpSearch').keyup(function(e)
        {
            if(e.which == 13) $('#searchform').submit(); 
        });

        $('#btnDelete').click(function()
        {
            $('#inpSearch').val('');
            $('#inpSearch').focus();
        });

        $('#btnSearch').click(function()
        {
            $('#searchform').submit();
        });

        $('#inpSearch').focus(function()
        {
            if($('#inpSearch').val() == '검색어를 입력해 주세요.' || $('#inpSearch').val() == recommendedKeywordView) $('#inpSearch').val('');
            $('#inpSearch').addClass('on'); 
            $('#divSearchList').show();
            $('#divSearchResult').hide();
        });

        $('#searchform').submit(function()
        {
            if($('#inpSearch').val() == '' || $('#inpSearch').val() == '검색어를 입력해 주세요.') 
            {
                alert('검색어를 확인해 주세요');
                return false;
            }
            if($('#inpSearch').val() == recommendedKeywordView)
            {
               $('#inpSearch').val(recommendedKeyword); 
            }
        });

		$("#morenum").val('0');
		getListOpt('client', '');

    });
    
    function viewIt(idx) {
    	location.href = "client_view.php?ci_idx=" + idx;
    }
    
    function getListOpt(moretype, link_chk) {
    	
    	if (link_chk == 'all') {
    		$("#list_type").val('all');
    	}else if(link_chk == 'my_no') {
    		$("#list_type").val('my_no');
    	}else if(link_chk == 'all_no') {
    		$("#list_type").val('all_no');	
    	}else {
    		$('#list_type').val('all_no');
    	}
    
    	$("#morenum").val('0');	
    	$("#moretype").val(moretype);
		$('#search_swhere').val('');
		$('#search_stext').val('');
   // 	$("#smember").val(smember);
    	getList();
    }
    

    function getList() {
    	
    	$.ajax({
    		async: true,
    		type: 'post',
    		dataType:'json',
    		url: './process/ajax_list.php',
    		data: $("#moreform").serialize(),
    		success: function(json) {
    			var listData = [];
    			var isInit = false;
    			if ($("#morenum").val() == "0") {
    				isInit = true;
    			}
    			
    			if (json.result_code == "0") {
    				var i = 1;
    				var mem_info_str = "";
    				var group_name = "";
    				
    				$(json.list_data).each(function(idx, item) {
    					
    					if (i == 1) {
    						className = "first";
    					} else {
    						className = "";
    					}
    				
    					if (item.mem_name == null) {
    						mem_info_str = "";
    					} else {
    						//small pop1" data-bpopup='{"transition":"slideDown","speed":850,"easing":"easeOutBack"}' onclick="popupMemInfo(<?=$mem_idx?>)"
    						mem_info_str = '<a href="#" class="' + className + ' small pop1 mem" data-bpopup=\'{"transition":"slideDown","speed":850,"easing":"easeOutBack"}\' onclick="popupMemInfo(' + item.mem_idx + ')" >' + item.mem_name + '</a>';
    					}
    					
    					if (item.group_name == null) {
    						group_name = "";
    					} else {
    						group_name = '<span class="c_black">' + item.group_name + '</span>';
    					}
    					
						listData.push('<li>');
						listData.push('<strong class="title">' + item.client_name);
						listData.push(mem_info_str);
						listData.push('</strong>');
						listData.push('<span class="person">' + item.info_str + '&nbsp;</span>');
						listData.push(item.tel_num_str);
						listData.push(item.client_email_str);
						listData.push('<span class="group">');
						listData.push(group_name);
						listData.push('</span>');
						listData.push('<a href="javascript:void(0)" onclick="window.location.href=\'client_view.php?idx=' + item.ci_idx + '\'"><span class="btn_state btn_more2"><span>&gt;</span></span></a>');
						listData.push('</li>');

    				});
    			
					$("#morenum").val(json.more_num);
    				
    				var more_num = parseInt(json.more_num);
    				var total_page = parseInt(json.total_page);

					if (more_num <= total_page) {
	    				if ($("#more_bar").css("display") == "none") {
	    					$("#more_bar").show();
	    				}
	    				$("#more_bar .btn_m_btn").show();
	    				$(".btn_m .btn_m_btn .btn_m_cnt ").html(json.last_idx + ' <span class="btn_m_total">/ ' + json.total_num + '</span>');
					}
					if (more_num >= total_page) {
						$("#more_bar .btn_m_btn").hide();
					}
					
    			}
    			if (isInit) {
    				$('.partner_list').html(listData.join('\n'));
    			} else {
    				$('.partner_list').append(listData.join('\n'));
    			}
    			
    			
    		},
    		complete: function() {
    			try {
					myScroll.refresh();
    			} catch(e) {}
    			
    		}
    	});
    }

//------------------------------------ 거래처검색
	function search_client(str1, str2)
	{
		$('#search_swhere').val(str1);
		$('#search_stext').val(str2);
		$("#morenum").val('0');
		getList();
	}
	
	function search_company(part){
	console.log(part);
		//var code_part = part;
		$('#search_company').val(part);
		
	//	searchform.action = "member_list.php";
	//	$("#moreform").submit();
		$("#morenum").val('0');
		getList();

	}
</script>

<div id="page">
	
	<form id="moreform" name="moreform" method="post" action="#" style="margin:0">
		<input type="hidden" id="search_swhere" name="swhere" value="<?=$swhere?>" />
		<input type="hidden" id="search_stext" name="stext" value="<?=$stext?>" />
		<input type="hidden" id="moretype" name="moretype" value="<?=$moretype;?>" />
		<input type="hidden" id="morenum"  name="morenum"  value="<?=$page_num;?>" />
		<input type="hidden" id="moresize" name="moresize" value="<?=$page_size;?>" />
		<input type="hidden" id="list_type" name="list_type" value="<?=$list_type;?>" />
		<input type="hidden" id="smember" name="smember" value="<?=$smember?>" />
		<input type="hidden" name="search_company" id="search_company" value="" />
	</form>
	
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>고객목록</h2>
			<form id="searchform" method="GET" action="">
				<fieldset>
				<input type="hidden" name="fmode" value="work" />
				<input type="hidden" name="smode" value="work" />
				<legend>컨텐츠 검색</legend>
					<div class="search_bar"> 
						<div class="search_area"> 
							<div class="inpwp"><input type="search" title="검색어 입력" id="inpSearch" autocomplete="off" autocorrect="off" name="keyword" value="검색할 단어 입력" class="" maxlength="40" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" /></div>
							<i class="spr_tm mag"></i> 
							<button type="button" class="del" id="btnDelete"><i class="spr_tm">검색어 삭제</i></button> 
						</div> 
						<button type="button" class="go" id="btnSearch"><i class="spr_tm">검색하기</i></button>
					</div> 
				</fieldset> 
			</form>
<?
	$part_where = "and part.comp_idx = '" . $code_comp . "'";
	$part_list     = company_part_data('list', $part_where, '', '', '');
	if($part_list['total_num'] > 0) {
?>
			<div class="message_bar">
				<select class="ngb_select" id="MemberGroupList" onchange="search_company(this.value);">
<?
		foreach ($part_list as $k => $part_data){
			if(is_array($part_data)){
				if ($code_part == $part_data['part_idx']) $class_str = ' class="select" selected="selected"';
					else $class_str = '';
?>					
					<option value="<?=$part_data['part_idx'];?>" <?=$class_str?>><?=$part_data['part_name'];?></option>
<?
			}
		}
?>
				</select>
			</div>
<?
	}
?>
		</article>
		<div id="wrapper" class="receipt partner_section">
			<div id="scroller">

				<ul class="partner_list">
					
					<!-- li>
						<strong class="title">(주)성우모바일 
							<a href="#" class="<?if ($i==1) {?>first <?}?>md-trigger mem" onclick="viewMemInfo(<?=$mem_idx?>)" data-modal="modal">
							김나영</a>
						</strong>
						<span class="person">장광식(팀장)</span>
						<a href="tel:031-8040-2959" class="tel">031-8040-2959</a>
						<a href="mailto:kschang@swmobile.co.kr" class="email">kschang@swmobile.co.kr</a>
						<a href="#"  class="mem"><span>담당자 :</span> 김나영</a>
						<span class="group">
							<span class="c_black">기업</span>
						</span>
						<a href="partner_view.php"><span class="btn_state btn_more2">&gt;</span></a>
					</li -->
				</ul>

<?
	include "./include/list_more.php";
?>

			</div>
		</div>
	</div>

<?
	include "./footer.php";
?>