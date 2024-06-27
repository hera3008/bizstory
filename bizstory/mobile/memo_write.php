<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";
?>

	<!-- BBS Write -->
	<div id="bbs_write" class="homebox full sub write">

		<!-- Toolbar -->
		<div class="toolbar han">
			<?echo($GoBack)?>
			<h1><a href="javascript:window.location.href='<?echo($LocalDir)?>main.php'">메모</a></h1>
			<a href="javascript:void(0);" onclick="chk_mome();" class="right_b">save</a>
		</div>
		<!-- //Toolbar -->

		<!-- Contents -->
		<div id="wrapper">
			<div id="scroller">

				<?echo($iconmenu)?>

				<!-- Memo Write -->
				<form name="momewrite" class="basic memo" Onsubmit="return chk_mome()" method="post" action="./">
					<ul>
						<li>
							<textarea name="param[memo]" id="memo" rows="13"></textarea>
						</li>
					</ul>
				</form>
				<!-- //Memo Write -->

			</div>
		</div>
		<!-- //Contents -->

	</div>
	<!-- //BBS Write -->

</body>
</html>