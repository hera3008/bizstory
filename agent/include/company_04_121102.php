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
	<div id="company_image_view"></div>
	<div id="company_page_view4">
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
				<a href="<?=$company_dir;?>/<?=$license_data["img_sname"];?>" id="img_image_<?=$file_idx;?>" title="사업자등록증 No.<?=$file_idx;?>"></a>
			</li>
<?
			$file_idx++;
		}

		if ($cert_list['total_num'] > 0)
		{
			foreach ($cert_list as $k => $cert_data)
			{
				if (is_array($cert_data))
				{
?>
			<li>
				<a href="<?=$company_dir;?>/<?=$cert_data["img_sname"];?>" id="img_image_<?=$file_idx;?>" title="<?=$cert_data['subject'];?> No.<?=$file_idx;?>"></a>
			</li>
<?
					$file_idx++;
				}
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

<script type="text/javascript" src="<?=$local_dir;?>/agent/js/jquery.lightbox.js" charset="utf-8"></script>
<script type="text/javascript">
//<![CDATA[
<?
	if ($total_num > 0)
	{
?>
	$('#company_page_view4 a').lightBox();
	$('#img_image_1').click();
<?
	}
?>
//]]>
</script>