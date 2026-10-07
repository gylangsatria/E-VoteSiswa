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

	public function voting_schedule() {
		return $this->db->get_where('tb_datapilketos', ['id' => 1])->row_array();
	}

	public function is_voting_open() {
		$row = $this->voting_schedule();
		if (empty($row['aktif']) || empty($row['tgl'])) {
			return true;
		}
		$mulai   = $row['tgl'] . ' ' . (!empty($row['jam_mulai']) ? $row['jam_mulai'] : '00:00:00');
		$selesai = $row['tgl'] . ' ' . (!empty($row['jam_selesai']) ? $row['jam_selesai'] : '23:59:59');
		$now     = date('Y-m-d H:i:s');
		return ($now >= $mulai && $now <= $selesai);
	}

	public function vote($username, $calon_nisn) {
		$calon = $this->calon_select($calon_nisn);
		if ($calon === NULL) {
			return false;
		}
		$data = [
			'username'     => $username,
			'nisn'         => $username,
			'calon_nisn'   => $calon_nisn,
			'opsi_mpkosis' => $calon['opsi_mpkosis'],
			'waktu_vote'   => date('Y-m-d H:i:s')
		];
		return $this->db->insert('tb_pilih', $data);
	}

	public function calon_select($nisn) {
		$row = $this->db->select('nisn, opsi_mpkosis')->get_where('tb_pilihan', ['nisn' => $nisn])->row_array();
		return $row ?: NULL;
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

	public function login_attempts_count($username) {
		$row = $this->db->get_where('tb_login_attempts', array('username' => substr($username, 0, 32)))->row_array();
		return $row ? (int) $row['attempts'] : 0;
	}

	public function login_locked($username) {
		return (bool) $this->db->query(
			'SELECT 1 FROM tb_login_attempts WHERE username = ? AND attempts >= 5 AND updated_at >= NOW() - INTERVAL 5 MINUTE',
			array(substr($username, 0, 32))
		)->row_array();
	}

	public function record_login_attempt($username) {
		$username = substr($username, 0, 32);
		$this->db->query(
			'INSERT INTO tb_login_attempts (username, attempts, updated_at) VALUES (?, 1, NOW())
			 ON DUPLICATE KEY UPDATE attempts = IF(updated_at < NOW() - INTERVAL 5 MINUTE, 1, attempts + 1), updated_at = NOW()',
			array($username)
		);
		$this->db->query('DELETE FROM tb_login_attempts WHERE updated_at < NOW() - INTERVAL 1 DAY');
		return $this->login_attempts_count($username);
	}

	public function reset_login_attempts($username) {
		return $this->db->delete('tb_login_attempts', array('username' => substr($username, 0, 32)));
	}
}

