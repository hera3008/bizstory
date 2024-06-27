<?
function test()
{
	$str = 
		Array
		(
			'total_login' => 7625,
			'last_login' => '2023-07-25 17:41:46',
			'work_all' => 726,
			'work_ing' => 32,
			'receipt_all' => 387,
			'receipt_ing' => 1,
			'msg_all' => 622,
			'msg_ing' => 0
		);

	return $str;

}

$chk = test();
print_r($chk);
?>