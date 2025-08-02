<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KategoriPergudangan extends CI_Controller
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
        $this->load->model('Model_KategoriPergudangan', 'kategori');
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

    //Kategori	
    public function tampil()
    {

        $this->session->set_userdata("judul", "Data Master");
        $ba = [
            'judul' => "Data Master",
            'subjudul' => "Kategori",
        ];
        $this->load->helper('url');
        $this->load->view('background_atas', $ba);
        $this->load->view('kategoripergudangan');
        $this->load->view('background_bawah');
    }

    public function ajax_list_kategori()
    {
        $list = $this->kategori->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $kategori) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $kategori->ktg_nama;
            $row[] = "<a href='#' onClick='ubah_kategori(" . $kategori->ktg_id . ")' class='btn btn-info' title='Ubah data Kategori'><i class='fa fa-pen'></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' onClick='hapus_kategori(" . $kategori->ktg_id . ")' class='btn btn-danger' title='Hapus data Kategori'><i class='fa fa-times'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kategori->count_all(),
            "recordsFiltered" => $this->kategori->count_filtered(),
            "data" => $data,
            "query" => $this->kategori->getlastquery(),
        );
        //output to json format
        echo json_encode($output);
    }

    public function cari()
    {
        $id = $this->input->post('ktg_id');
        $data = $this->kategori->cari_kategori($id);
        echo json_encode($data);
    }


    public function simpan()
    {

        $validasi = [
            [
                'field' => 'ktg_nama',
                'label' => 'Nama Kategori',
                'rules' => 'required'
            ],
        
        ];
        $this->form_validation->set_rules($validasi);
        $this->form_validation->set_message('required', '{field} tidak boleh kosong');
        if ($this->form_validation->run() == FALSE) {
            // $errors=validation_errors();
            $resp['status'] = 0;
            $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
            $resp['error'] = [
                'nama_error' => form_error('ktg_nama'),
            ];
            echo json_encode($resp);
        } else {
            $id = $this->input->post('ktg_id');
            $nama = $this->input->post('ktg_nama');
            $data = array(
                'ktg_nama' => $nama,
            );
            if ($id == 0) {
                $insert = $this->kategori->simpan("gdgs_kategori", $data);
            } else {
                $insert = $this->kategori->update("gdgs_kategori", array('ktg_id' => $id), $data);
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
        $delete = $this->kategori->delete('gdgs_kategori', 'ktg_id', $id);
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
