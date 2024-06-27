<?
setcookie("isLogin", "Y", 0, "/");

header("Location:index.php?pId=" . $pId . "&pPw=" . $pPw);
?>