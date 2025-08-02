<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JurnalUmumKeuangan extends CI_Controller
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
		$this->load->model('Model_JurnalUmumKeuangan', 'jurnal_umum');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Jurnal Umum");
		$ba = [
			'judul' => "Data Jurnal Umum",
			'subjudul' => "Jurnal Umum",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('jurnal_umumkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_jurnal_umum()
	{
		$list = $this->jurnal_umum->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $jurnal_umum) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $jurnal_umum->jur_jenis;
			$row[] = $jurnal_umum->akn_nama;
			$row[] = $jurnal_umum->jur_tgl;
			$row[] = $jurnal_umum->jur_jml;
			$row[] = $jurnal_umum->jur_saldo;
			$row[] = $jurnal_umum->jur_status == 0 ? "Invalid" : "Valid";
			$row[] = $jurnal_umum->jur_group;
			$row[] = "<a href='#' onClick='ubah_jurnal_umum(" . $jurnal_umum->jur_id . ")' class='btn btn-dark btn-sm' title='Ubah data jurnal_umum'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_jurnal_umum(" . $jurnal_umum->jur_id . ")' class='btn btn-danger btn-sm' title='Hapus data jurnal_umum'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->jurnal_umum->count_all(),
			"recordsFiltered" => $this->jurnal_umum->count_filtered(),
			"data" => $data,
			"query" => $this->jurnal_umum->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('jur_id');
		$data = $this->jurnal_umum->cari_jurnal_umum($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('jur_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->jurnal_umum->simpan("kkeu_jurnal_umum", $data);
		} else {
			$insert = $this->jurnal_umum->update("kkeu_jurnal_umum", array('jur_id' => $id), $data);
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
		$delete = $this->jurnal_umum->delete('kkeu_jurnal_umum', 'jur_id', $id);

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
