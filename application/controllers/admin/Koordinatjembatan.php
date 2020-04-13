<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class KoordinatJembatan extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        //kita load model yang dibutuhkan, yaitu model jembatan
        $this->load->model(array('Model_jembatan','Model_koordinatjembatan'));
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
        $data = array('content' => 'admin/koordinatjembatanform',
        'itemdatajembatan'=>$this->Model_jembatan->getAll(),
        'itemkoordinatjembatan'=>$this->Model_koordinatjembatan->getAll());//kita buat file formjembatan di dalam folder views/admin
        $this->load->view('templates/template-admin', $data);
    }

//crud koordinat jembatan
public function koordinatjembatan(){
    $data = array('content' => 'admin/koordinatjembatanform',
        'itemdatajembatan'=>$this->Model_jembatan->getAll(),
        'itemkoordinatjembatan'=>$this->Model_koordinatjembatan->getAll());
    $this->load->view('templates/template', $data, FALSE);
}
function simpandaftarkoordinatjembatan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_jembatan', 'Data jembatan', 'trim|required');
        if ($this->form_validation->run()==false) {
            $status = 'error';
            $msg = validation_errors();
        }else{
            if ($this->model_koordinatjembatan->getbyidjembatan($this->input->post('id_jembatan'))->num_rows()!=null) {
                $status = 'error';
                $msg = 'marker jembatan yang bersangkutan sudah ada, hapus terlebih dahulu';
            }else{
                if ($this->model_koordinatjembatan->create()) {
                    $status = 'success';
                    $msg = 'data berhasil disimpan';
                }else{
                    $status = 'error';
                    $msg = 'terjadi kesalahan saat menyimpan koordinat';
                }
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function hapusmarkerjembatan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        if ($this->model_koordinatjembatan->deletebyidjembatan($this->input->post('id_jembatan'))) {
            $status = 'success';
            $msg = 'data berhasil dihapus';
        }else{
            $status = 'error';
            $msg = 'terjadi kesalahan saat menghapus data';
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
    }
}
function viewmarkerjembatan(){
    if (!$this->input->is_ajax_request()) {
        show_404();
    }else{
        if ($this->model_koordinatjembatan->getbyidjembatan($this->input->post('id_jembatan'))->num_rows()!=null){
            $status = 'success';
            $msg = $this->model_koordinatjembatan->getbyidjembatan($this->input->post('id_jembatan'))->result();
            $datajembatan = $this->model_jembatan->read($this->input->post('id_jembatan'))->result();
        }else{
            $status = 'error';
            $msg = 'data tidak ditemukan';
            $datajembatan = null;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg,'datajembatan'=>$datajembatan)));
    }
}
//end crud koordinat jembatan

}
