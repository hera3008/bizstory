<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";

	$send_fmode = "work";
	$send_smode = "work";
	$today_date = date('Y-m-d');


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
        
        $(document).delegate('#more_bar', 'click', function(e) {
        	e.preventDefault();
        	$(this).focus();
        });

		getListOpt('work_list', 'my');

    });
    
    function viewIt(idx) {
    	location.href = "work_my_view.php?wi_idx=" + idx;
    }
    
    function getListOpt(moretype, link_chk) {
    	
    	if (link_chk == 'all') {
    		$("#smember").val('all');
    	} else {
    		$('#smember').val('');
    	}
    	
    	$("#morenum").val('0');
    	$("#moretype").val(moretype);
    	
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
    			if ($("#morenum").val() == "0") {
    				isInit = true;
    			}
    			
    			if (json.result_code == "0") {
    				
    				$(json.list_data).each(function(idx, itemData) {
    	//	console.log(json.list_data);				
    					listData += '<li><a href="javascript:" onclick="viewIt(' + itemData.wi_idx + ')">'
    							+ '<strong class="title ml10">' + itemData.subject_txt + itemData.read_work_str + '</strong>'
    							+ itemData.work_img
    							+ itemData.reg_date
    							+ itemData.charge_str
    							+ itemData.important_img
    							+ itemData.file_str
    							+ itemData.report_str
    							+ itemData.comment_str
    							+ itemData.end_date_str
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
    				$('.work_list').html(listData);
    			} else {
    				$('.work_list').append(listData);
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
</script>

<div id="page">
	<form id="moreform" name="moreform" method="post" style="margin:0">
		<input type="hidden" id="moretype" name="moretype" value="<?=$moretype;?>" />
		<input type="hidden" id="morenum"  name="morenum"  value="<?=$page_num;?>" />
		<input type="hidden" id="moresize" name="moresize" value="<?=$page_size;?>" />
		<input type="hidden" id="morelist_type" name="list_type" value="<?=$list_type;?>" />
		<input type="hidden" id="smember" name="smember" value="<?=$smember?>" />
		<input type="hidden" id="sview" name="sview" value="<?=$sview?>" />
	</form>
	
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
		<!-- 안내문구 -->
		<div id="advice">
			<div class="advice-body">
				<?=$work_list_title?>	

			</div>
			<button class="control-btn"><em>도움말 닫기</em></button>
		</div>
		<script type="text/javascript">$('#advice').mainVisual();</script>
		<!-- //안내문구 -->
	</div>

	<div id="content">
		<article>
			<h2>업무관리</h2>
			<form id="searchform" method="GET" action="">
				<fieldset>
				<input type="hidden" name="fmode" value="<?=$send_fmode?>" />
				<input type="hidden" name="smode" value="<?=$send_smode?>" />
				<input type="hidden" name="swhere"    value="<?=$send_swhere?>" />
				<input type="hidden" name="stext"     value="<?=$send_stext?>" />
				<input type="hidden" name="swtype"    value="<?=$send_swtype?>" />
				<input type="hidden" name="shwstatus" value="<?=$send_shwstatus?>" />
				<input type="hidden" name="smember"   value="<?=$send_smember?>" />
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
<?

	if ($code_ubstory == 'Y' && $code_level <= '21') {
?>
					<li><a href="javascript:" onclick="getListOpt('work_list', 'my')"><span class="btn_v">나의업무</span></a></li>
					<li><a href="javascript:" onclick="getListOpt('work_list', 'all')"><span class="btn_v">전체업무</span></a></li>
<?
	}
?>
					<li><a href="javascript:" onclick="regWork()"><span class="btn_g">등록</span></a></li>
				</ul>
			</div>
		</article>
		<div id="wrapper" class="work work_section">
			<div id="scroller">

				<ul class="work_list">
				</ul>


<?
	include "./include/list_more.php";
?>
				<!-- 
				<div id="horizontalBox">
					<div class="inBox02">
						<strong class="title ml10">김희철 회계사 무역업무 개발사항 <em class="push ml4" title="읽을 업무보고/코멘트">24</em></strong>
						<span class="btn04 ml10">알림</span>
						<span class="date">2013-05-27(월)</span>
						<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
						<span class="btn_level c_orange">상</span>	
						<span class="attach" title="첨부파일">1</span>
						<span class="report" title="업무보고서">52</span>
						<span class="cmt" title="코멘트">25</span>
					</div>
					<div class="inBox03">
						<span class="btn_state2 c_green2">반려</span>
					</div>
				</div>
				<!-- // -->

			</div>
			

		</div>
	</div>



<?
	include "./footer.php";
?>