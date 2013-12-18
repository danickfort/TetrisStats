<?php
include_once "../../../Highchart.php";

$chart = new Highchart();

$chart->chart->renderTo = "container";
//$chart->chart->type = "line";
$chart->title->text = "Player Progression";
$chart->subtitle->text = "An example of irregular time data in Highcharts JS";
$chart->xAxis->type = "datetime";
$chart->xAxis->dateTimeLabelFormats->days = "%e. %b";
$chart->yAxis->title->text = "game time";
$chart->yAxis->min = 0;
$chart->tooltip->formatter = new HighchartJsExpr("function() {
                                    return '<b>'+ this.series.name +'</b><br/>'+
                                    Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' m';}");

$chart->series[]->name = "ScoutyX";

$prout = array(	array(new HighchartJsExpr("Date.UTC(1970,  1, 27,11,11)"), 4),
                array(new HighchartJsExpr("Date.UTC(1971,  2, 27,11,11)"), 11),
                array(new HighchartJsExpr("Date.UTC(1973,  3, 27,11,11)"), 1.1),
                array(new HighchartJsExpr("Date.UTC(1973,  4, 27,11,11)"), 0.25),
                array(new HighchartJsExpr("Date.UTC(1973,  5, 27,11,11)"), 2),
                array(new HighchartJsExpr("Date.UTC(1974,  6, 27,11,11)"), 3),
                array(new HighchartJsExpr("Date.UTC(1975,  7, 27,11,11)"), 1.1),
                array(new HighchartJsExpr("Date.UTC(1976,  8, 27,11,11)"), 0.25),
                array(new HighchartJsExpr("Date.UTC(1976,  9, 27,11,11)"), 5),
                array(new HighchartJsExpr("Date.UTC(1976,  9, 27,11,11)"), 6),
                array(new HighchartJsExpr("Date.UTC(1977,  10, 27,11,11)"), 1.1),
                array(new HighchartJsExpr("Date.UTC(1977,  11, 27,11,11)"), 0.25),
                array(new HighchartJsExpr("Date.UTC(1978,  11, 27,11,11)"), 7));
				
$chart->series[0]->data = $prout;



$chart->series[]->name = "Temps Tetris";
$chart->series[1]->data = array(
                array(new HighchartJsExpr("Date.UTC(1970,  9, 18)"), 0),
                array(new HighchartJsExpr("Date.UTC(1970,  9, 26)"), 0.2),
                array(new HighchartJsExpr("Date.UTC(1970, 11,  1)"), 0.47),
                array(new HighchartJsExpr("Date.UTC(1970, 11, 11)"), 0.55),
                array(new HighchartJsExpr("Date.UTC(1970, 11, 25)"), 1.38),
                array(new HighchartJsExpr("Date.UTC(1971,  0,  8)"), 1.38),
                array(new HighchartJsExpr("Date.UTC(1971,  0, 15)"), 1.38),
                array(new HighchartJsExpr("Date.UTC(1971,  1,  1)"), 1.38),
                array(new HighchartJsExpr("Date.UTC(1971,  1,  8)"), 1.48),
                array(new HighchartJsExpr("Date.UTC(1971,  1, 21)"), 1.5),
                array(new HighchartJsExpr("Date.UTC(1971,  2, 12)"), 1.89),
                array(new HighchartJsExpr("Date.UTC(1971,  2, 25)"), 2.0),
                array(new HighchartJsExpr("Date.UTC(1971,  3,  4)"), 1.94),
                array(new HighchartJsExpr("Date.UTC(1971,  3,  9)"), 1.91),
                array(new HighchartJsExpr("Date.UTC(1971,  3, 13)"), 1.75),
                array(new HighchartJsExpr("Date.UTC(1971,  3, 19)"), 1.6),
                array(new HighchartJsExpr("Date.UTC(1971,  4, 25)"), 0.6),
                array(new HighchartJsExpr("Date.UTC(1971,  4, 31)"), 0.35),
                array(new HighchartJsExpr("Date.UTC(1971,  5,  7)"), 0));
/*
$chart->series[]->name = "Winter 2009-2010";
$chart->series[2]->data = array(
                array(new HighchartJsExpr("Date.UTC(1970,  9,  9)"), 0),
                array(new HighchartJsExpr("Date.UTC(1970,  9, 14)"), 0.15),
                array(new HighchartJsExpr("Date.UTC(1970, 10, 28)"), 0.35),
                array(new HighchartJsExpr("Date.UTC(1970, 11, 12)"), 0.46),
                array(new HighchartJsExpr("Date.UTC(1971,  0,  1)"), 0.59),
                array(new HighchartJsExpr("Date.UTC(1971,  0, 24)"), 0.58),
                array(new HighchartJsExpr("Date.UTC(1971,  1,  1)"), 0.62),
                array(new HighchartJsExpr("Date.UTC(1971,  1,  7)"), 0.65),
                array(new HighchartJsExpr("Date.UTC(1971,  1, 23)"), 0.77),
                array(new HighchartJsExpr("Date.UTC(1971,  2,  8)"), 0.77),
                array(new HighchartJsExpr("Date.UTC(1971,  2, 14)"), 0.79),
                array(new HighchartJsExpr("Date.UTC(1971,  2, 24)"), 0.86),
                array(new HighchartJsExpr("Date.UTC(1971,  3,  4)"), 0.8),
                array(new HighchartJsExpr("Date.UTC(1971,  3, 18)"), 0.94),
                array(new HighchartJsExpr("Date.UTC(1971,  3, 24)"), 0.9),
                array(new HighchartJsExpr("Date.UTC(1971,  4, 16)"), 0.39),
                array(new HighchartJsExpr("Date.UTC(1971,  4, 21)"), 0));*/
?>

<html>
  <head>
    <title>Time data with irregular intervals</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
      foreach ($chart->getScripts() as $script) {
         echo '<script type="text/javascript" src="' . $script . '"></script>';
      }
    ?>
  </head>
  <body>
    <div id="container"></div>
    <script type="text/javascript">
    <?php
      echo $chart->render("chart1");
    ?>
    </script>
  </body>
</html>