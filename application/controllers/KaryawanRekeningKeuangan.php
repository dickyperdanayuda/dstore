<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanRekeningKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanRekeningKeuangan', 'karyawan');
		$this->load->model('Model_BankKeuangan', 'bank');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Rekening Karyawan");
		$ba = [
			'judul' => "Data Rekening Karyawan",
			'subjudul' => "Rekening Karyawan",
		];
		$d = [
			'kry_id' => $id,
			'bank' => $this->bank->get_bank(),
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawan_rekeningkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan_rekening($id)
	{
		$list = $this->karyawan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->bnk_nama;
			$row[] = $karyawan->rekk_nomor;
			$row[] = "<a href='#' onClick='ubah_karyawan_rekening(" . $karyawan->rekk_id . ")' class='btn btn-dark btn-sm' title='Ubah data rekening'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan_kehalian(" . $karyawan->rekk_id . ")' class='btn btn-danger btn-sm' title='Hapus data rekening'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->karyawan->count_all($id),
			"recordsFiltered" => $this->karyawan->count_filtered($id),
			"data" => $data,
			"query" => $this->karyawan->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('rekk_id');
		$data = $this->karyawan->cari_karyawan_rekening($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('rekk_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan_rekening", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan_rekening", array('rekk_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan_rekening', 'rekk_id', $id);

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
