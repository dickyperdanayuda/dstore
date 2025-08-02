<?php
class Model_HargaProdukKasir extends CI_Model
{

	var $table = 'kspb_harga';
	var $column_order = array('hrgc_id', 'hrgc_prdp_id', 'hrgc_jml', 'hrgc_harga', 'hrgc_status'); //set column field database for datatable orderable
	var $column_search = array('hrgc_id', 'hrgc_prdp_id', 'hrgc_jml', 'hrgc_harga'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('hrgc_id' => 'asc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query() 
	{
		$this->db->from($this->table);
		$this->db->join("gdgs_produk", "prdg_id = hrgc_prdp_id", "left");
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			foreach ($this->order as $key => $order) {
				$this->db->order_by($key, $order);
			}
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function get_harga_produk()
	{
		$this->db->from("kspb_harga");
		$query = $this->db->get();

		return $query->result();
	}
	
	public function cek_pengguna()
	{
		$this->db->from("sys_login");
		$query = $this->db->get();

		return $query->row();
	}

	public function cari_harga_produk($id)
	{
		$this->db->from("kspb_harga");
		$this->db->where('hrgc_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function getlastquery()
	{
		$query = str_replace(array("\r", "\n", "\t"), '', trim($this->db->last_query()));

		return $query;
	}

	public function update($tbl, $where, $data)
	{
		$this->db->update($tbl, $data, $where);
		return $this->db->affected_rows();
	}

	public function simpan($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function delete($table, $field, $id)
	{
		$this->db->where($field, $id);
		$this->db->delete($table);

		return $this->db->affected_rows();
	}


}