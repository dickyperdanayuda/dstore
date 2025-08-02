<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdukPergudangan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!isset($this->session->userdata['id_user'])) {
            redirect(base_url("login"));
        }
        // if ($this->session->userdata("level") == 3) {
        // 	redirect(base_url("Dashboard"));
        // }

        $this->load->library('upload');
        $this->load->library('form_validation');
        $this->load->model('Model_ProdukPergudangan', 'produk');
        $this->load->model('Model_KategoriPergudangan', 'kategori');
        $this->load->model('Model_MerekPergudangan', 'merek');
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    //Produk	
    public function tampil()
    {

        $this->session->set_userdata("judul", "Data Master");
        $ba = [
            'judul' => "Data Master",
            'subjudul' => "Produk",
        ];
        $merek=$this->merek->get_merek();
        $kategori=$this->kategori->get_kategori();

        $d=[
            'merek'=>$merek,
            'kategori'=>$kategori
        ];
        $this->load->helper('url');
        $this->load->view('background_atas', $ba);
        $this->load->view('produkpergudangan',$d);
        $this->load->view('background_bawah');
    }

    public function ajax_list_produk()
    {
        $list = $this->produk->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $produk) {
            $no++;
			$kategori = "-";
			$merek = "-";
            $status="-";
			$kat = $this->kategori->cari_kategori($produk->prdg_ktg_id);
			if ($kat) {
				$kategori = $kat->ktg_nama;
			}

			$mrk = $this->merek->cari_merek($produk->prdg_mrk_id);
			if ($mrk) {
				$merek = $mrk->mrkg_nama;
			}
            if($produk->prdg_status==1){
                $status="Dijual";
            } else {
                $status="Tidak Dijual";
            }
            $row = array();
            $row[] = $no;
            $row[] = "<img src='".base_url('assets/images_pergudangan/produk/thumbs/').$produk->prdg_foto."' class='img-circle' style='width:100px; height:100px;'>";
            $row[] = $kategori;
            $row[] = $merek;
            $row[] = $produk->prdg_nama;
            $row[] = $produk->prdg_deskripsi;
            $row[] = $produk->prdg_kemasan;
            $row[] = $produk->prdg_isi;
            $row[] = $produk->prdg_satuan_isi;
            $row[] = $status;
            $row[] = "<a href='#' onClick='ubah_produk(" . $produk->prdg_id . ")' class='btn btn-info' title='Ubah data Produk'><i class='fa fa-pen'></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' onClick='hapus_produk(" . $produk->prdg_id . ")' class='btn btn-danger' title='Hapus data Produk'><i class='fa fa-times'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->produk->count_all(),
            "recordsFiltered" => $this->produk->count_filtered(),
            "data" => $data,
            "query" => $this->produk->getlastquery(),
        );
        //output to json format
        echo json_encode($output);
    }

    public function cari()
    {
        $id = $this->input->post('prdg_id');
        $data = $this->produk->cari_produk($id);
        echo json_encode($data);
    }


    public function simpan()
    {
        $validasi = [
            // [
            //     'field' => 'prdg_ktg_id',
            //     'label' => 'Kategori Produk',
            //     'rules' => 'required'
            // ],
            // [
            //     'field' => 'prdg_mrk_id',
            //     'label' => 'Merek Produk',
            //     'rules' => 'required'
            // ],
            [
                'field' => 'prdg_nama',
                'label' => 'Nama Produk',
                'rules' => 'required'
            ],
            // [
            //     'field' => 'prdg_kemasan',
            //     'label' => 'Kemasan Produk',
            //     'rules' => 'required'
            // ],
            // [
            //     'field' => 'prdg_isi',
            //     'label' => 'Isi Produk',
            //     'rules' => 'required|numeric'
            // ],
            // [
            //     'field' => 'prdg_satuan_isi',
            //     'label' => 'Isi Produk',
            //     'rules' => 'required'
            // ],
        ];
        $this->form_validation->set_rules($validasi);
        $this->form_validation->set_message('required', '{field} tidak boleh kosong');
        // $this->form_validation->set_message('numeric', '{field} hanya boleh angka kosong');
        if ($this->form_validation->run() == FALSE) {
            // $errors=validation_errors();
            $resp['status'] = 0;
            $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
            $resp['error'] = [
                // 'kategori_error' => form_error('prdg_ktg_id'),
                // 'merek_error' => form_error('prdg_mrk_id'),
                'nama_error' => form_error('prdg_nama'),
                // 'kemasan_error' => form_error('prdg_kemasan'),
                // 'isi_error' => form_error('prdg_isi'),
                // 'satuan_error' => form_error('prdg_satuan_isi'),
            ];
            echo json_encode($resp);
        } else {
            $id = $this->input->post('prdg_id');
            $kategori=$this->input->post('prdg_ktg_id');
            $merek=$this->input->post('prdg_mrk_id');
            $nama = $this->input->post('prdg_nama');
            $deskripsi = $this->input->post('prdg_deskripsi');
            $kemasan = $this->input->post('prdg_kemasan');
            $isi = $this->input->post('prdg_isi');
            $satuan_isi = $this->input->post('prdg_satuan_isi');
            $status = $this->input->post('prdg_status');
            if (!empty($_FILES['prdg_foto']['name'])) {
                $filename = $_FILES['prdg_foto']['name'];
                $arrName = explode(".", $filename);
                $idxName = count($arrName);
                $ext = $arrName[$idxName - 1];

                $config['upload_path'] = 'assets/images_pergudangan/produk/'; //path folder
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
                $config['encrypt_name'] = FALSE; //Enkripsi nama yang terupload
                $config['overwrite'] = TRUE; //Timpa file lama dengan file baru
                $config['file_name'] = $nama . "." . $ext; //ganti nama file

                $this->upload->initialize($config);
            }
            if (!is_dir('assets/images_pergudangan/produk')) {
                mkdir('assets/images_pergudangan/produk', 0777, TRUE);
                mkdir('assets/images_pergudangan/produk/thumbs', 0777, TRUE);
            }

            if (!empty($_FILES['prdg_foto']['name'])) {

                if ($this->upload->do_upload('prdg_foto')) {
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = 'assets/images_pergudangan/produk/' . $gbr['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['quality'] = '50%';
                    $config['width'] = 150;
                    $config['height'] = 150;
                    $config['new_image'] = 'assets/images_pergudangan/produk/thumbs/' . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $foto = $gbr['file_name'];
                } else {
                    die($this->upload->display_errors());
                    $resp['status'] = 0;
                    $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
                    $resp['error'] = [
                        'err' => $this->upload->display_errors()
                    ];
                }
                $data = array(
                    'prdg_ktg_id' => $kategori,
                    'prdg_mrk_id' => $merek,
                    'prdg_nama' => $nama,
                    'prdg_deskripsi' => $deskripsi,
                    'prdg_kemasan' => $kemasan,
                    'prdg_isi' => $isi,
                    'prdg_satuan_isi' => $satuan_isi,
                    'prdg_foto' => $foto,
                    'prdg_status' => $status,
                );
            } else {

                $data = array(
                    'prdg_ktg_id' => $kategori,
                    'prdg_mrk_id' => $merek,
                    'prdg_nama' => $nama,
                    'prdg_deskripsi' => $deskripsi,
                    'prdg_kemasan' => $kemasan,
                    'prdg_isi' => $isi,
                    'prdg_satuan_isi' => $satuan_isi,
                    // 'prdg_foto' => $foto,
                    'prdg_status' => $status,
                );
            }
            if ($id == 0) {
                $insert = $this->produk->simpan("gdgs_produk", $data);
            } else {
                $insert = $this->produk->update("gdgs_produk", array('prdg_id' => $id), $data);
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
                if ($id == 0) {
                    $resp['status'] = 0;
                    $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
                    $resp['error'] = $err;
                } else {
                    $resp['status'] = 1;
                    $resp['desc'] = "Berhasil menyimpan data";
                }
            }
            echo json_encode($resp);
        }
    }


    public function hapus($id)
    {
        $delete = $this->produk->delete('gdgs_produk', 'prdg_id', $id);
        // $err = $this->db->error();
        // if ($err)
        // {
        $resp['status'] = 1;
        $resp['desc'] = "Berhasil menghapus data";
        // }
        // else 
        // {
        // $resp['status'] = 0;
        // $resp['desc'] = "Ada kesalahan dalam penghapusan data $id";
        // $resp['error'] = $err;
        // }
        echo json_encode($resp);
    }
}
