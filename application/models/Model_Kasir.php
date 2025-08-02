<?php
class Model_Kasir extends CI_Model
{

	var $table = 'kspb_penjualan';
	var $column_order = array('pjlc_id', 'pjlc_tgl', 'pjlc_no_faktur', 'pjlc_plgc_id', 'pjlc_log_id','pjlc_user'); //set column field database for datatable orderable
	var $column_search = array('pjlc_id', 'pjlc_tgl', 'pjlc_no_faktur', 'pjlc_plgc_id', 'pjlc_log_id','pjlc_user'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('pjlc_tgl' => 'asc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query() 
	{
		$this->db->from($this->table);
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

	public function get_kasir()
	{
		$this->db->from("kspb_penjualan");
		$query = $this->db->get();

		return $query->result();
	}
	public function get_produk()
	{	
		$this->db->select("gdgs_produk.*, kspb_harga.*, (select sum(stkg_jml - stkg_terjual) from gdgs_stok where stkg_pblg_id = prdg_id and stkg_jml > stkg_terjual) as stok");
		$this->db->from("gdgs_produk");
		$this->db->join("kspb_harga","prdg_id = hrgc_prdp_id and hrgc_jml > 0 and hrgc_status = 1","left");
		$this->db->order_by("prdg_id","asc");
		
		$query = $this->db->get();
		// echo $this->getlastquery();
		return $query->result();
	}
	
	public function cek_pengguna()
	{
		$this->db->from("sys_login");
		$query = $this->db->get();

		return $query->row();
	}

	public function cari_kasir($id)
	{
		$this->db->from("kspb_penjualan");
		$this->db->where('pjlc_id', $id);
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