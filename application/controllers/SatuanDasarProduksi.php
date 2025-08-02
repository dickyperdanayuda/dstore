<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SatuanDasarProduksi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("tokin"));
		}
		if ($this->session->userdata("level") > 2) {
			redirect(base_url("Dashboard"));
		}
		$this->load->model('Model_SatuanDasarProduksi', 'satuandasarproduksi');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data SatuanDasarProduksi");
		$ba = [
			'judul' => "Data SatuanDasarProduksi",
			'subjudul' => "SatuanDasarProduksi",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('satuandasarproduksi', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_satuandasarproduksi()
	{
		$list = $this->satuandasarproduksi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $satuandasarproduksi) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $satuandasarproduksi->stn_nama;
			$row[] = $satuandasarproduksi->stn_satuan;
			$row[] = "<a href='#' onClick='ubah_satuandasarproduksi(" . $satuandasarproduksi->stn_id . ")' class='btn btn-dark btn-sm' title='Ubah data satuandasarproduksi'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_satuandasarproduksi(" . $satuandasarproduksi->stn_id . ")' class='btn btn-danger btn-sm' title='Hapus data satuandasarproduksi'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->satuandasarproduksi->count_all(),
			"recordsFiltered" => $this->satuandasarproduksi->count_filtered(),
			"data" => $data,
			"query" => $this->satuandasarproduksi->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('stn_id');
		$data = $this->satuandasarproduksi->cari_satuandasarproduksi($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('stn_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->satuandasarproduksi->simpan("prod_satuan", $data);
		} else {
			$insert = $this->satuandasarproduksi->update("prod_satuan", array('stn_id' => $id), $data);
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
		$delete = $this->satuandasarproduksi->delete('prod_satuan', 'stn_id', $id);

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
