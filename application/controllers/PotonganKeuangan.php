<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PotonganKeuangan extends CI_Controller
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
		$this->load->model('Model_PotonganKeuangan', 'potongan');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Potongan");
		$ba = [
			'judul' => "Data Potongan",
			'subjudul' => "Potongan",
		];
		$d = [
			'kry_id' => $id,
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('potongankeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_potongan($id)
	{
		$list = $this->potongan->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $potongan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $potongan->kry_nama;
			$row[] = $potongan->ptg_item;
			$row[] = $potongan->ptg_bulan;
			$row[] = $potongan->ptg_tahun;
			$row[] = $potongan->ptg_jml;
			$row[] = "<a href='#' onClick='ubah_potongan(" . $potongan->ptg_id . ")' class='btn btn-dark btn-sm' title='Ubah data potongan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_potongan(" . $potongan->ptg_id . ")' class='btn btn-danger btn-sm' title='Hapus data potongan'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->potongan->count_all($id),
			"recordsFiltered" => $this->potongan->count_filtered($id),
			"data" => $data,
			"query" => $this->potongan->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('ptg_id');
		$data = $this->potongan->cari_potongan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('ptg_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->potongan->simpan("kkeu_potongan", $data);
		} else {
			$insert = $this->potongan->update("kkeu_potongan", array('ptg_id' => $id), $data);
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
		$delete = $this->potongan->delete('kkeu_potongan', 'ptg_id', $id);

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
