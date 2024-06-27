<?
/*
	생성 : 2013.05.20
	수정 : 2013.05.20
	위치 : 설정폴더(관리자) > 업체관리 > 삭제업체 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$comp_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
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
		<input type="hidden" name="sclass"  value="' . $send_sclass . '" />
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

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y' && $comp_idx != '') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
		$where = " and comp.comp_idx = '" . $comp_idx . "'";
		$data = company_info_data("view", $where, '', '', '', 2);

		$data["start_date"] = date_replace($data["start_date"], 'Y-m-d');
		$data["end_date"]   = date_replace($data["end_date"], 'Y-m-d');
		$data['address'] = str_replace('||', ' ', $data['address']);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">업체정보 폼</legend>

				<div class="sub_frame"><h4>업체정보</h4></div>
				<table class="tinytable write" summary="업체정보를 등록/수정합니다.">
					<caption>업체정보</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="110px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th>총판</th>
							<td>
								<div class="left"><?=$data['sole_name'];?></div>
							</td>
							<th>업체분류</th>
							<td>
								<div class="left"><?=$data['comp_class_str'];?></div>
							</td>
						</tr>
						<tr>
							<th>상호명</th>
							<td>
								<div class="left"><?=$data['comp_name'];?></div>
							</td>
							<th>사업자등록번호</th>
							<td>
								<div class="left"><?=$data['comp_num'];?></div>
							</td>
						</tr>
						<tr>
							<th>대표자명</th>
							<td>
								<div class="left"><?=$data['boss_name'];?></div>
							</td>
							<th>고유번호</th>
							<td>
								<div class="left"><?=$data['distinct_num'];?></div>
							</td>
						</tr>
						<tr>
							<th>업종</th>
							<td>
								<div class="left"><?=$data['upjong'];?></div>
							</td>
							<th>업태</th>
							<td>
								<div class="left"><?=$data['uptae'];?></div>
							</td>
						</tr>
						<tr>
							<th>사업장주소</th>
							<td colspan="3">
								<div class="left"><?=$data['zip_code'];?></div>
								<div class="left mt"><?=$data['address'];?></div>
							</td>
						</tr>
						<tr>
							<th>전화번호</th>
							<td>
								<div class="left"><?=$data['tel_num'];?></div>
							</td>
							<th>팩스번호</th>
							<td>
								<div class="left"><?=$data['fax_num'];?></div>
							</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td colspan="3">
								<div class="left"><?=$data['comp_email'];?></div>
							</td>
						</tr>
						<tr>
							<th>담당자</th>
							<td>
								<div class="left"><?=$data['charge_name'];?></div>
							</td>
							<th>핸드폰 번호</th>
							<td>
								<div class="left"><?=$data['hp_num'];?></div>
							</td>
						</tr>
						<tr>
							<th>시작일</th>
							<td>
								<div class="left"><?=date_replace($data['start_date'], 'Y-m-d');?></div>
							</td>
							<th>종료일</th>
							<td>
								<div class="left"><?=date_replace($data['end_date'], 'Y-m-d');?></div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="sub_frame"><h4>업체설정</h4></div>
				<table class="tinytable write" summary="업체설정을 등록/수정합니다.">
					<caption>업체설정</caption>
					<colgroup>
						<col width="100px" />
						<col />
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th>서비스 가격</th>
							<td>
								<div class="left"><?=number_format($data['use_price']);?>원</div>
							</td>
							<th>메인화면</th>
							<td>
								<div class="left"><?=$set_main_type[$data['main_type']];?></div>
							</td>
						</tr>
						<tr>
							<th>세금계산서</th>
							<td colspan="3">
								<div class="left"><?=$set_use[$data['tax_yn']];?></div>
							</td>
						</tr>
						<tr>
							<th>지사통합</th>
							<td>
								<div class="left"><?=$set_use[$data['part_yn']];?></div>
							</td>
							<th>업무지사통합</th>
							<td>
								<div class="left"><?=$set_use[$data['part_work_yn']];?></div>
							</td>
						</tr>
						<tr>
							<th>지사수</th>
							<td>
								<div class="left"><?=number_format($data['part_cnt']);?>개</div>
							</td>
							<th>거래처수</th>
							<td>
								<div class="left"><?=number_format($data['client_cnt']);?>개</div>
							</td>
						</tr>
						<tr>
							<th>직원수</th>
							<td>
								<div class="left"><?=number_format($data['staff_cnt']);?>개</div>
							</td>
							<th>배너수</th>
							<td>
								<div class="left"><?=number_format($data['banner_cnt']);?>개</div>
							</td>
						</tr>
						<tr>
							<th>저장공간</th>
							<td>
								<div class="left"><?=$data['volume_num'];?>GByte</div>
							</td>
							<th>뷰어기능</th>
							<td>
								<div class="left"><?=$set_use[$data['viewer_yn']];?></div>
							</td>
						</tr>
						<tr>
							<th>에이전트사용</th>
							<td>
								<div class="left"><?=$set_use[$data['agent_yn']];?></div>
							</td>
							<th>에이전트타입</th>
							<td>
								<div class="left"><?=$data["agent_type"];?></div>
							</td>
						</tr>
						<tr>
							<th>파일공간</th>
							<td>
								<div class="left"><?=$set_file_class[$data['file_class']];?></div>
							</td>
							<th>외부파일주소</th>
							<td>
								<div class="left"><?=$data['file_out_url'];?></div>
							</td>
						</tr>
						<tr>
							<th>파일센터</th>
							<td colspan="3">
								<div class="left"><?=$set_use_num[$data['filecenter_yn']];?></div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<?
	}
?>