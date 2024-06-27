<?
	include "../common/set_info.php";
	include "../common/no_direct.php";

	db_connect();

	$update_query = "
		update " . $table_name . " set
			change_idx = '" . $change_idx . "'
		where
			" . $idx_name . " = '" . $table_idx . "'
	";
	db_query($update_query);
	query_history($update_query, $table_name, 'update');
	
	db_close();

	echo $update_query;
?>