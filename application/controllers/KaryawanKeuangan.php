<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KaryawanKeuangan extends CI_Controller
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
		$this->load->model('Model_KaryawanKeuangan', 'karyawan');
		$this->load->model('Model_JabatanKeuangan', 'jabatan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Karyawan");
		$ba = [
			'judul' => "Data Karyawan",
			'subjudul' => "Karyawan",
		];
		$d = [
			'jabatan' => $this->jabatan->get_jabatan(),
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('karyawankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_karyawan()
	{
		$list = $this->karyawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $karyawan) {
			$no++;
			$status = "";
			switch ($karyawan->kry_status_nikah) {
				case 1:
					$status = "Single";
					break;
				case 2:
					$status = "Menikah";
					break;
				case 3:
					$status = "Duda";
					break;
				case 4:
					$status = "Janda";
					break;
			}
			$row = array();
			$row[] = $no;
			$row[] = $karyawan->kry_nama;
			$row[] = $karyawan->kry_jk == 1 ? "Laki-Laki" : "Perempuan";
			$row[] = $karyawan->kry_telp;
			$row[] = $status;
			$row[] = $karyawan->jab_nama;
			$row[] = $karyawan->kry_jenis_gaji == 1 ? "Cash" : "Transfer";
			$row[] = "<a href='../KaryawanKeahlianKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Keahlian Karyawan'><i class='fa fa-award'></i></a>&nbsp;<a href='../KaryawanPendapatanKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Pendapatan Karyawan'><i class='fa fa-hand-holding-usd'></i></a>&nbsp;<a href='../KaryawanPendidikanKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Pendidikan Karyawan'><i class='fa fa-user-graduate'></i></a>&nbsp;<a href='../KaryawanRekeningKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Rekening Karyawan'><i class='fa fa-credit-card'></i></a>&nbsp;<a href='../KaryawanRumahKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Rumah Karyawan'><i class='fa fa-home'></i></a>&nbsp;<a href='../KaryawanTanggunganKeuangan/tampil/" . $karyawan->kry_id . "' class='btn btn-dark btn-sm' title='Tanggungan Karyawan'><i class='fa fa-users'></i></a>&nbsp;<a href='#' onClick='ubah_karyawan(" . $karyawan->kry_id . ")' class='btn btn-dark btn-sm' title='Ubah data karyawan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_karyawan(" . $karyawan->kry_id . ")' class='btn btn-danger btn-sm' title='Hapus data karyawan'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->karyawan->count_all(),
			"recordsFiltered" => $this->karyawan->count_filtered(),
			"data" => $data,
			"query" => $this->karyawan->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('kry_id');
		$data = $this->karyawan->cari_karyawan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('kry_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->karyawan->simpan("kkeu_karyawan", $data);
		} else {
			$insert = $this->karyawan->update("kkeu_karyawan", array('kry_id' => $id), $data);
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
		$delete = $this->karyawan->delete('kkeu_karyawan', 'kry_id', $id);

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
