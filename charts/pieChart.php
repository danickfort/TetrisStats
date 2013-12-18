<?php
include_once "highcharts/Highchart.php";
require("../core/database.php");

$chart = new Highchart();
$database = DatabaseManager::getInstance();

$chart->chart->renderTo = "pieContainer";
$chart->chart->plotBackgroundColor = null;
$chart->chart->plotBorderWidth = null;
$chart->chart->plotShadow = false;
$chart->title->text = "Fuck";

$chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %'; }");

$chart->plotOptions->pie->allowPointSelect = 1;
$chart->plotOptions->pie->cursor = "pointer";
$chart->plotOptions->pie->dataLabels->enabled = false;
$chart->plotOptions->pie->showInLegend = 1;
/*$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr("function() {
                    return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %';");*/



$data = array();
$query = "SELECT * FROM ts_users_stats WHERE user='1'";
$result = $database->query($query);
if (mysql_num_rows($result) == 1)
{
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
  {
    $tetrises = intval($row['tetrises']);
    $triples = intval($row['triples']);
    $doubles = intval($row['doubles']);
    $lines = intval($row['lines']);
    $singles = $lines - $tetrises - $triples - $doubles;
    $data = array(array("Tetris",$tetrises),array("Triple",$triples),array("Double",$doubles),array("Single",$singles));
  }
}
else die('more than 1 result!');
$chart->series[] = array('type' => "pie",
                         'name' => "Combo types spread",
                         'yDecimals' => 2
                        );
$chart->series[0]->data = $data;
/*$chart->series[] = array('type' => "pie",
                         'name' => "Browser share",
                         'data' => array(array("Firefox", 45),
                                         array("IE", 26.8),
                                         array('name' => 'Chrome',
                                               'y' => 12.8,
                                               'sliced' => true,
                                               'selected' => true),
                                         array("Safari", 8.5),
                                         array("Opera", 6.2),
                                         array("Others", 0.7)));*/
?>

    <div id="pieContainer"></div>
    <script type="text/javascript">
    <?php
      echo $chart->render("chart1");
    ?>
    </script>