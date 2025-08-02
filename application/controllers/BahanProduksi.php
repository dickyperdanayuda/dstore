<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BahanProduksi extends CI_Controller
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
		$this->load->library('upload');
		$this->load->model('Model_BahanProduksi', 'bahanproduksi');
		$this->load->model('Model_SatuanDasarProduksi', 'satuandasarproduksi');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function tampil()
	{
		$this->session->set_userdata("judul", "Data BahanProduksi");
		$ba = [
			'judul' => "Data BahanProduksi",
			'subjudul' => "BahanProduksi",
		];
		$d = [
		'satuan' => $this->satuandasarproduksi->get_satuandasarproduksi()
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('bahanproduksi', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_bahanproduksi()
	{
		$list = $this->bahanproduksi->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bahanproduksi) {
			$no++;
			$foto = "<div style='background-image:url(\"".base_url('assets/dist/img/no-image.jpg')."\");background-position:center;background-repeat:no-repeat;background-size:contain;height:80px;width:80px;'></div>";
			if ($bahanproduksi->bhn_foto)
			$foto = "<div style='background-image:url(\"".base_url('assets/files/bahan/thumbs/'.$bahanproduksi->bhn_foto)."\");background-position:center;background-repeat:no-repeat;background-size:contain;height:80px;width:80px;'></div>";
			$row = array();
			$row[] = $no;
			$row[] = $foto;
			$row[] = $bahanproduksi->bhn_nama;
			$row[] = $bahanproduksi->stn_satuan;
			$row[] = "<a href='#' onClick='ubah_bahanproduksi(" . $bahanproduksi->bhn_id . ")' class='btn btn-dark btn-sm' title='Ubah data bahanproduksi'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_bahanproduksi(" . $bahanproduksi->bhn_id . ")' class='btn btn-danger btn-sm' title='Hapus data bahanproduksi'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->bahanproduksi->count_all(),
			"recordsFiltered" => $this->bahanproduksi->count_filtered(),
			"data" => $data,
			"query" => $this->bahanproduksi->getlastquery(),
		);
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('bhn_id');
		$data = $this->bahanproduksi->cari_bahanproduksi($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('bhn_id');
		$data = $this->input->post();
		$nama = str_replace(' ', '-', trim($data['bhn_nama']));
		if (!empty($_FILES['file_foto']['name'])) {
			if (!is_dir('assets/files/bahan')) {
				mkdir('assets/files/bahan', 0777, TRUE);
				mkdir('assets/files/bahan/thumbs', 0777, TRUE);
			}
			$path = $_FILES['file_foto']['name'];
			$ext =  pathinfo($path, PATHINFO_EXTENSION);
			$config['upload_path'] = 'assets/files/bahan/'; //path folder
			$config['allowed_types'] = '*'; //type yang dapat diakses bisa anda sesuaikan
			$config['encrypt_name'] = FALSE; //Enkripsi nama yang terupload
			$config['overwrite'] = TRUE; //Gantikan file dengan nama yang sama
			$config['file_name'] = "{$nama}." . $ext; //ganti nama file

			$this->upload->initialize($config);
		}

		if (!empty($_FILES['file_foto']['name'])) {

			if ($this->upload->do_upload('file_foto')) {
				$foto = $this->upload->data();

				$config['image_library'] = 'gd2';
				$config['source_image'] = 'assets/files/bahan/' . $foto['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['quality'] = '50%';
				$config['width'] = 150;
				$config['height'] = 150;
				$config['new_image'] = 'assets/files/bahan/thumbs/' . $foto['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$data['bhn_foto'] = $foto['file_name'];
			}
		}

		if ($id == 0) {
			$insert = $this->bahanproduksi->simpan("prod_bahan", $data);
		} else {
			$insert = $this->bahanproduksi->update("prod_bahan", array('bhn_id' => $id), $data);
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
		$delete = $this->bahanproduksi->delete('prod_bahan', 'bhn_id', $id);

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
