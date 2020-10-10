<?php
/**
 * 
 */
class Model_pemesanan extends CI_Model
{
    public $nama_pemesan;
    public $id_pemesanan;
    
    public function set_idPemesanan($id_pemesanan)
    {
        $this->id_pemesanan = $id_pemesanan;
        $this->db->set('id_pemesanan',$id_pemesanan);
    }
	public function createPemesanan($data)
    {
        return $this->db->insert('data_pemesanan', $data);
    }

    public function createDetailPemesanan_batch($data)
    {
        return $this->db->insert_batch('data_detail_pemesanan', $data);
    }

    public function createDetailPemesanan($data)
    {
        return $this->db->insert('data_detail_pemesanan', $data);
    }

    public function getDataPemesananByIdUser($idUser,$limit=null, $orderBy=null,$typeOrder=null)
    {
        $this->db->where('id_pb=',$idUser);
        if($limit!=null){
            $this->db->limit($limit);
        }
        if($orderBy!=null){
            if($typeOrder!=null){
                $this->db->order_by($orderBy, $typeOrder);
            }else{
                $this->db->order_by($orderBy, "ASC");
            } 
        }
        return $this->db->get('data_pemesanan');
    }

    public function getDataPemesananByIdUserAndStatus($idUser, $status,$limit=null, $orderBy=null,$typeOrder=null)
    {
        $this->db->where('id_pb=',$idUser);
        $this->db->where('status_pemesanan', $status);
        if($limit!=null){
            $this->db->limit($limit);
        }
        if($orderBy!=null){
            if($typeOrder!=null){
                $this->db->order_by($orderBy, $typeOrder);
            }else{
                $this->db->order_by($orderBy, "ASC");
            } 
        }
        return $this->db->get('data_pemesanan');
    }

    public function getWhereDataPemesananByIdUsaha($where, $limit=null, $orderBy=null)
    {
        $this->db->select("`id_pemesanan`, `waktu_pemesanan`, `tipe_pengiriman`, `tgl_pengiriman`, `jarak`, `biaya_kirim`, `total_harga`, `status_pemesanan`, `id_pb`, `id_usaha`");
        $this->db->where($where);
        if($limit!=null){
            $this->db->limit($limit);
        }
        if($orderBy!==null){
            $this->db->order_by($orderBy);
        }
        $this->db->from('data_pemesanan');
        return $this->db->get();
    }

    public function detail_pemesan($id_pemesanan)
    {
        $where = "id_pemesanan = $id_pemesanan";
        $data = $this->getWhereDataPemesananByIdUsaha($where)->row();
        $id_pb = $data->id_pb;
        $query_pembeli = $this->db->select("`nama_pb`")->where('id_pb', $id_pb)->get("data_pembeli");
        $data_pembeli = $query_pembeli->row();
        $this->nama_pemesan = $data_pembeli->nama_pb;
    }

    public function get_pemesanan_pengiriman($where, $order=NULL)
    {
        $this->db->where($where);
        if($order!==NULL){
            $this->db->order_by($order);
        }
        $this->db->select("pemesanan.`id_pemesanan`, pemesanan.`waktu_pemesanan`, pemesanan.`tipe_pengiriman`, pemesanan.`tgl_pengiriman`, pemesanan.`jarak`, pemesanan.`biaya_kirim`, pemesanan.`total_harga`, pemesanan.`status_pemesanan`, pemesanan.`id_pb`, pemesanan.`id_usaha`, 
            pengiriman.`id_detail_pengiriman`, pengiriman.`id_pengiriman`, pengiriman.`id_pemesanan`, pengiriman.`urutan`, pengiriman.`status`, pengiriman.`penerima`");
        $this->db->from("data_pemesanan pemesanan");
        $this->db->join("data_detail_pengiriman pengiriman", "pemesanan.id_pemesanan = pengiriman.id_pemesanan");
        return $this->db->get();
    }

    public function get_selected_detail_pemesanan($select="", $where)
    {
        $this->db->select($select)
                 ->from("data_detail_pemesanan")
                 ->where($where);
        return $this->db->get();
    }

    public function getDetailPemesanan($idPemesanan)
    {
        $this->db->select('ddp.harga, dp.nama_produk, ddp.jml_produk, dv.nama_variasi, dp.id_produk, dp.foto_produk, ddp.sub_total, dp.berat_produk, ddp.berat_akhir, ddp.catatan');
        $this->db->from('data_detail_pemesanan ddp');
        $this->db->join('data_variasi_produk dvp', 'ddp.id_produk = dvp.id_variasiproduk');
        $this->db->join('data_produk dp', 'dvp.id_produk = dp.id_produk');
        $this->db->join('data_variasi dv', 'dvp.id_variasi = dv.id_variasi');
        $this->db->where('id_pemesanan =',$idPemesanan);
        return $this->db->get();
    }

    public function getDataPembayaranByIdPemesanan($idPemesanan)
    {
        $this->db->where('id_pemesanan' ,$idPemesanan);
        $this->db->limit(1);
        return $this->db->get('data_pembayaran');
    }

    public function updatePemesanan($data, $where)
    {
        return $this->db->update('data_pemesanan', $data, $where, 1);
    }

    public function getHargaPemesananByIdPemesanan($idPemesanan){
        $this->db->select('total_harga');
        $this->db->where('id_pemesanan =', $idPemesanan);
        $this->db->from('data_pemesanan');
        $this->db->limit(1);
        return $this->db->get();
    }

    public function getDataPemesananByID($id_pemesanan)
    {
        $this->db->select("`id_pemesanan`, `waktu_pemesanan`, `tipe_pengiriman`, `tgl_pengiriman`, `jarak`, `biaya_kirim`, `total_harga`, `status_pemesanan`, `id_pb`, `id_usaha`");
        $this->db->from('data_pemesanan');
        $this->db->where('id_pemesanan', $id_pemesanan);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function getDetailDataPembayaranByIdPemesanan($id_pemesanan)
    {
        $this->db->select('*');
        $this->db->from("data_pembayaran dp");
        $this->db->join('data_master_bank dmb', "dp.kode_bank = dmb.kode_bank", "LEFT");
        $this->db->where("dp.id_pemesanan", $id_pemesanan);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function getDetailPengirimanWithKurirKendaraan($id_pemesanan)
    {
        $this->db->select('*');
        $this->db->from("data_pengiriman dp");
        $this->db->join('data_kurir dk', 'dp.id_kurir = dk.id_kurir', 'RIGHT');
        $this->db->join('data_kendaraan dk2', 'dp.id_kendaraan = dk2.id_kendaraan', 'RIGHT');
        $this->db->where('dp.id_pemesanan', $id_pemesanan);
        return $this->db->get();
    }

    public function getDetailPengiriman($id_pemesanan)
    {
        $this->db->select('*');
        $this->db->from("data_pengiriman dp");
        $this->db->where('dp.id_pemesanan', $id_pemesanan);
        return $this->db->get();
    }

    public function UpdatePembayaran($data, $id_pemesanan)
    {
        $this->db->where('id_pemesanan', $id_pemesanan);
        return $this->db->update("data_pembayaran", $data);
    }

    public function DeletePembayaran($where)
    {
        return $this->db->delete("data_pembayaran", $where);
    }

    public function DeleteDetailPemesanan($where)
    {
        return $this->db->delete("data_detail_pemesanan", $where);
    }

    public function DeletePemesanan($where)
    {
        return $this->db->delete("data_pemesanan", $where);
    }

    public function get_where($select="", $where="", $JOIN=NULL, $group=NULL, $order=NULL, $limit=NULL)
    {
        $select1 = "pemesanan.`id_pemesanan`, pemesanan.`waktu_pemesanan`, pemesanan.`tipe_pengiriman`, pemesanan.`tgl_pengiriman`, pemesanan.`jarak`, pemesanan.`biaya_kirim`, pemesanan.`total_harga`, 
        pemesanan.`status_pemesanan`, pemesanan.`id_pb`, pemesanan.`id_usaha`";
        $selected = $select1 . ($select!==""||$select!==NULL||!empty($select)) ? ", " . $select : "";
        $this->db->select($selected);
        $this->db->from("data_pemesanan pemesanan");
        if(is_array($where) || $where!==""){
            $this->db->where($where);
        }
        if($group!==NULL){
            $this->db->group_by($group);
        }
        if($order!==NULL){
            $this->db->order_by($order);
        }
        if($limit!==NULL){
            $this->db->limit($limit);
        }
        if($JOIN!==NULL){
            for ($i=0; $i < count($JOIN); $i++) { 
                $join_on = ($JOIN[$i]['on']!==NULL) ? $JOIN[$i]['on'] : '';
                $join_position = ($JOIN[$i]['join']!==NULL) ? $JOIN[$i]['join'] : '';
                $this->db->join($JOIN[$i]['table'], $join_on, $join_position);
            }
        }
        return $this->db->get();
    }

    

    public function get_detail_where($select="*", $where, $JOIN=NULL, $group=NULL, $order=NULL, $limit=NULL)
    {
        $this->db->select($select);
        $this->db->from("data_detail_pemesanan detail_pemesanan");
        $this->db->where($where);
        if($group!==NULL){
            $this->db->group_by($group);
        }
        if($order!==NULL){
            $this->db->order_by($order);
        }
        if($limit!==NULL){
            $this->db->limit($limit);
        }
        if($JOIN!==NULL){
            for ($i=0; $i < count($JOIN); $i++) { 
                $join_on = ($JOIN[$i]['on']!==NULL) ? $JOIN[$i]['on'] : '';
                $join_position = ($JOIN[$i]['join']!==NULL) ? $JOIN[$i]['join'] : '';
                $this->db->join($JOIN[$i]['table'], $join_on, $join_position);
            }
        }
        return $this->db->get();
    }

    public function update_detail_pesanan($id_detail_pesanan, $data)
    {
        $this->db->where('id_dp', $id_detail_pesanan);
        return $this->db->update("data_detail_pemesanan", $data);
    }

    public function getPesananNonPriority($array)
    {
        $this->db->where_not_in('pemesanan.id_pemesanan', $array);
        $this->db->where('pemesanan.status_pemesanan', 'Terbayar');
        $this->db->where("pembayaran.verifikasi", "1");
        $this->db->where("DATE(tgl_pengiriman) = DATE(NOW())");
        $this->db->where("(pemesanan.tipe_pengiriman='Cepat' OR pemesanan.tipe_pengiriman = 'Biasa')");
        $this->db->select("pemesanan.`id_pemesanan`, pemesanan.`waktu_pemesanan`, pemesanan.`tipe_pengiriman`, pemesanan.`tgl_pengiriman`, pemesanan.`jarak`, pemesanan.`biaya_kirim`, pemesanan.`total_harga`, pemesanan.`status_pemesanan`, pemesanan.`id_pb`, pemesanan.`id_usaha");
        $this->db->from('data_pemesanan pemesanan');
        $this->db->join("data_pembayaran pembayaran", 'pembayaran.id_pemesanan = pemesanan.id_pemesanan');
        return $this->db->get();
    }

    public function getPesananPriority($array)
    {
        $this->db->where_in('pemesanan.id_pemesanan', $array);
        $this->db->where('pemesanan.status_pemesanan', 'Siap Dikirim');
        $this->db->where("pembayaran.verifikasi", "1");
        // $this->db->where("DATE(tgl_pengiriman) = DATE(NOW())");
        $this->db->select("pemesanan.`id_pemesanan`, pemesanan.`waktu_pemesanan`, pemesanan.`tipe_pengiriman`, pemesanan.`tgl_pengiriman`, pemesanan.`jarak`, pemesanan.`biaya_kirim`, pemesanan.`total_harga`, pemesanan.`status_pemesanan`, pemesanan.`id_pb`, pemesanan.`id_usaha");
        $this->db->from('data_pemesanan pemesanan');
        $this->db->join("data_pembayaran pembayaran", 'pembayaran.id_pemesanan = pemesanan.id_pemesanan');
        return $this->db->get();
    }

    public function update_pemesanan(int $id_pemesanan=0, array $data=array())
    {
        return $this->db->update("data_pemesanan", $data, "id_pemesanan = $id_pemesanan", 1);
    }

}