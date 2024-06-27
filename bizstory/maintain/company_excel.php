<?
	$file_name = iconv("UTF-8", "EUC-KR", "업체_" . date("YmdHis"));

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");

	include "../common/setting.php";
	include "../common/no_direct.php";
	include "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = '';
	if ($stext != '')
	{
		if ($swhere == 'comp.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$where .= " and (
				replace(comp.tel_num, '-', '') like '%" . $stext . "%'
				or replace(comp.fax_num, '-', '') like '%" . $stext . "%'
				or replace(comp.hp_num, '-', '') like '%" . $stext . "%'
			)";
		}
		else if ($swhere != '')
		{
			$where .= " and " . $swhere . " like '%" . $stext . "%'";
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'comp.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

	$list = company_info_data('list', $where, $orderby, '', '');
?>
<table border="1">
	<colgroup>
		<col />
	</colgroup>
	<thead>
		<tr>
			<th>번호</th>
			<th>업체명</th>
			<th>도메인</th>
			<th>대표자명</th>
			<th>사업자주소</th>
			<th>이메일주소</th>
			<th>전화번호</th>
			<th>팩스번호</th>
			<th>핸드폰번호</th>
			<th>우편번호</th>
			<th>주소</th>
			<th>업종</th>
			<th>업태</th>
			<th>담당자명</th>
			<th>시작일</th>
			<th>만료일</th>
			<th>승인</th>
			<th>승인일</th>
			<th>남은일수</th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="19">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$data['start_date'] = date_replace($data["start_date"], 'Y-m-d');
				$data['end_date']   = date_replace($data["end_date"], 'Y-m-d');
				$data['auth_date']  = date_replace($data["auth_date"], 'Y-m-d');
				$data["address"]    = str_replace('||', ' ', $data["address"]);

				$data_date = query_view("select datediff('" . $data['end_date'] . "', '" . date("Y-m-d") . "') as remain_days");
?>
		<tr>
			<td><?=$num;?></td>
			<td><?=$data["comp_name"];?></td>
			<td><?=$data["comp_domain"];?></td>
			<td><?=$data["boss_name"];?></td>
			<td><?=$data["comp_num"];?></td>
			<td><?=$data["comp_email"];?></td>
			<td><?=$data["tel_num"];?></td>
			<td><?=$data["fax_num"];?></td>
			<td><?=$data["hp_num"];?></td>
			<td><?=$data["zip_code"];?></td>
			<td><?=$data["address"];?></td>
			<td><?=$data["upjong"];?></td>
			<td><?=$data["uptae"];?></td>
			<td><?=$data["charge_name"];?></td>
			<td><?=$data["start_date"];?></td>
			<td><?=$data["end_date"];?></td>
			<td><?=$data["auth_yn"];?></td>
			<td><?=$data["auth_date"];?></td>
			<td><?=$data_date['remain_days'];?></td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
	</tbody>
</table>