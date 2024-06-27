<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비월별 - 상세목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
	$set_part_yn = $company_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ai.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ai.part_idx = '" . $code_part . "'";
	if ($acc_month != '') $where .= " and date_format(ai.account_date, '%Y-%m') = '" . $acc_month . "'";
	else $where .= " and date_format(ai.account_date, '%Y') = '" . $asyear . "'";
	if ($class_code != '') $where .= " and ai.class_code = '" . $class_code . "'";

	$orderby = 'ai.account_date asc';
	$list = account_info_data('list', $where, $orderby, '', '');
    
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<table class="tinytable">
			<colgroup>
				<col width="30px" />
				<col width="60px" />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
				<col width="150px" />
				<col width="80px" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort">No</th>
					<th class="nosort"><h3>종류</h3></th>
					<th class="nosort"><h3>날짜</h3></th>
					<th class="nosort"><h3>구분</h3></th>
					<th class="nosort"><h3>구분2</h3></th>
					<th class="nosort"><h3>계정</h3></th>
					<th class="nosort"><h3>금액</h3></th>
					<th class="nosort"><h3>적요</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="8">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
			// 구분
				$gubun_code_name = '<span style="';
				if ($data['gubun_code_bold'] == 'Y') $gubun_code_name .= 'font-weight:900;';
				if ($data['gubun_code_color'] != '') $gubun_code_name .= 'color:' . $data['gubun_code_color'] . ';';
				$gubun_code_name .= '">' . $data["gubun_code_name"] . '</span>';

			// 계정
				$class_code_name = '<span style="';
				if ($data['class_code_bold'] == 'Y') $class_code_name .= 'font-weight:900;';
				if ($data['class_code_color'] != '') $class_code_name .= 'color:' . $data['class_code_color'] . ';';
				$class_code_name .= '">' . $data["class_code_name"] . '(' . $data['class_code_value'] . ')</span>';

				if ($data['account_type'] == 'OUT')
				{
					$total_account_price -= $data['account_price'];
				}
				else
				{
					$total_account_price += $data['account_price'];
				}
                
            // 구분2
                if ($data['gubun_code'] == 'bank')
                {
                    $gubun_string = $data['bank_code_name'];
                }
                else if ($data['gubun_code'] == 'card')
                {
                    $gubun_string = $data['card_code_name'];
                }
                else
                {
                    $gubun_string = '';
                }
?>
				<tr>
					<td><?=$i;?></td>
					<td><?=$set_account_type[$data['account_type']];?></td>
					<td><span class="eng"><?=$data['account_date'];?></span></td>
					<td><?=$gubun_code_name;?></td>
					<td><?=$gubun_string;?></td>
					<td><?=$class_code_name;?></td>
					<td><span class="eng right"><?=number_format($data['account_price']);?></span></td>
					<td><div class="left"><?=$data['content'];?></div></td>
				</tr>
<?
				$i++;
			}
		}
	}
?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">합계</td>
					<td colspan="2"><span class="eng right"><?=number_format($total_account_price);?></span></td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
		</table>

		<div class="section">
			<div class="fr">
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
			</div>
		</div>

	</div>
</div>