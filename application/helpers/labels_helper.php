<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('org_mode')) {
	function org_mode() {
		static $mode = null;
		if ($mode !== null) {
			return $mode;
		}
		$CI =& get_instance();
		$CI->load->database();
		$row  = $CI->db->select('jenis')->get('tb_identitassekolah')->row_array();
		$mode = (isset($row['jenis']) && $row['jenis'] === 'madrasah') ? 'madrasah' : 'sekolah';
		return $mode;
	}
}

if ( ! function_exists('org_label')) {
	function org_label($key) {
		$madrasah = org_mode() === 'madrasah';
		$map = array(
			'organisasi' => $madrasah ? 'OSIM' : 'OSIS',
			'satuan'     => $madrasah ? 'Madrasah' : 'Sekolah',
			'satuan_lc'  => $madrasah ? 'madrasah' : 'sekolah',
			'kepala'     => $madrasah ? 'Kepala Madrasah' : 'Kepala Sekolah'
		);
		return isset($map[$key]) ? $map[$key] : '';
	}
}

if ( ! function_exists('tgl_jadwal')) {
	function tgl_jadwal($jadwal) {
		if (empty($jadwal['tgl'])) {
			return '';
		}
		$bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		$ts    = strtotime($jadwal['tgl']);
		$out   = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);

		$mulai   = !empty($jadwal['jam_mulai']) ? substr($jadwal['jam_mulai'], 0, 5) : '';
		$selesai = !empty($jadwal['jam_selesai']) ? substr($jadwal['jam_selesai'], 0, 5) : '';
		if ($mulai !== '' && $selesai !== '') {
			$out .= ', pukul ' . str_replace(':', '.', $mulai) . ' - ' . str_replace(':', '.', $selesai);
		}
		return $out;
	}
}
