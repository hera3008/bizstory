<?
	$col_num = 5;
?>
<!-- 말머리 -->
	<?=$bdata_list_category;?>

<!-- Total Page -->
	<div class="totalpage">
		<div class="infoleft">
			총게시물/총페이지 <strong><?=$page_view["total"];?> (<?=$page_view["page_now"];?>/<?=$page_view["page_total"];?>)</strong>
		</div>
		<div class="inforight">
			<?=$set_rss_btn;?>
		</div>
	</div>
	<hr />

<!-- Board List -->
	<form name="listform" method="post" action="<?=$this_page;?>" class="board-list-form">
		<fieldset>
			<legend>게시물 목록</legend>
			<table border="1" cellspacing="0" class="board-list" summary="<?=$set_explanation;?>">
			<caption><?=$set_title;?></caption>
				<thead>
					<tr>
					<?
						if ($set_auth_yn == "Y")
						{
							$col_num++;
					?>
						<th scope="col"><label for="b_idx"><input type="checkbox" id="b_idx" name="b_idx" onclick="chk_all('bidx', this);" class="type_checkbox" title="check all" /></label></th>
					<?	} ?>
						<th class="no" scope="col">번호</th>
					<?
						if ($set_category_list_yn == "Y")
						{
							$col_num++;
					?>
						<th scope="col">구분</th>
					<?	} ?>
						<th scope="col">제목</th>
					<?
						if ($set_file_yn == "Y")
						{
							$col_num++;
					?>
						<th scope="col">첨부</th>
					<?	} ?>
						<th scope="col">글쓴이</th>
						<th scope="col">등록일</th>
						<th scope="col">조회</th>
					<?
						if ($set_recom_yn == "Y")
						{
							$col_num++;
					?>
						<th scope="col">추천</th>
					<?	} ?>
					</tr>
				</thead>
				<tbody>
				<?	if ($total_bbs == 0) { ?>
					<tr class="notice">
						<td colspan="<?=$col_num;?>">등록된 데이타가 없습니다.</td>
					</tr>
				<?	} ?>