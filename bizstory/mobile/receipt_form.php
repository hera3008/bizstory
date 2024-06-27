<?
	include $local_path . "header.php";
?>

<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>

		<!-- 안내문구 -->
		<div id="advice">
			<div class="advice-body">
					
				<ul>
					<li>파일 업로드시 파일선택을 "쉬프트키"와 함께 다중선택하시면 한번에 여러개 파일을 올릴수 있습니다.</li>
				</ul>	

			</div>
			<button class="control-btn"><em>도움말 닫기</em></button>
		</div>
		<script type="text/javascript">$('#advice').mainVisual();</script>
		<!-- //안내문구 -->

	</div>

	<div id="content">
		<article class="mt_4">
			<h2>접수등록</h2>
		</article>
		<div id="wrapper" class="work">
			<div id="scroller">

				<div class="work_form">
					<table border="1" cellspacing="0" summary="접수등록 폼" class="table03">
						<caption>접수등록 폼</caption>
						<tbody>
							<tr>
								<th>지사</th>
								<td>(주)유비스토리</td>
							</tr>
							<tr>
								<th>거래처명</th>
								<td>
									<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요" onchange="check_client_info(this.value)">
										<option value="">거래처를 선택하세요</option><option value="127">(사)코리아콘서트오케스트라</option><option value="1157">(주)북션커뮤니케이션</option><option value="948">(주)산하정보기술</option><option value="39">LIPA 어학원</option><option value="502">경기도고양교육지원청</option><option value="19">경기도교육복지종합센터</option><option value="123">경기도교육연구원 교육정보실</option><option value="7">경기도교육청학부모지원</option><option value="25">경기도립중앙도서관</option><option value="501">경기도화성오산교육지원청</option><option value="50">고양교육지원청_이나누미</option><option value="13">고양시방과후학교_교육기부</option><option value="9">과천시보건소</option><option value="8">과천시복지정보센터</option><option value="11">과천시청</option><option value="10">과천시청소년수련관</option><option value="130">광명교육지원청 컨설팅 장학</option><option value="128">국립중앙과학관</option><option value="5">국사편찬위원회</option><option value="1">군포방과후학교</option><option value="131">군포의왕교육지원청</option><option value="468">그린텔(주)</option><option value="122">김포분관_도립중앙도서관</option><option value="38">다솔테크놀로지</option><option value="32">도봉문화정보센터</option><option value="33">도봉아이나라</option><option value="949">라이브컬쳐</option><option value="26">방과후지원센터-군포의왕교육지원청</option><option value="37">보평중학교</option><option value="18">부천시교육지원청</option><option value="28">불곡중학교(나우테크)</option><option value="859">서초어린이도서관</option><option value="129">성남도서관</option><option value="1155">성북구시설관리공단</option><option value="951">성일고등학교</option><option value="16">세린교회</option><option value="29">송탄보건소(다현)</option><option value="880">수아</option><option value="171">수향식품(주)</option><option value="293">시흥시 생명농업기술센터</option><option value="24">시흥시상수도과(알토비즈)</option><option value="584">시흥시청</option><option value="235">시흥시평생학습센터</option><option value="14">아르코미술관</option><option value="503">아주물산(주)</option><option value="22">안산교육지원청(네오딕)</option><option value="12">안산방과후학교지원센터</option><option value="40">안양과천_위센터</option><option value="20">안양과천교육지원청(웅일)</option><option value="15">안양과천교육지원청-컨설팅</option><option value="855">안양대학교 산학협력단</option><option value="23">안양시립도서관(인컴)</option><option value="947">안양지식산업진흥원</option><option value="3">안양진흥원앱창작터</option><option value="124">여주분관-도립중앙도서관</option><option value="126">영동군청</option><option value="27">영재교육지원센터-군포의왕교육지원청</option><option value="929">유비스토리 연구과제비</option><option value="125">의정부교육지원청</option><option value="295">의정부컨설팅장학</option><option value="457">중랑구립면목정보도서관</option><option value="31">중랑구립정보도서관</option><option value="469">중랑숲어린이도서관</option><option value="30">평택보건소(다현)</option><option value="291">평택분관_도립중앙도서관</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>작성자</th>
								<td><input name="param[writer]" id="post_writer" class="type_text" title="작성자를 입력하세요." value="김경화" type="text" /></td>
							</tr>
							<tr>
								<th>연락처</th>
								<td><input name="param[tel_num]" id="post_tel_num" class="type_text" title="전화번호를 입력하세요." size="15" value="011-9009-0957" type="text" /></td>
							</tr>
							<tr>
								<th>분류</th>
								<td>
									<select name="param[receipt_class]" id="post_receipt_class" title="분류를 선택하세요">
										<option value="">분류를 선택하세요</option><option value="1" selected="selected">웹사이트관련</option><option value="2">&nbsp;&nbsp;&nbsp;컨텐츠 수정/추가</option><option value="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;내용수정</option><option value="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;메뉴수정</option><option value="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;배너추가</option><option value="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;팝업추가</option><option value="11">&nbsp;&nbsp;&nbsp;오류수정요청</option><option value="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사이트오류</option><option value="13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;시스템오류</option><option value="14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;기타오류</option><option value="46">하드웨어관련</option><option value="47">문의사항관련</option><option value="9">&nbsp;&nbsp;&nbsp;사이트문의</option><option value="8">&nbsp;&nbsp;&nbsp;관공서공문</option><option value="10">&nbsp;&nbsp;&nbsp;기타문의</option>
									</select>
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
								<th>제목</th>
								<td>
									<input name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="25" value="" type="text" />
								</td>
							</tr>
							<tr>
								<th>내용</th>
								<td>
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
						</tbody>
					</table>
				</div>

				<div class="ta_c">
					<ul>
						<li><a href="./receipt_list.php"><span class="btn_g">등록</span></a></li>
						<li><a href="./receipt_list.php"><span class="btn_v">취소</span></a></li>
					</ul>
				</div>

			</div>
		</div>
	</div>

	<script>
		// 라디로 체크박스 관련
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