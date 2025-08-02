<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenggajianKeuangan extends CI_Controller
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
		$this->load->model('Model_PenggajianKeuangan', 'penggajian');
		$this->load->model('Model_KaryawanKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Penggajian");
		$ba = [
			'judul' => "Data Penggajian",
			'subjudul' => "Penggajian",
		];
		$d = [
			'karyawan' => $this->karyawan->get_karyawan(),
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('penggajiankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_penggajian()
	{
		$list = $this->penggajian->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $penggajian) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $penggajian->gji_kry_nama;
			$row[] = $penggajian->gji_bulan;
			$row[] = $penggajian->gji_tahun;
			$row[] = $penggajian->gji_pokok;
			$row[] = $penggajian->gji_tunjangan;
			$row[] = $penggajian->gji_potongan;
			$row[] = $penggajian->gji_total;
			$row[] = $penggajian->gji_metode_bayar == 1 ? "Cash" : "Transfer";
			$row[] = $penggajian->gji_pemberi;
			$row[] = $penggajian->gji_penerima;
			$row[] = $penggajian->gji_bank_penerima;
			$row[] = $penggajian->gji_rekening_penerima;
			$row[] = "<a href='#' onClick='ubah_penggajian(" . $penggajian->gji_id . ")' class='btn btn-dark btn-sm' title='Ubah data penggajian'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_penggajian(" . $penggajian->gji_id . ")' class='btn btn-danger btn-sm' title='Hapus data penggajian'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->penggajian->count_all(),
			"recordsFiltered" => $this->penggajian->count_filtered(),
			"data" => $data,
			"query" => $this->penggajian->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('gji_id');
		$data = $this->penggajian->cari_penggajian($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('gji_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->penggajian->simpan("kkeu_penggajian", $data);
		} else {
			$insert = $this->penggajian->update("kkeu_penggajian", array('gji_id' => $id), $data);
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
		$delete = $this->penggajian->delete('kkeu_penggajian', 'gji_id', $id);

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
