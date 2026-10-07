<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper(['form', 'url']);
		$this->load->library(['session']);
		$this->load->model('User_Model');
	}

	public function login() {
		if ($this->session->userdata('nisn')) {
			redirect('user/index');
		}

		$this->load->view('user/head');
		$this->load->view('user/login');
		$this->load->view('user/footer');
	}

	public function loginvalidation() {
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);

		if (! is_string($username) || ! is_string($password)) {
			$this->session->set_flashdata('user_failed', 'Username atau Password salah');
			redirect('user/login');
			return;
		}

		if ($this->User_Model->login_attempts($username) >= 5) {
			$this->session->set_flashdata('user_failed', 'Terlalu banyak percobaan login untuk akun ini. Silakan hubungi panitia.');
			redirect('user/login');
			return;
		}

		$result = $this->User_Model->login($username, $password);
        $valid  = $this->User_Model->valid($username);

        if ($valid === true) {
        	$this->session->set_flashdata('block', 'Anda sudah pernah melakukan voting. Akun Anda dinonaktifkan. Jika ini kesalahan, hubungi panitia.');
        	redirect('user/login');
        }

        if (is_array($result)) {
        	$this->User_Model->reset_login_attempts($username);
        	$this->session->sess_regenerate(TRUE);
        	$this->session->set_userdata([
        		'nisn' => $result['username']
]);

        	redirect('user/index');
        } else {
        	$this->User_Model->record_login_attempt($username);
        	$this->session->set_flashdata('user_failed', 'Username atau Password salah');
        	redirect('user/login');
        }
    }

    public function logout() {
    	$this->session->unset_userdata('nisn');
    	redirect('user/login');
    }

    public function index() {
    	if (! $this->session->userdata('nisn')) {
    		redirect('user/login');
    	}

        // Cegah cache agar tombol back tidak bisa akses ulang halaman voting
    	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    	$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
    	$this->output->set_header("Pragma: no-cache");

    	$username = $this->session->userdata('nisn');

    	$data = [
    		'username'            => $username,
    		'datacalon'           => $this->User_Model->datamodel(),
    		'sudah_memilih_osis'  => $this->User_Model->sudah_vote($username, 1),
    		'sudah_memilih_mpk'   => $this->User_Model->sudah_vote($username, 0),
    		'voting_open'         => $this->User_Model->is_voting_open(),
    		'jadwal'              => $this->User_Model->voting_schedule()
    	];

    	$navbar_data = ['username' => $username];

    	$this->load->view('user/head');
    	$this->load->view('user/navbar', $navbar_data);
    	$this->load->view('user/index', $data);
    	$this->load->view('user/footer');
    }

    public function vote() {
    	if (! $this->session->userdata('nisn')) {
    		redirect('user/login');
    	}

    	if (strtoupper($this->input->method()) !== 'POST') {
    		show_error('Metode tidak diizinkan.', 405);
    	}

    $calon_nisn   = $this->input->post('nisn', TRUE); // NISN calon
    $username     = $this->session->userdata('nisn');

    if (empty($username) || empty($calon_nisn) || ! is_string($calon_nisn)) {
    	$this->session->set_flashdata('user_failed', 'Data tidak lengkap. Silakan login ulang.');
    	redirect('user/login');
    	return;
    }

    $calon = $this->User_Model->calon_select($calon_nisn);
    if ($calon === NULL) {
    	$this->session->set_flashdata('user_failed', 'Kandidat tidak ditemukan.');
    	redirect('user/index');
    	return;
    }

    $opsi = (int) $calon['opsi_mpkosis'];

    if (! $this->User_Model->is_voting_open()) {
    	$this->session->set_flashdata('block', 'Voting hanya dapat dilakukan pada waktu yang telah ditentukan.');
    	redirect('user/index');
    	return;
    }

    // Cek apakah sudah vote untuk kategori ini
    if ($this->User_Model->sudah_vote($username, $opsi)) {
    	$this->session->set_flashdata('block', 'Anda sudah memilih untuk kategori ini.');
    	redirect('user/index');
    }

    // Simpan vote
    $simpan = $this->User_Model->vote($username, $calon_nisn);
    $this->User_Model->hadir($username);

    if ($simpan) {
    	redirect('user/viewlogout');
    } else {
    	log_message('error', 'Vote gagal: ' . $this->db->error()['message']);
    	$this->session->set_flashdata('user_failed', 'Gagal menyimpan suara. Silakan coba lagi.');
    	redirect('user/index');
    }
}


public function viewlogout() {
    $username = $this->session->userdata('nisn');

    // Cek apakah sudah memilih OSIM dan MPK
    $cek_osis = $this->User_Model->sudah_vote($username, 1);
    $cek_mpk  = $this->User_Model->sudah_vote($username, 0);

    if (! $cek_osis || ! $cek_mpk) {
        $this->session->set_flashdata('user_failed', 'Anda belum memilih ' . org_label('organisasi') . ' dan MPK. Silakan selesaikan voting terlebih dahulu.');
        redirect('user/index');
    }

    // Jika sudah lengkap, tampilkan halaman logout
    $navbar_data = ['username' => $username];
    $this->load->view('user/head');
    $this->load->view('user/navbar', $navbar_data);
    $this->load->view('user/viewlogout');
    $this->load->view('user/footer');
}

}

