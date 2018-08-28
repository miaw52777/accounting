<?

$datapoint = "";
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
		data.addColumn({ type: 'string', id: 'Start' });
        data.addColumn({ type: 'number', id: 'President' });
        
        
        data.addRows([
          [ ("2018/03/01"), 50 ],
          [ ("2018/03/02"), 100 ],
          [ ("2018/03/03"), 150 ]
		  ]);


        var options = {
          title: 'Weekly Chart',
          curveType: 'function',
		  titleTextStyle: {		  
		    fontName: 'Arial',
		    fontSize: 30			
		  },
          legend: 'none',
		  pointSize: 15,
		  series: {
			0: { pointShape: 'square' }
		  }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>