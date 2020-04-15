<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/phpoffice/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $this->form_validation->set_rules('jembatan_id', 'Data jembatan', 'trim|required');
        if ($this->form_validation->run()==false) {
            $status = 'error';
            $msg = validation_errors();
        }else{
            if ($this->Model_koordinatjembatan->getbyidjembatan($this->input->post('jembatan_id'))->num_rows()!=null) {
                $status = 'error';
                $msg = 'marker jembatan yang bersangkutan sudah ada, hapus terlebih dahulu';
            }else{
                if ($this->Model_koordinatjembatan->create()) {
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
        if ($this->Model_koordinatjembatan->deletebyidjembatan($this->input->post('jembatan_id'))) {
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
        if ($this->Model_koordinatjembatan->getbyidjembatan($this->input->post('jembatan_id'))->num_rows()!=null){
            $status = 'success';
            $msg = $this->Model_koordinatjembatan->getbyidjembatan($this->input->post('jembatan_id'))->result();
            $datajembatan = $this->Model_jembatan->read($this->input->post('id_jembatan'))->result();
        }else{
            $status = 'error';
            $msg = 'data tidak ditemukan';
            $datajembatan = null;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status'=>$status,'msg'=>$msg,'datajembatan'=>$datajembatan)));
    }
}
//end crud koordinat jembatan

public function export()
     {
          $semua_jembatan = $this->Model_koordinatjembatan->getData()->result();

          $spreadsheet = new Spreadsheet;

          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'No')
                      ->setCellValue('B1', 'Nama Jembatan')
                      ->setCellValue('C1', 'Keterangan')
                      ->setCellValue('D1', 'Latitude')
                      ->setCellValue('E1', 'Longitude');

          $kolom = 2;
          $nomor = 1;
          foreach($semua_jembatan as $jembatan) {

               $spreadsheet->setActiveSheetIndex(0)
                           ->setCellValue('A' . $kolom, $nomor)
                           ->setCellValue('B' . $kolom, $jembatan->namajembatan)
                           ->setCellValue('C' . $kolom, $jembatan->keterangan)
                           ->setCellValue('D' . $kolom, $jembatan->latitude)
                           ->setCellValue('E' . $kolom, $jembatan->longitude);

               $kolom++;
               $nomor++;

          }

          $writer = new Xlsx($spreadsheet);

          header('Content-Type: application/vnd.ms-excel');
	  header('Content-Disposition: attachment;filename="Data Jembatan.xlsx"');
	  header('Cache-Control: max-age=0');

	  $writer->save('php://output');
     }


}
