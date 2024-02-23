<form method="POST" name="frmeditstatus" id="frmeditstatus" action="<?php echo $_SERVER['PHP_SELF'] ?>">
   <input type="hidden" id="aksistatus" name="aksistatus" value="hapusprodukorder">
   <input type="hidden" id="dnopesan" name="dnopesan" value="<?php echo $nopesan ?>">
   <input type="hidden" name="urlfolder" id="urlfolder" value="<?php echo URL_PROGRAM_ADMIN.folder.'?op=info&pid='.$nopesan ?>">
   <input type="hidden" id="didproduk" name="didproduk" value="<?php echo $produkid ?>">
   <input type="hidden" id="diddetail" name="diddetail" value="<?php echo $iddetail ?>">
   <input type="hidden" id="didmember" name="didmember" value="<?php echo $idmember ?>">
   <input type="hidden" id="didgrup" name="didgrup" value="<?php echo $idgrup ?>">
   <input type="hidden" id="dqtylama" name="dqtylama" value="<?php echo $qty ?>">
   <input type="hidden" id="duklama" name="duklama" value="<?php echo $ukuran ?>">
   <input type="hidden" id="dwnlama" name="dwnlama" value="<?php echo $warna ?>">
   <input type="hidden" id="dtotlama" name="dtotlama" value="<?php echo $subtotal ?>">
   <input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM_ADMIN.'order' ?>">
   <input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM_ADMIN.'order/?op=info&pid='.$nopesan ?>">
   <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	    <a class="close" data-dismiss="modal">&times;</a>
	 	<h4 class="modal-title">Hapus Produk</h4>
       </div>
      <div class="modal-body">
	    <div id="hasileditstatus" style="display:none"></div>
	    <p>Apakah Anda ingin menghapus produk "<b><?php echo $produknm ?></b>" ?</p>
		<div class="form-group">
		   <a onclick="hapusprodukorder()" class="btn btn-sm btn-success">Ya</a>
		   <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal" id="btnclose">Tidak</button>
		</div>

      </div>
	</div>
   </div>
</form>