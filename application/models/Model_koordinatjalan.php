<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_koordinatjalan extends CI_Model {

    public function create(){
        foreach ($this->cart->contents() as $koordinat) {
            $data = array('jalan_id' => $this->input->post('jalan_id'),
                'latitude'=>$koordinat['latitude'],
                'longitude'=>$koordinat['longitude']);
            $query = $this->db->insert('tbl_koordinatjalan', $data);
        }
        return $query;
    }
    public function getAll(){
        $query = $this->db->get('tbl_koordinatjalan');//mengambil semua data koordinat jalan
        return $query;
    }
    public function getbyidjalan($id){
        $this->db->where('jalan_id', $id);
        $query = $this->db->get('tbl_koordinatjalan');//mengambil semua data koordinat jalan
        return $query;
    }
    public function read($id){
        $this->db->where('id_koordinatjalan', $id);//mengambil data koordinat jalan berdasarkan id_koordinatjalan
        $query = $this->db->get('tbl_koordinatjalan');
        return $query;
    }
    public function update(){
        $data = array('jalan_id'=>$this->input->post('jalan_id'),
            'latitude'=>$this->input->post('latitude'),
            'longitude'=>$this->input->post('longitude'));
        $this->db->where('id_koordinatjalan', $this->input->post('id_koordinatjalan'));//mengupdate berdasarkan id_koordinatjalan
        $query = $this->db->update('tbl_koordinatjalan', $data);
        return $query;
    }
    public function delete(){
        $this->db->where('id_koordinatjalan', $this->input->post('id_koordinatjalan'));//menghapus berdasarkan id_koordinatjalan
        $query = $this->db->delete('tbl_koordinatjalan');
        return $query;
    }
    public function deletebyidjalan($id){
        $this->db->where('jalan_id', $id);//menghapus berdasarkan id_koordinatjalan
        $query = $this->db->delete('tbl_koordinatjalan');
        return $query;
    }

    public function getData()
     {
          $this->db->select('tbl_jalan.namajalan,tbl_jalan.keterangan,tbl_koordinatjalan.latitude,tbl_koordinatjalan.longitude');
          $this->db->join('tbl_koordinatjalan', 'tbl_jalan.id_jalan = tbl_koordinatjalan.jalan_id');
          $this->db->from('tbl_jalan');

          return $this->db->get();
          
     }

}

/* End of file model_koordinatjalan.php */
/* Location: ./application/models/model_koordinatjalan.php */