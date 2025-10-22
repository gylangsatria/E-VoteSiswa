<?php
class User_Model extends CI_Model {

	public function login($username, $password) {
		$this->db->select('username, password');
		$this->db->from('tb_siswa');
		$this->db->where(['username' => $username, 'password' => $password]);
		$login = $this->db->get();

		return $login->num_rows() > 0 ? ['username' => $username, 'nisn' => $username] : false;
	}

	public function valid($username) {
		return $this->db->get_where('view_vote', ['username' => $username])->num_rows() > 0;
	}

	public function datamodel() {
		return $this->db->order_by('no', 'ASC')->get('tb_pilihan')->result_array();
	}

	public function vote($username, $nisn_pemilih, $calon_nisn, $opsi_mpkosis) {
		$data = [
			'username'     => $username,
			'nisn'         => $nisn_pemilih,
			'calon_nisn'   => $calon_nisn,
			'opsi_mpkosis' => $opsi_mpkosis,
			'waktu_vote'   => date('Y-m-d H:i:s')
		];
		return $this->db->insert('tb_pilih', $data);
	}

	public function hadir($username) {
		return $this->db->query("UPDATE tb_siswa SET hadir='Hadir' WHERE username='$username'");
	}

	public function sudah_vote($username, $opsi_mpkosis) {
		return $this->db->get_where('tb_pilih', [
			'username' => $username,
			'opsi_mpkosis' => $opsi_mpkosis
		])->num_rows() > 0;
	}
}
?>

