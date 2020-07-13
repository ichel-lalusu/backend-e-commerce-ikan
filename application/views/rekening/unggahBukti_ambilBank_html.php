            <?php
            foreach($dataRekening->result() as $data){
            ?>
            <option value="<?=$data->kode_bank?>"><?=$data->nama_bank?></option>
            <?php
            }
            ?>
