<?
	include $local_path . "header.php";
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

        getLocation(true);
        setCartCount();
    });

</script>
<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<article class="mt_4">
			<h2>업무관리</h2>
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
					<li><a href="./work_form.php"><span class="btn_g">등록</span></a></li>
				</ul>
			</div>
		</article>
		<div id="wrapper" class="work work_section">
			<div id="scroller">

				<ul class="work_list">
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 <em class="push ml4" title="읽을 업무보고/코멘트">24</em></strong>
							<span class="btn01 ml10">요청</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level c_orange">상</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state c_green">진행</span>
						</a>
					</li>
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 </strong>
							<span class="btn02 ml10">본인</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level c_green">중</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state c_red">지연</span>
						</a>
					</li>
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 <em class="push ml4" title="읽을 업무보고/코멘트">2</em></strong>
							<span class="btn03 ml10">승인</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level">하</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state c_purple">승인대기</span>
						</a>
					</li>
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 </strong>
							<span class="btn04 ml10">알림</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level c_orange">상</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state c_green2">반려</span>
						</a>
					</li>
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 </strong>
							<span class="btn05 ml10">지사</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level c_green">중</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state">진행</span>
						</a>
					</li>
					<li>
						<a href="./work_my_view.php">
							<strong class="title ml10">김희철 회계사 무역업무 개발사항 </strong>
							<span class="btn04 ml10">알림</span>
							<span class="date">2013-05-27(월)</span>
							<span class="c_d ml4"><em class="c_orange">서경원</em>외 6명</span>
							<span class="btn_level c_orange">상</span>	
							<span class="attach" title="첨부파일">1</span>
							<span class="report" title="업무보고서">52</span>
							<span class="cmt" title="코멘트">25</span>
							<span class="btn_state c_green2">반려</span>
						</a>
					</li>
				</ul>

				<div class="btn_m">
					<a class="btn_m_btn" href="javascript:moreResult(1);" onclick="nclk(this, 'bes.more', '', '2')">
						<span class="btn_m_wrap">
						<span class="btn_m_area">
						<span class="btn_m_lod" id="wait_load" style="display:none">
						<span class="btn_m_lodjs" id="ani_load" style="background-position:0px 0; display:none">로딩중..</span>
						</span>
						<span class="btn_m_txt">15개 더보기<br />
						<span class="btn_m_cnt">15 <span class="btn_m_total">/ 116</span></span>
						</span>
						</span>
						</span>
					</a>
					<a class="btn_m_top" href="#" onclick="nclk(this, 'bes.top', '', '')">맨위로</a>
				</div>

			</div>
		</div>
	</div>

<?
	include $local_path . "footer.php";
?>