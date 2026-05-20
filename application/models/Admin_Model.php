<?php 
Class Admin_Model extends CI_Model {
	public function login($username, $password) {
		$this->db->select('username, password');
		$this->db->from('tb_admin');
		$this->db->where('username', $username);
		$login = $this->db->get();
		
		if($login->num_rows() > 0) {
			$row = $login->row_array();
			if (password_verify($password, $row['password'])) {
				return true;
			}
		}
		return false;
	}
	public function cekelas() {
		$cek = $this->db->query("SELECT * FROM tb_kelas");
		if($cek->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	public function regvalid() {
		$load = $this->db->get('tb_identitassekolah');
		if($load->num_rows() > 0) {
			return $load->result_array();
		}
		else {
			return array();
		}
	}
	public function regsekolah($npsn,$nm_sekolah) {
		$data				= array(
			'npsn'			=> $npsn,
			'nm_sekolah'	=> $nm_sekolah
		);
		return $this->db->insert('tb_identitassekolah', $data);
	}
	public function dataadmin() {
		$load = $this->db->query('SELECT * FROM tb_admin');
		return $load->result_array();
	}
	public function gantipassword($username, $password_hash) {
		$this->db->where('username', $username);
		return $this->db->update('tb_admin', array('password' => $password_hash));
	}
	public function datapilketos() {
		return $this->db->get_where('tb_datapilketos', array('id' => 1))->result_array();
	}
	public function updatedatapilketos($tapel, $tgl){
		$this->db->where('id', 1);
		return $this->db->update('tb_datapilketos', array(
			'tapel' => $tapel,
			'tgl'   => $tgl
		));
	}
	public function resetuser($username) {
		return $this->db->delete('tb_pilih', array('username' => $username));
	}
	public function updateuser($username) {
		$this->db->where('username', $username);
		return $this->db->update('tb_siswa', array('hadir' => 'Tidak Hadir'));
	}
	public function resetdata(){
		$this->db->query("DELETE FROM tb_pilih");
		$this->db->query("DELETE FROM tb_siswa");
		$this->db->query("DELETE FROM tb_pilihan");
		$this->db->query("UPDATE tb_datapilketos SET tapel='', tgl='' WHERE id='1'");
		return true;
	}
	public function idsekolah() {
		$load = $this->db->query("SELECT * FROM tb_identitassekolah");
		return $load->result_array();
	}
	public function updateidsekolah($npsn, $nm_sekolah, $jln, $desa, $kec, $kab, $kpl_sekolah, $nip){
		return $this->db->update('tb_identitassekolah', array(
			'npsn'			=> $npsn,
			'nm_sekolah'	=> $nm_sekolah,
			'jln'			=> $jln,
			'desa'			=> $desa,
			'kec'			=> $kec,
			'kab'			=> $kab,
			'kpl_sekolah'	=> $kpl_sekolah,
			'nip'			=> $nip
		));
	}
	public function datakelas() {
		$load = $this->db->query("SELECT * FROM tb_kelas");
		return $load->result_array();
	}
	public function simpankelas($nm_kelas) {
		$data = array(
			'nm_kelas'	=> $nm_kelas
		);
		return $this->db->insert('tb_kelas', $data);
	}

	public function delete_all_votes() {
		return $this->db->truncate('tb_pilih');
	}

	public function hapussemuadpt() {
		return $this->db->truncate('tb_siswa');}


		public function tambahcalon($nisn, $no , $nama, $photo, $opsi_mpkosis) {
			$data = array(
				'nisn'          => $nisn,
				'no'            => $no,
				'nama'          => $nama,
				'photo'         => $photo,
        'opsi_mpkosis'  => $opsi_mpkosis
    );
			return $this->db->insert('tb_pilihan', $data);
		}

		public function hapuskelas($kd_kelas) {
			return $this->db->delete('tb_kelas', array('kd_kelas' => $kd_kelas));
		}
		
		public function hapussemuakelas() {
			return $this->db->truncate('tb_kelas');}

	public function updatecalon($nisn, $no, $nama, $opsi_mpkosis, $photo) {
		$data = array(
			'no'           => $no,
			'nama'         => $nama,
			'opsi_mpkosis' => $opsi_mpkosis
		);

		// Only update photo if a new one was uploaded
		if (!empty($photo)) {
			$data['photo'] = $photo;
		}

		$this->db->where('nisn', $nisn);
		return $this->db->update('tb_pilihan', $data);
	}


	public function hapuscalon($nisn) {
		return $this->db->delete('tb_pilihan', array('nisn' => $nisn));
	}
	public function datacalon() {
		$load	= $this->db->query("SELECT * FROM tb_pilihan ORDER BY no asc");
		return $load->result_array();
	}
	public function datadpt() {
		$load = $this->db->query("SELECT * FROM tb_siswa INNER JOIN tb_kelas ON tb_kelas.kd_kelas = tb_siswa.kd_kelas ORDER BY tb_siswa.kd_kelas asc");
		return $load->result_array();
	}
	public function datakddpt($username) {
		$this->db->select('tb_siswa.*, tb_kelas.nm_kelas');
		$this->db->from('tb_siswa');
		$this->db->join('tb_kelas', 'tb_kelas.kd_kelas = tb_siswa.kd_kelas');
		$this->db->where('tb_siswa.username', $username);
		return $this->db->get()->result_array();
	}
	public function simpandpt($username, $password, $nm_siswa, $jk,$kd_kelas) {
		$data 			= array(
			'username'	=> $username,
			'password'	=> password_hash($password, PASSWORD_DEFAULT),
			'nm_siswa'	=> $nm_siswa,
			'jk'		=> $jk,
			'kd_kelas'	=> $kd_kelas
		);
		return $this->db->insert('tb_siswa', $data);
	}
	public function simpanmassaldpt($nisn, $nama, $jk, $kelas) {
		$data			= array(
			'username'	=> $nisn,
			'password'	=> password_hash($nisn, PASSWORD_DEFAULT),
			'nm_siswa'	=> $nama,
			'jk'		=> $jk,
			'kd_kelas'	=> $kelas
		);
		return $this->db->insert('tb_siswa', $data);
	}
	public function hapusdpt($username) {
		return $this->db->delete('tb_siswa', array('username' => $username));
	}
	public function updatedpt($username, $nm_siswa, $jk,$kd_kelas) {
		$this->db->where('username', $username);
		return $this->db->update('tb_siswa', array(
			'nm_siswa'	=> $nm_siswa,
			'jk'		=> $jk,
			'kd_kelas'	=> $kd_kelas
		));
	}
	public function datacalonspesifik($nisn) {
		return $this->db->get_where('tb_pilihan', array('nisn' => $nisn))->result_array();
	}
	public function countcalon() {
		return $this->db->query("SELECT COUNT(*) AS jumlah FROM tb_pilihan")->row_array();
	}

	public function countpemilih() {
		return $this->db->query("SELECT COUNT(*) AS jumlah FROM tb_siswa")->row_array();
	}


	public function countvote() {
		return $this->db->query("SELECT COUNT(*) AS jumlah FROM tb_siswa WHERE hadir = 'Hadir'")->row_array();
	}

	public function hasilvote() {
		return $this->db
		->select('p.no, p.nama, p.photo, p.opsi_mpkosis, COUNT(v.id_pilih) AS jumlah')
		->from('tb_pilihan p')
		->join('tb_pilih v', 'p.nisn = v.calon_nisn', 'left')
		->group_by('p.nisn')
		->order_by('p.opsi_mpkosis ASC, jumlah DESC')
		->get()
		->result_array();
	}


public function jmldptL() {
    return $this->db->query("SELECT COUNT(*) AS L FROM tb_siswa WHERE jk = 'L'")->row_array();
}

public function jmldptP() {
    return $this->db->query("SELECT COUNT(*) AS P FROM tb_siswa WHERE jk = 'P'")->row_array();
}

public function jmlvoteL() {
    return $this->db->query("SELECT COUNT(*) AS L FROM tb_siswa WHERE jk = 'L' AND hadir = 'Hadir'")->row_array();
}

public function jmlvoteP() {
    return $this->db->query("SELECT COUNT(*) AS P FROM tb_siswa WHERE jk = 'P' AND hadir = 'Hadir'")->row_array();
}


	
	public function kuncivote() {
		$data	= $this->db->query("SELECT * FROM tb_pilih");
		return $data->result_array();
	}
	public function daftarhadir() {
		$data	= $this->db->query("SELECT * FROM tb_siswa INNER JOIN tb_kelas ON tb_kelas.kd_kelas = tb_siswa.kd_kelas ORDER BY tb_kelas.kd_kelas ASC");
		return $data->result_array();
	}
}
?>
