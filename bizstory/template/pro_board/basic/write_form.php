
	<fieldset>
		<legend class="blind">프로젝트게시판 등록/수정폼</legend>
		<table class="tinytable write" summary="프로젝트게시판를 등록/수정합니다.">
		<caption>프로젝트게시판</caption>
		<colgroup>
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
		<?
			if ($set_board['category_yn'] == "Y") {
		?>
			<tr>
				<th><label for="post_bc_idx">말머리</label></th>
				<td>
					<div class="left">
						<?=$cate_form;?>
					</div>
				</td>
			</tr>
		<?	} ?>
			<tr>
				<th><label for="post_writer">작성자</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[writer]" id="post_writer" class="type_text" title="작성자를 입력하세요." size="15" value="<?=$data['writer'];?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="post_subject">제목</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						<?=$secret_form;?>
						<?=$notice_form;?>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="post_remark">내용</label></th>
				<td>
					<div class="left textarea_span">
						<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
					</div>
				</td>
			</tr>
		<?
			if ($link_form_view != "")
			{
				echo $link_form_view;
			}
			if ($file_form_view != "")
			{
				echo $file_form_view;
			}
		?>
		</tbody>
		</table>

		<div class="section">
			<div class="fr">
				<?=$write_button_view;?>
			</div>
		</div>

	</fieldset>
