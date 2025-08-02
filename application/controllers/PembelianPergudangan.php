<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembelianPergudangan extends CI_Controller
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

		$this->load->library('upload');
		$this->load->model('Model_PembelianPergudangan', 'pembelian');
		// $this->load->model('Model_Penjualan', 'penjualan');
		$this->load->model('Model_ProdukPergudangan', 'produk');
		$this->load->model('Model_SupplierPergudangan', 'supplier');
		// $this->load->model('Model_Pengguna', 'pengguna');
		date_default_timezone_set('Asia/Jakarta');
	}

	//Pembelian	
	public function tampil()
	{

		$this->session->set_userdata("judul", "Data Master");
		$ba = [
			'judul' => "Data Master",
			'subjudul' => "Pembelian",
		];

		$produk = $this->produk->get_produk();
		$supplier = $this->supplier->get_supplier();

		$d = [
			'produk' => $produk,
			'supplier' => $supplier,
		];

		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pembelianpergudangan', $d);
		$this->load->view('background_bawah');
	}

	public function view_pembeliandetail()
	{
		$jpblg = $this->input->post('jpblg');
		$d = [
			'list_pembeliandetail' => $jpblg
		];
		$this->load->helper('url');
		$this->load->view('vpembeliandetailpergudangan', $d);
	}

	public function ajax_list_pembelian()
	{
		$list = $this->pembelian->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pembelian) {
			$no++;
			$produk = "-";
			$tgl_pembelian = "-";
			$status="-";
			$disbtn = "";
			$jumlah_beli="";
			if ($this->session->userdata("level") > 1) {
				$disbtn = "disabled";
			}
			if (($pembelian->pblg_tgl) && ($pembelian->pblg_tgl != '0000-00-00')) {
				$tgl_pembelian = $this->pembelian->tanggal($pembelian->pblg_tgl);
			}
			if($pembelian->pblg_status==1){
				$status="Cash";
			} else {
				$status="Hutang";
			}
			$prd = $this->produk->cari_produk($pembelian->pblg_prd_id);
			if ($prd) {
				$produk = $prd->prd_nama . " " . $prd->prd_ukuran . " " . $prd->prd_satuan . " (" . $prd->prd_isi . " " . $prd->prd_kemasan . ")";
			}
			$supplier = "-";
			$spl = $this->supplier->cari_supplier($pembelian->pblg_spl_id);
			if ($spl) {
				$supplier = $spl->spl_nama;
			}
			$pengguna = "-";
			$pgn = $this->pengguna->get_pengguna($pembelian->pblg_log_id);
			if ($pgn) {
				$pengguna = $pgn->log_user;
			}
			$row = array();
			$row[] = $no;
			$row[] = $tgl_pembelian;
			$row[] = $produk;
			$row[] = $supplier;
			// $row[] = $pembelian->pblg_jml;
			$row[] = number_format($pembelian->pblg_harga_beli, 0, ",", ".");
			$row[] = number_format($pembelian->pblg_bayar, 0, ",", ".");
			$row[] = $status;
			$row[] = $pengguna;
			$row[] = "<a href='#' onClick='hapus_pembelian(" . $pembelian->pblg_id . ")' class='btn btn-danger {$disbtn}' title='Hapus data Pembelian'><i class='fa fa-times'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pembelian->count_all(),
			"recordsFiltered" => $this->pembelian->count_filtered(),
			"data" => $data,
			"query" => $this->pembelian->getlastquery(),
		);
		//output to json format
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('pblg_id');
		$data = $this->pembelian->cari_pembelian($id);
		echo json_encode($data);
	}

	public function cari_harga($id, $jml)
	{
		$data = $this->penjualan->cari_harga($id, $jml);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('pblg_id');
		$pblg_pembeliandetail = $this->input->post('pblg_pembeliandetail');
		$pblg_spl_id = $this->input->post('pblg_spl_id');
		$tgls = explode("/", $this->input->post('pblg_tgl'));
		$pblg_tgl = "{$tgls[2]}-{$tgls[1]}-{$tgls[0]}";
		$det = explode(";", $pblg_pembeliandetail);
		$insert = 0;
		for ($i = 1; $i < count($det); $i++) {
			$val = explode("-", $det[$i]);
			$data = array(
				'pblg_prd_id' => $val[0],
				'pblg_spl_id' => $pblg_spl_id,
				'pblg_harga_modal' => $val[2],
				'pblg_hpp' => $val[3],
				'pblg_jml' => $val[1],
				'pblg_catatan' => $val[7],
				'pblg_tgl' => $pblg_tgl,
				'pblg_waktu' => date('Y-m-d H:i:s'),
			);
			$insert = $this->pembelian->simpan("zps_pembelian", $data);
			if ($insert) {
				$dstok = array(
					'stk_prd_id' => $val[0],
					'stk_pblg_id' => $insert,
					'stk_modal' => $val[2],
					'stk_hpp' => $val[3],
					'stk_jml' => $val[1],
					'stk_terjual' => 0,
					'stk_waktu' => date('Y-m-d H:i:s'),
				);
				$this->pembelian->simpan('zps_stok', $dstok);
			}
			if ($val[5] > 0) {
				$dharga = array(
					'hrg_prd_id' => $val[0],
					'hrg_jml' => 1,
					'hrg_satuan' => $val[6],
					'hrg_status' => 1
				);
				$this->produk->update('zps_harga', array("hrg_id" => $val[5]), $dharga);
			} else {
				$dharga = array(
					'hrg_prd_id' => $val[0],
					'hrg_jml' => 1,
					'hrg_satuan' => $val[6],
					'hrg_status' => 1
				);
				$this->produk->simpan('zps_harga', $dharga);
			}
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
			// $resp['err'] = $err;
			$resp['err']="Detail pembelian masih kosong";
		}
		echo json_encode($resp);
	}

	public function hapus($id)
	{
		$data = $this->pembelian->cari_stok($id);
		if ($data->stk_terjual == 0) {
			$delete = $this->pembelian->delete('zps_pembelian', 'pblg_id', $id);
			$error = $this->db->error();
			if (!empty($error)) {
				$err = $error['message'];
			} else {
				$err = "";
			}
			if ($delete) {
				$delstok = $this->pembelian->delete('zps_stok', 'stk_pblg_id', $id);
				$error = $this->db->error();
				if (!empty($error)) {
					$err = $error['message'];
				} else {
					$err = "";
				}
				if ($delstok) {
					$resp['status'] = 1;
					$resp['desc'] = "Berhasil menghapus data";
				} else {
					$resp['status'] = 0;
					$resp['desc'] = "Gagal hapus stok";
					$resp['error'] = $err;
				}
			} else {
				$resp['status'] = 0;
				$resp['desc'] = " Ada kesalahan dalam menghapus data";
				$resp['error'] = $err;
			}
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "Tidak dapat menghapus pembelian stok yang sudah terjual";
		}
		echo json_encode($resp);
	}
}
