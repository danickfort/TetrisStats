<?php
/**
*
* @author Danick Fort, Gary Nietlispach
*/


include_once "charts/highcharts/Highchart.php";

class ChartManager {

   private $userId;
   private $databaseConnection;

	
	public function __construct($userId, $databaseConnection) {
		$this->userId = $userId;
		$this->databaseConnection = $databaseConnection;
	}
        
		public function getNumberOfGamesPlayed()
		{
			$query = "SELECT COUNT(1) AS c FROM ts_times WHERE user='" . $this->userId . "'"; // COUNT(1) is fastest execution time. We don't need any info from row.
			$result = mysql_query($query, $this->databaseConnection);
			$a = mysql_fetch_array($result);
			echo $a['c'];
		}
		
		public function getPlayerHighscoresJSON()
		{
			$return_array = Array();

			$query = "SELECT ts_times.time FROM ts_times,ts_users WHERE ts_times.user = ts_users.id AND ts_users.id =" . $this->userId . " ORDER BY time ASC LIMIT 10";
			$res = mysql_query($query, $this->databaseConnection);
			while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
				array_push($return_array,$row);
			}
			
			echo json_encode($return_array);	
		}
        public function getPieChart()
        {
            $chart = new Highchart();
            
            $chart->chart->renderTo = "pieContainer";
            $chart->chart->plotBackgroundColor = null;
            $chart->chart->plotBorderWidth = null;
            $chart->chart->plotShadow = false;
			$chart->chart->width = 400;
			$chart->chart->height = 400;
            $chart->title->text = "Combo spread";
            $chart->title->enabled = false;
            
            $chart->tooltip->formatter = new HighchartJsExpr("function() {
                return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %'; }");
            
            $chart->plotOptions->pie->allowPointSelect = 1;
            $chart->plotOptions->pie->cursor = "pointer";
            $chart->plotOptions->pie->dataLabels->enabled = false;
            $chart->plotOptions->pie->showInLegend = 0;
			$chart->plotOptions->pie->size = "100%";
            
            $data = array();
            
            $query = "SELECT * FROM ts_users_stats WHERE user='" . $this->userId . "'";
            $result = mysql_query($query, $this->databaseConnection);
            if (mysql_num_rows($result) == 1)
            {
              while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
              {
                $tetrises = intval($row['tetrises']);
                $triples = intval($row['triples']);
                $doubles = intval($row['doubles']);
                $lines = intval($row['lines']);
                $singles = $lines - ($tetrises*4) - ($triples*3) - ($doubles*2);
                $data = array(array("Tetris",$tetrises),array("Triple",$triples),array("Double",$doubles),array("Single",$singles));
              }
            }
            else return("Pas de données pour le pie chart!");
            $chart->series[] = array('type' => "pie",
                                     'name' => "Combo types spread",
                                     'yDecimals' => 2
                                    );
            $chart->series[0]->data = $data;
            ?>

    <div id="pieContainer" style="float:left;"></div>
    <script type="text/javascript">
    <?php
      echo $chart->render("chart1");
    ?>
    </script>
    <?
		}
		public function getLineChart()
		{
			$chart = new Highchart();
			
			$chart->chart->renderTo = "lineChartContainer";
			$chart->chart->type = "line";
			$chart->chart->size = "100%";
			$chart->chart->width = 400;
			$chart->chart->height = 400;
			
			$chart->title->text = "Evolution of completion time, through time";
			$chart->subtitle->text = null;
			$chart->xAxis->type = "datetime";
			$chart->xAxis->dataTimeLabelFormats->millisecond = "%M:%S.%L";
			$chart->xAxis->dateTimeLabelFormats->month = "%M:%S.%L";
			$chart->xAxis->dateTimeLabelFormats->year = "%b";
			$chart->yAxis->title->text = "Completion time";
			$chart->xAxis->title->text = "Date and time";
			
			$chart->yAxis->type = "datetime";
			$chart->yAxis->dataTimeLabelFormats->millisecond = "%M:%S.%L";
			$chart->yAxis->dateTimeLabelFormats->month = "%M:%S.%L";
			$chart->yAxis->dateTimeLabelFormats->day = "%M:%S.%L";
			$chart->yAxis->dateTimeLabelFormats->minute = "%M:%S.%L";
			$chart->yAxis->dateTimeLabelFormats->year = "%M:%S.%L";
			$chart->tooltip->formatter = new HighchartJsExpr("function() {
												return '<b>'+ Highcharts.dateFormat('%a, %e %b', this.x) + '</b><br/>'+
												Highcharts.dateFormat('%M:%S.%L', this.y);}");

			$chart->series[]->name = "Completion time";


			$data = array();
			$query = "SELECT UNIX_TIMESTAMP(playedOn) AS playedOn, time FROM ts_times WHERE user='" . $this->userId . "'";
            $result = mysql_query($query, $this->databaseConnection);
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
			  $realPlayedOn = ($row['playedOn'] + (3600*2))*1000;
			  list($minute,$second,$ms) = split(":", $row['time'],3);
			  $time = new HighchartJsExpr("Date.UTC(1970,  9, 27, 0, $minute, $second, $ms)");
			  $data[] = array($realPlayedOn, $time);
			}
							
			$chart->series[0]->data = $data;
			$chart->series[0]->showInLegend =false;
            ?>

		<div id="lineChartContainer" style="float:left;"></div>
		<script type="text/javascript">
		<?php
		  echo $chart->render("chart1");
		?>
		</script>
		<?
		}

}
?>