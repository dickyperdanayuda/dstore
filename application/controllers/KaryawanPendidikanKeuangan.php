<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanPendidikanKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanPendidikanKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Pendidikan Karyawan");
		$ba = [
			'judul' => "Data Pendidikan Karyawan",
			'subjudul' => "Pendidikan Karyawan",
		];
		$d = [
			'kry_id' => $id,
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawan_pendidikankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan_pendidikan($id)
	{
		$list = $this->karyawan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->ddk_nama;
			$row[] = $karyawan->ddk_formal == 1 ? "Formal" : "Informal";
			$row[] = $karyawan->ddk_tgl_mulai;
			$row[] = $karyawan->ddk_tgl_selesai;
			$row[] = $karyawan->ddk_status == 1 ? "Selesai" : "Tidak Selesai";
			$row[] = $karyawan->ddk_sertifikat == 1 ? "Ada" : "Tidak Ada";
			$row[] = "<a href='#' onClick='ubah_karyawan_pendidikan(" . $karyawan->ddk_id . ")' class='btn btn-dark btn-sm' title='Ubah data pendidikan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan_kehalian(" . $karyawan->ddk_id . ")' class='btn btn-danger btn-sm' title='Hapus data pendidikan'><i class='fa fa-trash-alt'></i></a>";
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
		$id = $this->input->post('ddk_id');
		$data = $this->karyawan->cari_karyawan_pendidikan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('ddk_id');
		$data = $this->input->post();
		$tgl = explode("/", $data['ddk_tgl_mulai']);
		$data['ddk_tgl_mulai'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";

		$tgl2 = explode("/", $data['ddk_tgl_selesai']);
		$data['ddk_tgl_selesai'] = "{$tgl2[2]}-{$tgl2[1]}-{$tgl2[0]}";

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan_pendidikan", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan_pendidikan", array('ddk_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan_pendidikan', 'ddk_id', $id);

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
