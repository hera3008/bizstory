<?
/*
	생성 : 2012.07.11
	위치 : 메인화면 > 메모목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem = $_SESSION[$sess_str . '_mem_idx'];

	$memo_where = " and mm.comp_idx = '" . $code_comp . "' and mm.mem_idx = '" . $code_mem . "'";
	$memo_list = member_memo_data('list', $memo_where, '', '', '');

	foreach ($memo_list as $memo_k => $memo_data)
	{
		if (is_array($memo_data))
		{
			if ($sub_type == 'modify_view' && $memo_data['mm_idx'] == $mm_idx)
			{
?>
	<div id="memo_num_<?=$memo_data['mm_idx'];?>" class="loop view">
		<fieldset>
			<legend class="blind">메모수정 폼</legend>
			<div class="note_write"><input type="button" value="수정하기" onclick="check_memo_modify()" /></div>
			<div class="note_head"></div>
			<div class="note_body">
				<div>
					<textarea name="param[remark]" id="memomodify_remark" cols="20" rows="10" title="메모를 입력하세요."><?=$memo_data['remark'];?></textarea>
				</div>
			</div>
			<div class="note_footer"></div>
		</fieldset>
	</div>
<?
			}
			else
			{
?>
	<div id="memo_num_<?=$memo_data['mm_idx'];?>" class="loop view">

		<a href="javascript:void(0);" onclick="memo_modify('<?=$memo_data['mm_idx'];?>')" title="수정" class="modify">수정</a>
		<a href="javascript:void(0);" onclick="check_memo_delete('<?=$memo_data['mm_idx'];?>')" title="삭제" class="delete">삭제</a>
		<div class="note_head"></div>
		<div class="note_body">
			<div>
				<p id="memo_view">
					<?=nl2br($memo_data['remark']);?>
				</p>
			</div>
		</div>
		<div class="note_footer"></div>
	</div>
<?
			}
		}
	}

    db_close();
?>
