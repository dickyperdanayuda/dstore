<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TokoProduksi extends CI_Controller
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
		$this->load->model('Model_TokoProduksi', 'tokoproduksi');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data TokoProduksi");
		$ba = [
			'judul' => "Data TokoProduksi",
			'subjudul' => "TokoProduksi",
		];
		$d = [];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('tokoproduksi', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_tokoproduksi()
	{
		$list = $this->tokoproduksi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tokoproduksi) {
			$no++;
			switch($tokoproduksi->tok_jenis)
			{
				case 1 : $jenis = "Toko Sendiri"; break;
				case 2 : $jenis = "Toko Mitra"; break;
				default : $jenis = "Toko Biasa"; break;
			}
			
			$row = array();
			$row[] = $no;
			$row[] = $tokoproduksi->tok_nama;
			$row[] = $tokoproduksi->tok_alamat;
			$row[] = $tokoproduksi->tok_telp;
			$row[] = $tokoproduksi->tok_pemilik;
			$row[] = $jenis;
			$row[] = $tokoproduksi->tok_status == 1 ? "<span class='badge bg-success'>Aktif</span>":"<span class='badge bg-dark'>Tidak Aktif</span>";
			$row[] = "<a href='#' onClick='ubah_tokoproduksi(" . $tokoproduksi->tok_id . ")' class='btn btn-dark btn-sm' title='Ubah data tokoproduksi'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_tokoproduksi(" . $tokoproduksi->tok_id . ")' class='btn btn-danger btn-sm' title='Hapus data tokoproduksi'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->tokoproduksi->count_all(),
			"recordsFiltered" => $this->tokoproduksi->count_filtered(),
			"data" => $data,
			"query" => $this->tokoproduksi->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('tok_id');
		$data = $this->tokoproduksi->cari_tokoproduksi($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('tok_id');
		$data = $this->input->post();

		if ($id == 0) {
			$insert = $this->tokoproduksi->simpan("prod_toko", $data);
		} else {
			$insert = $this->tokoproduksi->update("prod_toko", array('tok_id' => $id), $data);
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
		$delete = $this->tokoproduksi->delete('prod_toko', 'tok_id', $id);

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
