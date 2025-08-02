<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MerekPergudangan extends CI_Controller
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

    //Merek	
    public function tampil()
    {

        $this->session->set_userdata("judul", "Data Master");
        $ba = [
            'judul' => "Data Master",
            'subjudul' => "Merek",
        ];
        $this->load->helper('url');
        $this->load->view('background_atas', $ba);
        $this->load->view('merekpergudangan');
        $this->load->view('background_bawah');
    }

    public function ajax_list_merek()
    {
        $list = $this->merek->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $merek) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $merek->mrkg_nama;
            $row[] = "<img src='".base_url('assets/images_pergudangan/merek/thumbs/').$merek->mrkg_logo."' class='img-circle' style='width:100px; height:100px;'>";
            $row[] = "<a href='#' onClick='ubah_merek(" . $merek->mrkg_id . ")' class='btn btn-info' title='Ubah data Merek'><i class='fa fa-pen'></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' onClick='hapus_merek(" . $merek->mrkg_id . ")' class='btn btn-danger' title='Hapus data Merek'><i class='fa fa-times'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->merek->count_all(),
            "recordsFiltered" => $this->merek->count_filtered(),
            "data" => $data,
            "query" => $this->merek->getlastquery(),
        );
        //output to json format
        echo json_encode($output);
    }

    public function cari()
    {
        $id = $this->input->post('mrkg_id');
        $data = $this->merek->cari_merek($id);
        echo json_encode($data);
    }


    public function simpan()
    {


        $validasi = [
            [
                'field' => 'mrkg_nama',
                'label' => 'Nama Merek',
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
                'nama_error' => form_error('mrkg_nama'),
            ];
            echo json_encode($resp);
        } else {
            $id = $this->input->post('mrkg_id');
            $nama = $this->input->post('mrkg_nama');
            if (!empty($_FILES['mrkg_logo']['name'])) {
                $filename = $_FILES['mrkg_logo']['name'];
                $arrName = explode(".", $filename);
                $idxName = count($arrName);
                $ext = $arrName[$idxName - 1];

                $config['upload_path'] = 'assets/images_pergudangan/merek/'; //path folder
                $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
                $config['encrypt_name'] = FALSE; //Enkripsi nama yang terupload
                $config['overwrite'] = TRUE; //Timpa file lama dengan file baru
                $config['file_name'] = $nama . "." . $ext; //ganti nama file

                $this->upload->initialize($config);
            }
            if (!is_dir('assets/images_pergudangan/merek')) {
                mkdir('assets/images_pergudangan/merek', 0777, TRUE);
                mkdir('assets/images_pergudangan/merek/thumbs', 0777, TRUE);
            }

            if (!empty($_FILES['mrkg_logo']['name'])) {

                if ($this->upload->do_upload('mrkg_logo')) {
                    $gbr = $this->upload->data();
                    //Compress Image
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = 'assets/images_pergudangan/merek/' . $gbr['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['quality'] = '50%';
                    $config['width'] = 150;
                    $config['height'] = 150;
                    $config['new_image'] = 'assets/images_pergudangan/merek/thumbs/' . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $foto = $gbr['file_name'];
                } else {
                    // die($this->upload->display_errors());
                    $resp['status'] = 0;
                    $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
                    $resp['error'] = [
                        'err' => $this->upload->display_errors()
                    ];
                }
                $data = array(
                    'mrkg_nama' => $nama,
                    'mrkg_logo' => $foto,
                );
            } else {

                $data = array(
                    'mrkg_nama' => $nama,
                );
            }
            if ($id == 0) {
                $insert = $this->merek->simpan("gdgs_merek", $data);
            } else {
                $insert = $this->merek->update("gdgs_merek", array('mrkg_id' => $id), $data);
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
        $delete = $this->merek->delete('gdgs_merek', 'mrkg_id', $id);
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
