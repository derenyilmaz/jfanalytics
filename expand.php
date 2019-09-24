<?php
    include "JotForm.php";

    $id = $_GET['id'];
    $key = $_GET['key'];
    $api = new JotForm($key);
    $submissions = $api->getFormSubmissions($id, 0, 500);
    $webhooks = $api->getFormWebhooks($id);
    $history = $api->getHistory();
    $userInfo = $api->getUser();
    $properties = $api->getFormProperties($id);
    $ips = array();
    $dates = array();
    $hooks = array();
    $form = $api->getForm($id);
    $total = 0;

    $title = $form["title"];
    $created = strtotime($userInfo['created_at']);


    foreach($submissions as $item){
        $dates[] = $item['created_at'];
        $ip = $item['ip'];
        $ips[$ip]++;
        $total++;
    }

    $totalhooks = 0;
    foreach($webhooks as $item){
        $totalhooks++;
        $hooks[$item]++;
    }

    //var_dump($history);

    $dataPoints = array();

    foreach($ips as $ip => $number){
        $dataPoints[] = array("y" => $number, "label" => $ip);
    }

    $dataPoints2 = array(
    	array("x"=> 00.00, "y"=> 0),
    	array("x"=> 01.00, "y"=> 0),
    	array("x"=> 02.00, "y"=> 0),
    	array("x"=> 03.00, "y"=> 0),
    	array("x"=> 04.00, "y"=> 0),
    	array("x"=> 05.00, "y"=> 0),
    	array("x"=> 06.00, "y"=> 0),
    	array("x"=> 07.00, "y"=> 0),
    	array("x"=> 08.00, "y"=> 0),
    	array("x"=> 09.00, "y"=> 0),
    	array("x"=> 10.00, "y"=> 0),
    	array("x"=> 11.00, "y"=> 0),
    	array("x"=> 12.00, "y"=> 0),
        array("x"=> 13.00, "y"=> 0),
        array("x"=> 14.00, "y"=> 0),
        array("x"=> 15.00, "y"=> 0),
        array("x"=> 16.00, "y"=> 0),
        array("x"=> 17.00, "y"=> 0),
        array("x"=> 18.00, "y"=> 0),
        array("x"=> 19.00, "y"=> 0),
        array("x"=> 20.00, "y"=> 0),
        array("x"=> 21.00, "y"=> 0),
        array("x"=> 22.00, "y"=> 0),
        array("x"=> 23.00, "y"=> 0)
    );

    foreach($dates as $item){
        $hour = (intval(substr($item,11,2))+8)%23;
        $dataPoints2[$hour]["y"]++;
    }

    $time = time();
    $interval = $time-$created;
    $days = round($interval/86400);

    //var_dump($properties);
    $integrations = array();
    $totalintegrations = 0;
    foreach($properties['integrations'] as $name => $items){
        //echo $name.'<br>';
        $totalintegrations++;
        $integrations[] = $name;
    }

    /*if(!$integrations){
        print "no integrations".'<br>';
    }
    if(!$webhooks){
        print "no webhooks";
    }*/
?>
<html>
<head>
    <title> <?php echo $title ?> </title>
    <link rel="stylesheet" href="skeleton.css">
    <div class="row">
        <div class="two columns"><p></p></div>
        <div class="three columns"><form action="https://www.jotform.com"><button type="submit">main page</button></form></div>
        <div class="three columns"><form action="forms.php"><button type="submit">forms</button></form></div>
        <div class="two columns"><form action="index.php"><button type="submit">log out</button></form></div>
        <div class="two columns"><p></p></div>
    </div>
    <style>
        .canvasjs-chart-credit{
            display: none !important;
        }
    </style>
    <script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
        	title: {
        		text: "submissions by ip addresses (<?php echo $total; ?> total)"
        	},
        	axisY: {
        		title: "number of submissions"
        	},
        	data: [{
        		type: "line",
        		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        	}]
        });
        chart.render();

        var chart2 = new CanvasJS.Chart("chartContainer2", {
        	animationEnabled: true,
        	exportEnabled: true,
        	theme: "light1", // "light1", "light2", "dark1", "dark2"
        	title:{
        		text: "submissions by hours (<?php echo $total; ?> total)"
        	},
        	data: [{
        		type: "column", //change type to bar, line, area, pie, etc
        		//indexLabel: "{y}", //Shows y value on all Data Points
        		indexLabelFontColor: "#5A5757",
        		indexLabelPlacement: "outside",
        		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
        	}]
        });
        chart2.render();

    }
    </script>
</head>
<body>
    <div class="row">
        <div class="one columns"><p></p></div>
        <div class="five columns">
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        </div>
        <div class="five columns">
            <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
        </div>
        <div class="one columns"><p></p></div>
    </div>
    <br>



    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <div class="row">
        <div class="one column"> <p></p> </div>
        <div class="five columns">
            <div class="u-full-width">
                <div class="row">
                    <div class="one columns">
                        <p></p>
                    </div>
                    <div class="one columns">
                        <p></p>
                    </div>

                    <div class="three columns, custom-header">integrations <?php if($totalintegrations){echo '('."total $totalintegrations".')';} ?></div>
                    <div class="one column">
                        <p></p>
                    </div>
                </div>

                <hr>

                <?php $i = 1;
                if($integrations){
                    foreach($integrations as $item) { ?>
                        <div class="row">
                            <div class="one columns">
                                <p></p>
                            </div>
                            <div class="one columns"> <?php echo $i; ?> </div>
                            <div class="three columns"> <?php echo $item; ?> </div>
                            <div class="one columns"><p></p></div>
                        </div>
                        <hr>
                        <?php $i++;
                    }
                }
                else{
                    print "no integrations!";
                }
                ?>

            </div>
        </div>

        <div class="five columns">
            <div class="u-full-width">
                <div class="row">
                    <div class="one columns">
                        <p></p>
                    </div>
                    <div class="one columns">
                        <p></p>
                    </div>

                    <div class="three columns, custom-header">webhooks <?php if($totalhooks){echo '('."total $totalhooks".')';} ?></div>
                    <div class="one column">
                        <p></p>
                    </div>
                </div>

                <hr>

                <?php $i = 1;
                if($webhooks){
                    foreach($webhooks as $item) { ?>
                        <div class="row">
                            <div class="one columns">
                                <p></p>
                            </div>
                            <div class="one columns"> <?php echo $i; ?> </div>
                            <div class="three columns"> <?php echo $item; ?> </div>
                            <div class="one columns"><p></p></div>
                        </div>
                        <hr>
                        <?php $i++;
                    }
                }
                else{
                    print "no webhooks!";
                }
                ?>

            </div>
        </div>
        <div class="one column"> <p></p> </div>
    </div>
</body>
</html>
