<?
/*
	수정 : 2012.11.30
	위치 : 회계업무 > 운영비관리 - 일괄등록확인
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$data_path = $comp_account_path;
	files_dir($data_path);

	$chk_file_save = $_POST['file_fname1_save_name'];

	if ($chk_file_save != '')
	{
		$chk_file_name = $_POST['file_fname1_file_name'];
		$chk_file_size = $_POST['file_fname1_file_size'];
		$chk_file_ext  = $_POST['file_fname1_file_ext'];

		$account_file = $tmp_path . '/' . $chk_file_save;

	// 확장자확인
		if ($chk_file_ext != 'csv')
		{
		// 저장된 파일 삭제
			@unlink($account_file);

			$str = '{"success_chk" : "N", "error_string" : "파일확장자는 .csv만 가능합니다."}';
			echo $str;
			exit;
		}

	// 등록된 파일을 가지고 보여주기
		$file_chk = file($account_file);
		$line_num = 0;
		foreach ($file_chk as $k => $v)
		{
			$line = han_utf($v);
			$line_arr = explode(',', $line);
			$field_num = 0;
			foreach ($line_arr as $line_k => $line_v)
			{
				$line_v = str_replace('"', '', $line_v);
				if ($line_k > 8)
				{
					$list_data[$line_num][$field_num] .= ', ' . $line_v;
				}
				else
				{
					$list_data[$line_num][$line_k] = $line_v;
					$field_num = $line_k;
				}
			}
			$line_num++;
		}
		@unlink($account_file); // 등록된 파일 삭제

		if ($line_num > 0)
		{
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_text">
			<ul>
				<li>입력한 내용이 맞으시면 '확인'을 클릭해주세요.</li>
			</ul>
		</div>

		<form id="insertform" name="insertform" method="post" class="writeform" action="<?=$this_page;?>">

		<fieldset>
			<legend class="blind">파일업로드 확인</legend>

			<table class="tinytable">
				<colgroup>
					<col width="50px" />
					<col width="60px" />
					<col width="80px" />
					<col width="80px" />
					<col width="100px" />
					<col width="60px" />
					<col width="150px" />
					<col width="180px" />
					<col width="80px" />
					<col width="80px" />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th class="nosort"><h3>NO</h3></th>
						<th class="nosort"><h3>종류</h3></th>
						<th class="nosort"><h3>날짜</h3></th>
						<th class="nosort"><h3>구분1</h3></th>
						<th class="nosort"><h3>구분2</h3></th>
						<th class="nosort"><h3>계정코드</h3></th>
						<th class="nosort"><h3>계정과목</h3></th>
						<th class="nosort"><h3>거래처명</h3></th>
						<th class="nosort"><h3>금액</h3></th>
						<th class="nosort"><h3>수수료포함</h3></th>
						<th class="nosort"><h3>적요</h3></th>
					</tr>
				</thead>
				<tbody>
<?
	$line_chk = 0;
	$i = 1;
	foreach ($list_data as $k => $data)
	{
		if ($k > 0)
		{
			$data1 = trim($data[0]);
			$data2 = trim($data[1]);
			$data2 = str_replace('.', '-', $data2);
			$data3 = trim($data[2]);
			$data4 = trim($data[3]);
			$data5 = trim($data[4]);
			$data6 = trim($data[5]);
			$data7 = trim($data[6]);
			$data8 = trim($data[7]);
			$data9 = trim($data[8]);

		// 구분
			$gubun_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.code_name = '" . $data3 . "'";
			$gubun_data = code_account_gubun_data('view', $gubun_where);

		// 카드일 경우
			$card_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.code_name = '" . $data4 . "'";
			$card_data = code_account_card_data('view', $card_where);

		// 통장일 경우
			$bank_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.code_name = '" . $data4 . "'";
			$bank_data = code_account_bank_data('view', $bank_where);

		// 거래처
			if ($data6 == '')
			{
				$client_idx    = '';
				$client_name   = '';
			}
			else
			{
				$client_where = " and ci.client_code = '" . $data6 . "'";
				$client_data = client_info_data('view', $client_where);
				if ($client_data['total_num'] == 0)
				{
					$client_idx    = '';
					$client_name   = '일치한 거래처없음';
				}
				else
				{
					$client_idx    = $client_data['ci_idx'];
					$client_name   = $client_data['client_name'];
				}
			}

			$account_type  = $data1;
			$account_date  = $data2; // 날짜
			$gubun_code    = $gubun_data['code_value'];
			$account_price = $data7;
			$charge_yn     = $data8;
			$content       = $data9;

			if ($account_type == '출금') $account_type = 'OUT';
			else $account_type = 'IN';

			if ($gubun_code == 'card')
			{
				$card_code = $card_data['code_idx'];
				$bank_code = '';
			}
			else if ($gubun_code == 'bank')
			{
				$card_code = '';
				$bank_code = $bank_data['code_idx'];
			}

			if ($data5 != '')
			{
				$class_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.code_value = '" . $data5 . "'";
				$class_data = code_account_class_data('view', $class_where);
				$class_code = $class_data['code_idx'];
				$class_name = $class_data['code_name'];
			}
			else
			{
				$class_code = '';
				$class_name = '';
			}

			if ($charge_yn == '') $charge_yn = 'N';
?>
					<tr>
						<td><span class="eng"><?=$i;?></span></td>
						<td><?=$data1;?></td>
						<td><span class="eng"><?=$data2;?></span></td>
						<td><?=$data3;?></td>
						<td><?=$data4;?></td>
						<td><span class="eng"><?=$data5;?></span></td>
						<td><?=$class_name;?></td>
						<td><?=$client_name;?></td>
						<td><span class="eng right"><?=$data7;?></span></td>
						<td><?=$data8;?></td>
						<td>
							<div class="left"><?=$data9;?></div>
							<input type="hidden" name="account_type_<?=$k;?>"  id="account_type_<?=$k;?>"  value="<?=$account_type;?>" />
							<input type="hidden" name="account_date_<?=$k;?>"  id="account_date_<?=$k;?>"  value="<?=$account_date;?>" />
							<input type="hidden" name="gubun_code_<?=$k;?>"    id="gubun_code_<?=$k;?>"    value="<?=$gubun_code;?>" />
							<input type="hidden" name="card_code_<?=$k;?>"     id="card_code_<?=$k;?>"     value="<?=$card_code;?>" />
							<input type="hidden" name="bank_code_<?=$k;?>"     id="bank_code_<?=$k;?>"     value="<?=$bank_code;?>" />
							<input type="hidden" name="class_code_<?=$k;?>"    id="class_code_<?=$k;?>"    value="<?=$class_code;?>" />
							<input type="hidden" name="client_idx_<?=$k;?>"    id="client_idx_<?=$k;?>"    value="<?=$client_idx;?>" />
							<input type="hidden" name="account_price_<?=$k;?>" id="account_price_<?=$k;?>" value="<?=$account_price;?>" />
							<input type="hidden" name="charge_yn_<?=$k;?>"     id="charge_yn_<?=$k;?>"     value="<?=$charge_yn;?>" />
							<input type="hidden" name="content_<?=$k;?>"       id="content_<?=$k;?>"       value="<?=$content;?>" />
						</td>
					</tr>
<?
			$line_chk++;
			$i++;
		}
	}
?>
				</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_green"><input type="button" value="등록하기" onclick="data_insert()" /></span>
					<span class="btn_big_violet"><input type="button" value="다시 업로드하기" onclick="open_data_upload()" /></span>
				</div>
			</div>

		</fieldset>
			<input type="hidden" name="sub_type"   value="insert_ok" />
			<input type="hidden" name="data_total" value="<?=$line_chk;?>" />
			<input type="hidden" name="part_idx"   value="<?=$code_part;?>" />
		</form>

	</div>
</div>

<?
		}
	}
?>
<script type="text/javascript">
//<![CDATA[
	function data_insert(str)
	{
		$.ajax({
			type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/account/account_upload_ok.php',
			data: $('#insertform').serialize(),
			success: function(msg) {
				if (msg.success_chk == 'Y')
				{
					check_auth_popup('업로드 완료되었습니다.');
					close_data_form();
				}
				else check_auth_popup('업로드 완료되지 않았습니다.');
			}
		});
		return false;
	}
//]]>
</script>