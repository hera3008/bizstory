<?
	include "../common/setting.php";
	include "../common/no_direct.php";
?>
<div id="document_preview">
	<ul>
<?
	for ($i = 1; $i <= $page_count; $i++)
	{
		$i_str = str_pad($i, 3, '0', STR_PAD_LEFT);
		$img_view_name = $image_url . 'image_' . $i_str . '.jpg';
?>
		<li>
			<a href="<?=$img_view_name;?>" id="image_<?=$i;?>" title="<?=$file_name;?> No.<?=$i;?>"></a>
		</li>
<?
	}
?>
	</ul>
</div>
