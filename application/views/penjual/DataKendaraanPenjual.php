<?php
if($data->num_rows() > 0){
    foreach($data->result() as $kendaraan){
    ?>
    <div class="card" id="kendaraan<?=$kendaraan->id_kendaraan?>">
        <div class="card-content" style="padding: 15px; margin-bottom: 5px">
            <div class="row" style="margin-bottom: 0px">
            <div class="col s5">
                <p class="left" style="font-size: small;">Jenis Kendaraan :</p>
            </div>
            <div class="col s7">
                <p class="right jenis_kendaraan" style="font-size: small;"><?=$kendaraan->jenis_kendaraan?></p>
            </div>
            </div>
            <div class="row" style="margin-bottom: 0px">
            <div class="col s5">
                <p class="left" style="font-size: small;">Plat Kendaraan :</p>
            </div>
            <div class="col s7">
                <p class="right plat_kendaraan" style="font-size: small;"><?=$kendaraan->plat_kendaraan?></p>
            </div>
            </div>
            <div class="row" style="margin-bottom: 0px">
            <div class="col s5">
                <p class="left" style="font-size: small;">Kapasitas Kendaraan:</p>
            </div>
            <div class="col s7">
                <p class="right kapasitas_kendaraan" style="font-size: small;"><?=$kendaraan->kapasitas_kendaraan?></p>
            </div>
            </div>
            <div class="row" style="margin-bottom: 10px">
            <div class="col 12 right">
                <div class="card-action" style="padding: 8px 0px 0px 0px">
                <a class="btn-floating waves-effect waves-light btn green" onclick="UbahKendaraan('<?=$kendaraan->id_kendaraan;?>')"><i class="material-icons" style="height: 40px; width: 40px">edit</i></a>
                <a class="btn-floating waves-effect waves-light btn red modal-trigger" href="#modal<?=$kendaraan->id_kendaraan;?>"><i class="material-icons" style="height: 40px; width: 40px">delete</i></a>
                </div>
            </div>
            </div>
        </div>
        <div id="modal<?=$kendaraan->id_kendaraan;?>" class="modal">
            <div class="modal-content">
            <h4>Hapus Kendaraan</h4>
            <p>Apakah anda yakin ingin menghapus kendaraan <?=$kendaraan->jenis_kendaraan?> dengan Plat <?=$kendaraan->plat_kendaraan?>?</p>
            </div>
            <div class="modal-footer">
            <button class="btn grey waves-effect waves-red" onclick="hapusKendaraan('<?=$kendaraan->id_kendaraan?>')">Ya</button>
            <a href="#!" class="modal-close waves-effect waves-green btn green">Tidak</a>
            </div>
        </div>
    </div>
    <?php
    }
}
?>