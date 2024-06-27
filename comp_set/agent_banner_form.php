<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 배너관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp      = $_SESSION[$sess_str . '_comp_idx'];
	$code_part      = search_company_part($code_part);
	$set_banner_cnt = $comp_set_data['banner_cnt'];
	$ab_idx    = $idx;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $ab_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $ab_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	$where = " and ab.comp_idx = '" . $code_comp . "' and ab.part_idx = '" . $code_part . "'";
	$list = agent_banner_data('list', $where, '', '', '');
	if ($set_banner_cnt <= $list['total_num'])
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth('더이상 배너를 등록할 수 없습니다.<br />최대 <?=$set_banner_cnt;?>개까지 가능합니다.');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		if($ab_idx !=""){
			$where = " and ab.ab_idx = '" . $ab_idx . "'";
			$data = agent_banner_data("view", $where);

			if ($data["view_yn"] == '') $data["view_yn"] = 'Y';
			if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;
		}else{
			$file_upload_num = 1;
		}
?>

<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>링크주소 입력시 "http://" 를 입력하세요.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num?>" />
			<input type="hidden" id="post_code_part"  name="code_part"  value="<?=$code_part;?>" />
			<?=$form_all;?>
			<fieldset>
				<legend class="blind">에이전트 배너 폼</legend>
				<table class="tinytable write" summary="에이전트 배너를 등록/수정합니다.">
				<caption>에이전트 배너</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td>
							<div class="left">
								<?=company_part_form($data['part_idx'], $data['part_name'], '');?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_content">배너명</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[content]" id="post_content" value="<?=$data['content'];?>" size="25" title="배너명 입력하세요." class="type_text"<?=$name_style;?> />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_link_url">배너 링크주소</label></th>
						<td>
							<div class="left">
								<input type="text" name="param[link_url]" id="post_link_url" value="<?=$data['link_url'];?>" size="30" title="배너 링크주소 입력하세요." class="type_text" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="f_fname1">이미지파일</label></th>
						<td>
				<?
					if ($data['img_sname'] != '')
					{
						$img_str = '<img src="' . $comp_banner_dir . '/' . $data['img_sname'] . '" alt="' . $data['content'] . '" width="373px" height="100px" />';
				?>
							<div class="filewrap">
								<div class="file" id="file_fname1_view">
									<?=$img_str;?>
									<a href="javascript:void(0);" class="btn_con" onclick="file_form_delete('<?=$data['ab_idx'];?>', '1')"><span>삭제</span></a>
								</div>
							</div>
				<?
					}
				?>
							<div class="filewrap">
								<span>373 * 100 </span>
								<div class="file" id="f_fname1_view">
									<input type="file" name="f_fname1" id="f_fname1" class="type_text type_file type_multi" title="파일 선택하기" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th>보기여부</th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($ab_idx == "") {
				?>
						<span class="btn_big_green"><input type="submit" value="등록" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" name="ab_idx"   value="<?=$ab_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>
<?
	}
?>
<script type="text/javascript">
//<![CDATA[
	file_setting('f_fname1', 'agent_banner', '1', '<?=$file_multi_size;?>', '');
//]]>
</script>

