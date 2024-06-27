<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
?>
	<!-- 회사소개 -->
	<div id="company_page_view3">
		<div class="logo"><?=$comp_logo_img;?></div>
		<div class="slogan">
			<p class="text1"><?=$company_data['slogan1'];?></p>
			<p class="text2"><?=$company_data['slogan2'];?></p>
		</div>
		<br />
		<div class="company_data content1">
			<?=$company_data['comp_remark'];?>
		</div>
	</div>
	<!-- //회사소개 -->

<script type="text/javascript">
//<![CDATA[
	$(".content1").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
//]]>
</script>