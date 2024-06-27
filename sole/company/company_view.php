<?
/*
	생성 : 2012.12.11
	수정 : 2012.12.11
	위치 : 총판관리 > 업체목록 - 보기
*/
	require_once "../../bizstory/common/setting.php";
	require_once "../../bizstory/common/no_direct.php";
	require_once "../common/member_chk.php";

	$comp_idx = $idx;

	$where = " and comp.comp_idx = '" . $comp_idx . "'";
	$data = company_info_data("view", $where);

	$data["start_date"] = date_replace($data["start_date"], 'Y-m-d');
	$data["end_date"]   = date_replace($data["end_date"], 'Y-m-d');

	if ($data["auth_yn"] == "") $data["auth_yn"] = "N";
	if ($data["start_date"] == "") $data["start_date"] = $data["auth_date"];
	$data['address1'] = str_replace('||', ' ', $data['address1']);

	$set_where = " and cs.comp_idx = '" . $comp_idx . "'";
	$set_data = company_setting_data("view", $set_where);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<fieldset>
			<legend class="blind">업체정보 상세보기</legend>

			<table class="tinytable write" summary="업체정보 상세내용입니다.">
			<caption>업체정보</caption>
			<colgroup>
				<col width="110px" />
				<col />
				<col width="110px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>업체분류</th>
					<td colspan="3">
						<div class="left"><?=$data["comp_class_str"];?></div>
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
						<div class="left">
							<?=$data['zip_code'];?>
						</div>
						<div class="left mt">
							<?=$data['address1'];?>
						</div>
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
						<div class="left"><?=$data['start_date'];?></div>
					</td>
					<th>종료일</th>
					<td>
						<div class="left"><?=$data['end_date'];?></div>
					</td>
				</tr>
			</tbody>
			</table>

			<table class="tinytable write" summary="업체설정에 대한 상세정보입니다.">
			<caption>업체설정</caption>
			<colgroup>
				<col width="110px" />
				<col />
				<col width="110px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>서비스 가격</th>
					<td>
						<div class="left"><?=number_format($set_data['use_price']);?> 원</div>
					</td>
					<th>메인화면</th>
					<td>
						<div class="left"><?=$set_main_type[$set_data['main_type']];?></div>
					</td>
				</tr>
				<tr>
					<th>지사통합</th>
					<td colspan="3">
						<div class="left"><?=$set_use[$set_data['part_yn']];?> * 'N'일 경우 지사끼리 데이타를 볼 수 없습니다.</div>
					</td>
				</tr>
				<tr>
					<th>업무지사통합</th>
					<td colspan="3">
						<div class="left"><?=$set_use[$set_data['part_work_yn']];?> * 'N'일 경우 지사끼리 업무를 볼 수 없습니다.</div>
					</td>
				</tr>
				<tr>
					<th>지사수</th>
					<td>
						<div class="left"><?=number_format($set_data['part_cnt']);?> 개</div>
					</td>
					<th>거래처수</th>
					<td>
						<div class="left"><?=number_format($set_data['client_cnt']);?>개</div>
					</td>
				</tr>
				<tr>
					<th>직원수</th>
					<td>
						<div class="left"><?=number_format($set_data['staff_cnt']);?> 개</div>
					</td>
					<th>배너수</th>
					<td>
						<div class="left"><?=number_format($set_data['banner_cnt']);?> 개</div>
					</td>
				</tr>
				<tr>
					<th>저장공간</th>
					<td>
						<div class="left"><?=$set_data['volume_num'];?> GByte</div>
					</td>
					<th>뷰어기능</th>
					<td>
						<div class="left"><?=$set_use[$set_data['viewer_yn']];?></div>
					</td>
				</tr>
				<tr>
					<th>에이전트사용</th>
					<td colspan="3">
						<div class="left"><?=$set_use[$set_data['agent_yn']];?></div>
					</td>
				</tr>
				<tr>
					<th>에이전트타입</th>
					<td colspan="3">
						<div class="left">
				<?
					$agent_type = explode(',', $set_data['agent_type']);
					foreach ($agent_type as $agent_k => $agent_v)
					{
						if ($agent_k > 0)
						{
							echo ', ';
						}
						echo $set_agent_type[$agent_v];
					}
				?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<span class="btn_big"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>

		</fieldset>
	</div>
</div>