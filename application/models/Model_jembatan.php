<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_jembatan extends CI_Model{

    public function create(){
        $data = array('namajembatan' => $this->input->post('namajembatan'),
        'keterangan'=>$this->input->post('keterangan'));
        $query = $this->db->insert('tbl_jembatan', $data);
        return $query;
    }
    public function getAll(){
        $query = $this->db->get('tbl_jembatan');
        return $query;
    }
    public function read($id){
        $this->db->where('id_jembatan', $id);
        $query = $this->db->get('tbl_jembatan');
        return $query;
    }
    public function delete($id){
        $this->db->where('id_jembatan', $id);
        $query = $this->db->delete('tbl_jembatan');
        return $query;
    }
    public function update($id){
        $data = array('namajembatan' => $this->input->post('namajembatan'),
        'keterangan'=>$this->input->post('keterangan'));
        $this->db->where('id_jembatan', $id);
        $query = $this->db->update('tbl_jembatan', $data);
        return $query;
    }

}