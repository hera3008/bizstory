<?
	include "../common/setting.php";
	include "../common/no_direct.php";
?>
				<tr>
					<th><label for="file_subject<?=$sort;?>">인증서제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="file_subject<?=$sort;?>" id="file_subject<?=$sort;?>" class="type_text" title="인증서제목" value="" size="40" />
							<input type="hidden" name="file_class<?=$sort;?>" id="file_class<?=$sort;?>" value="certificate" />
						</div>
					</td>
					<th><label for="file_fname<?=$sort;?>">인증서파일</label></th>
					<td>
						<div class="filewrap">
							<div class="file" id="file_fname<?=$sort;?>_view">
								<input type="file" name="file_fname<?=$sort;?>" id="file_fname<?=$sort;?>" class="type_text type_file type_multi" title="파일 선택하기" />
							</div>
							<span>* (.jpg, .gif, .png 만 가능) </span>
						</div>
					</td>
				</tr>