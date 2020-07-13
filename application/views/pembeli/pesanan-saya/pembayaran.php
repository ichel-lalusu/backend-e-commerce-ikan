<?php
$no = 1;
$totalData = count($dataRekening->result_array());
foreach($dataRekening->result() as $data){
    ?>
    <div class="card">
        <ul class="collection">
            <?php
            if($no==1){
                ?>
                <li class="collection-item" style="margin-top: -12px"><h5>Silahkan Transfer Ke:</h5></li>
                <?php
            }
            ?>
            <li class="collection-item">Nama Bank<span class="secondary-content namaBank"><?=$data->nama_bank;?>    </span></li>
            <li class="collection-item">No. Rekening<span class="secondary-content noRek"><?=$data->no_rekening;?></span></li>
            <li class="collection-item">Nama Pemilik Rekening<span class="secondary-content namaRekening"><?=strtoupper($data->nama_rekening)?></span></li>
        </ul>
    </div>
    <?php
    $no++;
}
?>
<a href="unggah-bukti.html" class="waves-effect waves-light btn blue" style="width: 100%; margin-top: 0px;">Upload Bukti Pembayaran</a>