<?
// 지사통합을 할 경우 이 메뉴가 보이도록
	$set_part_yn = $company_set_data['part_yn'];

	if ($_SESSION[$sess_str . '_ubstory_level'] <= '21' || $set_part_yn == 'Y')
	{
		$part_where = "and part.comp_idx = '" . $code_comp . "'";
		$part_list = company_part_data('list', $part_where, '', '', '');

		if ($part_list['total_num'] > 0)
		{
?>
	<div class="tabarea" id="part_menu">
		<p>지사명</p>
		<div class="tabarea_part">
<?
			foreach ($part_list as $k => $part_data)
			{
				if (is_array($part_data))
				{
					if ($code_part == $part_data['part_idx']) $class_str = ' class="select"';
					else $class_str = '';
?>
			<a href="javascript:void(0);" id="part_<?=$part_data['part_idx'];?>" onclick="move_part('<?=$part_data['part_idx'];?>')"<?=$class_str;?>>
				<?=$part_data['part_name'];?>
			</a>
<?
					if ($k < $part_list['total_num']-1)
					{
						echo '<span>|</span>';
					}
				}
			}
?>
		</div>
	</div>
<?
		}
	}
?>