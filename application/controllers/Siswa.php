<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//load file autoload.php
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Siswa extends CI_Controller {

	public function __construct()
	{
      	parent::__construct();
      	$this->load->model('m_siswa');

      	//load library php excel
      	$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));

      	//load library pdf.php di folder library
      	$this->load->library('pdf'); 
	}

	public function index()
	{
		$data['data_siswa'] = $this->m_siswa->data_siswa();
		$this->load->view('siswa/data_siswa', $data);
	}

	public function import()
	{
		$this->load->view('siswa/import');
	}


	public function import_excel(){
        $fileName = $this->input->post('file', TRUE);

		$config['upload_path'] = './assets/'; 
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
		$config['max_size'] = 10000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config); 
		  
	  	if (!$this->upload->do_upload('file')) {
		    $error = array('error' => $this->upload->display_errors());
		    $this->session->set_flashdata('msg','Ada kesalah dalam upload'); 
		    redirect('Welcome'); 
	  	}else{
		    $media = $this->upload->data();
		    $inputFileName = 'assets/'.$media['file_name'];
         
	        try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            }catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }
	 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
             
            for ($row = 2; $row <= $highestRow; $row++){                  //  Read a row of data into an array                 
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE
                );
                                                 
                //Sesuaikan sama nama kolom tabel di database  
                $data = array(
                    "nama"=> $rowData[0][0],
                    "jenis_kelamin"=> $rowData[0][1],
                    "alamat"=> $rowData[0][2]
                );
                 
                //sesuaikan nama dengan nama tabel
                $insert = $this->db->insert("tb_siswa", $data);
                delete_files($media['file_path']);
	                     
            }
	   }

        redirect('siswa/');
    }

	public function download()
	{
		force_download('Format_Import.xlsx',NULL);
	}


	public function export_excel_1(){

		$data  = $this->m_siswa->data_siswa();
        
        $total = $this->m_siswa->total_data_siswa()->num_rows();

	    $tanggal_sekarang = date('d-m-Y');

      	$spreadsheet = new Spreadsheet;
      	$spreadsheet->setActiveSheetIndex(0);

      	$spreadsheet->getActiveSheet()->mergeCells('A1:D1');
      	$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
		$spreadsheet->getActiveSheet()->setCellValue('A1', 'CONTOH EXPORT KE FILE EXCEL FORMAT .XLSX');
		$spreadsheet->getActiveSheet()->setCellValue('A2', 'HAYU NGODING');

        $sheet = $spreadsheet->getActiveSheet();

        // Kode untuk menambahkan Gambar 
  //       $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		// $drawing->setName('Paid');
		// $drawing->setDescription('Paid');
		// $drawing->setPath('assets/img/logo.png'); // put your path and image here
		// $drawing->setCoordinates('A1');
		// $drawing->setOffsetX(11);
		// // $drawing->setRotation(25);
		// $drawing->getShadow()->setVisible(true);
		// $drawing->getShadow()->setDirection(5);
		// $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->setActiveSheetIndex(0)
                  ->setCellValue('A4', 'NO')
                  ->setCellValue('B4', 'NAMA LENGKAP')
                  ->setCellValue('C4', 'JENIS KELAMIN')
                  ->setCellValue('D4', 'ALAMAT');

        //unutuk menentukan kolom pada isi data
      	$kolom = 5;
      	$nomor = 1;

      	foreach($data as $laporan) {

           $spreadsheet->setActiveSheetIndex(0)
                       ->setCellValue('A' . $kolom, $nomor)
                       ->setCellValue('B' . $kolom, $laporan->nama)
                       ->setCellValue('C' . $kolom, $laporan->jenis_kelamin)
                       ->setCellValue('D' . $kolom, $laporan->alamat);
           $kolom++;
           $nomor++;

	     }

	      $writer = new Xlsx($spreadsheet);
	      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

	      // untuk style pada kolom dan baris tertentu
		  $styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		   ];

		   $styleArray2 = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

			$styleArray3 = [
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

		  $total_border = $total + 4; // baris 4 adalah mulai pembuatan border

		  // $kepala_ruangan = $total_border + 2;
		  // $ketua 		  = $kepala_ruangan +1;
		  // $nama           = $kepala_ruangan + 5;
		  // $nama_2         = $kepala_ruangan + 6;

		  // untuk inisial dan memberikan style
		  $sheet->getStyle('A4:D'.$total_border)->applyFromArray($styleArray);
		  $sheet->getStyle('A1:A3')->applyFromArray($styleArray2);
		  $sheet->getStyle('A1:D1')->applyFromArray($styleArray2);
		  $sheet->getStyle('A4:D4')->applyFromArray($styleArray2);


		  // untuk memberikan tanda tangan 
	   //    $spreadsheet->getActiveSheet()->mergeCells('C'.$kepala_ruangan.':I'.$kepala_ruangan);
	   //    $spreadsheet->getActiveSheet()->mergeCells('C'.$ketua.':D'.$ketua);
		  // $spreadsheet->getActiveSheet()->setCellValue('C'.$kepala_ruangan, 'Bandung, '.$tanggal_sekarang);
		  // $spreadsheet->getActiveSheet()->setCellValue('C'.$ketua, 'Ketua PPDD Online');
		  // $sheet->getStyle('C'.$kepala_ruangan.':D'.$kepala_ruangan)->applyFromArray($styleArray3);
		  // $sheet->getStyle('C'.$ketua.':D'.$ketua)->applyFromArray($styleArray3);

		  // $spreadsheet->getActiveSheet()->mergeCells('C'.$nama.':D'.$nama);
		  // $spreadsheet->getActiveSheet()->setCellValue('C'.$nama, 'Suryo Sumaryoko, S.Pd. M.Si');
		  // $sheet->getStyle('C'.$nama.':D'.$nama)->applyFromArray($styleArray2);

		  // $spreadsheet->getActiveSheet()->mergeCells('C'.$nama_2.':D'.$nama_2);
		  // $spreadsheet->getActiveSheet()->setCellValue('C'.$nama_2, 'NIP: 196905201996012001');
		  // $sheet->getStyle('C'.$nama_2.':D'.$nama_2)->applyFromArray($styleArray3);

	      header('Content-Type: application/vnd.ms-excel');
		  header('Content-Disposition: attachment;filename="Data Siswa.xlsx"');
		  header('Cache-Control: max-age=0');

  	  	  $writer->save('php://output');
    }

    public function export_excel_2(){

		$data  = $this->m_siswa->data_siswa();
        
        $total = $this->m_siswa->total_data_siswa()->num_rows();

	    $tanggal_sekarang = date('d-m-Y');

      	$spreadsheet = new Spreadsheet;
      	$spreadsheet->setActiveSheetIndex(0);

      	$spreadsheet->getActiveSheet()->mergeCells('A1:D1');
      	$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
		$spreadsheet->getActiveSheet()->setCellValue('A1', 'CONTOH EXPORT KE FILE EXCEL FORMAT .XLS');
		$spreadsheet->getActiveSheet()->setCellValue('A2', 'HAYU NGODING');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)
                  ->setCellValue('A4', 'NO')
                  ->setCellValue('B4', 'NAMA LENGKAP')
                  ->setCellValue('C4', 'JENIS KELAMIN')
                  ->setCellValue('D4', 'ALAMAT');

        //unutuk menentukan kolom pada isi data
      	$kolom = 5;
      	$nomor = 1;

      	foreach($data as $laporan) {

           $spreadsheet->setActiveSheetIndex(0)
                       ->setCellValue('A' . $kolom, $nomor)
                       ->setCellValue('B' . $kolom, $laporan->nama)
                       ->setCellValue('C' . $kolom, $laporan->jenis_kelamin)
                       ->setCellValue('D' . $kolom, $laporan->alamat);
           $kolom++;
           $nomor++;

	     }

	      $writer = new Xlsx($spreadsheet);
	      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

	      // untuk style pada kolom dan baris tertentu
		  $styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		   ];

		   $styleArray2 = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

			$styleArray3 = [
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

		  $total_border = $total + 4; // baris 4 adalah mulai pembuatan border

		  // untuk inisial dan memberikan style
		  $sheet->getStyle('A4:D'.$total_border)->applyFromArray($styleArray);
		  $sheet->getStyle('A1:A3')->applyFromArray($styleArray2);
		  $sheet->getStyle('A1:D1')->applyFromArray($styleArray2);
		  $sheet->getStyle('A4:D4')->applyFromArray($styleArray2);

	      header('Content-Type: application/vnd.ms-excel');
		  header('Content-Disposition: attachment;filename="Data Siswa.xls"');
		  header('Cache-Control: max-age=0');

  	  	  $writer->save('php://output');        
    }

    public function export_csv(){

		$data  = $this->m_siswa->data_siswa();
        
        $total = $this->m_siswa->total_data_siswa()->num_rows();

	    $tanggal_sekarang = date('d-m-Y');

      	$spreadsheet = new Spreadsheet;
      	$spreadsheet->setActiveSheetIndex(0);

      	$spreadsheet->getActiveSheet()->mergeCells('A1:D1');
      	$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
		$spreadsheet->getActiveSheet()->setCellValue('A1', 'CONTOH EXPORT KE FILE EXCEL FORMAT .CSV');
		$spreadsheet->getActiveSheet()->setCellValue('A2', 'HAYU NGODING');

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)
                  ->setCellValue('A4', 'NO')
                  ->setCellValue('B4', 'NAMA LENGKAP')
                  ->setCellValue('C4', 'JENIS KELAMIN')
                  ->setCellValue('D4', 'ALAMAT');

        //unutuk menentukan kolom pada isi data
      	$kolom = 5;
      	$nomor = 1;

      	foreach($data as $laporan) {

           $spreadsheet->setActiveSheetIndex(0)
                       ->setCellValue('A' . $kolom, $nomor)
                       ->setCellValue('B' . $kolom, $laporan->nama)
                       ->setCellValue('C' . $kolom, $laporan->jenis_kelamin)
                       ->setCellValue('D' . $kolom, $laporan->alamat);
           $kolom++;
           $nomor++;

	     }

	      $writer = new Xlsx($spreadsheet);
	      $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	      $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

	      // untuk style pada kolom dan baris tertentu
		  $styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		   ];

		   $styleArray2 = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

			$styleArray3 = [
			    'alignment' => [
			        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			];

		  $total_border = $total + 4; // baris 4 adalah mulai pembuatan border

		  // untuk inisial dan memberikan style
		  $sheet->getStyle('A4:D'.$total_border)->applyFromArray($styleArray);
		  $sheet->getStyle('A1:A3')->applyFromArray($styleArray2);
		  $sheet->getStyle('A1:D1')->applyFromArray($styleArray2);
		  $sheet->getStyle('A4:D4')->applyFromArray($styleArray2);

	      header('Content-Type: application/vnd.ms-excel');
		  header('Content-Disposition: attachment;filename="Data Siswa.csv"');
		  header('Cache-Control: max-age=0');

  	  	  $writer->save('php://output');        
    }

    public function export_pdf()
	{
		// set ukuran kertas disini
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();

		// unutk menambahkan Logo
		$pdf->Image('assets/img/logo.png',10,8,20);

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(193,5,'CONTOH EXPORT KE FILE PDF',0,1,'C');

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(193,5,'HAYU NGODING',0,1,'C');

		
		//Buat Garis
		$pdf->SetLineWidth(1);
		$pdf->Line(10,31,200,31); // 31 adalah jarak dari atas kebawah atau posisi garis dan 200 adalah lebar garis
		$pdf->SetLineWidth(0);
		$pdf->Line(10,32,200,32);

		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(10,13,'',0,1);
		$pdf->Cell(193,11,'DATA SISWA',0,1,'C');
		// Memberikan space kebawah agar tidak terlalu rapat

		$pdf->Cell(10,2,'',0,1);

		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(12,6,'NO',1,0);
		$pdf->Cell(45,6,'NAMA LENGKAP',1,0);
		$pdf->Cell(30,6,'JENIS KELAMIN',1,0);
		$pdf->Cell(103,6,'ALAMAT',1,1);

		$pdf->SetFont('Arial','',9);

		$no  = 1;
		$biodata = $this->m_siswa->data_siswa();
		foreach ($biodata as $row){
			$pdf->Cell(12,6,$no++,1,0);
		    $pdf->Cell(45,6,$row->nama,1,0);
		    $pdf->Cell(30,6,$row->jenis_kelamin,1,0);
		    $pdf->Cell(103,6,$row->alamat,1,1);
		}

		// untuk memeberikan tanda tangan
		// $tanggal_sekarang = date('d-m-Y');
		// $pdf->SetFont('Arial','',9);
		// $pdf->Cell(10,7,'',0,1);
		// $pdf->Cell(145);
		// $pdf->Cell(190,4,'Bandung, '.$tanggal_sekarang,0,1,'L');
		// $pdf->Cell(145);
		// $pdf->Cell(190,4,'Ketua Panitia PPDB',0,1,'L');

		// $pdf->Cell(10,6,'',0,1);

		// $pdf->SetFont('Arial','B',9);
		// $pdf->Cell(10,7,'',0,1);
		// $pdf->Cell(145);
		// $pdf->Cell(190,4,'Anonim, S.Pd. M.Si',0,1,'L');
		// $pdf->SetFont('Arial','',9);
		// $pdf->Cell(145);
		// $pdf->Cell(190,4,'NIP: 196905201996012001',0,1,'L');


		// untuk memberikan barcode
		//EAN13 test
		$pdf->EAN13(10,280,'1234567809',10,0.35,9);

		$pdf->Output('','Data Siswa.pdf','');
	}

	public function lihat($id_siswa)
	{
		$data['data_siswa'] = $this->m_siswa->lihat($id_siswa);
		$this->load->view('siswa/lihat', $data);
	}

	public function edit($id_siswa)
	{
		$data['data_siswa'] = $this->m_siswa->edit($id_siswa);
		$this->load->view('siswa/edit', $data);
	}

	public function update()
	{
		$id_siswa = $this->input->post('id_siswa', true);

		$data = [
            "nama" => $this->input->post('nama', true),
            "jenis_kelamin" => $this->input->post('jenis_kelamin', true),
            "alamat" => $this->input->post('alamat', true)
        ];

        $update = $this->m_siswa->update($data, $id_siswa);
        if($update){
        	redirect(base_url().'index.php/siswa');
        }else{
			$this->load->view('siswa/tambah');
		}
	}

	public function hapus($id_siswa)
	{
		$hapus = $this->m_siswa->hapus($id_siswa);
		if($hapus){
			redirect(base_url().'index.php/siswa');
		}
	}
}
