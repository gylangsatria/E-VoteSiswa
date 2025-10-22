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
		if ($this->session->userdata('username')) {
			redirect('user/index');
		}

		$this->load->view('user/head');
		$this->load->view('user/login');
		$this->load->view('user/footer');
	}

	public function loginvalidation() {
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);

		$result = $this->User_Model->login($username, $password);
        $valid  = $this->User_Model->valid($username); // cek apakah sudah voting

        if ($valid === true) {
        	$this->session->set_flashdata('block', 'Anda sudah pernah melakukan voting. Akun Anda dinonaktifkan. Jika ini kesalahan, hubungi panitia.');
        	redirect('user/login');
        }

        if (is_array($result)) {
        	$this->session->set_userdata([
        		'username' => $result['username'],
    'nisn'     => $result['username'] // karena nisn = username
]);

        	redirect('user/index');
        } else {
        	$this->session->set_flashdata('failed', 'Username atau Password salah');
        	redirect('user/login');
        }
    }

    public function logout() {
    	$this->session->unset_userdata(['username', 'nisn']);
    	redirect('user/login');
    }

    public function index() {
    	if (! $this->session->userdata('username')) {
    		redirect('user/login');
    	}

        // Cegah cache agar tombol back tidak bisa akses ulang halaman voting
    	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    	$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
    	$this->output->set_header("Pragma: no-cache");

    	$username = $this->session->userdata('username');

    	$data = [
    		'username'            => $username,
    		'datacalon'           => $this->User_Model->datamodel(),
    		'sudah_memilih_osis'  => $this->User_Model->sudah_vote($username, 0),
    		'sudah_memilih_mpk'   => $this->User_Model->sudah_vote($username, 1)
    	];

    	$this->load->view('user/head');
    	$this->load->view('user/navbar');
    	$this->load->view('user/index', $data);
    	$this->load->view('user/footer');
    }

    public function vote() {
    	if (! $this->session->userdata('username')) {
    		redirect('user/login');
    	}

    $calon_nisn   = $this->input->post('nisn', TRUE); // NISN calon
    $opsi         = $this->input->post('opsi_mpkosis', TRUE); // 0 = OSIS, 1 = MPK
    $username     = $this->session->userdata('username');
    $nisn_pemilih = $this->session->userdata('nisn');

    // Debug log
    log_message('error', 'Vote attempt: username=' . $username . ', nisn=' . $nisn_pemilih . ', calon_nisn=' . $calon_nisn . ', opsi=' . $opsi);

    if (empty($nisn_pemilih) || empty($username) || empty($calon_nisn) || $opsi === null) {
    	$this->session->set_flashdata('failed', 'Data tidak lengkap. Silakan login ulang.');
    	redirect('user/login');
    }

    // Cek apakah sudah vote untuk kategori ini
    if ($this->User_Model->sudah_vote($username, $opsi)) {
    	$this->session->set_flashdata('block', 'Anda sudah memilih untuk kategori ini.');
    	redirect('user/index');
    }

    // Simpan vote
    $simpan = $this->User_Model->vote($username, $nisn_pemilih, $calon_nisn, $opsi);
    $this->User_Model->hadir($username);

    if ($simpan) {
    	log_message('error', 'Vote berhasil untuk ' . $username);
    	redirect('user/viewlogout');
    } else {
    	log_message('error', 'Vote gagal: ' . $this->db->error()['message']);
    	$this->session->set_flashdata('failed', 'Gagal menyimpan suara. Silakan coba lagi.');
    	redirect('user/index');
    }
}


public function viewlogout() {
    $username = $this->session->userdata('username');

    // Cek apakah sudah memilih OSIS dan MPK
    $cek_osis = $this->User_Model->sudah_vote($username, 0);
    $cek_mpk  = $this->User_Model->sudah_vote($username, 1);

    if (! $cek_osis || ! $cek_mpk) {
        $this->session->set_flashdata('failed', 'Anda belum memilih OSIS dan MPK. Silakan selesaikan voting terlebih dahulu.');
        redirect('user/index');
    }

    // Jika sudah lengkap, tampilkan halaman logout
    $this->load->view('user/head');
    $this->load->view('user/navbar');
    $this->load->view('user/viewlogout');
    $this->load->view('user/footer');
}

}

