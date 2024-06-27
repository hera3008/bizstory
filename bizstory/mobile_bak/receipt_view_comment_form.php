<?
/*
	생성 : 2012.09.11
	위치 : 접수댓글저장
*/
	require_once "../common/setting.php";
	require_once $local_path . "/bizstory/mobile/process/mobile_setting.php";
	require_once $mobile_path . "/process/member_chk.php";
	require_once $mobile_path . "/process/no_direct.php";
?>

<div class="new_report">
	<form name="commentform" id="commentform" method="post" action="<?=$this_page;?>">
		<input type="hidden" name="ri_idx" value="<?=$ri_idx;?>" />

		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" cols="50" rows="7" title="코멘트내용을 입력하세요." placeholder="코멘트내용을 입력하세요." class="type_text"><?=$data['remark'];?></textarea>
		</div>
		<div class="action">
			<span class="btn_big"><input type="button" value="등록" onclick="receipt_comment_check()"/></span>
			<span class="btn_big"><input type="button" value="취소" onclick="receipt_comment_insert('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
		</div>
	</form>
</div>
