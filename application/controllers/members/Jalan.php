<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jalan extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        //kita load model yang dibutuhkan, yaitu model jalan
        $this->load->model(array('model_jalan'));
        $this->load->helper('url');
        $this->load->library(array('form_validation','ion_auth'));
        if (!$this->ion_auth->logged_in()) {//cek login ga?
    		redirect('login','refresh');
    	}else{
            if (!$this->ion_auth->in_group('members')) {//cek admin ga?
                redirect('login','refresh');
            }
        }
    }

    function index()
    {
        $data = array('content' => 'members/formjalan',//kita buat file formjalan di dalam folder views/admin
        'itemjalan'=>$this->model_jalan->getAll());
        $this->load->view('templates/template-members', $data);
    }

    function create(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            //kita validasi inputnya dulu
            $this->form_validation->set_rules('namajalan', 'Nama Jalan', 'trim|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                if ($this->model_jalan->create()) {
                    $status = 'success';
                    $msg = "Data jalan berhasil disimpan";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat menyimpan data jalan";
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }


    function edit(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            //kita validasi inputnya dulu
            $this->form_validation->set_rules('id_jalan', 'ID Jalan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jalan');
                if ($this->model_jalan->read($id)->num_rows()!=null) {
                    $status = 'success';
                    $msg = $this->model_jalan->read($id)->result();
                }else{
                    $status = 'error';
                    $msg = "Data jalan tidak ditemukan";
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }

    function update(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            //kita validasi inputnya dulu
            $this->form_validation->set_rules('namajalan', 'Nama Jalan', 'trim|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
            $this->form_validation->set_rules('id_jalan', 'ID Jalan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jalan');
                if ($this->model_jalan->update($id)) {
                    $status = 'success';
                    $msg = "Data jalan berhasil diupdate";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat mengupdate data jalan";
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }

    function delete(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            //kita validasi inputnya dulu
            $this->form_validation->set_rules('id_jalan', 'ID Jalan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jalan');
                if ($this->model_jalan->delete($id)) {
                    $status = 'success';
                    $msg = "Data jalan berhasil dihapus";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat menghapus data jalan";
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }

}
?>