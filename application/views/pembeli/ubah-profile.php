<?php $source = base_url('foto_pembeli/'); ?>
<form method="POST" enctype="multipart/form-data" name="fupload" class="formValidate" id="formValidate" data-parsley-validate>
    <div class="card z-depth-0">
        
        <div class="col s12">
            <div class="input-field">
                <i class="material-icons prefix small icon-demo">contacts</i>
                <input id="nama_pb" name="nama_pb" type="text" class="validate" autocomplete="off" autofocus="" value="<?=$nama_pb?>">
                <label class="active" for="nama_pb">Nama Lengkap</label>
            </div>
        </div>
        <div class="col s12 m4 l3 user-section-negative-margin">
            <div class="col s12 center-align">
              <img class="responsive-img circle z-depth-5 foto_pb" width="100" src="<?=$source . $foto_pb?>" alt="" style="margin-top: 5px">
            </div>
          </div>
        <div class="col s12" style="margin-top: 24px">
            <div class="file-field input-field col s12">
                <div class="btn green">
                    <span>Foto Profil</span>
                    <input placeholder="Foto Profil" id="foto_pb" name="foto_pb" type="file" class="validate"> 
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" value="<?=$foto_pb?>">
                </div>
            </div>
        </div>
        <div class="col s12" style="margin-top: 24px">
            <div class="input-field">
                <p class="flow-text">Jenis Kelamin :</p>
                <p>
                    <label>
                        <input class="with-gap" name="jk_pb" value="Laki-laki" type="radio" <?=($jk_pb=="Laki-laki") ? 'checked' : '';?> />
                        <span>Laki-laki</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input class="with-gap" name="jk_pb" value="Perempuan" type="radio" <?=($jk_pb=="Perempuan") ? 'checked' : '';?> />
                        <span>Perempuan</span>
                    </label>
                </p>
            </div>
        </div>
        <div class="col s12" style="margin-top: 36px">
            <div class="input-field">
                <i class="material-icons prefix small icon-demo">date_range</i>
                <input value="<?=$tgllahir_pb;?>" id="tgllahir_pb" name="tgllahir_pb" type="text" class="datepicker" autocomplete="off">
                <label class="active" for="tgllahir_pb">Tanggal Lahir</label>
            </div>
        </div>
        <div class="col s12" style="margin-top: 24px">
            <div class="input-field">
                <i class="material-icons prefix small icon-demo">phone</i>
                <input id="telp_pb" name="telp_pb" type="tel" class="validate" autocomplete="off" value="<?=$telp_pb;?>">
                <label class="active" for="telp_pb">No. Hp</label>
            </div>
        </div>
        <div class="col s12" style="margin-top: 24px">
            <div class="input-field">
                <i class="material-icons prefix small icon-demo">home</i>
                <textarea id="alamat_pb" name="alamat_pb" type="text" class="materialize-textarea" autocomplete="off"><?=$alamat_pb;?></textarea>
                <label class="active" for="alamat_pb">Alamat Lengkap</label>
            </div>
        </div>
        <div class="col s12" style="margin-top: 24px">
            <div class="input-field">
                <i class="material-icons prefix small icon-demo"></i>
                <input id="kab_pb" name="kab_pb" type="text" class="validate" autocomplete="off" value="<?=$kab_pb;?>">
                <label class="active" for="kab_pb">Kota/Kabupaten</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix small icon-demo"></i>
                <input id="kec_pb" name="kec_pb" type="text" class="validate" autocomplete="off" value="<?=$kec_pb;?>">
                <label class="active" for="kec_pb">Kecamatan</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix small icon-demo"></i>
                <input id="kel_pb" name="kel_pb" type="text" class="validate" autocomplete="off" value="<?=$kel_pb;?>">
                <label class="active" for="kel_pb">Kelurahan</label>
            </div>
        </div>
        <div class="col s12">
            <b class="flow-text">Lokasi Alamat di Peta : </b>
        </div>
        <div class="col s12" id="map">
        </div>
        <div class="col s12">
            <p class="flow-text red-text" style="font-size: medium">Note:</p>
        </div>
        <div class="col s12">
            <input type="hidden" name='latitude' id="latitude">
            <input type="hidden" name='longitude' id="longitude">
            <!-- <h6><h6>Note:</h6></p> -->
        </div>
        <div class="col s12">
            <div class="row">
                <div class="col s6 right">
                    <a class="btn btn-small waves-effect waves-light green-grey" style="margin-top:10px; width:100%" type="button" onclick="initMap(firstLt, firstLg)">Lokasi Saat Ini</a>
                </div>
            <!-- </div> -->
        </div>
    </div>
</div>
</form>