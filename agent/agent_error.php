<?
	include "../bizstory/common/setting.php";
	include $local_path . "/agent/include/agent_chk.php";
	include $local_path . "/agent/include/header.php";
	// style="line-height:150%; padding:40px;"
?>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/agent/css/agent.css" media="all" />
<title>BizStory Agent</title>
</head>

<body id="agent_popup">
	<? include $local_path . "/agent/include/top.php"; ?>

	<div class="error_view">
		<?=$error_string;?>
	</div>
<?
	include $local_path . "/agent/include/tail.php";
?>