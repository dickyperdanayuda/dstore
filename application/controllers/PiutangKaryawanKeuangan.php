<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PiutangKaryawanKeuangan extends CI_Controller
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
		$this->load->model('Model_PiutangKaryawanKeuangan', 'piutang_karyawan');
		$this->load->model('Model_KaryawanKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Piutang Karyawan");
		$ba = [
			'judul' => "Data Piutang Karyawan",
			'subjudul' => "Piutang Karyawan",
		];
		$d = [
			'karyawan' => $this->karyawan->get_karyawan(),
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('piutang_karyawankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_piutang_karyawan()
	{
		$list = $this->piutang_karyawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $piutang_karyawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $piutang_karyawan->kry_nama;
			$row[] = $piutang_karyawan->piuk_tgl;
			$row[] = "Rp. " . number_format($piutang_karyawan->piuk_jml, 0);
			$row[] = $piutang_karyawan->piuk_keterangan;
			$row[] = $piutang_karyawan->piuk_pemberi;
			$row[] = "<a href='../PiutangBayarKeuangan/tampil/" . $piutang_karyawan->piuk_id . "' class='btn btn-dark btn-sm' title='Bayar Piutang'><i class='fas fa-donate'></i></a>&nbsp;<a href='#' onClick='ubah_piutang_karyawan(" . $piutang_karyawan->piuk_id . ")' class='btn btn-dark btn-sm' title='Ubah data piutang_karyawan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_piutang_karyawan(" . $piutang_karyawan->piuk_id . ")' class='btn btn-danger btn-sm' title='Hapus data piutang_karyawan'><i class='fa fa-trash-alt'></i></a>";
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

	public function cari()
	{
		$id = $this->input->post('piuk_id');
		$data = $this->piutang_karyawan->cari_piutang_karyawan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('piuk_id');
		$data = $this->input->post();
		$tgl = explode("/", $data['piuk_tgl']);
		$data['piuk_tgl'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";


		if ($id == 0) {
			$insert = $this->piutang_karyawan->simpan("kkeu_piutang_karyawan", $data);
		} else {
			$insert = $this->piutang_karyawan->update("kkeu_piutang_karyawan", array('piuk_id' => $id), $data);
		}
		$error = $this->db->error();
		if (!empty($error)) {
			$err = $error['message'];
		} else {
			$err = "";
		}
		if ($insert) {
			$resp['status'] = 1;
			$resp['desc'] = "Berhasil menyimpan data";
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "Ada kesalahan dalam penyimpanan!";
			$resp['error'] = $err;
		}
		echo json_encode($resp);
	}

	public function hapus($id)
	{
		$delete = $this->piutang_karyawan->delete('kkeu_piutang_karyawan', 'piuk_id', $id);

		if ($delete) {
			$resp['status'] = 1;
			$resp['desc'] = "<i class='fa fa-check-circle text-success'></i>&nbsp;&nbsp;&nbsp; Berhasil menghapus data";
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "<i class='fa fa-exclamation-circle text-danger'></i>&nbsp;&nbsp;&nbsp;Gagal menghapus data !";
		}
		echo json_encode($resp);
	}
}
