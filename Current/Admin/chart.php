<?php
require_once 'Admin-Header.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/apexcharts.css">
    <title>Chart</title>
</head>
<style>
    #area {
        width: 700px;
        height: 400px;
        margin-left: 10%;
        display: inline-block;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
        
    }
    #trend{
        width: 700px;
        height: 400px;
        margin-left: 10%;
        display: inline-block;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
        
    }
    #totalcomple{
        width: 700px;
        height: 400px;
        display: inline-block;
        margin-left: 10%;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
    #customerarea{
        width: 700px;
        height: 400px;
        display: inline-block;
        margin-left: 10%;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
        
    }
    #numberofregister{
        display: inline-block;
        width: 700px;
        height: 400px;
        margin-left: 10%;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
        
    }
    #paynopay{
        display: inline-block;
        width: 700px;
        height: 400px;
        margin-left: 10%;
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
        
        
    }
</style>
<body>
    <div id ="trend"></div>
    <div id ="area"></div>
    <div id ="totalcomple"></div>
    <div id ="customerarea"></div>
    <div id ="numberofregister"></div>
    <div id ="paynopay"></div>
</body>
<script src ="dist/apexcharts.min.js"></script>
<script>
    var areachart = document.querySelector('#trend');
     var options = {
          series: [{
            name: "mmbtu",
            data: [1000, 4100, 3500, 5100, 4900, 6200, 6900, 9100, 14800]
        }],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Overall Gas Usage Trend',
          align: 'center'
        },
       
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
        };

        var areachart = new ApexCharts(document.querySelector("#trend"), options);
        areachart.render();
      
</script>
<script>
      var areatrend = document.querySelector('#area');
      var options = {
          series: [{
            name: "mmbtu",
          data: [14000, 14300, 4480, 4700, 5400, 5800, 6900, 11000, 12000, 13800]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            horizontal: true,
            distributed: true
          }
        },
        dataLabels: {
          enabled: false,
          
        },
        title: {
          text: 'Overall Gas Usage By Area',
          align: 'center'
        },
       
        xaxis: {
          categories: [' Airport Road', 'Bandar Baru Permyjaya', 'Luak', 'Brighton Road', 'Lutong', 'PermaisuriPujut', 'Piasau',
            'Pujut', 'Senadin', 'Town'
          ],
        },
        
        };

        var chart = new ApexCharts(document.querySelector("#area"), options);
        chart.render();
      
</script>
<script>
    var optionscompletion = document.querySelector('#totalcomple');
    var optionscompletion = {
          series: [63],
          chart: {
          height: 350,
          type: 'radialBar',
        },
        title: {
          text: 'Coverage Completion',
          align: 'center'
        },
        plotOptions: {
          radialBar: {
            hollow: {
              size: '70%',
            }
          },
        },
        labels: ['Coverage Gas For Miri'],
        };

        var optionscompletion = new ApexCharts(document.querySelector("#totalcomple"), optionscompletion);
        optionscompletion.render();
      
</script>
<script>
    var totalcustomerarea = document.querySelector('#customerarea');
    var options = {
          series: [44, 55, 13, 43, 22,44, 55, 13, 43, 22],
          chart: {
          width: 600,
          type: 'pie',
        },
        labels: [' Airport Road', 'Bandar Baru Permyjaya', 'Luak', 'Brighton Road', 'Lutong', 'PermaisuriPujut', 'Piasau',
            'Pujut', 'Senadin', 'Town'],
        responsive: [{
          breakpoint: 900,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };


        var totalcustomerarea = new ApexCharts(document.querySelector("#customerarea"), options);
        totalcustomerarea.render();
      
      
</script>
<script>
    var numberofpeopleregister = document.querySelector('#numberofregister');
    var options = {
          series: [44, 55],
          chart: {
          width: 500,
          type: 'pie',
        },
        labels: [' Register', 'Unregister'],
        responsive: [{
          breakpoint: 900,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };


        var numberofpeopleregister = new ApexCharts(document.querySelector("#numberofregister"), options);
        numberofpeopleregister.render();
      
      
</script>

<script>
    var paynopay = document.querySelector('#paynopay');
    var options = {
        series: [{
          name: "mmbtu",
        data: [14000, 14300, 4480, 4700]
      }],
        chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          borderRadius: 10,
          horizontal: false,
          distributed: true
        }
      },
      dataLabels: {
        enabled: false,
        
      },
      title: {
        text: 'Status',
        align: 'center'
      },
     
      xaxis: {
        categories: ['Paid', 'UnPaid', 'OverPaid','Not Paid',
        ],
      },
      
      };

      var paynopay = new ApexCharts(document.querySelector("#paynopay"), options);
      paynopay.render();
    
</script>
</html>

<?php
require_once 'Admin-Footer.php'
?>
