<?php
class User_Model extends CI_Model {

	public function login($username, $password) {
		$this->db->select('username, password');
		$this->db->from('tb_siswa');
		$this->db->where('username', $username);
		$login = $this->db->get();

		if ($login->num_rows() > 0) {
			$row = $login->row_array();
			if (password_verify($password, $row['password'])) {
				return ['username' => $username, 'nisn' => $username];
			}
		}
		return false;
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
		$this->db->where('username', $username);
		return $this->db->update('tb_siswa', array('hadir' => 'Hadir'));
	}

	public function sudah_vote($username, $opsi_mpkosis) {
		return $this->db->get_where('tb_pilih', [
			'username' => $username,
			'opsi_mpkosis' => $opsi_mpkosis
		])->num_rows() > 0;
	}
}
?>

