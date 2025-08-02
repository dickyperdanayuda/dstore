<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanProdukKasir extends CI_Controller
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
		$this->load->model('Model_PenjualanProdukKasir', 'penjualanprodukkasir');
		$this->load->model('Model_PelangganKasir', 'pelanggankasir'); 
		$this->load->model('Model_JenisPembayaranKasir', 'jenispembayarankasir');

		$this->load->model('Model_ProdukPergudangan', 'produk');
		date_default_timezone_set('Asia/Jakarta');
	}

	

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengguna");
		$ba = [
			'judul' => "Data Pengguna",
			'subjudul' => "Pengguna",
		];
		$d = [ 'pelanggankasir' => $this->pelanggankasir->get_pelanggan(),
				'jenispembayarankasir' => $this->jenispembayarankasir->get_jenispembayaran(),
				'produk' => $this->produk->get_produk()
			];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('penjualanprodukkasir', $d);
		$this->load->view('background_bawah');
		
	}
	public function ajax_list_penjualan()
	{
		$list = $this->penjualanprodukkasir->get_datatables(); 
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $penjualanprodukkasir) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $penjualanprodukkasir->pjlc_tgl;
            $row[] = $penjualanprodukkasir->pjlc_no_faktur;
            $row[] = $penjualanprodukkasir->plgc_nama;
            $row[] = "<a href='#' onClick='ubah_pelanggan(" . $penjualanprodukkasir->pjlc_id . ")' class='btn btn-info btn-sm' title='Ubah data pengguna'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_pelanggan(" . $penjualanprodukkasir->pjlc_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengguna'><i class='fa fa-trash-alt'></i></a>";
            $data[] = $row;
        }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->penjualanprodukkasir->count_all(),
			"recordsFiltered" => $this->penjualanprodukkasir->count_filtered(),
			"data" => $data,
			"query" => $this->penjualanprodukkasir->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('plgc_id');
		$data = $this->penjualanprodukkasir->cari_pelanggan($id);
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
			$insert = $this->penjualanprodukkasir->simpan("kspb_pelanggan", $data);
		} else {
			$insert = $this->penjualanprodukkasir->update("kspb_pelanggan", array('plgc_id' => $id), $data);
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

	public function view_penjualandetail()
	{
		$jpjd = $this->input->post('jpjd');
		$d = [
			'list_penjualandetail' => $jpjd
		];
		$this->load->helper('url');
		$this->load->view('penjualanprodukkasir', $d);
	}
	
	public function hapus($id)
	{
		$delete = $this->penjualanprodukkasir->delete('kspb_pelanggan', 'plgc_id', $id);

		if ($delete) {
			$resp['status'] = 1;
			$resp['desc'] = "<i class='fa fa-check-circle text-success'></i>&nbsp;&nbsp;&nbsp; Berhasil menghapus data";
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "<i class='fa fa-exclamation-circle text-danger'></i>&nbsp;&nbsp;&nbsp;Gagal menghapus data !";
		}
		echo json_encode($resp);
	}
	public function get_produk($id)
	{
		$produk = $this->produk->get_produk($id);
		
	}


	

	
}
