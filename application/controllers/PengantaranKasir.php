<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengantaranKasir extends CI_Controller
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
		$this->load->model('Model_PengantaranKasir', 'pengantarankasir');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		redirect(base_url("PengantaranKasir/tampil"));
	}


	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengguna");
		$ba = [
			'judul' => "Data Jangkauan Pengantaran",
			'subjudul' => "Jangkauan Pengantaran",
		];
		$d = [];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pengantarankasir', $d);
		$this->load->view('background_bawah');
		
	}
	public function ajax_list_pengantaran()
	{
		$list = $this->pengantarankasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pengantarankasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pengantarankasir->jngc_nama;
            $row[] = $pengantarankasir->jngc_jarak;
            $row[] = $pengantarankasir->jngc_ongkos;
            $row[] = "<a href='#' onClick='ubah_pengantaran(" . $pengantarankasir->jngc_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_pengantaran(" . $pengantarankasir->jngc_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pengantarankasir->count_all(),
			"recordsFiltered" => $this->pengantarankasir->count_filtered(),
			"data" => $data,
			"query" => $this->pengantarankasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('jngc_id');
		$data = $this->pengantarankasir->cari_pengantaran($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$data = $this->input->post();
		$id = $this->input->post('jngc_id');
		$nama = $this->input->post('jngc_nama');
		$jarak = $this->input->post('jngc_jarak');
		$ongkos = $this->input->post('jngc_ongkos');
				

		if ($id == 0) {
			$insert = $this->pengantarankasir->simpan("kspb_jangkauan", $data);
		} else {
			$insert = $this->pengantarankasir->update("kspb_jangkauan", array('jngc_id' => $id), $data);
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
		$delete = $this->pengantarankasir->delete('kspb_jangkauan', 'jngc_id', $id);

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
