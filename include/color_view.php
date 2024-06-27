<?
	require_once "../common/setting.php";
?>
<style>
	#colorlist {
		border: 1px solid #CCCCCC; margin: 2px; padding: 5px;
	}
	
	.colorlist {
		float:left; display:block;
		margin:5px 0 0 0;
		width:20px; height:20px;
		text-align:center; position:relative;
	}

	.clear {
		display:block;
		float:none;
		clear:both;
		height:0; width:0;
		font-size:0 !important;
		line-height:0 !important;
		overflow:hidden;
		margin:0; padding:0 !important;
	}



</style>
<div id="colorlist">
	<div onclick="$('#fontcolorview').html('');" style="cursor:pointer">X</div>
	<div class="clear"></div>
<?
	foreach ($set_color_list as $k => $v)
	{
		foreach ($v as $k1 => $v1)
		{
			echo '
	<div class="colorlist" style="background-color: #' . $v1 . '" onclick="title_color(\'' . $v1 . '\');"></div>
			';
		}
		echo '<div class="clear"></div>';
	}
?>
</div>