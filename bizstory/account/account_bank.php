<?
/*
	생성 : 2012.11.21
	위치 : 회계업무 > 운영비관리 - 등록/수정 - 계좌목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

	$chk_where = " and ai.ai_idx = '" . $ai_idx . "'";
	$chk_data = account_info_data("view", $chk_where);

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $part_idx . "'";
	$list = code_account_bank_data('list', $where, '', '', '');

	if ($ai_idx == '')
	{
		$chk_str = 'param';
	}
	else
	{
		$chk_str = 'modify_param';
	}
?>
<select name="<?=$chk_str;?>[bank_code]" id="post_bank_code" title="계좌를 선택하세요.">
	<option value="">계좌선택</option>
<?
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
?>
	<option value="<?=$data['code_idx'];?>"<?=selected($data['code_idx'], $chk_data['bank_code']);?>><?=$data['code_name'];?>(<?=$data['bank_num'];?>)</option>
<?
		}
	}
?>
</select>