<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengeluaranKeuangan extends CI_Controller
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
		$this->load->model('Model_PengeluaranKeuangan', 'pengeluaran');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengeluaran");
		$ba = [
			'judul' => "Data Pengeluaran",
			'subjudul' => "Pengeluaran",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pengeluarankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_pengeluaran()
	{
		$list = $this->pengeluaran->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pengeluaran) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $pengeluaran->klr_tgl;
			$row[] = $pengeluaran->klr_keperluan;
			$row[] = "Rp. " . number_format($pengeluaran->klr_jml, 0);
			$row[] = "<a href='#' onClick='ubah_pengeluaran(" . $pengeluaran->klr_id . ")' class='btn btn-dark btn-sm' title='Ubah data pengeluaran'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_pengeluaran(" . $pengeluaran->klr_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengeluaran'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pengeluaran->count_all(),
			"recordsFiltered" => $this->pengeluaran->count_filtered(),
			"data" => $data,
			"query" => $this->pengeluaran->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('klr_id');
		$data = $this->pengeluaran->cari_pengeluaran($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('klr_id');
		$data = $this->input->post();
		$tgl = explode("/", $data['klr_tgl']);
		$data['klr_tgl'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";

		if ($id == 0) {
			$insert = $this->pengeluaran->simpan("kkeu_pengeluaran", $data);
		} else {
			$insert = $this->pengeluaran->update("kkeu_pengeluaran", array('klr_id' => $id), $data);
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
		$delete = $this->pengeluaran->delete('kkeu_pengeluaran', 'klr_id', $id);

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
