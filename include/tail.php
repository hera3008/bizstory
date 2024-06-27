				</div>
				<div id="footer"></div>

				<div id="notify_view"></div>
				<input type="hidden" id="notify_chk_date" value="<?=date('Y-m-d H:i:s');?>" />
			</td>
		</tr>
	</table>

	<div id="backgroundPopup"></div>
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>
<?
// 서브메뉴 펼치기 위해서
	if ($fmode != '' && $smode != '')
	{
		$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
		$navi_data = menu_info_data("view", $navi_where);
		$navi_up = $navi_data['up_mi_idx'];
		$navi_up_arr = explode(',', $navi_up);
		foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
		{
			if ($navi_up_k > 0)
			{
				if ($navi_up_k == 1) $chk_up = $navi_up_v;
				else $chk_up .= '_' . $navi_up_v;
			}
		}

	// 게시판일 경우
		if ($bs_idx != '') $chk_up = $navi_data['mi_idx'];
	}
?>
<script type="text/javascript">
//<![CDATA[
	var now_sub_menu_id = 'submenu_<?=$chk_up;?>';

// 파일 미리보기
	$('#popup_file_preview').dialog({
		autoOpen: false, width: 1000, modal: true,
		buttons: {
			"창닫기": function() { $(this).dialog("close"); }
		}
	});

//------------------------------------ notify info
	function notify_info_list()
	{
		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/notify_info.php',
			data: {'chk_date':$('#notify_chk_date').val()},
			success: function(msg) {
				$('#notify_view').html(msg);
				$('#notify_chk_date').val();
				$.notify({
					inline: true,
					href: '#notify_view'
				}, 10000);
			}
		});
	}
	//setInterval(notify_info_list, 10000);
<?
	$site_url = str_replace('www.', '', $site_url);
	if ($site_url == 'on-trade.co.kr')
	{
?>
	$(document).ready(function() {
		$("#footer").html('<address><em>Copyright &copy;</em><strong>On Trade</strong><span>All Rights Reserved.</span></address>');
		$(".engName").html('On Trade');
		$(".hanName").html('On Trade');
	});
<?
	}
?>
//]]>
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44940031-1', 'bizstory.co.kr');
  ga('send', 'pageview');

</script>
<?

	include $local_path . "/include/footer.php";
    
    include $local_path . "/bizstory/common/db_close.php";
?>
