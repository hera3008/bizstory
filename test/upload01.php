<?
/*
	생성 : 2013.01.21
	수정 : 2013.01.22
	위치 : 파일등록
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
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

	$file_upload_num = 0;
	$file_chk_num    = $file_upload_num + 1;

	$upload_dir         = '/filemanager/bizstory/tmp';
	$upload_file_check  = 'http://220.90.137.171/filemanager/site_file_temp.php';
	//$upload_file_check  = '/bizstory/test/upload_ok.php';
	$upload_file_delete = 'http://220.90.137.171/filemanager/site_file_delete.php';
	$upload_file_ok     = 'http://220.90.137.171/filemanager/site_file_ok.php';
?>
<style>
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
*/

.uploadify {
	position: relative;
	margin-bottom: 1em;
}
.uploadify-button {
	background-color: #505050;
	background-image: linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -o-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -moz-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -webkit-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -ms-linear-gradient(bottom, #505050 0%, #707070 100%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #505050),
		color-stop(1, #707070)
	);
	background-position: center top;
	background-repeat: no-repeat;
	-webkit-border-radius: 30px;
	-moz-border-radius: 30px;
	border-radius: 30px;
	border: 2px solid #808080;
	color: #FFF;
	font: bold 12px Arial, Helvetica, sans-serif;
	text-align: center;
	text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
	width: 100%;
}
.uploadify:hover .uploadify-button {
	background-color: #606060;
	background-image: linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -o-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -moz-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -webkit-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -ms-linear-gradient(top, #606060 0%, #808080 100%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #606060),
		color-stop(1, #808080)
	);
	background-position: center bottom;
}
.uploadify-button.disabled {
	background-color: #D0D0D0;
	color: #808080;
}
.uploadify-queue {
	margin-bottom: 1em;
}
.uploadify-queue-item {
	background-color: #F5F5F5;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	font: 11px Verdana, Geneva, sans-serif;
	margin-top: 5px;
	max-width: 350px;
	padding: 10px;
}
.uploadify-error {
	background-color: #FDE5DD !important;
}
.uploadify-queue-item .cancel a {
	background: url('../img/uploadify-cancel.png') 0 0 no-repeat;
	float: right;
	height:	16px;
	text-indent: -9999px;
	width: 16px;
}
.uploadify-queue-item.completed {
	background-color: #E5E5E5;
}
.uploadify-progress {
	background-color: #E5E5E5;
	margin-top: 10px;
	width: 100%;
}
.uploadify-progress-bar {
	background-color: #0099FF;
	height: 3px;
	width: 1px;
}
</style>
<script type="text/javascript">
//<![CDATA[

/*
					$('#' + swfuploadify.movieName).wrap($wrapper);
					// Recreate the reference to wrapper
					$wrapper = $('#' + settings.id);
					// Add the data object to the wrapper
					$wrapper.data('uploadify', swfuploadify);


					var $button = $('<div />', {
						'id'    : settings.id + '-button',
						'class' : 'uploadify-button ' + settings.buttonClass
					});
					if (settings.buttonImage) {
						$button.css({
							'background-image' : "url('" + settings.buttonImage + "')",
							'text-indent'      : '-9999px'
						});
					}
					$wrapper.append($button);
*/
//------------------------------------ 업로드파일설정
	function multi_setting(upload_name, max_size, add_name, upload_ext)
	{
		var settings = {
			id              : upload_name,
			uploader        : '<?=$upload_file_check;?>',
			buttonClass     : '',
			buttonImage     : '/bizstory/add/filemanager/file_submit.gif',
			width           : 74,
			height          : 26,
			fileSizeLimit   : max_size,
			fileTypeDesc    : 'Any old file you want...',
			fileTypeExts    : upload_ext,
			uploadLimit     : 1,
			formData        : {}
		}
		var $wrapper = $('<div />', {
			'id'    : settings.id,
			'class' : 'uploadify',
			'css'   : {
				'height' : settings.height + 'px',
				'width'  : settings.width + 'px'
			}
		});
		var $button = $('<div />', {
			'id'    : settings.id + '-button',
			'class' : 'uploadify-button ' + settings.buttonClass
		});
		if (settings.buttonImage) {
			$button.css({
				'background-image' : "url('" + settings.buttonImage + "')",
				'text-indent'      : '-9999px'
			});
		}
		$wrapper.append($button);

		alert(settings.buttonClass);

		/*

		$('#' + upload_name).uploadify({
			'swf'            : '/bizstory/add/filemanager/uploadify.swf',
			'buttonImage'    : '/bizstory/add/filemanager/file_submit.gif',
			'width'          : 74, // 이미지 width
			'height'         : 26, //이미지 height
			'multi'          : true, // 여러파일선택
			'auto'           : true, // 자동파일업로드
			'fileObjName'    : 'file_fname', // 파이이름
			'fileSizeLimit'  : '10000KB', // 최대파일크기
			'fileTypeDesc'   : 'Any old file you want...', // 파일설명
			'fileTypeExts'   : '*.gif; *.jpg; *.png', // 가능확장자파일
			//'progressData'   : 'speed',
			'uploadLimit'    : 1, // 가능파일
			'method'         : 'post',
			'successTimeout' : 30, // 초
			'uploader'       : '<?=$upload_file_check;?>',
			'formData'       : {
				'upload_name' : upload_name,
				'add_name'    : add_name,
				'file_max'    : max_size,
				'upload_ext'  : upload_ext,
				'sort'        : file_chk_num
			},
			'onSelect': function(file) {
				alert('The file ' + file.name + ' was added to the queue.');

				//if (fileObj.size > max_size)
				//{
				//	check_auth_popup(fileObj.name + '은 ' + max_size + 'Byte보다 크기 때문에 올릴 수 없습니다.');
				//	$('#' + upload_name).uploadifyCancel($('.uploadifyQueueItem').first().attr('id').replace('#' + upload_name,''));
				//}
			},
			'onSelectError' : function() {
				alert('The file ' + file.name + ' returned an error and was not added to the queue.');
			},
			'onUploadStart': function(file) {
				alert('Starting to upload ' + file.name);
			},
			'onUploadSuccess': function(file, data, response) {
				alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
				//multi_complete(response, upload_name, add_name);
			},
			'onUploadComplete': function(file) {
				alert('The file ' + file.name + ' finished processing.');
			},
			'onUploadError': function (file, errorCode, errorMsg, errorString) {
				alert('The file ' + file.name + ' could not be uploaded: ' + errorString + ' ' + errorMsg + ' ' + errorCode);
			}
		});
		*/
	}
//]]>
</script>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">접수정보 폼</legend>
			<table class="tinytable write" summary="접수정보를 등록/수정합니다.">
			<caption>접수정보</caption>
			<colgroup>
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view"></ul>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big fl"><input type="button" value="등록하기" onclick="check_form()" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	multi_setting('file_fname', '<?=$file_multi_size;?>', 'check', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		$.ajax({
			type: 'post', dataType: 'json', url: link_ok,
			data: $('#postform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					list_data();
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}
//]]>
</script>
