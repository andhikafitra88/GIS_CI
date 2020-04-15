        <div class="col-md-8 col-sm-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-list"></span> Daftar Jalan</h3>
              </div>
              <div class="panel-body">
                  <table class="table table-bordered">
                      <th>No</th>
                      <th>Nama Jalan</th>
                      <th>Keterangan</th>
                      <tbody id="daftarjalan">
                          <?php
                          $no = 1;
                          foreach ($itemjalan->result() as $jalan) {
                              ?>
                              <tr>
                                  <td><?php echo $no;?></td>
                                  <td><?php echo $jalan->namajalan;?></td>
                                  <td><?php echo $jalan->keterangan;?></td>
                            
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
    </div>
</div>

<script>
    $(document).on('click','#simpanjalan',simpanjalan)
    .on('click','#resetjalan',resetjalan)
    .on('click','#updatejalan',updatejalan)
    .on('click','#editjalan',editjalan)
    .on('click','#deletejalan',deletejalan);
    function simpanjalan() {//simpan jalan
        var datajalan = {'namajalan':$('#namajalan').val(),
        'keterangan':$('#keterangan').val()};console.log(datajalan);
        $.ajax({
            url : '<?php echo site_url("admin/jalan/create");?>',
            data : datajalan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('#daftarjalan').load('<?php echo current_url()." #daftarjalan > *";?>');
                    resetjalan();//form langsung dikosongkan pas selesai input data
                }else{
                    alert(data.msg);
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
            }
        })
    }

    function editjalan() {//edit jalan
        var id = $(this).data('idjalan');
        var datajalan = {'id_jalan':id};console.log(datajalan);
        $('input[name=editjalan'+id+']').attr('disabled',true);//biar ga di klik dua kali, maka di disabled
        $.ajax({
            url : '<?php echo site_url("admin/jalan/edit");?>',
            data : datajalan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('input[name=editjalan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
                    $('#simpanjalan').attr('disabled',true);
                    $('#updatejalan').attr('disabled',false);
                    $.each(data.msg,function(k,v){
                        $('#id_jalan').val(v['id_jalan']);
                        $('#namajalan').val(v['namajalan']);
                        $('#keterangan').val(v['keterangan']);
                    })
                }else{
                    alert(data.msg);
                    $('input[name=editjalan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
                $('input[name=editjalan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
            }
        })
    }

    function updatejalan() {//update jalan
        var datajalan = {'namajalan':$('#namajalan').val(),
        'keterangan':$('#keterangan').val(),
        'id_jalan':$('#id_jalan').val()};console.log(datajalan);
        $.ajax({
            url : '<?php echo site_url("admin/jalan/update");?>',
            data : datajalan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('#daftarjalan').load('<?php echo current_url()." #daftarjalan > *";?>');
                    resetjalan();//form langsung dikosongkan pas selesai input data
                }else{
                    alert(data.msg);
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
            }
        })
    }

    function resetjalan() {//reset form jalan
        $('#namajalan').val('');
        $('#keterangan').val('');
        $('#id_jalan').val('');
        $('#simpanjalan').attr('disabled',false);
        $('#updatejalan').attr('disabled',true);
    }
    function deletejalan() {//delete jalan
        if (confirm("Anda yakin akan menghapus data jalan ini?")) {
            var id = $(this).data('idjalan');
            var datajalan = {'id_jalan':id};console.log(datajalan);
            $.ajax({
                url : '<?php echo site_url("admin/jalan/delete");?>',
                data : datajalan,
                dataType : 'json',
                type : 'POST',
                success : function(data,status){
                    if (data.status!='error') {
                        $('#daftarjalan').load('<?php echo current_url()." #daftarjalan > *";?>');
                        resetjalan();//form langsung dikosongkan pas selesai input data
                    }else{
                        alert(data.msg);
                    }
                },
                error : function(x,t,m){
                    alert(x.responseText);
                }
            })
        }
    }
</script>