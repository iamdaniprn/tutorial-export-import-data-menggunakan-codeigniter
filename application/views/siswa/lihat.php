<!DOCTYPE html>
<html>
<head>
	<title>Belajar Export Dan Import Data</title>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h2>Belajar Export Dan Import Data</h2>
		<h3>Data Siswa</h3>
		<a href="<?php echo base_url() ?>index.php/siswa"><button type="button" class="btn btn-warning btn-sm">Kembali</button></a>
		<br><br>
		    <?php foreach ($data_siswa as $row) {
		    ?>

		    <div class="card" style="width: 30rem;">
			  <div class="card-header">
			    Profil Siswa
			  </div>
			  <div class="card-body">
			    <h5 class="card-title"><?php echo $row->nama ?></h5>
			    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row->jenis_kelamin ?></h6>
			    <p class="card-text"><?php echo $row->alamat ?></p>
			  </div>
			</div>
		        
		    <?php } ?>
	</div>

</body>
</html>