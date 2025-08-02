<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AkunKeuangan extends CI_Controller
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
		$this->load->model('Model_AkunKeuangan', 'akun');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Akun");
		$ba = [
			'judul' => "Data Akun",
			'subjudul' => "Akun",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('akunkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_akun()
	{
		$list = $this->akun->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $akun) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $akun->akn_jenis == 1 ? "Debit" : "Kredit";
			$row[] = $akun->akn_nama;
			$row[] = $akun->akn_kode;
			$row[] = $akun->akn_level;
			$row[] = $akun->akn_induk;
			$row[] = $akun->akn_entry;
			$row[] = "<a href='#' onClick='ubah_akun(" . $akun->akn_id . ")' class='btn btn-dark btn-sm' title='Ubah data akun'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_akun(" . $akun->akn_id . ")' class='btn btn-danger btn-sm' title='Hapus data akun'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->akun->count_all(),
			"recordsFiltered" => $this->akun->count_filtered(),
			"data" => $data,
			"query" => $this->akun->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('akn_id');
		$data = $this->akun->cari_akun($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('akn_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->akun->simpan("kkeu_akun", $data);
		} else {
			$insert = $this->akun->update("kkeu_akun", array('akn_id' => $id), $data);
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
		$delete = $this->akun->delete('kkeu_akun', 'akn_id', $id);

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
