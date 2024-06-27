<?
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";

// 이미지
	$img_where = " and cf.cf_idx = '" . $cf_idx . "'";
	$img_data = company_file_data('view', $img_where);
?>
	<img src="<?=$company_dir;?>/<?=$img_data["img_sname"];?>" alt="<?=$img_data['subject'];?>" style="cursor:pointer" onclick="window.close();" />