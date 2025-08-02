<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierPergudangan extends CI_Controller
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
        $this->load->model('Model_SupplierPergudangan', 'supplier');
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

    //Supplier	
    public function tampil()
    {

        $this->session->set_userdata("judul", "Data Master");
        $ba = [
            'judul' => "Data Master",
            'subjudul' => "Supplier",
        ];
        $this->load->helper('url');
        $this->load->view('background_atas', $ba);
        $this->load->view('supplierpergudangan');
        $this->load->view('background_bawah');
    }

    public function ajax_list_supplier()
    {
        $list = $this->supplier->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $supplier) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $supplier->splg_nama;
            $row[] = $supplier->splg_telp;
            $row[] = $supplier->splg_alamat;

            $row[] = "<a href='#' onClick='ubah_supplier(" . $supplier->splg_id . ")' class='btn btn-info' title='Ubah data Supplier'><i class='fa fa-pen'></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a href='#' onClick='hapus_supplier(" . $supplier->splg_id . ")' class='btn btn-danger' title='Hapus data Supplier'><i class='fa fa-times'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->supplier->count_all(),
            "recordsFiltered" => $this->supplier->count_filtered(),
            "data" => $data,
            "query" => $this->supplier->getlastquery(),
        );
        //output to json format
        echo json_encode($output);
    }

    public function cari()
    {
        $id = $this->input->post('splg_id');
        $data = $this->supplier->cari_supplier($id);
        echo json_encode($data);
    }


    public function simpan()
    {

        $validasi = [
            [
                'field' => 'splg_nama',
                'label' => 'Nama Supplier',
                'rules' => 'required'
            ],
            [
                'field' => 'splg_telp',
                'label' => 'Telpon Supplier',
                'rules' => 'required|numeric'
            ],
            [
                'field' => 'splg_alamat',
                'label' => 'Alamat Supplier',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($validasi);
        $this->form_validation->set_message('required', '{field} tidak boleh kosong');
        $this->form_validation->set_message('numeric', '{field} hanya bisa diisi angka');
        if ($this->form_validation->run() == FALSE) {
            // $errors=validation_errors();
            $resp['status'] = 0;
            $resp['desc'] = "Ada kesalahan dalam penyimpanan!";
            $resp['error'] = [
                'nama_error' => form_error('splg_nama'),
                'telp_error' => form_error('splg_telp'),
                'alamat_error' => form_error('splg_alamat'),
            ];
            echo json_encode($resp);
        } else {
            $id = $this->input->post('splg_id');
            $nama = $this->input->post('splg_nama');
            $splg_telp = $this->input->post('splg_telp');
            $splg_alamat = $this->input->post('splg_alamat');
            $data = array(
                'splg_nama' => $nama,
                'splg_telp' => $splg_telp,
                'splg_alamat' => $splg_alamat,
            );
            if ($id == 0) {
                $insert = $this->supplier->simpan("gdgs_supplier", $data);
            } else {
                $insert = $this->supplier->update("gdgs_supplier", array('splg_id' => $id), $data);
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
        $delete = $this->supplier->delete('gdgs_supplier', 'splg_id', $id);
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
