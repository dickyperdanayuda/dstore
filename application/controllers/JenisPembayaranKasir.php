<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisPembayaranKasir extends CI_Controller
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
		$this->load->model('Model_JenisPembayaranKasir', 'jenispembayarankasir');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		redirect(base_url("JenisPembayaranKasir/tampil"));
	}


	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengguna");
		$ba = [
			'judul' => "Data Jenis Pembayaran",
			'subjudul' => "Jenis Pembayaran",
		];
		$d = [];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('jenispembayarankasir', $d);
		$this->load->view('background_bawah');
		
	}
	public function ajax_list_jenispembayaran()
	{
		$list = $this->jenispembayarankasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $jenispembayarankasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $jenispembayarankasir->jpbc_id;
            $row[] = $jenispembayarankasir->jpbc_nama;
            $row[] = "<a href='#' onClick='ubah_jenis_pembayaran(" . $jenispembayarankasir->jpbc_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_jenis_pembayaran(" . $jenispembayarankasir->jpbc_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->jenispembayarankasir->count_all(),
			"recordsFiltered" => $this->jenispembayarankasir->count_filtered(),
			"data" => $data,
			"query" => $this->jenispembayarankasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('jpbc_id');
		$data = $this->jenispembayarankasir->cari_jenis_pembayaran($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$data = $this->input->post();
		$id = $this->input->post('jpbc_id');
		$nama = $this->input->post('jpbc_nama');
		

		if ($id == 0) {
			$insert = $this->jenispembayarankasir->simpan("kspb_jenis_pembayaran", $data);
		} else {
			$insert = $this->jenispembayarankasir->update("kspb_jenis_pembayaran", array('jpbc_id' => $id), $data);
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
		$delete = $this->jenispembayarankasir->delete('kspb_jenis_pembayaran', 'jpbc_id', $id);

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
