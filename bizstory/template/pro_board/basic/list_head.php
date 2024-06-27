<?
	$col_num = 4;

	echo $category_list_view;
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<span class="btn_big fr"><input type="button" value="등록" onclick="project_form('')" /></span>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
<?
	if ($set_board['auth_yn'] == "Y")
	{
		$col_num++;
?>
		<col width="30px" />
<?	} ?>
		<col width="40px" />
<?
	if ($set_board['category_yn'] == "Y")
	{
		$col_num++;
?>
		<col width="100px" />
<?	} ?>
		<col />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
<?
	if ($set_board['auth_yn'] == "Y") {
?>
			<th class="nosort"><input type="checkbox" name="bidx" onclick="check_all('bidx', this);" /></th>
<?	} ?>
			<th class="nosort">번호</th>
<?
	if ($set_board['category_yn'] == "Y") {
?>
			<th class="nosort"><h3>구분</h3></th>
<?	} ?>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>작성자</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
		</tr>
	</thead>
	<tbody>
	<?	if ($total_board == 0) { ?>
		<tr>
			<td colspan="<?=$col_num;?>">등록된 데이타가 없습니다.</td>
		</tr>
	<?	} ?>