<!DOCTYPE html>
<html>
<head>
	<title>Belajar Export Dan Import Data</title>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jquery.dataTables.min.css">
</head>
<body>
	<div class="container">
		<h2>Belajar Export Dan Import Data</h2>
		<h3>Data Siswa</h3>
		<a href="<?php echo base_url() ?>index.php/siswa/import"><button type="button" class="btn btn-primary btn-sm">Import Siswa</button></a>
		<a href="<?php echo base_url() ?>index.php/siswa/export_pdf"><button type="button" class="btn btn-success btn-sm">Export PDF</button></a>
		<a href="<?php echo base_url() ?>index.php/siswa/export_excel_1"><button type="button" class="btn btn-info btn-sm">Export Excel 2016</button></a>
		<a href="<?php echo base_url() ?>index.php/siswa/export_excel_2"><button type="button" class="btn btn-dark btn-sm">Export Excel 2010</button></a>
		<a href="<?php echo base_url() ?>index.php/siswa/export_csv"><button type="button" class="btn btn-danger btn-sm">Export CSV</button></a>
		<br><br>
		<table id="myTable" class="table table-striped table-bordered" style="width:100%">
		    <thead class="thead-dark">
		        <tr>
		            <th>No</th>
		            <th>Nama</th>
		            <th>Jenis Kelamin</th>
		            <th>Alamat</th>
		            <th>Opsi</th>
		        </tr>
		    </thead>
		    <tbody>
		    <?php $no=1; foreach ($data_siswa as $row) {
		    ?>
		        <tr>
		            <td><?php echo $no++; ?></td>
		            <td><?php echo $row->nama ?></td>
		            <td><?php echo $row->jenis_kelamin ?></td>
		            <td><?php echo $row->alamat ?></td>
		            <td>
		            	<a href="<?php echo base_url() ?>index.php/siswa/lihat/<?php echo $row->id_siswa ?>"><button type="button" class="btn btn-success btn-sm">Lihat</button></a>
		            	<a href="<?php echo base_url() ?>index.php/siswa/edit/<?php echo $row->id_siswa ?>"><button type="button" class="btn btn-primary btn-sm">Edit</button></a>
		            	<a href="<?php echo base_url() ?>index.php/siswa/hapus/<?php echo $row->id_siswa ?>"><button type="button" class="btn btn-danger btn-sm">Hapus</button></a>
		            </td>
		        </tr>
		    <?php } ?>
		    </tbody>
		</table>
	</div>

</body>
</html>

<script src="<?php echo base_url()?>assets/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.dataTables.min.js"></script>

<script>
  	$(document).ready( function () {
    	$('#myTable').DataTable();
    });
</script>