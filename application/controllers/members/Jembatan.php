<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jembatan extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        //kita load model yang dibutuhkan, yaitu model jembatan
        $this->load->model(array('model_jembatan'));
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
        $data = array('content' => 'members/formjembatan',//kita buat file formjembatan di dalam folder views/admin
        'itemjembatan'=>$this->model_jembatan->getAll());
        $this->load->view('templates/template-members', $data);
    }

    function create(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            //kita validasi inputnya dulu
            $this->form_validation->set_rules('namajembatan', 'Nama Jembatan', 'trim|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                if ($this->model_jembatan->create()) {
                    $status = 'success';
                    $msg = "Data jembatan berhasil disimpan";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat menyimpan data jembatan";
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
            $this->form_validation->set_rules('id_jembatan', 'ID Jembatan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jembatan');
                if ($this->model_jembatan->read($id)->num_rows()!=null) {
                    $status = 'success';
                    $msg = $this->model_jembatan->read($id)->result();
                }else{
                    $status = 'error';
                    $msg = "Data jembatan tidak ditemukan";
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
            $this->form_validation->set_rules('namajembatan', 'Nama Jembatan', 'trim|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
            $this->form_validation->set_rules('id_jembatan', 'ID Jembatan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jembatan');
                if ($this->model_jembatan->update($id)) {
                    $status = 'success';
                    $msg = "Data jembatan berhasil diupdate";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat mengupdate data jembatan";
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
            $this->form_validation->set_rules('id_jembatan', 'ID Jembatan', 'trim|required');
            if ($this->form_validation->run()==false) {
                $status = 'error';
                $msg = validation_errors();
            }else{
                $id = $this->input->post('id_jembatan');
                if ($this->model_jembatan->delete($id)) {
                    $status = 'success';
                    $msg = "Data jembatan berhasil dihapus";
                }else{
                    $status = 'error';
                    $msg = "terjadi kesalahan saat menghapus data jembatan";
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }
}