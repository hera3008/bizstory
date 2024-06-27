<?
	$menu_list = array(
		  "1" => array("접수", "receipt.php")
	);
?>
	<div class="tabs">
<?
	foreach ($menu_list as $k => $menu_data)
	{
?>
		<a href="javascript:void(0);" onclick="move_part('<?=$part_data['part_idx'];?>')"><?=$menu_data['0'];?></a>
<?
			if ($k < count($menu_list)-1)
			{
				echo '<span>|</span>';
			}
	}
?>
	</div>