<!DOCTYPE html>
<html>
<head>
	<title>Belajar Export Dan Import Data</title>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Belajar Export Dan Import Data</h2>
				<h3>Data Siswa</h3>
				<a href="<?php echo base_url() ?>index.php/siswa"><button type="button" class="btn btn-warning btn-sm">Kembali</button></a>
				<br><br>
					
				<span><b>Untuk Melakukan Import data harus disiapkan terlebih dahulu Data Excel Berformat <span style="color: red">.xlsx .xls .csv .ods </span> atau <span style="color: red">.ots</span> Maksimal Ukuran <span style="color: red">1 MB</span></b></span>
				<br>
				<span>Download contoh format file excel disini</span>
				<a href="<?php echo base_url() ?>index.php/siswa/download"><button type="button" class="btn btn-info btn-sm">Download</button></a>
				<hr>

				<form action="<?php echo base_url() ?>index.php/siswa/import_excel" method="post" enctype="multipart/form-data">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Masukkan File Excel disini</label>
				    <input type="file" name="file">
				  </div>
				  
				  <button type="submit" class="btn btn-primary">Import</button>
				</form>
			</div>
		</div>
	</div>

</body>
</html>