<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PiutangBayarKeuangan extends CI_Controller
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
		$this->load->model('Model_PiutangBayarKeuangan', 'piutang_bayar');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil($id)
	{
		$this->session->set_userdata("judul", "Data Pembayaran Piutang");
		$ba = [
			'judul' => "Data Pembayaran Piutang",
			'subjudul' => "Pembayaran Piutang",
		];
		$d = [
			'piuk_id' => $id
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('piutang_bayarkeuangan', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_piutang_bayar($id)
	{
		$list = $this->piutang_bayar->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $piutang_bayar) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $piutang_bayar->piub_tgl;
			$row[] = "Rp. " . number_format($piutang_bayar->piub_jml, 0);
			$row[] = $piutang_bayar->piub_penerima;
			$row[] = "<a href='#' onClick='ubah_piutang_bayar(" . $piutang_bayar->piub_id . ")' class='btn btn-dark btn-sm' title='Ubah data piutang_bayar'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_piutang_bayar(" . $piutang_bayar->piub_id . ")' class='btn btn-danger btn-sm' title='Hapus data piutang_bayar'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->piutang_bayar->count_all($id),
			"recordsFiltered" => $this->piutang_bayar->count_filtered($id),
			"data" => $data,
			"query" => $this->piutang_bayar->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('piub_id');
		$data = $this->piutang_bayar->cari_piutang_bayar($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('piub_id');
		$data = $this->input->post();
		$tgl = explode("/", $data['piub_tgl']);
		$data['piub_tgl'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";

		if ($id == 0) {
			$insert = $this->piutang_bayar->simpan("kkeu_piutang_bayar", $data);
		} else {
			$insert = $this->piutang_bayar->update("kkeu_piutang_bayar", array('piub_id' => $id), $data);
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
		$delete = $this->piutang_bayar->delete('kkeu_piutang_bayar', 'piub_id', $id);

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
