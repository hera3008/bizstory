<?
/*
	생성 : 2012.11.21
	위치 : 회계업무 > 운영비관리 - 등록/수정 - 카드목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];

	$chk_where = " and ai.ai_idx = '" . $ai_idx . "'";
	$chk_data = account_info_data("view", $chk_where);

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $part_idx . "'";
	$list = code_account_card_data('list', $where, '', '', '');

	if ($ai_idx == '')
	{
		$chk_str = 'param';
	}
	else
	{
		$chk_str = 'modify_param';
	}
?>
<select name="<?=$chk_str;?>[card_code]" id="post_card_code" title="카드를 선택하세요.">
	<option value="">카드선택</option>
<?
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
?>
	<option value="<?=$data['code_idx'];?>"<?=selected($data['code_idx'], $chk_data['card_code']);?>><?=$data['code_name'];?>(<?=$data['mem_name'];?> - <?=$data['card_num'];?>)</option>
<?
		}
	}
?>
</select>