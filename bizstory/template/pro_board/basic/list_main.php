
		<tr>
		<?
			if ($set_board['auth_yn'] == "Y") {
		?>
			<td><input type="checkbox" id="bidx_<?=$i;?>" name="chk_b_idx[]" value="<?=$data["b_idx"];?>" title="선택" /></td>
		<?
			}
		?>
			<td class="num">
		<?
			if ($b_data["b_idx"] == $b_idx) {
		?>
				<span class="arrow">현재글</span>
		<?
			} else {
		?>
				<?=$bbs_num;?>
		<?
			}
		?>
			</td>
		<?
			if ($set_board['category_yn'] == "Y") {
		?>
			<td><?=$b_data["category_view"];?></td>
		<?
			}
		?>
			<td>
				<div class="left">
					<?=$b_data["gab"];?>
					<a href="javascript:void(0);" onclick="view_open('<?=$b_data["b_idx"];?>')"><?=$b_data['subject'];?></a>
					<?=$b_data["file_view"];?>
					<?=$b_data["comment_view"];?>
					<?=$b_data["secret_view"];?>
				</div>
			</td>
			<td><?=$b_data["writer"];?></td>
			<td><span class="num"><?=date_replace($b_data['reg_date'], 'Y.m.d');?></span></td>
		</tr>
