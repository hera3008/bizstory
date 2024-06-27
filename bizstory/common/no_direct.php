<?
	if (!isset($_SERVER["HTTP_REFERER"]))
	{
		$string_url = urlencode($local_dir . '/');
		error_page('no_direct', $string_url);
		exit;
	}
?>