<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanPendapatanKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanPendapatanKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Pendapatan Karyawan");
		$ba = [
			'judul' => "Data Pendapatan Karyawan",
			'subjudul' => "Pendapatan Karyawan",
		];
		$d = [
			'kry_id' => $id,
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawan_pendapatankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan_pendapatan($id)
	{
		$list = $this->karyawan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->dpt_item;
			$row[] = "Rp. " . number_format($karyawan->dpt_jml, 0);
			$row[] = $karyawan->dpt_status == 1 ? "Aktif" : "Tidak Aktif";
			$row[] = "<a href='#' onClick='ubah_karyawan_pendapatan(" . $karyawan->dpt_id . ")' class='btn btn-dark btn-sm' title='Ubah data pendapatan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan_kehalian(" . $karyawan->dpt_id . ")' class='btn btn-danger btn-sm' title='Hapus data pendapatan'><i class='fa fa-trash-alt'></i></a>";
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
		$id = $this->input->post('dpt_id');
		$data = $this->karyawan->cari_karyawan_pendapatan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('dpt_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan_pendapatan", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan_pendapatan", array('dpt_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan_pendapatan', 'dpt_id', $id);

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
