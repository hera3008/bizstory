<?
echo "out=";
print_r($_POST);
function test()
{
	global $_POST, $_SESSION;

	echo "in=";
	print_r($_POST);
}
test();

?>