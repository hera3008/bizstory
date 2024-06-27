
	<fieldset>
		<legend class="blind">프로젝트게시판 상세보기</legend>
		<table class="tinytable view" summary="프로젝트게시판를 상세하게 봅니다.">
		<caption>프로젝트게시판</caption>
		<colgroup>
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th>제목</th>
				<td>
					<div class="left">
						<strong><?=$data['category_view'];?> <?=$data['subject'];?></strong>
					</div>
				</td>
			</tr>
			<tr>
				<th>작성자</th>
				<td>
					<div class="left"><?=$data['writer'];?>(<?=$data['reg_date'];?>)</div>
				</td>
			</tr>
			<tr>
				<th>내용</th>
				<td>
					<div class="left">
						<p class="memo">
							<?=$data['img_view'];?>
							<?=$data['remark'];?>
						</p>
					</div>
				</td>
			</tr>
			<?=$link_form_view;?>
			<?=$file_form_view;?>
		</tbody>
		</table>
	</fieldset>