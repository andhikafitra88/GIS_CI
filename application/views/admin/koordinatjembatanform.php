<!--script google map-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAoCaD__6xurXTvz-iCH0RhYfXgAnVH9w&callback=initMap"></script>
<script>
$(document).on('click','#clearmap',clearmap)
.on('click','#simpandaftarkoordinatjembatan',simpandaftarkoordinatjembatan)
.on('click','#hapusmarkerjembatan',hapusmarkerjembatan)
.on('click','#viewmarkerjembatan',viewmarkerjembatan);
    var map;
    var markers = [];

    function initialize() {
        var mapOptions = {
        zoom: 14,
        // Center di kantor kabupaten kudus
        center: new google.maps.LatLng(-6.990783, 110.422740)
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        // Add a listener for the click event
        google.maps.event.addListener(map, 'rightclick', addLatLng);
        google.maps.event.addListener(map, "rightclick", function(event) {
          var lat = event.latLng.lat();
          var lng = event.latLng.lng();
          $('#latitude').val(lat);
          $('#longitude').val(lng);
          //alert(lat +" dan "+lng);
        });
    }

    /**
     * Handles click events on a map, and adds a new point to the marker.
     * @param {google.maps.MouseEvent} event
     */
    function addLatLng(event) {
        var marker = new google.maps.Marker({
        position: event.latLng,
        title: 'Simple GIS',
        map: map
        });
        markers.push(marker);
    }
    //membersihkan peta dari marker
    function clearmap(e){
        e.preventDefault();
        $('#latitude').val('');
        $('#longitude').val('');
        setMapOnAll(null);
    }
    //buat hapus marker
    function setMapOnAll(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
      markers = [];
    }
    //end buat hapus marker

    function simpandaftarkoordinatjembatan(e){
        e.preventDefault();
        var datakoordinat = {'jembatan_id':$('#id_jembatan').val(),'latitude':$('#latitude').val(),'longitude':$('#longitude').val()};
        console.log(datakoordinat);
        $.ajax({
            url : '<?php echo site_url("admin/Koordinatjembatan/simpandaftarkoordinatjembatan") ?>',
            dataType : 'json',
            data : datakoordinat,
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('#daftarkoordinatjembatan').load('<?php echo current_url()."/ #daftarkoordinatjembatan > *" ?>');
                    alert(data.msg);
                    clearmap(e);
                }else{
                    alert(data.msg);
                }
            }
        })
    }
    function hapusmarkerjembatan(e){
        e.preventDefault();
        var datakoordinat = {'jembatan_id':$(this).data('iddatajembatan')};
        $.ajax({
            url : '<?php echo site_url("admin/Koordinatjembatan/hapusmarkerjembatan") ?>',
            data : datakoordinat,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    alert(data.msg);
                    $('#daftarkoordinatjembatan').load('<?php echo current_url()."/ #daftarkoordinatjembatan > *" ?>');
                    clearmap(e);
                }else{
                    alert(data.msg);
                }
            }
        })
    }
    function viewmarkerjembatan(e){
        e.preventDefault();
        var datakoordinat = {'jembatan_id':$(this).data('iddatajembatan')};
        $.ajax({
            url : '<?php echo site_url("admin/koordinatjembatan/viewmarkerjembatan") ?>',
            data : datakoordinat,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    clearmap(e);
                    //load marker
                    $.each(data.msg,function(m,n){
                        $('#latitude').val(n['latitude']);
                        $('#longitude').val(n['longitude']);
                        var lot = parseFloat(n["latitude"]);
                        var lmg = parseFloat(n["longitude"]);
                        var myLatLng = {lat: parseFloat(n["latitude"]), lng: parseFloat(n["longitude"])};
                        console.log(m,n);
                        addMarker(n['namajembatan'],myLatLng,lot,lmg);

                    })
                    //end load marker
                }else{
                    alert(data.msg);
                }
            }
        })
    }
    // Menampilkan marker lokasi jembatan
    function addMarker(nama,location,lat,lon) {
        var mapOptions = {
        zoom: 14,
        center: location
        }
var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title : nama
        });
        marker.setMap(map);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<!--end script google map-->
<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-globe"></span> Peta</div>
                <div class="panel-body" style="height:300px;" id="map-canvas">
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-list"></span> Daftar Koordinat</div>
                <div class="panel-body" style="min-height:300px;">
                    <form action="#">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control" name="latitude" id="latitude">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" id="longitude">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="datajembatan">Data jembatan</label>
                            <?php if ($itemdatajembatan->num_rows()!=null) {
                                echo '<select name="id_jembatan" id="id_jembatan" class="form-control">';
                                foreach ($itemdatajembatan->result() as $datajembatan) {
                                    echo "<option value='".$datajembatan->id_jembatan."'>".$datajembatan->namajembatan."</option>";
                                }
                                echo '</select>';
                            }else{
                                echo anchor('admin/jembatan', '<span class="glyphicon glyphicon-plus"></span> Tambah Data jembatan', 'class="btn btn-info form-control"');
                            } ?>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info btn-sm" id="simpandaftarkoordinatjembatan"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                            <button class="btn btn-info btn-sm" id="clearmap"><span class="glyphicon glyphicon-globe"></span> ClearMap</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-th-list"></span> Daftar Koordinat marker Data jembatan</div>
                <div class="panel-body" style="min-height:400px">
                <a href="<?php echo site_url('admin/koordinatjembatan/export'); ?>">Download Data</a>
                    <table class="table">
                        <th>No</th>
                        <th>Data jembatan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th></th>
                        <tbody id="daftarkoordinatjembatan">
                            <?php
                            if ($itemkoordinatjembatan->num_rows()!=null) {
                                $no = 1;
                                foreach ($itemdatajembatan->result() as $jembatan) {
                                    echo "<tr>";
                                    echo "<td>";
                                    echo $no++;
                                    echo "</td>";
                                    echo "<td>";
                                    echo $jembatan->namajembatan;
                                    echo "</td>";
                                    echo "<td>";
                                    foreach ($itemkoordinatjembatan->result() as $koordinat) {
                                        if ($koordinat->jembatan_id==$jembatan->id_jembatan) {
                                            echo $koordinat->latitude."</br>";
                                        }
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    foreach ($itemkoordinatjembatan->result() as $koordinat) {
                                        if ($koordinat->jembatan_id==$jembatan->id_jembatan) {
                                            echo $koordinat->longitude."</br>";
                                        }
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    echo '<button class="btn-info btn btn-sm" id="viewmarkerjembatan" data-iddatajembatan="'.$jembatan->id_jembatan.'"><span class="glyphicon-globe glyphicon"></span> View marker</button> ';
                                    echo '<button class="btn-danger btn btn-sm" id="hapusmarkerjembatan" data-iddatajembatan="'.$jembatan->id_jembatan.'"><span class="glyphicon-remove glyphicon"></span></button>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                             ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>