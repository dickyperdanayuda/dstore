<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanKaryawanKeuangan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		$this->load->model('Model_KaryawanKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$ba = [
			'judul' => "Laporan Karyawan",
			'subjudul' => "",
		];

		$d = [
			'jml_pria' => $this->karyawan->jml_pria(),
			'jml_wanita' => $this->karyawan->jml_wanita(),
		];

		$this->load->view('background_atas', $ba);
		$this->load->view('lap_karyawankeuangan', $d);
		$this->load->view('background_bawah');
	}
}
