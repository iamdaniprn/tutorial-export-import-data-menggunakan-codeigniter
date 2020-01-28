<?php
class M_siswa extends CI_Model{
 
    public function data_siswa()
    {
        $this->db->select('*');
   		$this->db->from('tb_siswa');
        $this->db->order_by('id_siswa', 'DESC');
        $hasil = $this->db->get();

        return $hasil->result();
    }

    public function total_data_siswa()
    {
        $this->db->select('*');
        $this->db->from('tb_siswa');
        $this->db->order_by('id_siswa', 'DESC');
        $hasil = $this->db->get();

        return $hasil;
    }

    public function simpan($data)
    {
        return $this->db->insert('tb_siswa', $data);
    }

    public function lihat($id_siswa)
    {
        return $this->db->select('*')->where('id_siswa', $id_siswa)->get('tb_siswa')->result();
    }

    public function edit($id_siswa)
    {
        return $this->db->select('*')->where('id_siswa', $id_siswa)->get('tb_siswa')->result();
    }

    public function update($data, $id_siswa)
    {       
        return $this->db->where('id_siswa', $id_siswa)->update('tb_siswa', $data);
    }

    public function hapus($id_siswa)
    {
        return $this->db->where('id_siswa', $id_siswa)->delete('tb_siswa');
    }
}
?>