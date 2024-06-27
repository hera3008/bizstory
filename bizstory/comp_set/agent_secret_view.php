<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 알림관리 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$abn_idx   = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and abn.abn_idx = '" . $abn_idx . "'";
		$data = agent_bnotice_data('view', $where);

	// 중요도
		if ($data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
		else if ($data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
		else if ($data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
		else $important_span = '';

	// 거래처분류 2단계까지만
		$group_view = client_group_view($data['ccg_idx']);
		$group_name = $group_view['group_level1'];
		if ($group_view['group_level2'] != '') $group_name .= ' &gt; ' . $group_view['group_level2'];
		if ($group_name == '') $group_name = '거래처그룹전체';

		$file_where = " and abnf.abn_idx = '" . $abn_idx . "'";
		$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">알림상세보기</legend>
			<table class="tinytable view" summary="등록한 알림에 대한 상세정보입니다.">
			<caption>알림상세보기</caption>
			<colgroup>
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>거래처그룹</th>
					<td><div class="left"><?=$group_name;?></div></td>
				</tr>
				<tr>
					<th>등록일</th>
					<td><div class="left"><?=$data['reg_date'];?></div></td>
				</tr>
				<tr>
					<th>제목</th>
					<td>
						<div class="left">
							<strong><?=$data['subject'];?></strong>
							<?=$important_span;?>
						</div>
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td>
						<div class="left">
							<p class="memo">
								<?=$data['remark'];?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>첨부파일</th>
					<td>
						<div class="left file">
				<?
					if ($file_list['total_num'] > 0) {
				?>
							<ul>
				<?
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
				?>
								<li>
									<a href="<?=$local_dir;?>/bizstory/comp_set/agent_bnotice_download.php?abnf_idx=<?=$file_data['abnf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>
								</li>
				<?
							}
						}
				?>
							</ul>
				<?
					}
				?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
	if ($auth_menu['mod'] == 'Y')
	{
		$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="data_form_open(\'' . $abn_idx . '\')" /></span>';
	}
	if ($auth_menu['del'] == 'Y')
	{
		$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $abn_idx . '\')" /></span>';
	}
?>
			<div class="section">
				<div class="fr">
					<?=$btn_modify;?>
					<?=$btn_delete;?>
				</div>
			</div>
		</fieldset>
		</form>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>

<?
	}
?>