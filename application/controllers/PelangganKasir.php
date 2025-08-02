<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PelangganKasir extends CI_Controller
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


		$this->load->model('Model_PelangganKasir', 'pelanggankasir');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		redirect(base_url("PelangganKasir/tampil"));
	}


	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pelanggan");
		$ba = [
			'judul' => "Data Pelanggan",
			'subjudul' => "Pelanggan",
		];
		$d = [];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pelanggankasir', $d);
		$this->load->view('background_bawah');
		
	}
	public function ajax_list_pelanggan()
	{
		$list = $this->pelanggankasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pelanggankasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pelanggankasir->plgc_nama;
            $row[] = $pelanggankasir->plgc_telp;
            $row[] = $pelanggankasir->plgc_alamat;
            $row[] = $pelanggankasir->plgc_tgl_bergabung;
            $row[] = "<a href='#' onClick='ubah_pelanggan(" . $pelanggankasir->plgc_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_pelanggan(" . $pelanggankasir->plgc_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pelanggankasir->count_all(),
			"recordsFiltered" => $this->pelanggankasir->count_filtered(),
			"data" => $data,
			"query" => $this->pelanggankasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('plgc_id');
		$data = $this->pelanggankasir->cari_pelanggan($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$data = $this->input->post();
		$id = $this->input->post('plgc_id');
		$nama = $this->input->post('plgc_nama');
		$telp = $this->input->post('plgc_telp');
		$alamat = $this->input->post('plgc_alamat');
		$tgl_bergabung = explode("/", $data['plgc_tgl_bergabung']);
		$tgl = "{$tgl_bergabung[2]}-{$tgl_bergabung[1]}-{$tgl_bergabung[0]}";

		$plgc_log_id = $this->session->userdata("id_user");
		$plgc_user = $this->session->userdata("username");
		
		$data = array(
				'plgc_id' => $id,
				'plgc_nama' => $nama,
				'plgc_telp' => $telp,
				'plgc_alamat' => $alamat,
				'plgc_tgl_bergabung' => $tgl,
				'plgc_log_id' => $this->session->userdata("id_user"),
				'plgc_user' => $this->session->userdata("username"),
		);
		
		if ($id == 0) {
			$insert = $this->pelanggankasir->simpan("kspb_pelanggan", $data);
		} else {
			$insert = $this->pelanggankasir->update("kspb_pelanggan", array('plgc_id' => $id), $data);
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
		$delete = $this->pelanggankasir->delete('kspb_pelanggan', 'plgc_id', $id);

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
