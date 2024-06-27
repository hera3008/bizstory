<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";


	$send_fmode = "filecenter";
	$send_smode = "filemanager";
	if ($pro_end == '')
	{
		$pro_end = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}
	
	$mem_idx = $code_mem;
	if ($up_level == '') $up_level = 2;	
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;pro_end=' . $send_pro_end;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="pro_end" value="' . $send_pro_end . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($up_idx == '') {
		$result = filecenter_open_check($up_idx, $code_comp, $code_part);
		$chk_up = $result['chk_up'];
		$project_fi_idx = $result['project_fi_idx'];
		$vdrive_fi_idx = $result['vdrive_fi_idx'];
		$up_idx = $vdrive_fi_idx;
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$path_data     = filecenter_folder_path($up_idx); // 현위치
	$dir_auth_page = filecenter_auth_folder($up_idx); // 권한확인 - 현위치
	

	$first_name = $path_data['path_up_name'][1];

	$form_chk = 'N';
	if ($dir_auth_page['dir_view_auth'] == 'Y') // 목록권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}
?>

<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>
				<em class="mr_70">파일관리</em>
				<span class="btn_vdrive">파일올리기</span>
			</h2>
			<form id="searchform" method="GET" action="./vdrive_list.php">
				<fieldset>
				<input type="hidden" name="fmode" value="<?=$send_fmode?>" />
				<input type="hidden" name="smode" value="<?=$send_smode?>" />
				<input type="hidden" name="swhere"    value="<?=$send_swhere?>" />
				<input type="hidden" name="stext"     value="<?=$send_stext?>" />
				<input type="hidden" name="swtype"    value="<?=$send_swtype?>" />
				<input type="hidden" name="shwstatus" value="<?=$send_shwstatus?>" />
				<input type="hidden" name="smember"   value="<?=$send_smember?>" />
				<input type="hidden" name="up_idx" id="list_up_idx" value="<?=$up_idx?>" />
				<input type="hidden" name="up_level" id="list_up_level" value="<?=$list_up_level?>" />
				<legend>컨텐츠 검색</legend>
					<div class="search_bar"> 
						<div class="search_area"> 
							<div class="inpwp"><input type="search" title="검색어 입력" id="inpSearch" autocomplete="off" autocorrect="off" name="keyword" value="검색할 단어 입력" class="" maxlength="40" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" /></div>
							<i class="spr_tm mag"></i> 
							<button type="button" class="del" id="btnDelete"><i class="spr_tm">검색어 삭제</i></button> 
						</div> 
						<button type="button" class="go" id="btnSearch"><i class="spr_tm">검색하기</i></button>
					</div> 
				</fieldset> 
			</form>
			<div class="message_bar">
				<!-- ul>
					<li><a href="javascript:" onclick="regFile()"><span class="btn_g">등록</span></a></li>
				</ul -->
				<span class="btn_vup">상위</span>

				<select class="ngb_select" id="VdriveList">
					<option value="0">이름순</option>
					<option value="1">종류순</option>
					<option value="2">중요 표시순</option>
					<option value="3">업로드 최신순</option>
					<option value="3">수정한 날짜 최신순</option>
				</select>
			</div>
		</article>
		<div id="wrapper" class="work work_section">
			<div id="scroller">
				<ul class="list">
					<li>
						<a href="javascript:" onclick="file_list_view('3', '3')">				
							<img src="./images/ico_p.png" alt="Member" title="Member"> 
							<strong class="title mt12">Project</strong>
						</a>
					</li>
					<li>
						<a href="javascript:" onclick="file_list_view('3', '3')">				
							<img src="./images/ico_v.png" alt="Member" title="Member"> 
							<strong class="title mt12">V-Drive </strong>
						</a>
					</li>
				</ul>
				<ul>
					<li class="md-trigger" data-modal="filemodal">
						다운로드
					</li>
				</ul>
			</div>
		</div>
	</div>
<!-- 팝업 내용 -->
<div class="md-modal md-effect" id="filemodal">
	<div class="md-content">
		<h3 class="v_title">V-Drive Upload Component</h3>
		<div>
			<div class="vcenter_area">
				컴포넌트 자리
			</div>
			<button class="md-close"><img src="./images/btn_close.png" alt="닫기" /></button>
		</div>
	</div>
</div>
<div class="md-overlay"></div>
<script type="text/javascript">
 	function file_list_view(up_idx, up_level)
	{
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);
		$("#searchform").submit();
	}

	function showFileDialog(fi_idx, file_name) {
		$("#filemodal .md-content h3").html( file_name );
		//var file_link = '<a href="<?=$set_filecneter_url?>/file_download.php?idx=' + fi_idx + '&amp;idx2=<?=$code_mem?>" title="' + file_name + ' 다운로드">내려받기</a>';
		$("#filemodal #file_download").html( file_link );
		//$("#filemodal .md-content div").prepend(json.mem_img.img_53);
		//$("#filemodal .md-content div ul").html( html );
	}
</script>
<?
	include "./footer.php";
?>