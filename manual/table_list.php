<?
	include "../bizstory/common/setting.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="robots" content="noindex, nofollow" />
<meta name="description" content="Business application." />
<meta name="keywords" content="bizstory,biz,business,ubstory" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="icon" type="image/ico" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.png" rel="icon" type="image/png" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="shortcut icon" type="image/ico" />
<title> Bizstory Manual DATABASE</title>
<style>
	html, body {height:100%;}
	html {overflow-y:scroll;}
	body {background-color:#fff; margin:5px; padding:5px;}
	body {font:12px "NanumGothicBoldWeb", "NanumGothicWeb", "Malgun Gothic", "Dotum", "돋움", "sans-serif", "AppleGothic", "Arial", "Helvetica";}
	img {vertical-align:middle;}
	table {border-spacing:0px; border-top:1px solid; border-left:1px solid;}
	caption {font-size:16px; font-weight:700;}
	td {padding:5px; border-right:1px solid; border-bottom:1px solid;}
	th {padding:7px; border-right:1px solid; border-bottom:1px solid;}

	.center {text-align:center;}
</style>
</head>

<body>
<?
	db_connect();

	$table_sql = mysql_query("show tables");
	while ($table_data = mysql_fetch_array($table_sql))
	{
		$chk_string = substr($table_data[0], 0, 1);
		if ($chk_string != '_')
		{
			$comm_sql = mysql_query("select table_comment from information_schema.tables where table_name = '" . $table_data[0] . "'");
			$comm_data = mysql_fetch_array($comm_sql);
?>
	<table summary="<?=$table_data[0];?> 정보">
		<caption><?=$table_data[0];?>(<?=$comm_data["table_comment"];?>)</caption>
		<colgroup>
			<col width="150px;"></col>
			<col width="180px;"></col>
			<col width="60px;"></col>
			<col width="150px;"></col>
			<col width="200px;"></col>
		</colgroup>
		<thead>
			<tr>
				<th>필드</th>
				<th>종류</th>
				<th>Null</th>
				<th>기본값</th>
				<th>설명</th>
			</tr>
		</thead>
		<tbody>
<?
		$field_sql = mysql_query("show full columns from " . $table_data[0]);
		while ($field_data = mysql_fetch_array($field_sql))
		{
			$field_name    = $field_data["Field"];
			$field_type    = $field_data["Type"];
			$field_null    = $field_data["Null"];
			$field_key     = $field_data["Key"];
			$field_default = $field_data["Default"];
			$field_extra   = $field_data["Extra"];
			$field_Comment = $field_data["Comment"];

			if ($field_null == "NO") $field_null = "N";
			else if ($field_null == "YES") $field_null = "Y";
?>
			<tr>
				<td><?=$field_name;?></td>
				<td><?=$field_type;?></td>
				<td class="center"><?=$field_null;?></td>
				<td><?=$field_default;?></td>
				<td><?=$field_Comment;?></td>
			</tr>
<?
		}
?>
		</tbody>
	</table>
	<br />

	<table summary="<?=$table_data[0];?> 인덱스 정보">
		<caption>인덱스</caption>
		<thead>
			<tr>
				<th>키 이름</th>
				<th>종류</th>
				<th>고유값</th>
				<th>Packed</th>
				<th>필드</th>
				<th>Cardinality</th>
				<th>Collation</th>
				<th>Null</th>
				<th>Comment</th>
			</tr>
		</thead>
		<tbody>
<?
		$index_sql = mysql_query("SHOW INDEX FROM " . $table_data[0] . " WHERE Seq_in_index = 1");
		while ($index_data = mysql_fetch_array($index_sql))
		{
			if ($index_data["Non_unique"] == "0") $index_data["Non_unique"] = "예";
			else if ($index_data["Non_unique"] == "1") $index_data["Non_unique"] = "아니오";
?>
			<tr>
				<th><?=$index_data["Key_name"];?></th>
				<td><?=$index_data["Index_type"];?></td>
				<td><?=$index_data["Non_unique"];?></td>
				<td><?=$index_data["Packed"];?></td>
				<td><?=$index_data["Column_name"];?></td>
				<td><?=$index_data["Cardinality"];?></td>
				<td><?=$index_data["Collation"];?></td>
				<td><?=$index_data["Null"];?></td>
				<td><?=$index_data["Comment"];?></td>
			</tr>
<?
		}
?>
		</tbody>
	</table>
	<br />
<?
		}
	}

	db_close();
?>
</body>
</html>