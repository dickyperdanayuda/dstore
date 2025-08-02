<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SatuanTampilProduksi extends CI_Controller
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
		$this->load->model('Model_SatuanTampilProduksi', 'satuantampilproduksi');
		$this->load->model('Model_SatuanDasarProduksi', 'satuandasarproduksi');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data SatuanTampilProduksi");
		$ba = [
			'judul' => "Data SatuanTampilProduksi",
			'subjudul' => "SatuanTampilProduksi",
		];
		$d = [
		'satuan' => $this->satuandasarproduksi->get_satuandasarproduksi()
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('satuantampilproduksi', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_satuantampilproduksi()
	{
		$list = $this->satuantampilproduksi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $satuantampilproduksi) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $satuantampilproduksi->stn_satuan;
			$row[] = $satuantampilproduksi->stnt_tampil;
			$row[] = "1:{$satuantampilproduksi->stnt_pembanding}";
			$row[] = "<a href='#' onClick='ubah_satuantampilproduksi(" . $satuantampilproduksi->stnt_id . ")' class='btn btn-dark btn-sm' title='Ubah data satuantampilproduksi'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_satuantampilproduksi(" . $satuantampilproduksi->stnt_id . ")' class='btn btn-danger btn-sm' title='Hapus data satuantampilproduksi'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->satuantampilproduksi->count_all(),
			"recordsFiltered" => $this->satuantampilproduksi->count_filtered(),
			"data" => $data,
			"query" => $this->satuantampilproduksi->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('stnt_id');
		$data = $this->satuantampilproduksi->cari_satuantampilproduksi($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('stnt_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->satuantampilproduksi->simpan("prod_satuan_tampil", $data);
		} else {
			$insert = $this->satuantampilproduksi->update("prod_satuan_tampil", array('stnt_id' => $id), $data);
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
		$delete = $this->satuantampilproduksi->delete('prod_satuan_tampil', 'stnt_id', $id);

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
