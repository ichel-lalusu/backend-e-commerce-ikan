<?php
if($data->num_rows() > 0){
  foreach($data->result() as $rekening){
    ?>
    <div class="card z-depth-0" id="rekening<?=$rekening->id_rekening?>">
      <div class="collection">
        <div class="card-content" style="padding: 12px 0px; margin: 0px 4px">
          <div class="col s12">
            <div class="col s5">
              <p class="left" style="font-size: small;">Nama Bank :</p>
            </div>
            <div class="col s7">
              <p class="right nama_bank" style="font-size: small;"><?=$rekening->nama_bank?></p>
            </div>
          </div>
          <div class="col s12">
            <div class="col s5">
              <p class="left" style="font-size: small;">No. Rekening :</p>
            </div>
            <div class="col s7">
              <p class="right no_rekening" style="font-size: small;"><?=$rekening->no_rekening?></p>
            </div>
          </div>
          <div class="col s12">
            <div class="col s6">
              <p class="left" style="font-size: small;">Nama Pemilik Rekening :</p>
            </div>
            <div class="col s6">
              <p class="right nama_rekening" style="font-size: small;"><?=$rekening->nama_rekening?></p>
            </div>
          </div>
          <div class="row">
            <div class="col 12 right">
              <div class="card-action" style="padding: 12px 0px 0px 0px">
                <button onclick="UbahRekening(<?=$rekening->id_rekening;?>)" class="btn-floating waves-effect waves-light btn green"><i
                  class="material-icons" style="height: 40px; width: 40px">edit</i>
                </button>
                <a class="btn-floating waves-effect waves-light btn red modal-trigger" href="#modal<?=$rekening->id_rekening;?>"><i class="material-icons" style="height: 40px; width: 40px">delete</i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="modal<?=$rekening->id_rekening;?>" class="modal">
        <div class="modal-content">
          <h5>Hapus Rekening</h5>
          <p>Apakah anda yakin ingin menghapus rekening <?=$rekening->nama_rekening?> dengan No. Rekening <?=$rekening->no_rekening?>?</p>
        </div>
        <div class="modal-footer">
          <button class="btn grey waves-effect waves-red" onclick="HapusRekening(<?=$rekening->id_rekening?>)">Ya</button>
          <a href="#!" class="modal-close waves-effect waves-green btn green">Tidak</a>
        </div>
      </div>
    </div>
    <?php
  }
}
?>