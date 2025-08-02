<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanTanggunganKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanTanggunganKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Tanggungan Karyawan");
		$ba = [
			'judul' => "Data Tanggungan Karyawan",
			'subjudul' => "Tanggungan Karyawan",
		];
		$d = [
			'kry_id' => $id,
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawan_tanggungankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan_tanggungan($id)
	{
		$list = $this->karyawan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$usia = "";
			switch ($karyawan->tgn_rentang_usia) {
				case 1:
					$usia = "Bayi";
					break;
				case 2:
					$usia = "Anak-Anak";
					break;
				case 3:
					$usia = "Remaja";
					break;
				case 4:
					$usia = "Dewasa";
					break;
				case 5:
					$usia = "Tua";
					break;
			}
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->tgn_nama;
			$row[] = $karyawan->tgn_hubungan;
			$row[] = $usia;
			$row[] = "<a href='#' onClick='ubah_karyawan_tanggungan(" . $karyawan->tgn_id . ")' class='btn btn-dark btn-sm' title='Ubah data tanggungan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan_kehalian(" . $karyawan->tgn_id . ")' class='btn btn-danger btn-sm' title='Hapus data tanggungan'><i class='fa fa-trash-alt'></i></a>";
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
		$id = $this->input->post('tgn_id');
		$data = $this->karyawan->cari_karyawan_tanggungan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('tgn_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan_tanggungan", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan_tanggungan", array('tgn_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan_tanggungan', 'tgn_id', $id);

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
