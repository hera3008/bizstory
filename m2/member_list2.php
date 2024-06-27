<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./header.php";



?>


<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>직원목록</h2>
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
				<select class="ngb_select" id="MemberGroupList">
					<option value="0">(주)유비스토리</option>
					<option value="1">도서관사업부</option>
					<option value="2">외부협력업체</option>
					<option value="3">브이센터</option>
				</select>
			</div>
		</article>
		<div id="wrapper" class="member_section">
			<div id="scroller">

				<ul class="member_list">
					
					
					<li>
						<p class="name small pop1" data-bpopup='{"transition":"slideDown","speed":850,"easing":"easeOutBack"}'>[<span style="color:#0075c8">(주)유비스토리</span>:부설연구소] <strong style="color:#ff6c00">김나영</strong> 실장</p>
						<img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" width="22px" height="22px" />						
						<ul class="member_info">
							<li class="email">naryong82@nate.com</li>
							<li class="tel">010-3111-4862</li>
						</ul>
					</li>
					
					
				</ul>
			</div>
		</div>
	</div>
<!-- 팝업창 -->	
<div id="popup">
        
    	<h3>김나영<span>실장</span></h3>
		<div>
			<img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" width="53px" height="70px" />			
			<ul>
				<li class="group">(주)유비스토리 : 부설연구소</li>
				<li class="email"><a href="mailto:naryong82@nate.com">naryong82@nate.com</a></li>
				<li class="tel"><a href="tel:010-3111-4862">010-3111-4862</a></li>
				<li class="date">최종접속 : 2013-11-14 09:23:38</li>
			</ul>
			<button class="b-close"><img src="./images/btn_close.png" alt="닫기"></button>
		</div>
    
    </div>
</div>
<!-- //팝업창 -->	
<?
	include "./footer.php";
?>