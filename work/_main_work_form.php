
		<div id="popup_write" class="ajax_write">
			<div class="ajax_frame">
				<form name="popup_writeform" class="writeform" method="post" action="./">
				<fieldset>
					<legend>글쓰기 폼</legend>
					<div class="warning">
						<div class="margin">
							<h4>등록 하는데 문제가 있습니다.</h4>
							<ul>
								<li><label for="popup_upa_idx" class="error">지사를 선택해 주십시오.</label></li>
							</ul>
						</div>
					</div>
					<table class="tinytable write" summary="업무종류, 직원선택, 부서선택 및 내용과 파일을 등록합니다.">
					<caption>글작성</caption>
					<colgroup>
						<col width="75px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th><span>업무종류</span></th>
							<td>
								<div class="left">
									<ul>
										<li>
											<label for="popup_work_class1">
												<input type="radio" name="param[popup_work_class]" id="popup_work_class1" class="type_radio" title="본인" />
												<span>본인</span>
											</label>
										</li>
										<li>
											<label for="popup_work_class2">
												<input type="radio" name="param[popup_work_class]" id="popup_work_class2" class="type_radio" title="요청" />
												<span>요청</span>
											</label>
										</li>
										<li>
											<label for="popup_work_class3">
												<input type="radio" name="param[popup_work_class]" id="popup_work_class3" class="type_radio" title="이슈" />
												<span>이슈</span>
											</label>
										</li>
										<li>
											<label for="popup_work_class4">
												<input type="radio" name="param[popup_work_class]" id="popup_work_class4" class="type_radio" title="공통" />
												<span>공통</span>
											</label>
										</li>
										<li>
											<label for="popup_work_class5">
												<input type="radio" name="param[popup_work_class]" id="popup_work_class5" class="type_radio" title="업무일지" />
												<span>업무일지</span>
											</label>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="popup_upa_idx">직원선택</label></th>
							<td>
								<div class="left">
									<select id="popup_upa_idx" name="param[popup_upa_idx]" title="지사를 선택해 주십시오." size="4" class="{validate:{required:true}}">
										<option value="">지사 선택하세요</option>
										<option value="11">부설연구소(이전데이터)</option>
										<option value="100">이음스토리</option>
										<option value="101">유비스토리</option>
									</select>
									<select id="popup_ui_idx" name="param[popup_ui_idx]" title="직원을 선택해 주십시오." size="4" class="{validate:{required:true}}">
										<option value="">직원 선택하세요</option>
										<option value="30">김경화</option>
										<option value="317">김봉수</option>
										<option value="350">김성율</option>
										<option value="347">김용철</option>
										<option value="343">김희진</option>
										<option value="32">문은지</option>
										<option value="26">서경원</option>
										<option value="346">최예옥</option>
										<option value="348">황용구</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="popup_chk_100">부서선택</label></th>
							<td>
								<div class="left">
									<ul>
										<li class="first">
											<label for="popup_chk_100">
												<input type="checkbox" name="popup_chk_100" id="popup_chk_100" class="type_checkbox" title="이음스토리" />
												<span>이음스토리</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_0">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_0]" id="popup_upa_ui_idx_0" class="type_checkbox" title="김성율" />
												<span>김성율</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_1">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_1]" id="popup_upa_ui_idx_1" class="type_checkbox" title="정은솔" />
												<span>정은솔</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_2">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_2]" id="popup_upa_ui_idx_2" class="type_checkbox" title="정주영" />
												<span>정주영</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_3">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_3]" id="popup_upa_ui_idx_3" class="type_checkbox" title="황용구" />
												<span>황용구</span>
											</label>
										</li>
									</ul>
									<ul>
										<li class="first">
											<label for="popup_chk_101">
												<input type="checkbox" name="popup_chk_101" id="popup_chk_101" class="type_checkbox" title="이음스토리" />
												<span>유비스토리</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_4">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_4]" id="popup_upa_ui_idx_4" class="type_checkbox" title="김경화" />
												<span>김경화</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_5">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_5]" id="popup_upa_ui_idx_5" class="type_checkbox" title="김용철" />
												<span>김용철</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_6">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_6]" id="popup_upa_ui_idx_6" class="type_checkbox" title="문은지" />
												<span>문은지</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_7">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_7]" id="popup_upa_ui_idx_7" class="type_checkbox" title="서경원" />
												<span>서경원</span>
											</label>
										</li>
										<li>
											<label for="popup_upa_ui_idx_8">
												<input type="checkbox" name="user_param[popup_upa_ui_idx_8]" id="popup_upa_ui_idx_8" class="type_checkbox" title="최예옥" />
												<span>최예옥</span>
											</label>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div class="left">
									<textarea name="param[popup_memo]" id="popup_memo" title="내용" cols="40" rows="20">에디터 삽입</textarea>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="popup_file_name1">일반타입</label></th>
							<td>
								<div class="left file">
									<input type="file" name="popup_file_name1" id="popup_file_name1" class="type_text type_file type_basic {validate:{required:true,accept:'docx?|txt|pdf'}}" title="일반타입 파일 찾아보기" size="40" />
									<a href="./" title="추가" onclick="" class="file_plus">추가</a>
								</div>
							</td>
						</tr>
					</tbody>
					</table>
					<div class="section">
						<!-- Button -->
						<span class="btn_big fr"><input type="submit" value="글작성" /></span>
						<!-- Button -->
					</div>
				</fieldset>
				</form>
			</div>
		</div>