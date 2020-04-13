<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Koordinatjalan extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        //kita load model yang dibutuhkan, yaitu model jembatan
        $this->load->model(array('Model_jalan'));
        $this->load->model(array('Model_koordinatjalan'));
        $this->load->helper('url');
        $this->load->library(array('form_validation','ion_auth','cart'));
        if (!$this->ion_auth->logged_in()) {//cek login ga?
    		redirect('login','refresh');
    	}else{
            if (!$this->ion_auth->in_group('admin')) {//cek admin ga?
                redirect('login','refresh');
            }
        }
    }
    function index()
    {
        $data = array('content' => 'admin/koordinatjalanform','itemdatajalan'=>$this->Model_jalan->getAll(),'itemkoordinatjalan'=>$this->Model_koordinatjalan->getAll());//kita buat file formjembatan di dalam folder views/admin
        $this->load->view('templates/template-admin', $data);
    }

//crud koordinat jalan ()
public function koordinatjalan(){
    $data = array('content' => 'admin/koordinatjalanform',
        'itemdatajalan'=>$this->Model_jalan->getAll(),
        'itemkoordinatjalan'=>$this->Model_koordinatjalan->getAll());
    $this->load->view('templates/template-admin', $data, FALSE);
}
function tambahkoordinatjalan()
{
    if(!$this->input->is_ajax_request())
    {
        show_404();
    }else
    {
        if($this->cart->contents()==null){
            $data = array(
            'id'      => 1,
            'qty'     => 1,
            'price'   => 1,
            'jenis'      => 'jalan',
            'name'    => 1,
            'latitude'=> $this->input->post('latitude'),
            'longitude'=> $this->input->post('longitude')
            );

            $this->cart->insert($data);
            $status = "success";
            $msg = "<div class='alert alert-success'>Data berhasil disimpan</div>";
        }else{
            $urut = 0;
            foreach ($this->cart->contents() as $jalan) {
                $urut +=1;
            }
            $data = array(
                    'id'      => $urut + 1,
                    'qty'     => 1,
                    'price'   => 1,
                    'jenis'      => 'jalan',
                    'name'    => $urut + 1,
                    'latitude'=> $this->input->post('latitude'),
                    'longitude'=> $this->input->post('longitude')
                );

            $this->cart->insert($data);
            $status = "success";
            $msg = "<div class='alert alert-success'>Data berhasil disimpan</div>";
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function hapusdaftarkoordinatjalan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        $hapus = $this->cart->destroy();
        $status = 'success';
        $msg = 'data koordinat berhasil dihapus';

        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function simpandaftarkoordinatjalan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_jalan', 'Data Jalan', 'trim|required');
        if ($this->form_validation->run()==false) {
            $status = 'error';
            $msg = validation_errors();
        }else{
            if ($this->model_koordinatjalan->getbyidjalan($this->input->post('id_jalan'))->num_rows()!=null) {
                $status = 'error';
                $msg = 'polyline jalan yang bersangkutan sudah ada, hapus terlebih dahulu';
            }else{
                if ($this->model_koordinatjalan->create()) {
                    $status = 'success';
                    $msg = 'data berhasil disimpan';
                    $this->cart->destroy();
                }else{
                    $status = 'error';
                    $msg = 'terjadi kesalahan saat menyimpan koordinat';
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function hapuspolylinejalan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        if ($this->model_koordinatjalan->deletebyidjalan($this->input->post('id_jalan'))) {
            $status = 'success';
            $msg = 'data berhasil dihapus';
        }else{
            $status = 'error';
            $msg = 'terjadi kesalahan saat menghapus data';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function viewpolylinejalan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        if ($this->model_koordinatjalan->getbyidjalan($this->input->post('id_jalan'))->num_rows()!=null){
            $status = 'success';
            $msg = $this->model_koordinatjalan->getbyidjalan($this->input->post('id_jalan'))->result();
            $datajalan = $this->model_jalan->read($this->input->post('id_jalan'))->result();
        }else{
            $status = 'error';
            $msg = 'data tidak ditemukan';
            $datajalan = null;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg,'datajalan'=>$datajalan)));
    }
}
//end crud koordinat jalan


}


