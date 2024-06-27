<?
	$string_url     = urldecode($recv_string_url);
	$string_url_arr = explode('?', $string_url);
	$string_url_len = count($string_url_arr);

	for ($i = 1; $i < $string_url_len; $i++)
	{
		$url_str = $string_url_arr[$i];
		if ($i == 1) $move_query = $url_str;
		else $move_query .= urlencode('&' . $url_str);
	}
	$move_url = $string_url_arr[0];
?>
<div id="page-body">
	<div id="error_page">
		<div class="ui-widget errorw">
			<h2><strong>에러내용</strong></h2>
			<div class="ui-state-error ui-corner-all">
				<p>
					<?=$set_error_message[$error_type]['message'];?>
				</p>
			</div>
			<a href="javascript:void(0);" onclick="location.href='<?=$move_url;?>?<?=$move_query;?>'" class="btn_big fr"><span><?=$set_error_message[$error_type]['error'];?></span></a>
		</div>
		<div id="footer"></div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	// 레이어 가운데정렬
	$(window).resize(function(){
		$('#error_page').css({
			position:'absolute',
			left: ($(window).width() - $('#error_page').outerWidth())/2,
			top: ($(window).height() - $('#error_page').outerHeight())/2-100
		});
	});
	// To initially run the function:
	$(window).resize();
//]]>
</script>