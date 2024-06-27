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
			<h2>업무관리</h2>
		</article>
		<div id="wrapper" class="work">
			<div id="scroller">

				<!-- 안내문구 -->
				<div id="advice">
					<div class="advice-body">
							
						<ul>
							<li>업무종류가 [요청], [알림]일 경우에는 본인선택이 불가능합니다.</li>
							<li>업무종류가 [승인]일 경우 승인자로 지정된 사람은 업무 담당에서 제외됩니다.</li>
							<li>업무종류가 [요청], [승인]일 경우 업무종류변경이 안됩니다.</li>
							<li>업무등록시 승인자를 지정하게 되면 수정이 안됩니다.</li>
							<li>파일 업로드시 파일선택을 "쉬프트키"와 함께 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</li>
						</ul>	

					</div>
					<button class="control-btn"><em>도움말 닫기</em></button>
				</div>
				<script type="text/javascript">$('#advice').mainVisual();</script>
				<!-- //안내문구 -->

				<div class="work_form">
					<table border="1" cellspacing="0" summary="업무등록 폼" class="table03">
						<tr>
							<th class="w100">업무종류</th>
							<td>
								<div class="left">
									<input name="param[charge_idx]" id="post_charge_idx" value="" title="담당자를 선택하세요." type="hidden">
									<input name="param[apply_idx]" id="post_apply_idx" value="" title="승인자를 선택하세요." type="hidden">
									<ul>
										<li>
											<select name="param[work_type]" id="post_work_type" title="업무종류를 선택하세요." onchange="work_type_select();">
												<option value="">:: 업무종류선택 ::</option>
												<option value="WT01" selected="selected">본인</option>
												<option value="WT02">요청</option>
												<option value="WT03">승인</option>
												<option value="WT04">알림</option>
											</select>
										</li>
										<li>
											<span id="apply_view" class="none">ㆍ승인자 지정
											<select name="chk_apply_idx" id="chk_apply_idx" title="승인자 지정을 하세요." onchange="popup_apply_select()">
												<option value="">승인자 지정을 하세요.</option>
												<option value="">(주)유비스토리</option>
												<option value="6">&nbsp;&nbsp;&nbsp;&nbsp;우덕성</option>
												<option value="2">&nbsp;&nbsp;&nbsp;&nbsp;서경원</option>
												<option value="129">&nbsp;&nbsp;&nbsp;&nbsp;정상진</option>
												<option value="15">&nbsp;&nbsp;&nbsp;&nbsp;김나영</option>
												<option value="131">&nbsp;&nbsp;&nbsp;&nbsp;노대성</option>
												<option value="59">&nbsp;&nbsp;&nbsp;&nbsp;김혁</option>
												<option value="8">&nbsp;&nbsp;&nbsp;&nbsp;문은지</option>
												<option value="7">&nbsp;&nbsp;&nbsp;&nbsp;김경화</option>
												<option value="86">&nbsp;&nbsp;&nbsp;&nbsp;지인영</option>
												<option value="486">&nbsp;&nbsp;&nbsp;&nbsp;김정미</option>
												<option value="195">&nbsp;&nbsp;&nbsp;&nbsp;고동원</option>
												<option value="">도서관사업부</option>
												<option value="126">&nbsp;&nbsp;&nbsp;&nbsp;임완선</option>
												<option value="169">&nbsp;&nbsp;&nbsp;&nbsp;고광태</option>
												<option value="10">&nbsp;&nbsp;&nbsp;&nbsp;김용철</option>
												<option value="334">&nbsp;&nbsp;&nbsp;&nbsp;양성기</option>
												<option value="138">&nbsp;&nbsp;&nbsp;&nbsp;최우영(휴맥)</option>
												<option value="151">&nbsp;&nbsp;&nbsp;&nbsp;김진오</option>
												<option value="128">&nbsp;&nbsp;&nbsp;&nbsp;박상규</option>
												<option value="125">&nbsp;&nbsp;&nbsp;&nbsp;이호진</option>
												<option value="134">&nbsp;&nbsp;&nbsp;&nbsp;진태환(광주)</option>
												<option value="136">&nbsp;&nbsp;&nbsp;&nbsp;송준혁(대전)</option>
												<option value="135">&nbsp;&nbsp;&nbsp;&nbsp;송현만(강원)</option>
												<option value="233">&nbsp;&nbsp;&nbsp;&nbsp;유창우</option>
												<option value="400">&nbsp;&nbsp;&nbsp;&nbsp;정순기(e정보)</option>
												<option value="">외부협력업체</option>
												<option value="194">&nbsp;&nbsp;&nbsp;&nbsp;최진우</option>
												<option value="487">&nbsp;&nbsp;&nbsp;&nbsp;김규보</option>
												<option value="352">&nbsp;&nbsp;&nbsp;&nbsp;김성률</option>
												<option value="371">&nbsp;&nbsp;&nbsp;&nbsp;박근형본부장</option>
												<option value="372">&nbsp;&nbsp;&nbsp;&nbsp;박주상</option>
												<option value="364">&nbsp;&nbsp;&nbsp;&nbsp;박홍근</option>
												<option value="270">&nbsp;&nbsp;&nbsp;&nbsp;최석순</option>
												<option value="373">&nbsp;&nbsp;&nbsp;&nbsp;정재덕</option>
												<option value="196">&nbsp;&nbsp;&nbsp;&nbsp;정재문</option>
												<option value="197">&nbsp;&nbsp;&nbsp;&nbsp;이경원</option>
												<option value="330">&nbsp;&nbsp;&nbsp;&nbsp;김남홍</option>
												<option value="165">&nbsp;&nbsp;&nbsp;&nbsp;김희철</option>
												<option value="">브이센터</option>
												<option value="414">&nbsp;&nbsp;&nbsp;&nbsp;김삼성</option>
												<option value="415">&nbsp;&nbsp;&nbsp;&nbsp;윤태정</option>
												<option value="416">&nbsp;&nbsp;&nbsp;&nbsp;여인웅</option>
												<option value="419">&nbsp;&nbsp;&nbsp;&nbsp;김희근</option>
												<option value="418">&nbsp;&nbsp;&nbsp;&nbsp;김욱철</option>
												<option value="410">&nbsp;&nbsp;&nbsp;&nbsp;김대진</option>
												<option value="425">&nbsp;&nbsp;&nbsp;&nbsp;성윤숙</option>
												<option value="447">&nbsp;&nbsp;&nbsp;&nbsp;이은석</option>
												<option value="442">&nbsp;&nbsp;&nbsp;&nbsp;김동수</option>
												<option value="444">&nbsp;&nbsp;&nbsp;&nbsp;최구신</option>
												<option value="443">&nbsp;&nbsp;&nbsp;&nbsp;박강식</option>
												<option value="448">&nbsp;&nbsp;&nbsp;&nbsp;박주석</option>
												<option value="446">&nbsp;&nbsp;&nbsp;&nbsp;최구매</option>
												<option value="449">&nbsp;&nbsp;&nbsp;&nbsp;무선사업부</option>
												<option value="445">&nbsp;&nbsp;&nbsp;&nbsp;이동식</option>
											</select>
											</span>
										</li>
									</ul>
									<div id="charge_view" class="none">
										<div class="charge_view_box">
											<ul>
												<li class="part_name">ㆍ담당자선택</li>
											</ul>

											<ul>
												<li class="first">
													<label for="partidx1">
														<input class="type_checkbox" title="(주)유비스토리" name="partidx1" id="partidx1" onclick="check_all2('partidx1', this, '1'); popup_member_select();" type="checkbox">
														<span style="color:#0075c8">(주)유비스토리</span>
													</label>
													<span onclick="part_charge_chk('1')" class="pointer" id="part_charge_btn_1"> + </span>
												</li>
											</ul>
											<div class="none" id="part_staff_view_1">
												<ul>
													<li class="second">
														<label for="partidx1-1">
															<input class="type_checkbox" title="부설연구소" name="partidx1-1" id="partidx1-1" onclick="check_all2('partidx1-1', this, '0'); popup_member_select();" type="checkbox"> <span>부설연구소</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx1-1_6">
																	<input name="check_member_idx[]" id="partidx1-1_6" value="6" class="type_checkbox" title="우덕성" onclick="popup_member_select();" type="checkbox"> 우덕성
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_129">
																	<input name="check_member_idx[]" id="partidx1-1_129" value="129" class="type_checkbox" title="정상진" onclick="popup_member_select();" type="checkbox"> 정상진
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_15">
																	<input name="check_member_idx[]" id="partidx1-1_15" value="15" class="type_checkbox" title="김나영" onclick="popup_member_select();" type="checkbox"> 김나영
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_131">
																	<input name="check_member_idx[]" id="partidx1-1_131" value="131" class="type_checkbox" title="노대성" onclick="popup_member_select();" type="checkbox"> 노대성
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_59">
																	<input name="check_member_idx[]" id="partidx1-1_59" value="59" class="type_checkbox" title="김혁" onclick="popup_member_select();" type="checkbox"> 김혁
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_8">
																	<input name="check_member_idx[]" id="partidx1-1_8" value="8" class="type_checkbox" title="문은지" onclick="popup_member_select();" type="checkbox"> 문은지
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_7">
																	<input name="check_member_idx[]" id="partidx1-1_7" value="7" class="type_checkbox" title="김경화" onclick="popup_member_select();" type="checkbox"> 김경화
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_86">
																	<input name="check_member_idx[]" id="partidx1-1_86" value="86" class="type_checkbox" title="지인영" onclick="popup_member_select();" type="checkbox"> 지인영
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_486">
																	<input name="check_member_idx[]" id="partidx1-1_486" value="486" class="type_checkbox" title="김정미" onclick="popup_member_select();" type="checkbox"> 김정미
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx1-1_195">
																	<input name="check_member_idx[]" id="partidx1-1_195" value="195" class="type_checkbox" title="고동원" onclick="popup_member_select();" type="checkbox"> 고동원
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx1-3">
															<input class="type_checkbox" title="경영지원" name="partidx1-3" id="partidx1-3" onclick="check_all2('partidx1-3', this, '0'); popup_member_select();" type="checkbox"> <span>경영지원</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx1-3_2">
																	<input name="check_member_idx[]" id="partidx1-3_2" value="2" class="type_checkbox" title="서경원" onclick="popup_member_select();" type="checkbox"> 서경원
																</label>
															</li>
														</ul>
													</li>
												</ul>
											</div>
											<ul>
												<li class="first">
													<label for="partidx11">
														<input class="type_checkbox" title="도서관사업부" name="partidx11" id="partidx11" onclick="check_all2('partidx11', this, '1'); popup_member_select();" type="checkbox">
														<span style="color:#009e25">도서관사업부</span>
													</label>
													<span onclick="part_charge_chk('11')" class="pointer" id="part_charge_btn_11"> + </span>
												</li>
											</ul>
											<div class="none" id="part_staff_view_11">
												<ul>
													<li class="second">
														<label for="partidx11-13">
															<input class="type_checkbox" title="영업부" name="partidx11-13" id="partidx11-13" onclick="check_all2('partidx11-13', this, '0'); popup_member_select();" type="checkbox"> <span>영업부</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx11-13_126">
																	<input name="check_member_idx[]" id="partidx11-13_126" value="126" class="type_checkbox" title="임완선" onclick="popup_member_select();" type="checkbox"> 임완선
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx11-28">
															<input class="type_checkbox" title="SE" name="partidx11-28" id="partidx11-28" onclick="check_all2('partidx11-28', this, '0'); popup_member_select();" type="checkbox"> <span>SE</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx11-28_169">
																	<input name="check_member_idx[]" id="partidx11-28_169" value="169" class="type_checkbox" title="고광태" onclick="popup_member_select();" type="checkbox"> 고광태
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_138">
																	<input name="check_member_idx[]" id="partidx11-28_138" value="138" class="type_checkbox" title="최우영(휴맥)" onclick="popup_member_select();" type="checkbox"> 최우영(휴맥)
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_128">
																	<input name="check_member_idx[]" id="partidx11-28_128" value="128" class="type_checkbox" title="박상규" onclick="popup_member_select();" type="checkbox"> 박상규
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_125">
																	<input name="check_member_idx[]" id="partidx11-28_125" value="125" class="type_checkbox" title="이호진" onclick="popup_member_select();" type="checkbox"> 이호진
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_134">
																	<input name="check_member_idx[]" id="partidx11-28_134" value="134" class="type_checkbox" title="진태환(광주)" onclick="popup_member_select();" type="checkbox"> 진태환(광주)
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_136">
																	<input name="check_member_idx[]" id="partidx11-28_136" value="136" class="type_checkbox" title="송준혁(대전)" onclick="popup_member_select();" type="checkbox"> 송준혁(대전)
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_135">
																	<input name="check_member_idx[]" id="partidx11-28_135" value="135" class="type_checkbox" title="송현만(강원)" onclick="popup_member_select();" type="checkbox"> 송현만(강원)
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_233">
																	<input name="check_member_idx[]" id="partidx11-28_233" value="233" class="type_checkbox" title="유창우" onclick="popup_member_select();" type="checkbox"> 유창우
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-28_400">
																	<input name="check_member_idx[]" id="partidx11-28_400" value="400" class="type_checkbox" title="정순기(e정보)" onclick="popup_member_select();" type="checkbox"> 정순기(e정보)
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx11-30">
															<input class="type_checkbox" title="경영지원" name="partidx11-30" id="partidx11-30" onclick="check_all2('partidx11-30', this, '0'); popup_member_select();" type="checkbox"> <span>경영지원</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx11-30_10">
																	<input name="check_member_idx[]" id="partidx11-30_10" value="10" class="type_checkbox" title="김용철" onclick="popup_member_select();" type="checkbox"> 김용철
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx11-30_334">
																	<input name="check_member_idx[]" id="partidx11-30_334" value="334" class="type_checkbox" title="양성기" onclick="popup_member_select();" type="checkbox"> 양성기
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx11-31">
															<input class="type_checkbox" title="연구개발" name="partidx11-31" id="partidx11-31" onclick="check_all2('partidx11-31', this, '0'); popup_member_select();" type="checkbox"> <span>연구개발</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx11-31_151">
																	<input name="check_member_idx[]" id="partidx11-31_151" value="151" class="type_checkbox" title="김진오" onclick="popup_member_select();" type="checkbox"> 김진오
																</label>
															</li>
														</ul>
													</li>
												</ul>
											</div>
											<ul>
												<li class="first">
													<label for="partidx29">
														<input class="type_checkbox" title="외부협력업체" name="partidx29" id="partidx29" onclick="check_all2('partidx29', this, '1'); popup_member_select();" type="checkbox">
														<span style="color:#3a32c3">외부협력업체</span>
													</label>
													<span onclick="part_charge_chk('29')" class="pointer" id="part_charge_btn_29"> + </span>
												</li>
											</ul>
											<div class="none" id="part_staff_view_29">
												<ul>
													<li class="second">
														<label for="partidx29-37">
															<input class="type_checkbox" title="연구과제협력" name="partidx29-37" id="partidx29-37" onclick="check_all2('partidx29-37', this, '0'); popup_member_select();" type="checkbox"> <span>연구과제협력</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx29-37_194">
																	<input name="check_member_idx[]" id="partidx29-37_194" value="194" class="type_checkbox" title="최진우" onclick="popup_member_select();" type="checkbox"> 최진우
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_371">
																	<input name="check_member_idx[]" id="partidx29-37_371" value="371" class="type_checkbox" title="박근형본부장" onclick="popup_member_select();" type="checkbox"> 박근형본부장
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_372">
																	<input name="check_member_idx[]" id="partidx29-37_372" value="372" class="type_checkbox" title="박주상" onclick="popup_member_select();" type="checkbox"> 박주상
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_364">
																	<input name="check_member_idx[]" id="partidx29-37_364" value="364" class="type_checkbox" title="박홍근" onclick="popup_member_select();" type="checkbox"> 박홍근
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_270">
																	<input name="check_member_idx[]" id="partidx29-37_270" value="270" class="type_checkbox" title="최석순" onclick="popup_member_select();" type="checkbox"> 최석순
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_373">
																	<input name="check_member_idx[]" id="partidx29-37_373" value="373" class="type_checkbox" title="정재덕" onclick="popup_member_select();" type="checkbox"> 정재덕
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_196">
																	<input name="check_member_idx[]" id="partidx29-37_196" value="196" class="type_checkbox" title="정재문" onclick="popup_member_select();" type="checkbox"> 정재문
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_197">
																	<input name="check_member_idx[]" id="partidx29-37_197" value="197" class="type_checkbox" title="이경원" onclick="popup_member_select();" type="checkbox"> 이경원
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-37_330">
																	<input name="check_member_idx[]" id="partidx29-37_330" value="330" class="type_checkbox" title="김남홍" onclick="popup_member_select();" type="checkbox"> 김남홍
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx29-39">
															<input class="type_checkbox" title="외주용역" name="partidx29-39" id="partidx29-39" onclick="check_all2('partidx29-39', this, '0'); popup_member_select();" type="checkbox"> <span>외주용역</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx29-39_487">
																	<input name="check_member_idx[]" id="partidx29-39_487" value="487" class="type_checkbox" title="김규보" onclick="popup_member_select();" type="checkbox"> 김규보
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-39_352">
																	<input name="check_member_idx[]" id="partidx29-39_352" value="352" class="type_checkbox" title="김성률" onclick="popup_member_select();" type="checkbox"> 김성률
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx29-39_165">
																	<input name="check_member_idx[]" id="partidx29-39_165" value="165" class="type_checkbox" title="김희철" onclick="popup_member_select();" type="checkbox"> 김희철
																</label>
															</li>
														</ul>
													</li>
												</ul>
											</div>
											<ul>
												<li class="first">
													<label for="partidx117">
														<input class="type_checkbox" title="브이센터" name="partidx117" id="partidx117" onclick="check_all2('partidx117', this, '1'); popup_member_select();" type="checkbox">
														<span style="color:#7820b9">브이센터</span>
													</label>
													<span onclick="part_charge_chk('117')" class="pointer" id="part_charge_btn_117"> + </span>
												</li>
											</ul>
											<div class="none" id="part_staff_view_117">
												<ul>
													<li class="second">
														<label for="partidx117-169">
															<input class="type_checkbox" title="설계" name="partidx117-169" id="partidx117-169" onclick="check_all2('partidx117-169', this, '0'); popup_member_select();" type="checkbox"> <span>설계</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-169_415">
																	<input name="check_member_idx[]" id="partidx117-169_415" value="415" class="type_checkbox" title="윤태정" onclick="popup_member_select();" type="checkbox"> 윤태정
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-170">
															<input class="type_checkbox" title="연구소" name="partidx117-170" id="partidx117-170" onclick="check_all2('partidx117-170', this, '0'); popup_member_select();" type="checkbox"> <span>연구소</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-170_416">
																	<input name="check_member_idx[]" id="partidx117-170_416" value="416" class="type_checkbox" title="여인웅" onclick="popup_member_select();" type="checkbox"> 여인웅
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-171">
															<input class="type_checkbox" title="영업" name="partidx117-171" id="partidx117-171" onclick="check_all2('partidx117-171', this, '0'); popup_member_select();" type="checkbox"> <span>영업</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-171_414">
																	<input name="check_member_idx[]" id="partidx117-171_414" value="414" class="type_checkbox" title="김삼성" onclick="popup_member_select();" type="checkbox"> 김삼성
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-171_410">
																	<input name="check_member_idx[]" id="partidx117-171_410" value="410" class="type_checkbox" title="김대진" onclick="popup_member_select();" type="checkbox"> 김대진
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-171_449">
																	<input name="check_member_idx[]" id="partidx117-171_449" value="449" class="type_checkbox" title="무선사업부" onclick="popup_member_select();" type="checkbox"> 무선사업부
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-172">
															<input class="type_checkbox" title="관리" name="partidx117-172" id="partidx117-172" onclick="check_all2('partidx117-172', this, '0'); popup_member_select();" type="checkbox"> <span>관리</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-172_419">
																	<input name="check_member_idx[]" id="partidx117-172_419" value="419" class="type_checkbox" title="김희근" onclick="popup_member_select();" type="checkbox"> 김희근
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-172_425">
																	<input name="check_member_idx[]" id="partidx117-172_425" value="425" class="type_checkbox" title="성윤숙" onclick="popup_member_select();" type="checkbox"> 성윤숙
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-173">
															<input class="type_checkbox" title="품질" name="partidx117-173" id="partidx117-173" onclick="check_all2('partidx117-173', this, '0'); popup_member_select();" type="checkbox"> <span>품질</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-173_444">
																	<input name="check_member_idx[]" id="partidx117-173_444" value="444" class="type_checkbox" title="최구신" onclick="popup_member_select();" type="checkbox"> 최구신
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-174">
															<input class="type_checkbox" title="가공" name="partidx117-174" id="partidx117-174" onclick="check_all2('partidx117-174', this, '0'); popup_member_select();" type="checkbox"> <span>가공</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-174_442">
																	<input name="check_member_idx[]" id="partidx117-174_442" value="442" class="type_checkbox" title="김동수" onclick="popup_member_select();" type="checkbox"> 김동수
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-174_443">
																	<input name="check_member_idx[]" id="partidx117-174_443" value="443" class="type_checkbox" title="박강식" onclick="popup_member_select();" type="checkbox"> 박강식
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-175">
															<input class="type_checkbox" title="구매" name="partidx117-175" id="partidx117-175" onclick="check_all2('partidx117-175', this, '0'); popup_member_select();" type="checkbox"> <span>구매</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-175_446">
																	<input name="check_member_idx[]" id="partidx117-175_446" value="446" class="type_checkbox" title="최구매" onclick="popup_member_select();" type="checkbox"> 최구매
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-176">
															<input class="type_checkbox" title="생산" name="partidx117-176" id="partidx117-176" onclick="check_all2('partidx117-176', this, '0'); popup_member_select();" type="checkbox"> <span>생산</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-176_447">
																	<input name="check_member_idx[]" id="partidx117-176_447" value="447" class="type_checkbox" title="이은석" onclick="popup_member_select();" type="checkbox"> 이은석
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-176_448">
																	<input name="check_member_idx[]" id="partidx117-176_448" value="448" class="type_checkbox" title="박주석" onclick="popup_member_select();" type="checkbox"> 박주석
																</label>
															</li>
															<li class="mem_name">
																<label for="partidx117-176_445">
																	<input name="check_member_idx[]" id="partidx117-176_445" value="445" class="type_checkbox" title="이동식" onclick="popup_member_select();" type="checkbox"> 이동식
																</label>
															</li>
														</ul>
													</li>
												</ul>
												<ul>
													<li class="second">
														<label for="partidx117-227">
															<input class="type_checkbox" title="공정" name="partidx117-227" id="partidx117-227" onclick="check_all2('partidx117-227', this, '0'); popup_member_select();" type="checkbox"> <span>공정</span>
														</label>
														<ul>
															<li class="mem_name">
																<label for="partidx117-227_418">
																	<input name="check_member_idx[]" id="partidx117-227_418" value="418" class="type_checkbox" title="김욱철" onclick="popup_member_select();" type="checkbox"> 김욱철
																</label>
															</li>
														</ul>
													</li>
												</ul>
											</div>
										</div>
										<input name="chk_charge_idx" id="chk_charge_idx" value="" title="담당자를 선택하세요." type="hidden" />
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>업무제목</th>
							<td>
								<input name="param[subject]" id="post_subject" title="업무제목을 입력하세요." placeholder="업무제목을 입력하세요." value="" type="text" style="width:97%;" required />
							</td>
						</tr>
						<tr>
							<th>공개여부</th>
							<td>
								<ul>
									<li><label for="post_open_yn_1"><input name="param[open_yn]" id="post_open_yn_1" value="Y" checked="checked" type="radio" />공개</label></li>
									<li><label for="post_open_yn_2"><input name="param[open_yn]" id="post_open_yn_2" value="N" type="radio" />비공개</label></li>
								</ul>
								<div class="field_help">* 업무뿐만 아니라 관련 첨부자료와 코멘트의 상태도 공개여부에 따라 전환됩니다.</div>
							</td>
						</tr>
						<tr>
							<th>기한</th>
							<td>
								<ul>
									<li>
										<select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, 'deadline_date_view')">
						
											<option value="2013-08-12">2013-08-12 (오늘)</option>
											<option value="2013-08-13">2013-08-13 (내일)</option>
											<option value="2013-08-14">2013-08-14 (수)</option>
											<option value="2013-08-15">2013-08-15 (목)</option>
											<option value="2013-08-16">2013-08-16 (금)</option>				<option value="-">---------------</option>
											<option value="select">직접선택하기</option>
										</select>
									</li>
									<li>
										<span id="deadline_date_view" class="none">
											<input name="deadline_date2" id="post_deadline_date2" class="type_text datepicker hasDatepicker" title="기한을 입력하세요." size="10" value="2013-08-12" type="text"><img title="..." alt="..." src="/bizstory/images/btn/calendar.png" class="ui-datepicker-trigger">
										</span>
									</li>
									<li>
										<select name="deadline_str1" id="post_deadline_str1" title="덧붙이기(선택사항)" onchange="deadline_str_view(this.value, 'deadline_str_view')">
											<option value="">덧붙이기(선택사항)</option>
											<option value="WD01">가능한 빨리</option>
											<option value="WD02">오전까지</option>
											<option value="WD03">점심 전까지</option>
											<option value="WD04">오후까지</option>
											<option value="WD05">퇴근 전까지</option>
											<option value="WD06">-------------</option>
											<option value="select">직접입력하기</option>
										</select>
									</li>
									<li>
										<span id="deadline_str_view" class="none">
											<input name="deadline_str2" id="post_deadline_str2" class="type_text" title="직접입력하세요." size="20" type="text">
										</span>
									</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th>분류</th>
							<td>
								<div class="left">
									<input name="param[work_class]" id="post_work_class" value="1" type="hidden">
									<div id="work_class_view_btn">
										<a href="javascript:void(0);" onclick="popup_work_class('post_work_class', 'work_class_view')">수정하기</a>
									</div>
									<div class="field_help" id="work_class_view_select">일반업무</div>
									<div id="work_class_view">

										<div id="work_class_view" class="work_class">
											<div id="work_class_list">	
												<div class="work_class_list">
													<ul>
														<li>
															<label for="codeidx_0"><input name="check_code_idx" id="codeidx_0" value="1" checked="checked" type="radio" />일반업무</label>
														</li>
														<li>
															<label for="codeidx_1"><input name="check_code_idx" id="codeidx_1" value="11" type="radio" />개발사항</label>
														</li>
														<li>
															<label for="codeidx_2"><input name="check_code_idx" id="codeidx_2" value="12" type="radio" />영업관련</label>
														</li>
														<li>
															<label for="codeidx_3"><input name="check_code_idx" id="codeidx_3" value="13" type="radio" />디자인관련</label>
														</li>
														<li>
															<label for="codeidx_4"><input name="check_code_idx" id="codeidx_4" value="10" type="radio" />경영지원</label>
														</li>
														<li>
															<label for="codeidx_5"><input name="check_code_idx" id="codeidx_5" value="9" type="radio" />주간보고서</label>
														</li>
													</ul>
												</div>
											</div>

											<div class="dotted"></div>

											<div class="work_class_form">
												<input name="work_class_str" id="work_class_str" title="업무분류를 입력하세요." class="type_text" type="text" />
												<a href="javascript:void(0);" onclick="check_work_class_insert();" class="btn"><span>추가</span></a>
											</div>

											<div class="work_class_top">
												<div class="new">
													<a href="javascript:void(0);" onclick="popup_work_class_select('post_work_class', 'work_class_view');" class="btn_sml2"><span>확인</span></a>
													<a href="javascript:void(0);" onclick="$('#work_class_view').html('');" class="btn_sml2"><span>닫기</span></a>
												</div>
											</div>
										</div>

									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>중요도</th>
							<td>
								<ul>
									<li><label for="post_important_1"><input name="param[important]" id="post_important_1" value="WI01" checked="checked" type="radio" />해당없음</label></li>
									<li><label for="post_important_2"><input name="param[important]" id="post_important_2" value="WI02" type="radio" />상</label></li>
									<li><label for="post_important_3"><input name="param[important]" id="post_important_3" value="WI03" type="radio" />중</label></li>
									<li><label for="post_important_4"><input name="param[important]" id="post_important_4" value="WI04" type="radio" />하</label></li>
								</ul>
							</td>
						</tr>
						<tr>
							<th>내용</th>
							<td style="width:88%;">
								<textarea style="display:none;" name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"></textarea><iframe src="/bizstory/editor/smarteditor/SmartEditor2Skin.html" style="width:100% !important; height: 449px;" frameborder="0" scrolling="no"></iframe>
							</td>
						</tr>
						<tr>
							<th>첨부파일</th>
							<td>
								<div class="filewrap">
									<input style="display: none;" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" type="file"><object style="visibility: visible;" id="file_fnameUploader" data="/common/upload/uploadify.swf" type="application/x-shockwave-flash" height="30" width="82"><param value="high" name="quality"><param value="transparent" name="wmode"><param value="sameDomain" name="allowScriptAccess"><param value="uploadifyID=file_fname&amp;pagepath=/&amp;buttonImg=/common/upload/file_submit.gif&amp;script=/common/upload/uploadify_multi.php&amp;folder=/data/tmp&amp;scriptData=upload_name%3Dfile_fname%26add_name%3Dmessage%26file_max%3D157286400%26upload_ext%3D%26sort%3D1&amp;width=82&amp;height=30&amp;wmode=transparent&amp;method=POST&amp;queueSizeLimit=999&amp;simUploadLimit=1&amp;multi=true&amp;auto=true&amp;fileDataName=Filedata" name="flashvars"></object>
									<div id="file_fnameQueue" class="uploadifyQueue"></div>
									<div class="file">
										<ul id="file_fname_view"></ul>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>

				<div class="ta_c mb15">
					<ul>
						<li><a href="./work_list.php"><span class="btn_g">등록</span></a></li>
						<li><a href="./work_list.php"><span class="btn_v">취소</span></a></li>
					</ul>
				</div>

			</div>
		</div>
	</div>

	<script>
		$(document).ready(
			function(){
				$('input[type=radio]').ezMark();
				$('input[type=checkbox]').ezMark();
			}
		);
	</script>

<?
	include $local_path . "footer.php";
?>