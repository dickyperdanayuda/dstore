<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BankKeuangan extends CI_Controller
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
		$this->load->model('Model_BankKeuangan', 'bank');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Bank");
		$ba = [
			'judul' => "Data Bank",
			'subjudul' => "Bank",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('bankkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_bank()
	{
		$list = $this->bank->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bank) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $bank->bnk_kode;
			$row[] = $bank->bnk_nama;
			$row[] = $bank->bnk_nama_umum;
			$row[] = "<a href='#' onClick='ubah_bank(" . $bank->bnk_id . ")' class='btn btn-dark btn-sm' title='Ubah data bank'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_bank(" . $bank->bnk_id . ")' class='btn btn-danger btn-sm' title='Hapus data bank'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->bank->count_all(),
			"recordsFiltered" => $this->bank->count_filtered(),
			"data" => $data,
			"query" => $this->bank->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('bnk_id');
		$data = $this->bank->cari_bank($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('bnk_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->bank->simpan("kkeu_bank", $data);
		} else {
			$insert = $this->bank->update("kkeu_bank", array('bnk_id' => $id), $data);
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
		$delete = $this->bank->delete('kkeu_bank', 'bnk_id', $id);

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
