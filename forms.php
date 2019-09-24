<?php
    include "JotForm.php";

    session_start();
    $appkey = $_SESSION['key'];
    $usr = $_SESSION['usr'];
    $newAPI = new JotForm($appkey);
    $forms = $newAPI->getForms(0,500);
    $userInfo = $newAPI->getUser();
    $created = strtotime($userInfo['created_at']);
    $time = time();
    $interval = $time-$created;
    $days = round($interval/86400);

?>

<html>
<head>
    <title><?php echo "$usr" ?></title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="skeleton.css">
    <div class="row">
        <div class="two columns"><p></p></div>
        <div class="three columns"><p> you have registered <?php echo $days; ?> days ago</p></div>
        <div class="two columns"><form action="https://www.jotform.com"><button type="submit">main page</button></form></div>
        <div class="two columns"><form action="index.php"><button type="submit">log out</button></form></div>
        <div class="two column"><p></p></div>
    </div>
</head>

<body>
    <div class="row"> <p></p></div>
    <div class="u-full-width">
		<div class="row">
			<div class="two columns"> <p></p> </div>
			<div class="five columns"><b>Form</b></div>
			<div class="four columns"><b>ID</b></div>
			<div class="one column">
			</div>
		</div>

		<hr>

		<?php $i = 0;
		while ($row = $forms[$i]) { ?>
			<div class="row">
				<div class="one columns">
					<p></p>
				</div>
                <div class="one columns"> <?php echo $i+1; ?> </div>
				<div class="five columns"> <?php echo $row['title']; ?> </div>
				<div class="three columns"> <?php echo $row['id']; ?> </div>
				<div class="one columns"><p></p></div>
                <div class="one columns">
                    <a href="expand.php?id=<?php echo $row['id'] ?>&key=<?php echo $appkey?>">expand</a>
                </div>
			</div>
			<hr>
		<?php $i++;
		} ?>

	</div>
</body>
</html>
