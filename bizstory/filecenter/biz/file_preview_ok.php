<?
	include "../../common/setting.php";
	include "../../common/no_direct.php";

	$update_query = "
		update " . $table_name . " set
			change_idx = '" . $change_idx . "'
		where
			" . $idx_name . " = '" . $table_idx . "'
	";
	db_query($update_query);
	query_history($update_query, $table_name, 'update');

	if ($table_name2 != '' && $idx_name2 != '' && $table_idx2 != '')
	{
		$update_query2 = "
			update " . $table_name2 . " set
				change_idx = '" . $change_idx . "'
			where
				" . $idx_name2 . " = '" . $table_idx2 . "'
		";
		db_query($update_query2);
		query_history($update_query2, $table_name2, 'update');
	}

	echo 'ok';
?>