<?
	include $local_path . "header.php";
?>

<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<article class="mt_4">
			<h2>접수목록</h2>
		</article>
		<div id="wrapper" class="receipt">
			<div id="scroller">

				<div class="work_area">
					<div class="work_inner">
						
						<div class="title">
							<strong>읽어보시고 판단 후 답변 요망</strong>
							
							<strong class="regist"><a href="javascript:void(0)" onclick="staff_layer_open('2');"><span style="color:#ff6c00" id="workregsttaff_2_855">이재원</span></a><span class="data">(031-8012-6024) 2013-07-08 17:21:32 </span><strong>
						</div>
						<table border="1" cellspacing="0" summary="업무내용" class="table02">
							<tr>
								<th class="w100">거래처명</th>
								<td>경기도교육복지종합센터</td>
							</tr>
							<tr>
								<th>담당자</th>
								<td>
									[<span class="c_blue">(주)유비스토리</span>:<span class="c_green">부설연구소</span>] <strong class="c_brown">김경화</strong>
								</td>
							</tr>
							<tr>
								<th>접수분류</th>
								<td>웹사이트관련</td>
							</tr>
							<tr>
								<th>분류</th>
								<td>일반업무</td>
							</tr>
							<tr>
								<td colspan="2" class="ptb10l5">
									<p>일단 제 직통번호가 바뀌었습니다. 031-8012-6024입니다.</p>
									<p>1. 세션 유지 시간이 긴 느낌이 있는데 최대 7분으로 셋팅 바랍니다. (체감은 10분 이상 자리 비우고 와도 살아 있는 것 같습니다.)</p>
									<p>2. 새로 고침 키는 오작동에 대한 기다림이 싫어 누르는 경우가 많으니, 로그아웃 상태로 전환 시켜 로그인 과정을 거쳐 오게끔 해주시기 바랍니다. 상태에 따른 문구가 표출되면 이해하고 로그인 과정을 거칠 것 같습니다.</p>
									<p>p.s 좋은 솔루션을 매번 강좌 접수 때마다 노이로제 걸리고 있습니다.</p>
								</td>
							</tr>
							<tr>
								<th>첨부파일</th>
								<td></td>
							</tr>
						</table>

					</div>
				</div>

				<!-- 접수등록 상태 -->
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span class="txt">
							<span><span class="fw700 c_blown">접수분류</span></span>
							<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택">
								<option value="">접수분류 선택</option>
								<option value="1" selected="selected">웹사이트관련</option>
								<option value="2">&nbsp;&nbsp;&nbsp;컨텐츠 수정/추가</option>
								<option value="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;내용수정</option>
								<option value="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;메뉴수정</option>
								<option value="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;배너추가</option>
								<option value="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;팝업추가</option>
								<option value="11">&nbsp;&nbsp;&nbsp;오류수정요청</option>
								<option value="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사이트오류</option>
								<option value="13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;시스템오류</option>
								<option value="14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;기타오류</option>
								<option value="46">하드웨어관련</option>
								<option value="47">문의사항관련</option>
								<option value="9">&nbsp;&nbsp;&nbsp;사이트문의</option>
								<option value="8">&nbsp;&nbsp;&nbsp;관공서공문</option>
								<option value="10">&nbsp;&nbsp;&nbsp;기타문의</option>
							</select>
							
							<span><span class="fw700 c_blown">담당자</span></span>
							<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택">
								<option value="">담당자 선택</option>
								<option value="195">고동원</option>
								<option value="7" selected="selected">김경화</option>
								<option value="15">김나영</option>
								<option value="486">김정미</option>
								<option value="59">김혁</option>
								<option value="131">노대성</option>
								<option value="8">문은지</option>
								<option value="2">서경원</option>
								<option value="508">서동욱</option>
								<option value="6">우덕성</option>
								<option value="129">정상진</option>
								<option value="86">지인영</option>
							</select>
							
							<span><span class="fw700 c_blown">완료예정일</span></span>
							<input id="detail_end_pre_date" name="detail_end_pre_date" class="type_text datepicker hasDatepicker" title="완료예정일 입력하세요." size="10" value="2013-08-23" type="text"><img title="..." alt="..." src="/bizstory/images/btn/calendar.png" class="ui-datepicker-trigger">
							<span class="btn09"><input value="접수승인" onclick="check_singular('')" type="button"></span>
						</span>
					</div>

					<div class="plural_view">
						<div class="info_text">
							<ul>
								<li>접수된 업무진행시 담당자를 여러명 지정할 때 사용합니다.</li>
							</ul>
						</div>
						<table class="table01">
							<thead>
								<tr>
									<th>분류</th>
									<th>내용</th>
									<th>담당자</th>
									<th>상태</th>
									<th>완료예정일</th>
									<th>완료일</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">등록된 데이타가 없습니다.</td>
								</tr>
							</tbody>
						</table>
						<div class="section">
							<div class="fr">
								<a href="javascript:void(0)" onclick="plural_form('');"><img src="/bizstory/images/btn/btn_detail.gif" alt="상세업무등록"></a>
							</div>
						</div>
					</div>
					<div class="mem_regist">
						<span class="ico_mem fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]
					</div>
				</div>
				<!-- //접수등록 상태 -->

				<!-- 접수등록 상세업무등록 상태 -->
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span class="txt">
							<span>다수 접수업무가 등록되었습니다.</span>
					</div>

					<table class="table01" summary="다수접수 등록/수정합니다.">
						<caption>접수</caption>
						<tbody>
							<tr>
								<th><label for="detail_receipt_class">접수분류</label></th>
								<td>
									<select id="detail_receipt_class" name="detail_receipt_class" title="접수분류 선택"><option value="">접수분류 선택</option><option value="1" selected="selected">웹사이트관련</option><option value="2">&nbsp;&nbsp;&nbsp;컨텐츠 수정/추가</option><option value="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;내용수정</option><option value="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;메뉴수정</option><option value="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;배너추가</option><option value="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;팝업추가</option><option value="11">&nbsp;&nbsp;&nbsp;오류수정요청</option><option value="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사이트오류</option><option value="13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;시스템오류</option><option value="14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;기타오류</option><option value="46">하드웨어관련</option><option value="47">문의사항관련</option><option value="9">&nbsp;&nbsp;&nbsp;사이트문의</option><option value="8">&nbsp;&nbsp;&nbsp;관공서공문</option><option value="10">&nbsp;&nbsp;&nbsp;기타문의</option></select>
								</td>
								<th><label for="detail_mem_idx">담당자</label></th>
								<td>
									<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택"><option value="">담당자 선택</option><option value="195">고동원</option><option value="7" selected="selected">김경화</option><option value="15">김나영</option><option value="486">김정미</option><option value="59">김혁</option><option value="131">노대성</option><option value="8">문은지</option><option value="2">서경원</option><option value="508">서동욱</option><option value="6">우덕성</option><option value="129">정상진</option><option value="86">지인영</option></select>
								</td>
							</tr>
							<tr>
								<th><label for="detail_end_pre_date">완료예정일</label></th>
								<td colspan="3">
									<input id="detail_end_pre_date" name="detail_end_pre_date" class="type_text datepicker hasDatepicker" title="완료예정일 입력하세요." size="10" value="2013-08-23" type="text"><img title="..." alt="..." src="/bizstory/images/btn/calendar.png" class="ui-datepicker-trigger">
								</td>
							</tr>
							<tr>
								<th><label for="detail_remark">내용</label></th>
								<td colspan="3">
									<textarea style="display: none;" name="detail_remark" id="detail_remark" title="내용을 입력하세요."></textarea><iframe src="/bizstory/editor/smarteditor/SmartEditor2Skin.html" style="width: 100%; height: 170px;" frameborder="0" scrolling="no"></iframe>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="section">
						<div class="fr">
							<span class="btn10"><input value="등록" onclick="check_plural()" type="button"></span>
							<span class="btn09"><input value="취소" onclick="plural_list()" type="button"></span>
						</div>
					</div>
				</div>
				<!-- //접수등록 상세업무등록 상태 -->

				<!-- 접수승인 상태 -->
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span class="txt">
							<span><span class="fw700 c_blown">접수분류</span> : 웹사이트관련</span>
							
							<span><span class="fw700 c_blown">담당자</span> : 지인영</span>
							
							<span><span class="fw700 c_blown">완료예정일</span> : 2013-07-04</span>
						</span>
					</div>
					<div class="mem_regist">
						<span class="ico_mem fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]
						<div class="mem_user">
							<span><img src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" /></span>
							<span class="user"><a class="name_ui">지인영</a></span>
						</div>
						<ul>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_orange fw700">접수승인</span> [지인영 : 2013-07-04 11:13:36]
							</li>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_green fw700">작업진행</span> [지인영 : 2013-07-04 11:13:36]
							</li>
						</ul>
					</div>
				</div>
				<!-- //접수승인 상태 -->

					<!-- div class="receipt_veiw_area" id="receipt_section_view">
						<div class="receipt_veiw_frame">
							<div class="status_box">
								<div id="receipt_section">
									<div class="singular_top">
										<p class="count">
											<span class="txt">
												<span><img src="/bizstory/images/icon/icon_03.png" alt="">
												<span>접수분류</span> : </span>웹사이트관련<span><img src="/bizstory/images/icon/icon_03.png" alt=""><span>담당자</span> : </span>
											지인영
													<span><img src="/bizstory/images/icon/icon_03.png" alt=""><span>완료예정일</span> : </span>
											2013-07-10		

							</span>		</p>
	</div>
	<div class="plural_view" id="end_view_32839" style="display:none">
		<div class="info_text">
			<ul>
				<li>담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.
				<span id="status_end_text_32839" style="display:none" class="status_end_text">
					<span><img src="/bizstory/images/icon/icon_04.png" alt="금지"></span> 완료, 취소처리시 수정, 삭제 불가
				</span>
				</li>
			</ul>
		</div>
		<div class="info_status">
			<div class="mem_img">
				<img class="photo" src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" height="80px" width="80px">			</div>
			<div class="info_status_remark">
				<div class="info_status_remark_area">
					<textarea cols="30" rows="5" name="detail_remark_end_32839" id="detail_remark_end_32839" title="완료문구를 입력하세요."></textarea>
				</div>
			</div>
		</div>
	</div>
								<div class="dotted2"></div>
								<div class="status_info" id="status_history_info"><div><span class="icon01"></span> <span style="font-weight:900;color:#ff0000;">접수등록</span> [이영선 : 2013-07-10 09:51:42]</div><div>
										<div class="mem_user">
											<span class="mem"><img src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" height="26px" width="26px"></span>
											<span class="user"><a class="name_ui">지인영</a></span>
										</div>
									<div><span class="icon03"></span> <span style="font-weight:900;color:#ff6c00;">접수승인</span> [김경화 : 2013-07-10 10:21:42]</div></div></div>
							</div>
						</div>
					</div>
					</div -->

				<!-- //업무상태 -->
				
				<!-- 코멘트 -->
				<div id="task_comment" class="comment_box1">
					<div class="comment_top">
						<p class="count"><a href="javascript:void(0)" onclick="comment_view()" id="comment_gate" title="코멘트목록" class="ui-link btn_i_minus"><span class="empty"></span> 코멘트 <span id="comment_total_value">[4]</span></a></p>
						<div class="new" id="comment_new_btn"><img src="/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')"></div>
					</div>

					<div id="new_comment" title="코멘트쓰기"></div>

					<form id="commentlistform" name="commentlistform" method="post" action="/bizstory/m/work_my_view.php">
					<input id="commentlist_sub_type" name="sub_type" value="" type="hidden">
					<input id="commentlist_wi_idx" name="wi_idx" value="3705" type="hidden">
					<input id="commentlist_wc_idx" name="wc_idx" value="" type="hidden">
					<div id="comment_list_data">
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/2/member_2_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">서경원</span>
								<span class="date">2013-02-20 18:16:19</span>
							</div>

							<div class="comment_wrap">
								<div class="comment_data">의정부건은 이의신청 해주세요</div>
							</div>
						</div>

						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/2/member_2_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">서경원</span>
								<span class="date">2013-01-27 21:27:05</span>
							</div>
							<div class="comment_wrap">
								<div class="comment_data">
									안양과천, 안산, 의정부 총3개 까지만진행 3개면 입찰에는 충분함
								</div>
							</div>
						</div>
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-01-25 14:14:27</span>
							</div>
							<div class="comment_wrap">
								<div class="comment_data">
									<p>두 기관에 모두 공지 완료</p><p>_신청기간은 다음주 목(31일)요일까지 </p>
								</div>
							</div>
						</div>
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-01-25 13:43:05</span>
							</div>

							<div class="comment_wrap">
								<div class="comment_data">
									- 모든 기관에 다 알릴까요? 안양과천, 의정부교육지원청
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
				<!-- //코멘트 -->

			</div>
		</div>
	</div>

<?
	include $local_path . "footer.php";
?>