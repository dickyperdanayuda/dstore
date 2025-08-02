<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DiskonKasir extends CI_Controller
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
		$this->load->model('Model_DiskonKasir', 'diskonkasir');
		$this->load->model('Model_PelangganKasir', 'pelanggankasir'); 
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		redirect(base_url("DiskonKasir/tampil"));
	}


	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengguna");
		$ba = [
			'judul' => "Diskon",
			'subjudul' => "Pelanggan",
		];
		$d =[ 'pelanggankasir' => $this->pelanggankasir->get_pelanggan()
			];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('diskonkasir', $d);
		$this->load->view('background_bawah');
		
	}

	public function ajax_list_diskon()
	{
		$list = $this->diskonkasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $diskonkasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $diskonkasir->plgc_nama;
            
            if ($diskonkasir->dspl_jenis == 1) {
                $row[] = "Persen";
            } else if ($diskonkasir->dspl_jenis == 2) {
                $row[] = "Nominal";
            }

            $row[] = $diskonkasir->dspl_jml;
            $row[] = $diskonkasir->dspl_status == 1 ? "Aktif" : "Tidak Aktif";
            $row[] = $diskonkasir->dspl_keterangan;
            $row[] = "<a href='#' onClick='ubah_diskon(" . $diskonkasir->dspl_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_diskon(" . $diskonkasir->dspl_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->diskonkasir->count_all(),
			"recordsFiltered" => $this->diskonkasir->count_filtered(),
			"data" => $data,
			"query" => $this->diskonkasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('dspl_id');
		$data = $this->diskonkasir->cari_diskon($id);
		echo json_encode($data);
	}
	
	public function simpan()
	{
		$data = $this->input->post();
		$id = $this->input->post('dspl_id');
		$id_plgc = $this->input->post('dspl_plgc_id');
		$jenis = $this->input->post('dspl_jenis');
		$jumlah = $this->input->post('dspl_jml');
		$status = $this->input->post('dspl_status');
		$keterangan = $this->input->post('dspl_keterangan');
		


		if ($id == 0) {
			$insert = $this->diskonkasir->simpan("kspb_diskon_pelanggan", $data);
		} else {
			$insert = $this->diskonkasir->update("kspb_diskon_pelanggan", array('dspl_id' => $id), $data);
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
		$delete = $this->diskonkasir->delete('kspb_diskon_pelanggan', 'dspl_id', $id);

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
