<?php
Class Admin extends CI_Controller {
	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('form','url'));
		$this->load->library(array('session', 'pdflibrary', 'Excelreaders'));
		$this->load->model(array('Admin_Model'));

		if ( ! in_array(strtolower($this->router->fetch_method()), array('login', 'loginvalidation'), TRUE)
			&& ! $this->session->userdata('admin'))
		{
			redirect('admin/login');
		}
	}

	private function require_post()
	{
		if (strtoupper($this->input->method()) !== 'POST')
		{
			show_error('Metode tidak diizinkan.', 405);
		}
	}
	public function login() {
		if($this->session->userdata('admin'))
		{
			redirect('admin/index');
		}
		$this->load->view('admin/head');
		$this->load->view('admin/login');
	}
	public function gantipassword() {
		if(! $this->session->userdata('admin'))
		{
			redirect('admin/login');
		}
		$data['idsekolah']	= $this->Admin_Model->idsekolah();
		$data['dataadmin']	= $this->Admin_Model->dataadmin();
		$this->load->view('admin/head');
		$this->load->view('admin/admin-navbar');
		$this->load->view('admin/gantipassword', $data);
		$this->load->view('admin/footer', $data);
	}
	public function updatepassword() {
		$this->require_post();
		$username		= $this->session->userdata('admin');
		$password		= $this->input->post('password');
		$password_hash	= password_hash($password, PASSWORD_DEFAULT);
		$update			= $this->Admin_Model->gantipassword($username, $password_hash);
		if($update === true) {
			$this->session->set_flashdata('update', 'Berhasil Memperbarui Password');
			redirect('admin/gantipassword');
		}
		else {
			$this->session->set_flashdata('updatefailed', 'Gagal Memperbarui Password');
			redirect('admin/gantipassword');
		}
	}
	public function logout() {
		$this->session->unset_userdata('admin');
		redirect('admin/login');
	}
	public function loginvalidation() {
		// Rate limiting: max 5 percobaan per akun (persisten di DB)
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);

		if (! is_string($username) || ! is_string($password) || trim($username) === '') {
			$this->session->set_flashdata('failed', 'Username atau Password salah');
			redirect('admin/login');
			return;
		}

		if ($this->Admin_Model->login_locked($username)) {
			$this->session->set_flashdata('failed', 'Terlalu banyak percobaan login untuk akun ini. Tunggu 5 menit lalu coba lagi, atau hubungi panitia.');
			redirect('admin/login');
			return;
		}

		$result = $this->Admin_Model->login($username, $password);
		if($result == true) {
			$this->Admin_Model->reset_login_attempts($username);
			$this->session->unset_userdata('failed');
			$this->session->sess_regenerate(TRUE);
			$this->session->set_userdata(array(
				'admin'	=> $username
			));
			redirect('admin/regvalid');
		}
		else
		{
			if (! $this->Admin_Model->admin_exists($username)) {
				$this->session->set_flashdata('failed', 'Username tidak terdaftar');
			}
			elseif ($this->Admin_Model->record_login_attempt($username) >= 5) {
				$this->session->set_flashdata('failed', 'Password salah 5 kali. Akun terkunci 5 menit, lalu coba lagi atau hubungi panitia.');
			}
			else {
				$this->session->set_flashdata('failed', 'Username atau Password Salah');
			}
			redirect('admin/login');
		}
	}
	public function regvalid(){
		if (! $this->session->userdata('admin')) {
			redirect('admin/login');
		}

		$data = $this->Admin_Model->regvalid();

        // pastikan $data valid
		if (empty($data) || !isset($data[0]['npsn'])) {
                // kalau tidak ada data sekolah sama sekali
			redirect('admin/regsekolah');
			return;
		}

        // ambil row pertama (yang biasanya dimaksudkan)
		$valid = $data[0];

		if (empty($valid['npsn'])) {
			redirect('admin/regsekolah');
		} else {
			redirect('admin/index');
		}
	}
	public function regsekolah() {
		$data = $this->Admin_Model->regvalid();
		if(!empty($data)) {
			redirect('admin/index');
		}
		$this->load->view('admin/head');
		$this->load->view('admin/regsekolah');
	}
	public function simpansekolah() {
		$this->require_post();
		$npsn		= $this->input->post('npsn');
		$nm_sekolah	= $this->input->post('nm_sekolah');
		$reg		= $this->Admin_Model->regsekolah($npsn,$nm_sekolah);
		if($reg === true) {
			redirect('admin/index');
		}
		else {
			$this->session->set_flashdata('regfailed', 'Registrasi Gagal');
			redirect('admin/regsekolah');
		}
	}
	public function index() {
		if(! $this->session->userdata('admin'))
		{
			redirect('admin/login');
		}
		$data['valid'] = $this->Admin_Model->regvalid();
		if(empty($data['valid'])) {
			redirect('admin/regsekolah');
		}
		$data['jmlcalon']	= $this->Admin_Model->countcalon();
		$data['jmlpemilih']	= $this->Admin_Model->countpemilih();
		$data['idsekolah']	= $this->Admin_Model->idsekolah();
		$data['datapilketos'] = $this->Admin_Model->datapilketos();
		$this->load->view('admin/head');
		$this->load->view('admin/admin-navbar');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer', $data);
	}
	public function updatedatapilketos(){
		$this->require_post();
		$tapel       = $this->input->post("tapel");
		$tgl         = $this->input->post('tgl');
		$jam_mulai   = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');
		$aktif       = $this->input->post('aktif') ? 1 : 0;

		$tgl         = ($tgl === '' || $tgl === null) ? null : $tgl;
		$jam_mulai   = ($jam_mulai === '' || $jam_mulai === null) ? null : $jam_mulai;
		$jam_selesai = ($jam_selesai === '' || $jam_selesai === null) ? null : $jam_selesai;

		if ($aktif && ($tgl === null || $jam_mulai === null || $jam_selesai === null)) {
			$this->session->set_flashdata('updatefailed', 'Tanggal, jam mulai, dan jam selesai wajib diisi untuk mengaktifkan batas waktu voting.');
			redirect('admin/index');
			return;
		}

		if ($jam_mulai !== null && $jam_selesai !== null && strtotime($jam_selesai) <= strtotime($jam_mulai)) {
			$this->session->set_flashdata('updatefailed', 'Jam selesai harus lebih besar dari jam mulai.');
			redirect('admin/index');
			return;
		}

		$update = $this->Admin_Model->updatedatapilketos($tapel, $tgl, $jam_mulai, $jam_selesai, $aktif);

    if($update){  // perbandingan benar
    	$this->session->set_flashdata('update', 'Berhasil Menyimpan Data');
    	redirect('admin/index');
    }
    else {
    	$this->session->set_flashdata('updatefailed', 'Gagal Menyimpan User');
    	redirect('admin/index');
    }
}

public function resetuser() {
	$this->require_post();
	$username	= $this->input->post('username');
	$reset		= $this->Admin_Model->resetuser($username);
	if($reset === true) {
		$updateuser	= $this->Admin_Model->updateuser($username);
		$this->session->set_flashdata('info', 'Berhasil Mereset User');
		redirect('admin/index');
	}
	else {
		$this->session->set_flashdata('failed', 'Gagal Mereset User');
		redirect('admin/index');
	}
}
public function resetdata() {
	$this->require_post();
	$reset = $this->Admin_Model->resetdata();
	if($reset === true) {
		$this->session->set_flashdata('reset', 'Berhasil Mereset Data');
		redirect('admin/index');
	}
	else {
		$this->session->set_flashdata('resetfailed', 'Gagal Mereset Data');
		redirect('admin/index');
	}
}
public function idsekolah() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$data['valid'] = $this->Admin_Model->regvalid();
	if(empty($data['valid'])) {
		redirect('admin/regsekolah');
	}
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/idsekolah', $data);
	$this->load->view('admin/footer', $data);
}
public function updateidsekolah() {
	$this->require_post();
	$npsn			= $this->input->post('npsn');
	$nm_sekolah		= $this->input->post('nm_sekolah');
	$jln			= $this->input->post('jln');
	$desa			= $this->input->post('desa');
	$kec			= $this->input->post('kec');
	$kab			= $this->input->post('kab');
	$kpl_sekolah	= $this->input->post('kpl_sekolah');
	$nip			= $this->input->post('nip');
	$jenis			= ($this->input->post('jenis') === 'madrasah') ? 'madrasah' : 'sekolah';
	$save			= $this->Admin_Model->updateidsekolah($npsn, $nm_sekolah, $jln, $desa, $kec, $kab, $kpl_sekolah, $nip, $jenis);
	if($save === true) {
		$this->session->set_flashdata('info', 'Berhasil Memperbarui Data');
		redirect('admin/idsekolah');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Memperbarui Data');
		redirect('admin/idsekolah');
	}
}
public function datakelas() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$data['datakelas']	= $this->Admin_Model->datakelas();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/datakelas', $data);
	$this->load->view('admin/footer', $data);
}
public function simpankelas() {
	$this->require_post();
	$nm_kelas	= $this->input->post('nm_kelas');
	$save		= $this->Admin_Model->simpankelas($nm_kelas);
	if($save === true) {
		$this->session->set_flashdata('info', 'Berhasil Menambahkan Data');
		redirect('admin/datakelas');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Menambahkan Data');
		redirect('admin/datakelas');
	}
}
public function hapuskelas($kd_kelas) {
	$this->require_post();
	$hapus = $this->Admin_Model->hapuskelas($kd_kelas);
	if($hapus === true) {
		$this->session->set_flashdata('info', 'Berhasil Menghapus Data');
		redirect('admin/datakelas');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Menghapus Data');
		redirect('admin/datakelas');
	}
}

public function hapussemuakelas() {
	$this->require_post();
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$jumlah = $this->db->count_all('tb_kelas');

	if ($jumlah > 0) {
		$this->Admin_Model->hapussemuakelas();
		$this->session->set_flashdata('success', 'Semua data Kelas berhasil dihapus.');
	} else {
		$this->session->set_flashdata('warning', 'Data Kelas sudah kosong.');
	}

    redirect('admin/datakelas'); // atau ke halaman DPT
}

public function hapussemuadpt() {
	$this->require_post();
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$jumlah = $this->db->count_all('tb_siswa');

	if ($jumlah > 0) {
		$this->Admin_Model->hapussemuadpt();
		$this->session->set_flashdata('success', 'Semua data DPT berhasil dihapus.');
	} else {
		$this->session->set_flashdata('warning', 'Data DPT sudah kosong.');
	}

    redirect('admin/datadpt'); // atau ke halaman DPT
}


public function tambahcalon() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/tambahcalon');
	$this->load->view('admin/footer', $data);
}
public function hapuscalon($nisn) {
	$this->require_post();
	$hapus = $this->Admin_Model->hapuscalon($nisn);
	if($hapus === true) {
		$this->session->set_flashdata('info', 'Berhasil Menghapus Data');
		redirect('admin/datacalon/');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Menghapus Data');
		redirect('admin/datacalon/');
	}
}
public function tambahdpt() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$cekelas			= $this->Admin_Model->cekelas();
	if($cekelas == false) {
		echo "
		<script>
		alert('Anda Belum Menambahan Data Kelas, Silahkan Ditambahkan Terlebih Dahulu.');
		location.href = '".base_url('index.php/admin/datakelas')."';
		</script>
		";
	}
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$data['datakelas']	= $this->Admin_Model->datakelas();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/tambahdpt', $data);
	$this->load->view('admin/footer', $data);
}

public function datadpt() {
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$data['idsekolah'] = $this->Admin_Model->idsekolah();
	$keyword = $this->input->get('keyword');

	$this->db->select('tb_siswa.*, tb_kelas.nm_kelas');
	$this->db->from('tb_siswa');
    $this->db->join('tb_kelas', 'tb_kelas.kd_kelas = tb_siswa.kd_kelas', 'left'); // sesuaikan jika nama kolom berbeda

    if ($keyword) {
    	$this->db->group_start();
    	$this->db->like('tb_siswa.username', $keyword);
    	$this->db->or_like('tb_siswa.nm_siswa', $keyword);
    	$this->db->group_end();
    }

    $data['datadpt'] = $this->db->get()->result_array();

    $this->load->view('admin/head');
    $this->load->view('admin/admin-navbar');
    $this->load->view('admin/datadpt', $data);
    $this->load->view('admin/footer', $data);
}

public function simpandpt() {
	$this->require_post();
	$username	= $this->input->post('nisn');
	$password	= $this->input->post('nisn');
	$nm_siswa	= $this->input->post('nm_siswa');
	$jk 		= $this->input->post('jk');
	$kd_kelas	= $this->input->post('kd_kelas');

	if ($this->Admin_Model->dataadadpt($username)) {
		$this->session->set_flashdata('failed', 'NISN ' . $username . ' sudah terdaftar di DPT.');
		redirect('admin/tambahdpt/');
	}

	$save 		= $this->Admin_Model->simpandpt($username, $password, $nm_siswa, $jk ,$kd_kelas);
	if($save === true) {
		$this->session->set_flashdata('info', 'Berhasil Menambahkan Data');
		redirect('admin/tambahdpt/');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Menambahkan Data');
		redirect('admin/tambahdpt/');
	}
}

// reset hasil vote 

public function reset_vote() {
	$this->require_post();
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

    // Cek apakah data voting masih ada
	$jumlah = $this->db->count_all('tb_pilih');

	if ($jumlah > 0) {
		$this->Admin_Model->delete_all_votes();
		$this->session->set_flashdata('success', 'Hasil voting berhasil direset.');
	} else {
		$this->session->set_flashdata('warning', 'Data voting sudah kosong.');
	}

    redirect('admin/hasilvote'); // atau ke halaman hasil vote
}


//simpan masal edit
public function simpanmassaldpt() {
	$this->require_post();
	if (!$this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$log = [];

    // Validasi file upload
	if (!isset($_FILES['datadpt']) || $_FILES['datadpt']['error'] != 0) {
		$log[] = '❌ File tidak valid atau gagal diupload.';
		$this->session->set_flashdata('failed', 'File tidak valid atau gagal diupload.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

	// Validasi MIME asli di server (bukan dari nilai klien)
	$finfo     = finfo_open(FILEINFO_MIME_TYPE);
	$file_mime = finfo_file($finfo, $_FILES['datadpt']['tmp_name']);
	finfo_close($finfo);

	$allowed_mime = array(
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/zip',
		'application/x-ole-storage',
		'text/csv',
		'text/plain'
	);
	if (!in_array($file_mime, $allowed_mime)) {
		$log[] = '❌ Tipe file tidak didukung. Gunakan file Excel (.xls, .xlsx) atau CSV.';
		$this->session->set_flashdata('failed', 'Tipe file tidak didukung.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

	$ext = strtolower(pathinfo($_FILES['datadpt']['name'], PATHINFO_EXTENSION));
	if (!in_array($ext, array('xls', 'xlsx', 'csv'), TRUE)) {
		$log[] = '❌ Ekstensi file tidak diizinkan. Gunakan .xls, .xlsx, atau .csv.';
		$this->session->set_flashdata('failed', 'Ekstensi file tidak diizinkan.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

	// Siapkan folder uploads (fallback ke temp jika tidak writable)
	$upload_dir = FCPATH . 'uploads/';
	if (!is_dir($upload_dir)) {
		@mkdir($upload_dir, 0755, true);
	}
	if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
		$upload_dir = sys_get_temp_dir() . '/evotesiswa_uploads/';
		@mkdir($upload_dir, 0755, true);
	}

	// Whitelist karakter nama file
	$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['datadpt']['name']));
	$target = $upload_dir . $filename;

    // Pindahkan file ke folder uploads
	if (!move_uploaded_file($_FILES['datadpt']['tmp_name'], $target)) {
		$log[] = '❌ Gagal memindahkan file ke folder uploads.';
		$this->session->set_flashdata('failed', 'Gagal upload file.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

    // Set permission aman (readable only)
	@chmod($target, 0644);

    // Baca isi file menggunakan SpreadsheetReader (mendukung CSV, XLS, XLSX)
	require_once APPPATH . 'third_party/spreadsheet-reader/SpreadsheetReader.php';

	try {
		$reader = new SpreadsheetReader($target);
		$rows = iterator_to_array($reader);
	} catch (Throwable $e) {
		$log[] = '❌ Gagal membaca file' . ((defined('ENVIRONMENT') && ENVIRONMENT === 'production') ? '.' : ': ' . $e->getMessage());
		unlink($target);
		$this->session->set_flashdata('failed', 'Gagal membaca file. Pastikan format CSV/Excel benar.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

	$jumlah_baris = count($rows);
	$log[] = "📊 Jumlah baris terbaca: $jumlah_baris";

	if ($jumlah_baris < 2) {
		$log[] = '❌ File tidak memiliki data (minimal 1 baris data + header).';
		unlink($target);
		$this->session->set_flashdata('failed', 'File kosong atau tidak memiliki data.');
		$this->session->set_flashdata('log', $log);
		redirect('admin/tambahdpt/');
		return;
	}

	$berhasil = 0;

	// Baris 0 = header, data dimulai dari baris 1
	for ($i = 1; $i < $jumlah_baris; $i++) {
		$row = $rows[$i];
		$nisn  = trim($row[0] ?? '');
		$nama  = trim($row[1] ?? '');
		$jk    = trim($row[2] ?? '');
		$kelas = trim($row[3] ?? '');

		$log[] = "🔍 Baris " . ($i + 1) . ": NISN=$nisn | Nama=$nama | JK=$jk | Kelas=$kelas";

		if ($nisn && $nama && $jk && $kelas) {
			try {
				$simpan = $this->Admin_Model->simpanmassaldpt($nisn, $nama, $jk, $kelas);
				if ($simpan === true) {
					$berhasil++;
				} else {
					$log[] = "❌ Gagal simpan ke DB: $nisn | $nama | $jk | $kelas";
				}
			} catch (Throwable $e) {
				$log[] = "❌ Baris " . ($i + 1) . " (NISN $nisn) ditolak database, kemungkinan NISN duplikat.";
			}
		} else {
			$log[] = "⚠️ Data tidak lengkap di baris " . ($i + 1) . ", dilewati.";
		}
	}

	unlink($target);

	$gagal = $jumlah_baris - 1 - $berhasil;
	$log[] = "✅ Total berhasil: $berhasil | ❌ Total gagal: $gagal";

	if ($berhasil > 0) {
		$this->session->set_flashdata('info', "Berhasil menambahkan $berhasil data. Gagal: $gagal");
	} else {
		$this->session->set_flashdata('failed', "Tidak ada data berhasil ditambahkan.");
	}

	$this->session->set_flashdata('log', $log);
	redirect('admin/tambahdpt/');
}


// akhir simpan masal 

public function hapusdpt($username = NULL) {
	$this->require_post();
	$username = ($username === NULL) ? $this->input->post('username') : $username;
	$hapus	= $this->Admin_Model->hapusdpt($username);
	if($hapus === true) {
		$this->session->set_flashdata('info', 'Berhasil Menghapus Data');
		redirect('admin/datadpt/');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Berhasil Menghapus Data');
		redirect('admin/datadpt/');
	}
}
public function editdpt($username = NULL) {
	if ($username === NULL) {
		$username = $this->input->get('nisn', TRUE);
	}

	$username = is_string($username) ? trim($username) : '';
	$data['datakddpt']	= $this->Admin_Model->datakddpt($username);
	$data['nisn']		= $username;
	$data['datakelas']	= $this->Admin_Model->datakelas();
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/editdpt', $data);
	$this->load->view('admin/footer', $data);
}
public function updatedpt($username = NULL){
	$this->require_post();
	$username	= (string) $this->input->post('nisn');
	$nm_siswa	= $this->input->post('nm_siswa');
	$jk			= $this->input->post('jk');
	$kd_kelas	= $this->input->post('kd_kelas');
	$update		= $this->Admin_Model->updatedpt($username, $nm_siswa, $jk,$kd_kelas);
	if($update === true) {
		$this->session->set_flashdata('info', 'Berhasil Mengupdate Data');
	}
	else
	{
		$this->session->set_flashdata('failed', 'Gagal Mengupdate Data');
	}
	redirect('admin/datadpt');
}

public function editcalon($nisn) {
	$data['datacalon']	= $this->Admin_Model->datacalonspesifik($nisn);
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/editcalon', $data);
	$this->load->view('admin/footer', $data);
}



public function simpancalon() {
	$this->require_post();
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$nisn          = $this->input->post('nisn');
	$no            = $this->input->post('no');
	$nama          = $this->input->post('nama');
	$nama_wakil    = $this->input->post('nama_wakil');
    $opsi_mpkosis  = $this->input->post('opsi_mpkosis'); // 0 = MPK, 1 = OSIM

    $config['upload_path']   = './asset/img/';
    $config['allowed_types'] = 'gif|jpg|jpeg|png';
    $config['max_size']      = 1024;
    $config['file_name']     = $nisn;

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('photo')) {
    	$upload_data = $this->upload->data();
    	$photo       = $upload_data['file_name'];

        // Pastikan method tambahcalon di Admin_Model menerima parameter tambahan
    	$this->Admin_Model->tambahcalon($nisn, $no, $nama, $nama_wakil, $photo, $opsi_mpkosis);
    	$this->session->set_flashdata('info', 'Berhasil Menambahkan Data');
    } else {
    	$this->session->set_flashdata('failed', 'Gagal Menambahkan Data: ' . $this->upload->display_errors('', ''));
    }

    redirect('admin/tambahcalon');
}

/*
	public function updatecalon() {
		$nisn		= $this->input->post('nisn');
		$no			= $this->input->post('no');
		$nama		= $this->input->post('nama');
		$upade		= $this->Admin_Model->updatecalon($nisn, $no ,$nama);
		if($update === true) {
			$this->session->set_flashdata('info', 'Berhasil MemperbaruiData');
			redirect('admin/editcalon/'.$nisn);
		}
		else
		{
			$this->session->set_flashdata('failed', 'Gagal Memperbarui Data');
			redirect('admin/editcalon/'.$nisn);
		}
	} */

	public function updatecalon() {
		$this->require_post();
		if (! $this->session->userdata('admin')) {
			redirect('admin/login');
		}

		$nisn          = $this->input->post('nisn');
		$no            = $this->input->post('no');
		$nama          = $this->input->post('nama');
		$nama_wakil    = $this->input->post('nama_wakil');
		$opsi_mpkosis  = $this->input->post('opsi_mpkosis');

    // Konfigurasi upload
		$config['upload_path']   = './asset/img/';
		$config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['file_name']     = 'calon_' . $nisn;

    $this->load->library('upload', $config);

    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
    	if ($this->upload->do_upload('photo')) {
    		$upload_data = $this->upload->data();
    		$photo = $upload_data['file_name'];
    	} else {
    		$this->session->set_flashdata('failed', 'Upload foto gagal: ' . $this->upload->display_errors('', ''));
    		redirect('admin/editcalon/' . $nisn);
    		return;
    	}
    }

    // Kirim ke model
    $update = $this->Admin_Model->updatecalon($nisn, $no, $nama, $nama_wakil, $opsi_mpkosis, $photo);

    if ($update) {
    	$this->session->set_flashdata('info', 'Berhasil Memperbarui Data');
    } else {
    	$this->session->set_flashdata('failed', 'Gagal Memperbarui Data');
    }

    redirect('admin/editcalon/' . $nisn);
}


public function datacalon() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$data['datacalon']	= $this->Admin_Model->datacalon();
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/datacalon', $data);
	$this->load->view('admin/footer', $data);
}
public function hasilvote() {
	if(! $this->session->userdata('admin'))
	{
		redirect('admin/login');
	}
	$data['vote']		= $this->Admin_Model->hasilvote();
	$data['jmlpemilih']	= $this->Admin_Model->countpemilih();
	$data['jmlvote']	= $this->Admin_Model->countvote();
	$data['idsekolah']	= $this->Admin_Model->idsekolah();
	$this->load->view('admin/head');
	$this->load->view('admin/admin-navbar');
	$this->load->view('admin/hasilvote', $data);
	$this->load->view('admin/footer', $data);
}
public function daftarhadir() {
    // Cek sesi login
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

    // Load model
	$this->load->model('Admin_Model');

    // Ambil data statistik dan daftar hadir
    $data['vote']         = $this->Admin_Model->hasilvote();       // result_array() untuk grafik vote
    $data['jmlpemilih']   = $this->Admin_Model->countpemilih();    // row_array() → ['jumlah']
    $data['jmlvote']      = $this->Admin_Model->countvote();       // row_array() → ['jumlah']
    $data['idsekolah']    = $this->Admin_Model->idsekolah();       // info sekolah
    $data['daftarhadir']  = $this->Admin_Model->daftarhadir();     // result_array() → list siswa hadir

    // Load tampilan
    $this->load->view('admin/head');
    $this->load->view('admin/admin-navbar');
    $this->load->view('admin/daftarhadir', $data);
    $this->load->view('admin/footer');
}

public function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
public function cetakdaftarhadir(){
	$this->require_post();
	$datasekolah	= $this->Admin_Model->idsekolah();
	$data			=	$this->Admin_Model->daftarhadir();
	foreach($datasekolah as $loaddata) {}
		ob_start();
	$pdf = new FPDF('p', 'mm', 'a4');
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(190,7, $loaddata['nm_sekolah'],0,1,'C');
	$pdf->Cell(190,7, 'Daftar Hadir Pemilihan Ketua ' . org_label('organisasi'),0,1,'C');
	$pdf->Cell(10,7,'',0,1);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(10,10, 'No',1,0, 'C');
	$pdf->Cell(35,10, 'Nisn',1,0, 'C');
	$pdf->Cell(70,10, 'Nama',1,0, 'C');
	$pdf->Cell(35,10, 'Kelas',1,0, 'C');
	$pdf->Cell(35,10, 'Keterangan',1,1, 'C');
	$no = 1;
	$pdf->SetFont('Arial','',12);
	foreach($data as $load) {
		$pdf->cell(10,10, $no++ , 1,0, 'C' );
		$pdf->cell(35,10, $load['username'] , 1,0, 'C' );
		$pdf->cell(70,10, $load['nm_siswa'] , 1,0, 'L' );
		$pdf->cell(35,10, $load['nm_kelas'] , 1,0, 'C' );
		$pdf->cell(35,10, $load['hadir'] , 1,1, 'L' );
	}
	$pdf->Cell(10,7,'',0,1);
	$pdf->Cell(115,10, '',0,0, 'L');
	$pdf->Cell(70,10, $loaddata['desa'].', '. $this->tgl_indo(date('Y-m-d')),0,1, 'L');
	$pdf->Cell(115,10, '',0,0, 'L');
	$pdf->Cell(70,10, org_label('kepala'),0,1, 'L');
	$pdf->Cell(10,20,'',0,1);
	$pdf->Cell(115,6, '',0,0, 'L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(70,6, $loaddata['kpl_sekolah'],0,1, 'L');
	$pdf->Cell(115,6, '',0,0, 'L');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(70,6, 'NIP: '.$loaddata['nip'],0,1, 'L');
	$pdf->Output();
	ob_end_flush();
}
public function laporan() {
	if (! $this->session->userdata('admin')) {
		redirect('admin/login');
	}

	$datasekolah   = $this->Admin_Model->idsekolah();
    $daftarhadir   = $this->Admin_Model->daftarhadir(); // ganti nama dari $data
    $jmldptL       = $this->Admin_Model->jmldptL();
    $jmldptP       = $this->Admin_Model->jmldptP();
    $jmlvoteL      = $this->Admin_Model->jmlvoteL();
    $jmlvoteP      = $this->Admin_Model->jmlvoteP();
    $datavote      = $this->Admin_Model->hasilvote();
    $datapilketos  = $this->Admin_Model->datapilketos();

    $loaddata = !empty($datasekolah) ? $datasekolah[0] : ['nm_sekolah'=>'-', 'kab'=>'-', 'desa'=>'-', 'kpl_sekolah'=>'-', 'nip'=>'-'];
$dptL  = isset($jmldptL['L']) ? (int) $jmldptL['L'] : 0;
$dptP  = isset($jmldptP['P']) ? (int) $jmldptP['P'] : 0;
$voteL = isset($jmlvoteL['L']) ? (int) $jmlvoteL['L'] : 0;
$voteP = isset($jmlvoteP['P']) ? (int) $jmlvoteP['P'] : 0;
    $pilketos = !empty($datapilketos[0]) ? $datapilketos[0] : ['tapel' => '-', 'tgl' => '-'];

    $dptTotal       = $dptL + $dptP;
    $voteTotal      = $voteL + $voteP;
    $tidakMemilihL  = max(0, $dptL - $voteL);
    $tidakMemilihP  = max(0, $dptP - $voteP);
    $tidakMemilih   = $tidakMemilihL + $tidakMemilihP;
    $partisipasi    = ($dptTotal > 0) ? round(($voteTotal / $dptTotal) * 100, 2) : 0;

    // Bersihkan output sebelum PDF
    if (ob_get_length()) ob_end_clean();
    ob_start();

    $pdf = new FPDF('L', 'mm', 'Legal');
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(330,7, 'LAPORAN PELAKSANAAN PEMILIHAN KETUA ' . strtoupper(org_label('organisasi')) . ' DAN MPK',0,1,'C');
    $pdf->Cell(330,7, 'TAHUN PELAJARAN '.$pilketos['tapel'],0,1,'C');
    $pdf->Cell(10,7,'',0,1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(60,7, 'Kabupaten', 0,0);
    $pdf->Cell(5,7, ':', 0,0);
    $pdf->Cell(60,7, $loaddata['kab'], 0,1);
    $pdf->Cell(60,7, 'Tanggal Pelaksanaan', 0,0);
    $pdf->Cell(5,7, ':', 0,0);
    $pdf->Cell(60,7, $pilketos['tgl'], 0,1);

    // Tabel DPT
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,21, 'No',1,0, 'C');
    $pdf->Cell(90,21, 'Nama ' . org_label('satuan'),1,0, 'C');
    $pdf->Cell(60,7, 'Daftar Pemilih Tetap',1,0, 'C');
    $pdf->Cell(60,7, 'Jumlah Yang Menggunakan',1,0, 'C');
    $pdf->Cell(80,7, 'Jumlah Yang Tidak Menggunakan',1,0, 'C');
    $pdf->Cell(1,7, '', 0,1);
    $pdf->Cell(10,7, '', 0,0);
    $pdf->Cell(90,7, '', 0,0);
    $pdf->Cell(60,7, '(DPT)',1,0, 'C');
    $pdf->Cell(60,7, 'Hak Suara',1,0, 'C');
    $pdf->Cell(80,7, 'Hak Suara',1,0, 'C');
    $pdf->Cell(1,7, '', 0,1);
    $pdf->Cell(10,7, '', 0,0);
    $pdf->Cell(90,7, '', 0,0);
    $pdf->Cell(20,7, 'L', 1,0, 'C');
    $pdf->Cell(20,7, 'P', 1,0, 'C');
    $pdf->Cell(20,7, 'Jumlah', 1,0, 'C');
    $pdf->Cell(20,7, 'L', 1,0, 'C');
    $pdf->Cell(20,7, 'P', 1,0, 'C');
    $pdf->Cell(20,7, 'Jumlah', 1,0, 'C');
    $pdf->Cell(20,7, 'L', 1,0, 'C');
    $pdf->Cell(20,7, 'P', 1,0, 'C');
    $pdf->Cell(40,7, 'Jumlah', 1,1, 'C');

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(10,10, '1',1,0, 'C');
    $pdf->Cell(90,10, $loaddata['nm_sekolah'],1,0, 'C');
    $pdf->Cell(20,10, $dptL, 1,0, 'C');
    $pdf->Cell(20,10, $dptP, 1,0, 'C');
    $pdf->Cell(20,10, $dptTotal, 1,0, 'C');
    $pdf->Cell(20,10, $voteL, 1,0, 'C');
    $pdf->Cell(20,10, $voteP, 1,0, 'C');
    $pdf->Cell(20,10, $voteTotal, 1,0, 'C');
    $pdf->Cell(20,10, $tidakMemilihL, 1,0, 'C');
    $pdf->Cell(20,10, $tidakMemilihP, 1,0, 'C');
    $pdf->Cell(40,10, $tidakMemilih, 1,1, 'C');

    $pdf->Cell(10,7,'',0,1);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(60,7, 'Persentase Partisipasi Pemilih: '.$partisipasi.'%', 0,1);

    // Hasil OSIM
    $pdf->Cell(10,7,'',0,1);
    $pdf->Cell(60,7, 'Hasil Pemilihan Ketua ' . org_label('organisasi'), 0,1);
    $pdf->Cell(30,12, 'No Urut',1,0, 'C');
    $pdf->Cell(100,12, 'Nama Kandidat',1,0, 'C');
    $pdf->Cell(80,12, 'Jumlah Perolehan Suara',1,1, 'C');
    $pdf->SetFont('Arial','',12);
    foreach($datavote as $hasil) {
    	if (isset($hasil['opsi_mpkosis']) && $hasil['opsi_mpkosis'] == 1) {
    		$pdf->Cell(30,7, $hasil['no'],1,0, 'C');
    		$pdf->Cell(100,7, $hasil['nama'] . ' / ' . $hasil['nama_wakil'],1,0, 'L');
    		$pdf->Cell(80,7, $hasil['jumlah'],1,1, 'C');
    	}
    }

    // Hasil MPK
    $pdf->Cell(10,7,'',0,1);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(60,7, 'Hasil Pemilihan Ketua MPK', 0,1);
    $pdf->Cell(30,12, 'No Urut',1,0, 'C');
    $pdf->Cell(100,12, 'Nama Kandidat',1,0, 'C');
    $pdf->Cell(80,12, 'Jumlah Perolehan Suara',1,1, 'C');
    $pdf->SetFont('Arial','',12);
    foreach($datavote as $hasil) {
    	if (isset($hasil['opsi_mpkosis']) && $hasil['opsi_mpkosis'] == 0) {
    		$pdf->Cell(30,7, $hasil['no'], 1,0, 'C');
    		$pdf->Cell(100,7, $hasil['nama'] . ' / ' . $hasil['nama_wakil'], 1,0, 'L');
    		$pdf->Cell(80,7, $hasil['jumlah'], 1,1, 'C');
    	}
    }

    // Tanda tangan
    $pdf->Cell(10,14,'',0,1);
    $pdf->Cell(220,10, '',0,0, 'L');
    $pdf->Cell(70,10, $loaddata['desa'].', '.$this->tgl_indo(date('Y-m-d')),0,1, 'L');
    $pdf->Cell(220,10, '',0,0, 'L');
    $pdf->Cell(70,10, org_label('kepala'),0,1, 'L');
    $pdf->Cell(10,20,'',0,1);
    $pdf->Cell(220,6, '',0,0, 'L');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(70,6, $loaddata['kpl_sekolah'],0,1, 'L');
    $pdf->Cell(220,6, '',0,0, 'L');
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(70,6, 'NIP: '.$loaddata['nip'],0,1, 'L');

    $pdf->Output();
}
}

