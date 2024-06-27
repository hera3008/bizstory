<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
	$send_fmode = "receipt";
	$send_smode = "receipt";
	$today_date     = date('Y-m-d');
	$set_part_yn = $comp_set_data['part_yn'];
	$code_part = search_company_part($code_part);
	$top_code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem      = $_SESSION[$sess_str . '_mem_idx'];
	
	//echo $send_fmode."</br>";
	//echo $send_smode."</br>";
	
	$top_chk = member_chk_data($code_mem);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$receipt_str = 'Y';

// 접수 - 완료, 보류, 취소는 제외
	$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
	$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
	$receipt_page = receipt_info_data('page', $receipt_where);
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
		getListOpt('receipt', 'my_no');

    });
    
    function viewIt(idx) {
    	location.href = "receipt_view.php?ri_idx=" + idx;
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
    			var listData = "";
    			var isInit = false;
    			if($("#morenum").val() == "0") {
    				isInit = true;
    			}
    			
    			if(json.result_code == "0") {
    //	console.log(json.result_code);
    				$(json.list_data).each(function(idx, itemData) {
    //	console.log(json.list_data);			
    					listData += '<li><a href="javascript:" onclick="search_client(\'ci.client_name\',\'' + itemData.client_name + '\')">'
    							 + '<strong class="title ml10">' + itemData.subject_txt + '</strong>'
    							 + itemData.receipt_status_str
    							 + '<span class="data">' + itemData.reg_date + '</span>'
    							 + '<span class="c_green">' + itemData.client_name + '</span>'
    							 + itemData.file_str
    							 + itemData.total_coment
    							 + '<span class="btn_state btn_more2" onclick="viewIt(' + itemData.ri_idx + ')"><span>></span></a>'
    							 + '</a></li>';
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
    				$('.receipt_list').html(listData);
    			}else {
    				$('.receipt_list').append(listData);
    			}
    		},
    		complete: function() {
    			try {
    				myScroll.refresh();	
    			} catch(e) {}
    			
    		}
    	});
    }
    
    function regWork() {
		alertMsg('준비 중 입니다.');
	}
//------------------------------------ 거래처검색
	function search_client(str1, str2)
	{
		$('#search_swhere').val(str1);
		$('#search_stext').val(str2);
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
	</form>
	
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>접수목록</h2>

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
			<div class="message_bar">
				<ul>
					<li>
						<a href="javascript:void(0);" onclick="getListOpt('receipt', 'my_no')">
							<span class="btn_v">나의 미처리</span>
							<em class="push2 push_po"><?=number_format($top_chk['receipt_ing']);?></em>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="getListOpt('receipt', 'all_no')"><span class="btn_v">전체 미처리</span></a><em class="push push_po"><?=number_format($receipt_page['total_num']);?></em>
					</li>
					<li><a href="javascript:void(0);" onclick="getListOpt('receipt', 'all')"><span class="btn_v">전체목록</span></a></li>
					<li><a href="javascript:" onclick="regWork()"><span class="btn_g">등록</span></a></li>
				</ul>
			</div>
		</article>
		<div id="wrapper" class="receipt receipt_section">
			<div id="scroller">

				<ul class="receipt_list">
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