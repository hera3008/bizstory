
				<tr class="loop-bg<?=$class_str;?>">
				<?
					if ($set_auth_yn == "Y") {
				?>
					<td class="check">
						<label for="bidx_<?=$bbslist_i;?>"><input type="checkbox" id="bidx_<?=$bbslist_i;?>" name="chk_b_idx[]>" value="<?=$b_data["b_idx"];?>" class="type_checkbox" title="선택" /></label>
					</td>
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
					if ($set_category_list_yn == "Y") {
				?>
					<td><?=$b_data["category_str"];?></td>
				<?
					}
				?>
					<td class="title">
						<?=$b_data["gab"];?>
				<?
					if ($set_category_list_yn != "Y" && $menu_info["category_menu_yn"] != "Y") {
				?>
						<?=$b_data["category_str"];?>
				<?
					}
				?>
				<?
					if ($b_data["read_url"] != "") {
				?>
						<a href="<?=$b_data["read_url"];?>"><strong class="litype">&#183;</strong><?=$b_data["subject"];?></a>
				<?
					} else {
				?>
						<strong class="litype">&#183;</strong><?=$b_data["subject"];?>
				<?
					}
				?>
				<?
					if ($b_data["total_tail"] != "") { // 댓글이 있을 경우
				?>
						<span class="replynum"><?=$b_data["total_tail"];?></span>
				<?
					}
				?>
						<?=$b_data["secret_yn"];?><?=$b_data["new_yn"];?>
					</td>
				<?
					if ($set_file_yn == "Y") {
				?>
					<td class="file"><?=$b_data["file_yn"];?></td>
				<?
					}
				?>
					<td class="author"><span><?=$b_data["writer"];?></span></td>
					<td class="date"><?=date_replace($b_data["write_date"], "Y.m.d");?></td>
					<td class="vcount"><?=$b_data["views"];?></td>
				<?
					if ($set_recom_yn == "Y") {
				?>
					<td class="rcount"><?=$b_data["recom"];?></td>
				<?
					}
				?>
				</tr>