<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HargaProdukKasir extends CI_Controller
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
		$this->load->model('Model_HargaProdukKasir', 'hargaprodukkasir');
		$this->load->model('Model_ProdukPergudangan', 'produk'); 
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
			'judul' => "Data Pengguna",
			'subjudul' => "Pengguna",
		];
		$d =[ 'produk' => $this->produk->get_produk()
			];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('hargaprodukkasir', $d);
		$this->load->view('background_bawah');
		
	}
	public function ajax_list_harga_produk()
	{
		$list = $this->hargaprodukkasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $hargaprodukkasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $hargaprodukkasir->prdg_nama;

            $row[] = $hargaprodukkasir->hrgc_jml;
            $row[] = $hargaprodukkasir->hrgc_harga;
            $row[] = $hargaprodukkasir->hrgc_status == 1 ? "Digunakan" : "Tidak Digunakan";
            $row[] = "<a href='#' onClick='ubah_harga_produksi(" . $hargaprodukkasir->hrgc_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#'  onClick='hapus_harga_produksi(" . $hargaprodukkasir->hrgc_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->hargaprodukkasir->count_all(),
			"recordsFiltered" => $this->hargaprodukkasir->count_filtered(),
			"data" => $data,
			"query" => $this->hargaprodukkasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('hrgc_id');
		$data = $this->hargaprodukkasir->cari_harga_produk($id);
		echo json_encode($data);
	}
	public function get_harga_produk($produk)
    {
        $data = $this->hargaprodukkasir->get_harga_produk();
        if ($data) {
            $result = "<option Pilih {$hargaprodukkasir}";
            foreach ($data as $dt) {
                $result .= "<option value={$dt->id}>{$dt->nama}</option>";
            }
        }
        echo $result;
    }

    public function simpan()
	{
		$data = $this->input->post();
		$id = $this->input->post('hrgc_id');
		$id_prdp = $this->input->post('hrgc_prdp_id');
		$jml = $this->input->post('hrgc_jml');
		$harga = $this->input->post('hrgc_harga');
		$status = $this->input->post('hrgc_status');
		


		if ($id == 0) {
			$insert = $this->hargaprodukkasir->simpan("kspb_harga", $data);
		} else {
			$insert = $this->hargaprodukkasir->update("kspb_harga", array('hrgc_id' => $id), $data);
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
		$delete = $this->hargaprodukkasir->delete('kspb_harga', 'hrgc_id', $id);

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
