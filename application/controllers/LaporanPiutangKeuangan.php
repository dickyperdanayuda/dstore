<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanPiutangKeuangan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		if ($this->session->userdata("level") > 2) {
			redirect(base_url("Dashboard"));
		}
		$this->load->model('Model_LapPiutangKeuangan', 'piutang_karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Laporan Piutang Karyawan");
		$ba = [
			'judul' => "Laporan Piutang Karyawan",
			'subjudul' => "Piutang Karyawan",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('lap_piutangkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_piutang_karyawan()
	{
		$list = $this->piutang_karyawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $piutang_karyawan) {
			$no++;
			$total = $this->piutang_karyawan->total_piutang($piutang_karyawan->piuk_kry_id);
			$dibayar = $this->piutang_karyawan->total_dibayar($piutang_karyawan->piuk_id);
			$sisa = $total->total - $dibayar->dibayar;
			if ($sisa == 0) {
				$keterangan = "Lunas";
			} else {
				$keterangan = "Belum Lunas";
			}

			$row = array();
			$row[] = $no;
			$row[] = $piutang_karyawan->kry_nama;
			$row[] = "Rp. " . number_format($total->total, 0);
			$row[] = "Rp. " . number_format($dibayar->dibayar, 0);
			$row[] = "Rp. " . number_format($sisa, 0);
			$row[] = $keterangan;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->piutang_karyawan->count_all(),
			"recordsFiltered" => $this->piutang_karyawan->count_filtered(),
			"data" => $data,
			"query" => $this->piutang_karyawan->getlastquery(),
		);
		echo json_encode($output);
	}
}
