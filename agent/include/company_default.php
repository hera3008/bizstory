
<!-- 상단top_SyntaxHighlighter -->
<div id="tfuse-top-sliding-panel-container">
	<div id="tfuse-top-sliding-panel">
		<div class="tfuse-top-sliding-panel-container">
			<div class="tfuse-top-sliding-panel-selectBoxes">
				<div class="tfuse-top-sliding-panel-combobox-content">
					<div id="company_info"></div>
					<div class="tab_m">
						<ul>
							<li class="m01"><a href="javascript:void(0)" onclick="company_page_view('01')">담당자보기</a></li>
							<li class="m02"><a href="javascript:void(0)" onclick="company_page_view('02')">직원보기</a></li>
							<li class="m03"><a href="javascript:void(0)" onclick="company_page_view('03')">회사소개</a></li>
							<li class="m04"><a href="javascript:void(0)" onclick="company_page_view('04')">인증서보기</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tfuse-top-sliding-panel-panel-bot">
		<a href="#" onclick="return false;" id="tfuse-top-sliding-panel-btn-slide" class="ajax-open-tfuse-top-slide" title="Hide/Show Panel">회사정보</a>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/jquery.bt.js"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/tfuse-top-panel.js"></script>
<link rel="stylesheet" id="tfuse-sliding-panel-css" href="<?=$local_dir;?>/bizstory/css/tfuse-top-panel.css" type="text/css" media="screen" />

<script src="<?=$local_dir;?>/bizstory/js/jquery.mousewheel.min.js"></script> <!-- mousewheel plugin -->
<script src="<?=$local_dir;?>/bizstory/js/jquery.mCustomScrollbar.js"></script> <!-- custom scrollbars plugin -->
<link rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/jquery.mCustomScrollbar.css" type="text/css" media="screen" />

<script type="text/javascript">
//<![CDATA[
// 회사정보
	function company_page_view(idx)
	{
		var link_str = '<?=$local_dir;?>/agent/include/company_' + idx + '.php';
		$.ajax({
			type: 'post', dataType: 'html', url: link_str,
			data: {'idx_comp':'<?=$client_comp;?>', 'idx_part':'<?=$client_part;?>', 'idx_client':'<?=$client_idx;?>'},
			success : function(msg) {
				$('#company_info').html(msg);
			}
		});
	}
	company_page_view('01');
//]]>
</script>