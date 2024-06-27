<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
?>
	<!-- 회사소개 -->
	<div id="company_page_view3">
		슬로건1 : <?=$company_data['slogan1'];?><br />
		슬로건2 : <?=$company_data['slogan2'];?><br />
		<br />
		<?=$company_data['comp_remark'];?>
	</div>
	<!-- //회사소개 -->