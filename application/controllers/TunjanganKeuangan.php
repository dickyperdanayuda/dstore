<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TunjanganKeuangan extends CI_Controller
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
		$this->load->model('Model_TunjanganKeuangan', 'tunjangan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Tunjangan");
		$ba = [
			'judul' => "Data Tunjangan",
			'subjudul' => "Tunjangan",
		];
		$d = [
			'kry_id' => $id
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('tunjangankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_tunjangan($id)
	{
		$list = $this->tunjangan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tunjangan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $tunjangan->kry_nama;
			$row[] = $tunjangan->tjg_item;
			$row[] = $tunjangan->tjg_bulan;
			$row[] = $tunjangan->tjg_tahun;
			$row[] = "Rp. " . number_format($tunjangan->tjg_jml, 0);
			$row[] = "<a href='#' onClick='ubah_tunjangan(" . $tunjangan->tjg_id . ")' class='btn btn-dark btn-sm' title='Ubah data tunjangan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_tunjangan(" . $tunjangan->tjg_id . ")' class='btn btn-danger btn-sm' title='Hapus data tunjangan'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->tunjangan->count_all($id),
			"recordsFiltered" => $this->tunjangan->count_filtered($id),
			"data" => $data,
			"query" => $this->tunjangan->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('tjg_id');
		$data = $this->tunjangan->cari_tunjangan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('tjg_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->tunjangan->simpan("kkeu_tunjangan", $data);
		} else {
			$insert = $this->tunjangan->update("kkeu_tunjangan", array('tjg_id' => $id), $data);
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
		$delete = $this->tunjangan->delete('kkeu_tunjangan', 'tjg_id', $id);

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
