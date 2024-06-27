				</tbody>
			</table>
			<input type="hidden" name="sub_type" />
			<input type="submit" class="none" value="submit" />
			<?=$form_default;?>
		</fieldset>
	</form>

<!-- page -->
	<div class="pagination">
		<?=$page_view["page_view"]; ?>
	</div>
	<hr />

<!-- button -->
	<div class="boardnavigation">
		<div class="buttonleft">
			<span class="button"><a href="<?=$this_page;?>?<?=$field_default;?>">목록보기</a></span>
		</div>
		<div class="buttonright">
			<?=$set_btn_write;?>
			<?=$set_btn_sel_del;?>
			<?=$set_btn_sel_copy;?>
			<?=$set_btn_sel_move;?>
		</div>
	</div>

<!-- search -->
	<form name="searchform" method="get" action="<?=$this_page;?>" class="boardsearchform">
		<fieldset>
			<legend>게시물 검색</legend>
			<select name="s_where" title="검색선택">
				<option value="subject_remark" <?=selected($s_where, "subject_remark"); ?>>제목 + 내용</option>
				<option value="subject"        <?=selected($s_where, "subject"); ?>>제목</option>
				<option value="remark"         <?=selected($s_where, "remark"); ?>>내용</option>
				<option value="writer"         <?=selected($s_where, "writer"); ?>>작성자</option>
				<option value="user_id"        <?=selected($s_where, "user_id"); ?>>아이디</option>
			</select>
			<input name="s_text" type="text" class="type_text" title="검색어" value="<?=$s_text;?>" />
			<span class="button"><input type="submit" value="검색" /></span>
			<?=$form_search;?>
		</fieldset>
	</form>