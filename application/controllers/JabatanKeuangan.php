<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JabatanKeuangan extends CI_Controller
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
		$this->load->model('Model_JabatanKeuangan', 'jabatan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Jabatan");
		$ba = [
			'judul' => "Data Jabatan",
			'subjudul' => "Jabatan",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('jabatankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_jabatan()
	{
		$list = $this->jabatan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $jabatan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $jabatan->jab_kode;
			$row[] = $jabatan->jab_nama;
			$row[] = "<a href='#' onClick='ubah_jabatan(" . $jabatan->jab_id . ")' class='btn btn-dark btn-sm' title='Ubah data jabatan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_jabatan(" . $jabatan->jab_id . ")' class='btn btn-danger btn-sm' title='Hapus data jabatan'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->jabatan->count_all(),
			"recordsFiltered" => $this->jabatan->count_filtered(),
			"data" => $data,
			"query" => $this->jabatan->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('jab_id');
		$data = $this->jabatan->cari_jabatan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('jab_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->jabatan->simpan("kkeu_jabatan", $data);
		} else {
			$insert = $this->jabatan->update("kkeu_jabatan", array('jab_id' => $id), $data);
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
		$delete = $this->jabatan->delete('kkeu_jabatan', 'jab_id', $id);

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
