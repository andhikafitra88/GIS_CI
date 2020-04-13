<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Gis</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/js/canvasjs.min.js')?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
      <div class="container">
          <h1 class="text-center">Simple Gis</h1>
          <div class="row">
              <div class="col-md-12 col-sm-12" id="grafik" style="height: 300px; width: 100%;">

              </div>
          </div>
          <div class="row">
              <div class="col-md-6 col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-list"></span> Data Jalan</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                          <th>No</th>
                          <th>Nama Jalan</th>
                          <th></th>
                          <tbody id="datajalan">
                              <?php
                              $no = 1;
                              foreach($itemjalan->result() as $jalan){
                                  ?>
                                  <tr>
                                      <td><?php echo $no;?></td>
                                      <td><?php echo $jalan->namajalan;?></td>
                                      <td></td>
                                  </tr>
                                  <?php
                                  $no++;
                              }
                              ?>
                          </tbody>
                        </table>
                    </div>
                  </div>
              </div>
              <div class="col-md-6 col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-list"></span> Data Jembatan</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                          <th>No</th>
                          <th>Nama Jalan</th>
                          <th></th>
                          <tbody id="datajembatan">
                              <?php
                              $no = 1;
                              foreach($itemjembatan->result() as $jembatan){
                                  ?>
                                  <tr>
                                      <td><?php echo $no;?></td>
                                      <td><?php echo $jembatan->namajembatan;?></td>
                                      <td></td>
                                  </tr>
                                  <?php
                                  $no++;
                              }
                              ?>
                          </tbody>
                        </table>
                    </div>
                  </div>
                  <button type="button" name="loadgrafik" class="btn btn-primary" id="loadgrafik"><span class="glyphicon glyphicon-refresh"></span> Grafik</button>
              </div>
          </div>
      </div>
      <script>
      window.onload = loadgrafik;
      $(document).on('click','#loadgrafik',loadgrafik);
      function loadgrafik(){
          /*var jalan = '';
          var jembatan = '';
          buatgrafik(jalan,jembatan);*/
          $.ajax({
              url : '<?php echo site_url("home/loaddata");?>',
              dataType : 'json',
              type : 'POST',
              success : function(data,status){
                  if (data.status!='error') {
                      buatgrafik(data.jalan,data.jembatan);
                  }else{
                      buatgrafik(data.jalan,data.jembatan);
                  }
              },
              error : function(x,t,m){
                  alert(x.responseText);
              }
          })
      }
      function buatgrafik(jalan,jembatan){
          var chart = new CanvasJS.Chart("grafik",
          {
            title:{
              text: "Data Jalan dan Jembatan"
            },
            animationEnabled: true,
            axisY: {
              title: "Jumlah Jalan dan Jembatan"
            },
            legend: {
              verticalAlign: "bottom",
              horizontalAlign: "center"
            },
            theme: "theme2",
            data: [

            {
              type: "column",
              showInLegend: true,
              legendMarkerColor: "grey",
              //legendText: "MMbbl = one million barrels",
              dataPoints: [
              {y: jalan,  label: "Jalan"},
              {y: jembatan,  label: "Jembatan"}
              ]
            }
            ]
          });

          chart.render();
      }
      </script>
  </body>
</html>