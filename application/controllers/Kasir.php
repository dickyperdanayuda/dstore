 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends CI_Controller
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


		$this->load->model('Model_Kasir', 'kasir');
		$this->load->model('Model_ProdukPergudangan', 'produk');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		redirect(base_url("Kasir/tampil"));
	}


	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pelanggan");
		$ba = [
			'judul' => "Data Pelanggan",
			'subjudul' => "Pelanggan",
		];
		// $d = [];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('kasir');
		$this->load->view('background_bawah');
		
	}
	public function cek_harga($id)
	{	
		$this->db->from("kspb_harga");
		$this->db->where("hrgc_prdp_id", $id);
		$this->db->where("hrgc_status = 1");
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}
	public function load_produk()
	{

		$allproduk = $this->kasir->get_produk();
		$bigpic = "";
		$produknya = array();
		foreach ($allproduk as $produk) {
			$bigpics = explode(",", $produk->prdg_foto);
			$bigpic = $produk->prdg_foto;
			if (count($bigpics) > 0) {
				$bigpic = $bigpics[0];
			}
			if ($bigpic) {
				$biggbr = base_url('assets/images/produk/' . $bigpic);
			} else {
				$biggbr = base_url('assets/dist/img/no-image.jpg');
			}
			if ($produk->stok) {
				$untukproduk = new \stdClass();
				// $harga = $this->kasir->cek_harga($produk->prd_id);
				$untukproduk->id = $produk->prdg_id;
				$untukproduk->barcode = $produk->prdg_barcode;
				$untukproduk->diskon = 0;
				// $untukproduk->stok = $produk->stok;
				// $untukproduk->modal = $harga->stk_modal;
				// $untukproduk->hpp = $produk->stk_hpp;
				// $untukproduk->gbr = $biggbr;
				// $untukproduk->harga = $produk->hrgc_harga;
				$untukproduk->nama = $produk->prdg_nama;
				$untukproduk->isi = $produk->prdg_isi;
				$untukproduk->satuan = $produk->prdg_satuan_isi;
				$untukproduk->ukuran = $produk->prdg_deskripsi;
				$untukproduk->kemasan = $produk->prdg_kemasan;
				$produknya[] = $untukproduk;
			}
			
		}
		// print_r($produknya);die();
		echo json_encode($produknya);
	}

	public function cari()
	{
		$id = $this->input->post('prdg_id');
		$data = $this->produk->cari_produk($id);
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
