<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";

// 사업자등록증
	$license_where = " and cf.comp_idx = '" . $client_comp . "' and cf.file_class = 'license'";
	$license_data = company_file_data('view', $license_where);

// 인증서
	$cert_where = " and cf.comp_idx = '" . $client_comp . "' and cf.file_class = 'certificate'";
	$cert_list = company_file_data('list', $cert_where, '', '', '');

	$total_num = $license_data['total_num'] + $license_data['total_num'];
?>
	<!-- 인증서보기 -->
	<div id="company_page_view4" class="comp_box content1">
<?
	$file_idx = 1;
	if ($total_num > 0)
	{
?>
		<ul>
<?
		if ($license_data['total_num'] > 0)
		{
?>
			<li>
				<a href="javascript:void(0);" onclick="new_open('<?=$local_dir;?>/agent/include/comp_images.php?cf_idx=<?=$license_data['cf_idx'];?>', 'images_popup', '900', '700', 'Yes')" title="사업자등록증 No.<?=$file_idx;?>">
					<img src="<?=$company_dir;?>/<?=$license_data["img_sname"];?>" alt="사업자등록증" width="100px;" class="comp_images" />
					<br /><div class="img_name">사업자등록증</div>
				</a>
			</li>
<?
			$file_idx++;
		}

		foreach ($cert_list as $k => $cert_data)
		{
			if (is_array($cert_data))
			{
?>
			<li>
				<a href="javascript:void(0);" onclick="new_open('<?=$local_dir;?>/agent/include/comp_images.php?cf_idx=<?=$cert_data['cf_idx'];?>', 'images_popup', '900', '700', 'Yes')" title="<?=$cert_data['subject'];?> No.<?=$file_idx;?>">
					<img src="<?=$company_dir;?>/<?=$cert_data["img_sname"];?>" alt="<?=$cert_data['subject'];?>" width="100px;" class="comp_images" />
					<br /><div class="img_name"><?=$cert_data['subject'];?></div>
				</a>
			</li>
<?
				$file_idx++;
			}
		}
?>
		</ul>
<?
	}
	else
	{
		echo '등록된 인증서가 없습니다.';
	}
?>
	</div>
	<!-- //인증서보기 -->

<script type="text/javascript">
//<![CDATA[
	$(".content1").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
//]]>
</script>