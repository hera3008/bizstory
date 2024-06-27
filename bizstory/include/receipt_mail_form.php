<?
	global $site_url, $local_dir, $comp_receipt_path, $comp_receipt_dir;

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $table_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$receipt_data = $receipt_info->receipt_info_view();
	$file_list    = $receipt_info->receipt_file();
?>

<link type="text/css" rel="stylesheet" href="http://<?=$site_url;?><?=$local_dir;?>/bizstory/css/common.css" media="all" />
<fieldset>
	<legend class="blind">접수정보 폼</legend>
	<table class="tinytable view" summary="접수정보를 등록/수정합니다." border="1" cellpadding="3" cellspacing="0">
	<caption>접수정보</caption>
	<colgroup>
		<col width="100px" />
		<col width="300px" />
		<col width="100px" />
		<col width="300px" />
	</colgroup>
	<tbody>
		<tr>
			<th>지사</th>
			<td>
				<div class="left"><?=$receipt_data['part_name'];?></div>
			</td>
			<th>거래처명</th>
			<td>
				<div class="left"><?=$receipt_data['client_name'];?></div>
			</td>
		</tr>
		<tr>
			<th>접수분류</th>
			<td>
				<div class="left">
		<?
			$receipt_class = $receipt_data['receipt_class_str'];
			foreach ($receipt_class as $k => $v)
			{
				if ($k == 1) echo $v;
				else echo ' &gt; ', $v;
			}
		?>
				</div>
			</td>
			<th>작성자</th>
			<td>
				<div class="left1"><?=$receipt_data['writer'];?> (<a href="tel:<?=$receipt_data['tel_num'];?>" class="tel"><?=$receipt_data['tel_num'];?></a>)</div>
			</td>
		</tr>
		<tr>
			<th>제목</th>
			<td colspan="3">
				<div class="left"><strong><?=$receipt_data['subject'];?></strong></div>
			</td>
		</tr>
		<tr>
			<th>내용</th>
			<td colspan="3">
				<div class="left">
					<p class="memo"><?=$receipt_data['remark'];?></p>
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
							<a href="http://<?=$site_url;?><?=$local_dir;?>/bizstory/work/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>
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
			<th><label for="view_receipt_status">접수상태</label></th>
			<td>
				<div class="left"><?=$receipt_data['receipt_status_str'];?></div>
			</td>
		</tr>
	</tbody>
	</table>
</fieldset>