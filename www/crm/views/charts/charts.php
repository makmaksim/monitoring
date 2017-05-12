<?php defined('BASEPATH') OR exit('No direct script access allowed'); //var_dump($charts) ?>
<!--<pre>
    <?php print_r($charts) ?>
</pre>-->
<?php if(!empty($charts)) : ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <?php foreach($charts as $key => $chart) : 
        $params = unserialize($chart->params);
    ?>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['bar']});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([

              [<?=implode(',', $chart->names) ?>],
              <?php if(isset($chart->res) && $chart->res) : foreach($chart->res as $val) : ?>
                [<?=implode(',', $val)?>],
              <?php endforeach; endif;?>
            ]);

            var options = {
              chart: {
                title: '<?=$chart->name?>',
                subtitle: '<?=$chart->description?>',
              },
              bars: '<?=($params['type'] == 1) ? 'horizontal' : 'vertical'?>', // Required for Material Bar Charts.
              hAxis: {format: 'decimal'},
            };

            var chart = new google.charts.Bar(document.getElementById('chart<?=$key?>'));

            chart.draw(data, options);
          }
        </script>
        <div class="chart_block">
            <div id="chart<?=$key?>"></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>