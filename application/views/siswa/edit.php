<!DOCTYPE html>
<html>
<head>
	<title>Belajar Export Dan Import Data</title>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>Belajar Export Dan Import Data</h2>
				<h3>Data Siswa</h3>
				<a href="<?php echo base_url() ?>index.php/siswa"><button type="button" class="btn btn-warning btn-sm">Kembali</button></a>
				<br><br>
				
				<?php foreach ($data_siswa as $row) { 
					$jenis_kelamin = $row->jenis_kelamin;
				?>
				<form action="<?php echo base_url() ?>index.php/siswa/update" method="post">
				  <input type="hidden" name="id_siswa" value="<?php echo $row->id_siswa ?>">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Nama</label>
				    <input type="text" class="form-control" name="nama" value="<?php echo $row->nama ?>">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Jenis Kelamin</label>
				    <select class="form-control" name="jenis_kelamin">
				    	<option value="Laki-Laki" <?php if($jenis_kelamin == 'Laki-Laki'){ echo 'selected'; } ?> >Laki - Laki</option>
				    	<option value="Perempuan" <?php if($jenis_kelamin == 'Perempuan'){ echo 'selected'; } ?> >Perempuan</option>
				    </select>
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Alamat</label>
				    <input type="text" class="form-control" name="alamat" value="<?php echo $row->alamat ?>">
				  </div>
				  <button type="submit" class="btn btn-primary">Update</button>
				</form>
				<?php } ?>

			</div>
		</div>
	</div>

</body>
</html>