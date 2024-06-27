<?
	include "../common/setting.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./header.php";

	$send_fmode = "msg";
	$send_smode = "msg";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$mem_idx   = $idx2;
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $send_page_num;
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
		<input type="hidden" name="page_num"  value="' . $send_page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
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

	if ($form_chk == 'Y')
	{
		$file_upload_num = 0;
		$file_chk_num    = 1;

	// 지사별
		$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
		$part_list = company_part_data('list', $part_where, '', '', '');
?>

<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)"><em>이전</em></a>
<?
	include "body_header.php";
?>
	</div>
	<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
	<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
	<input type="hidden" name="sub_type" value="post" />
	<?=$form_all;?>
	
	<div id="content">
		<article class="mt_4">
			<h2>쪽지작성</h2>
		</article>
		<div id="wrapper" class="work">
			<div id="scroller">

				<div class="work_form">
					<table border="1" cellspacing="0" summary="업무등록 폼" class="table03">
						<caption>업무등록 폼</caption>
						<tbody>
							<tr>
								<th>받는자</th>
								<td>
							<?
								$charge_view = form_charge_view('receive_idx[]', $mem_idx, $part_list, '', 'msg');
								echo $charge_view['change_view'];
							?>
								</td>
							</tr>
							<tr>
								<th>내용</th>
								<td>
									<textarea style="display:none;" name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"></textarea>
								</td>
							</tr>
							<tr>
								<th>첨부파일</th>
								<td>
									<div class="filewrap">
										<input style="display: none;" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" type="file"><object style="visibility: visible;" id="file_fnameUploader" data="/common/upload/uploadify.swf" type="application/x-shockwave-flash" height="30" width="82"><param value="high" name="quality"><param value="transparent" name="wmode"><param value="sameDomain" name="allowScriptAccess"><param value="uploadifyID=file_fname&amp;pagepath=/&amp;buttonImg=/common/upload/file_submit.gif&amp;script=/common/upload/uploadify_multi.php&amp;folder=/data/tmp&amp;scriptData=upload_name%3Dfile_fname%26add_name%3Dmessage%26file_max%3D157286400%26upload_ext%3D%26sort%3D1&amp;width=82&amp;height=30&amp;wmode=transparent&amp;method=POST&amp;queueSizeLimit=999&amp;simUploadLimit=1&amp;multi=true&amp;auto=true&amp;fileDataName=Filedata" name="flashvars"></object>
										<div id="file_fnameQueue" class="uploadifyQueue"></div>
										<div class="file">
											<ul id="file_fname_view"></ul>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="ta_c mb15">
					<ul>
						<li><a href="javascript:" onclick="submitIt()"><span class="btn_g">등록</span></a></li>
						<li><a href="javascript:" onclick="cancelIt()"><span class="btn_v">취소</span></a></li>
					</ul>
				</div>

			</div>
		</div>
	</div>
	
	<script>
		function submitIt() {
			
		}
		
		function cancelIt() {
			
		}
		
		// 라디로 체크박스 관련
		$(document).ready(
			function(){
				$('input[type=radio]').ezMark();
				$('input[type=checkbox]').ezMark();
			}
		);
		
	</script>
<?
	}

	include "./footer.php";
?>