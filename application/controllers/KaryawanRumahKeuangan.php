<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanRumahKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanRumahKeuangan', 'karyawan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Rumah Karyawan");
		$ba = [
			'judul' => "Data Rumah Karyawan",
			'subjudul' => "Rumah Karyawan",
		];
		$d = [
			'kry_id' => $id,
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawan_rumahkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan_rumah($id)
	{
		$list = $this->karyawan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$jenis = "";
			$tipe = "";
			switch ($karyawan->rmh_jenis) {
				case 1:
					$jenis = "Pribadi";
					break;
				case 2:
					$jenis = "Sewa";
					break;
				case 3:
					$jenis = "Menumpang";
					break;
			}
			switch ($karyawan->rmh_type) {
				case 1:
					$tipe = "Rumah Bulatan";
					break;
				case 2:
					$tipe = "Rumah Petak";
					break;
				case 3:
					$tipe = "Apartemen";
					break;
				case 4:
					$tipe = "Kamar";
					break;
			}
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->rmh_alamat;
			$row[] = $karyawan->rmh_maps;
			$row[] = $jenis;
			$row[] = $tipe;
			$row[] = "<a href='#' onClick='ubah_karyawan_rumah(" . $karyawan->rmh_id . ")' class='btn btn-dark btn-sm' title='Ubah data rumah'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan_kehalian(" . $karyawan->rmh_id . ")' class='btn btn-danger btn-sm' title='Hapus data rumah'><i class='fa fa-trash-alt'></i></a>";
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
		$id = $this->input->post('rmh_id');
		$data = $this->karyawan->cari_karyawan_rumah($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('rmh_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan_rumah", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan_rumah", array('rmh_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan_rumah', 'rmh_id', $id);

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
