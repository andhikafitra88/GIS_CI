
        <div class="col-md-8 col-sm-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-list"></span> Daftar Jembatan</h3>
              </div>
              <div class="panel-body">
                  <table class="table table-bordered">
                      <th>No</th>
                      <th>Nama Jembatan</th>
                      <th>Keterangan</th>
                      <tbody id="daftarjembatan">
                          <?php
                          $no = 1;
                          foreach ($itemjembatan->result() as $jembatan) {
                              ?>
                              <tr>
                                  <td><?php echo $no;?></td>
                                  <td><?php echo $jembatan->namajembatan;?></td>
                                  <td><?php echo $jembatan->keterangan;?></td>
                                  
                                  </td>
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
    $(document).on('click','#simpanjembatan',simpanjembatan)
    .on('click','#resetjembatan',resetjembatan)
    .on('click','#updatejembatan',updatejembatan)
    .on('click','#editjembatan',editjembatan)
    .on('click','#deletejembatan',deletejembatan);
    function simpanjembatan() {//simpan jembatan
        var datajembatan = {'namajembatan':$('#namajembatan').val(),
        'keterangan':$('#keterangan').val()};console.log(datajembatan);
        $.ajax({
            url : '<?php echo site_url("admin/jembatan/create");?>',
            data : datajembatan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('#daftarjembatan').load('<?php echo current_url()." #daftarjembatan > *";?>');
                    resetjembatan();//form langsung dikosongkan pas selesai input data
                }else{
                    alert(data.msg);
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
            }
        })
    }
    function resetjembatan() {//reset form jembatan
        $('#namajembatan').val('');
        $('#keterangan').val('');
        $('#id_jembatan').val('');
        $('#simpanjembatan').attr('disabled',false);
        $('#updatejembatan').attr('disabled',true);
    }
    function updatejembatan() {//update jembatan
        var datajembatan = {'namajembatan':$('#namajembatan').val(),
        'keterangan':$('#keterangan').val(),
        'id_jembatan':$('#id_jembatan').val()};console.log(datajembatan);
        $.ajax({
            url : '<?php echo site_url("admin/jembatan/update");?>',
            data : datajembatan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('#daftarjembatan').load('<?php echo current_url()." #daftarjembatan > *";?>');
                    resetjembatan();//form langsung dikosongkan pas selesai input data
                }else{
                    alert(data.msg);
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
            }
        })
    }
    function editjembatan() {//edit jembatan
        var id = $(this).data('idjembatan');
        var datajembatan = {'id_jembatan':id};console.log(datajembatan);
        $('input[name=editjembatan'+id+']').attr('disabled',true);//biar ga di klik dua kali, maka di disabled
        $.ajax({
            url : '<?php echo site_url("admin/jembatan/edit");?>',
            data : datajembatan,
            dataType : 'json',
            type : 'POST',
            success : function(data,status){
                if (data.status!='error') {
                    $('input[name=editjembatan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
                    $('#simpanjembatan').attr('disabled',true);
                    $('#updatejembatan').attr('disabled',false);
                    $.each(data.msg,function(k,v){
                        $('#id_jembatan').val(v['id_jembatan']);
                        $('#namajembatan').val(v['namajembatan']);
                        $('#keterangan').val(v['keterangan']);
                    })
                }else{
                    alert(data.msg);
                    $('input[name=editjembatan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
                }
            },
            error : function(x,t,m){
                alert(x.responseText);
                $('input[name=editjembatan'+id+']').attr('disabled',false);//disabled di set false, karena transaksi berhasil
            }
        })
    }
    function deletejembatan() {//delete jembatan
        if (confirm("Anda yakin akan menghapus data jembatan ini?")) {
            var id = $(this).data('idjembatan');
            var datajembatan = {'id_jembatan':id};console.log(datajembatan);
            $.ajax({
                url : '<?php echo site_url("admin/jembatan/delete");?>',
                data : datajembatan,
                dataType : 'json',
                type : 'POST',
                success : function(data,status){
                    if (data.status!='error') {
                        $('#daftarjembatan').load('<?php echo current_url()." #daftarjembatan > *";?>');
                        resetjembatan();//form langsung dikosongkan pas selesai input data
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