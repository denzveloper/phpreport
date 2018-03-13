<?php
include ("./res.php");
if($pre!=1)
	header("Location: ./index.php");
$temp="";
if(isset($_GET['cal']))
	$tglvl=$_GET['cal'];
else
	$tglvl="$now-$bln-$har";
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tampil Data :: <?php echo $appnam; ?> <?php echo $ver; ?></title>
	<!-- Bootstrap -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/styles.css" rel="stylesheet">
	<style>
		body {
			background-color: #fbfbfb;
		}
	</style>
</head>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<body>
<div class="navbar-wrapper">
      <div class="container">
        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Silahkan Pilih menunya..</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php"><?php echo $appnam; ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="<?php echo $a;?>">Membuat Laporan</a></li>
                <li><a href="<?php echo $b;?>">Verifikasi Laporan</a></li>
                <li class="active"><a href="<?php echo $c;?>">Lihat Laporan</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo singkat($nama); ?> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
					          <li><a href="<?php echo $x;?>">Edit Profil</a></li>
                    <li><a href="<?php echo $y;?>">Akun Manager</a></li>
                    <li><a href="<?php echo $z;?>">Buat akun baru</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">Log Keluar</li>
                    <li><a href="logout.php" onclick="return confirm('<?php echo $nama;?> Yakin, ingin keluar dari <?php echo $appnam ?>?')"><?php echo "$nama";?></a></li>
                  </ul>
                </li>
              </ul>
              </ul>
            </div>
          </div>
        </nav>

      </div>
    </div>
    <div class="container marketing">
    <div class="col-lg-4">
    <form role="form" action="" method="get">
    <select name="area" class="form-control" required autofocus>
    	<?php
    	$ambil=mysqli_query($koneksi, "SELECT * FROM data ORDER BY lokasi");
    	while($data=mysqli_fetch_array($ambil)){
    		if($temp!=$data['lokasi']){
    			$temp=$data['lokasi'];
    			echo "<option value='$temp'>$temp</option>";
    		}
    	}
    	?>
    </select>
	</div>
	<div class="col-lg-3">
    <input type="date" name="cal" value="<?php echo "$tglvl"; ?>" class="form-control" required autofocus>
	</div>
	<div class="col-lg-2">
		<button type="submit" name="lihat" class="btn btn-info btn-block"><span class="glyphicon glyphicon-open-file"></span>&nbsp;Tampil Data</button>
	</div>
	<div class="col-lg-2">
		<?php if(isset($_GET['lihat'])&&isset($_GET['cal'])&&isset($_GET['area'])){
			echo '<a href="tampil.php" class="btn btn-default btn-block"><span class="glyphicon glyphicon-menu-left"></span>&nbsp;Kembali</a>'; } ?>
	</div>
	<div class="col-lg-1">
        &nbsp;
	</div>
	</form>
	<br /><br />
	<?php
	if(isset($_GET['del'])){
		$idata=$_GET['id'];
		$query=mysqli_query($koneksi, "SELECT * FROM data WHERE id='$idata'");
		$data=mysqli_fetch_array($query);
		$yrs=$data[tahun];
		$bula=$data[bulan];
		$hr=$data[day];
		$bul=bulanan($data[bulan]);
		if($pre==1){
         $ok=mysqli_query($koneksi, "DELETE FROM data WHERE id='$idata' AND day='$hr' AND bulan='$bula' AND tahun='$yrs' AND verifikasi=1");
          if($ok){
            $bul=bulanan($data[bulan]);
            echo "<br /><br /><div class='alert alert-warning'><b>Data Dihapus!</b><br />Data pada Tanggal: \"$data[day] $bul $data[tahun]\" untuk wilayah \"$lokasi\" Telah dihapus!<br /></div>
          <a href='./tampil.php' title='Kembali..'>&#171;Kembali..</a>";
          }
          else{
            echo '<div class="alert alert-danger"><b>Galat!</b><br />Maaf, Ada kesalahan terjadi pada saat menghapus!<br /><i>Coba Lagi nanti..</i></div><a href="./tampil.php" title="Kembali..">&#171;Kembali..</a>';
          }
    	}
    	exit;
	}
	if(isset($_GET['lihat'])&&isset($_GET['cal'])&&isset($_GET['area'])){
	list($yrs,$bul,$hr)=explode("-", $_GET['cal']);
	$temp=addslashes(trim($_GET['area']));
	$data=mysqli_query($koneksi, "SELECT * FROM data WHERE lokasi='$temp' AND day='$hr' AND bulan='$bul' AND tahun='$yrs' AND verifikasi=1");
	$data=mysqli_fetch_array($data);
	if(isset($data)==""){
		echo "<table class='table table-striped table-hover table-responsive vtext'><tr><td align='center'>
        <h1><span class='text-muted glyphicon glyphicon glyphicon-question-sign'></span></h1><h3>Data Tidak ada!</h3>
      <sub>Data yang diminta tidak tersedia untuk saat ini. Silahkan, coba lagi nanti.</sub><br /><br /></td></tr></table>";
	}else{
	$bul=bulanan($data[bulan]);
	echo "
	<h3 align='center'>HASIL LAPORAN</h3>
	<table>
	<tr><th>Disusun</th><td>: <i>$data[creator]</td></tr>
    <tr><th>Diverifikasi&nbsp;</th><td>: <i>$data[ver_by]</td></tr>
    <tr><th>Tanggal</th><td>: <i>$data[day] $bul $data[tahun]</td></tr>
    </table>
    <hr />
    <h4>Laporan Distribusi</h4>
    <div style='overflow-x:auto;'>
    <table cellpadding='10' align='center' class='table table-bordered table-responsive'>
    <tr align='center' class='bg-primary'>
        <td colspan='3'>SPK PENUTUPAN</td>
        <td rowspan='2'> SPK PENYAMBUNGAN KEMBALI</td>
        <td rowspan='2'> SPK PENGGANTI WATER MATER</td>
        <td rowspan='2'> SPK PEMASANGAN WATER METER SL BARU</td>
    </tr>
    <tr align='center' class='bg-primary'>
    	<td>DIKELUARKAN</td>
    	<td>DILAKSANAKAN</td>
    	<td>TIDAK DILAKSANAKAN</td>
    </tr>
    <tr align='center'>
        <td style='padding: 5px'>$data[spk_keluar]</td>
        <td style='padding: 5px'>$data[spk_dilak]</td>
        <td style='padding: 5px'>$data[spk_tdkdilak]</td>
        <td style='padding: 5px'>$data[spk_pk]</td>
        <td style='padding: 5px'>$data[spk_water]</td>
        <td style='padding: 5px'>$data[spk_slbaru]</td>  
    </tr>
	</table>
	</div>
	<h4>Pengaduan Teknik</h4>
	<div style='overflow-x:auto;'>
	<table cellpadding='10' align='center' class='table table-bordered table-responsive'>
    <tr align='center' class='bg-primary'>
        <td rowspan='2'> KUALITAS </td>
        <td rowspan='2'> KUANTITAS </td>
        <td rowspan='2'> KONTINUITAS </td>
        <td rowspan='2'> KEBOCORAN </td>
        <td rowspan='2'> LAIN-LAIN </td>
        <td colspan='2'>PEMUTUSAN</td>
    </tr>
    <tr align='center' class='bg-primary'>
        <td>KU</td>
        <td>SL</td>
    </tr>
    <tr align='center'>
        <td style='padding: 5px'>$data[kl]</td>
        <td style='padding: 5px'>$data[ku]</td>
        <td style='padding: 5px'>$data[kon]</td>
        <td style='padding: 5px'>$data[bocor]</td>
        <td style='padding: 5px'>$data[dll]</td>
        <td style='padding: 5px'>$data[putus_ku2]</td>
        <td style='padding: 5px'>$data[putus_sl2]</td>
    </tr>
	</table>
	</div>
	<h4>Laporan Hublang</h4>
    <div style='overflow-x:auto;'>
	<table cellpadding='10' align='center' class='table table-bordered table-responsive'>
    <tr align='center' class='bg-primary'>
        <td colspan='2' rowspan='2' align='center'> JUMLAH <br>SL BARU </td>
        <td colspan='8' align='center' style='padding: 10px'> SAMBUNG LANGGAN </td>
    </tr>
    <tr align='center' class='bg-primary'>
        <td colspan='2'> BARU </td>
        <td colspan='2'> PENYAMBUNGAN KEMBALI </td>
        <td colspan='2'> MUTASI TARIF </td>
        <td colspan='2'> PEMUTUSAN </td>
        </tr>
    <tr align='center' class='bg-primary'>
        <td>SL</td><td>KU</td>
        <td>SL</td><td>KU</td>
        <td>SL</td><td>KU</td>
        <td>SL</td><td>KU</td>
        <td>SL</td><td>KU</td>
    </tr>
    <tr align='center'>
        <td style='padding: 5px'>$data[jml_sl]</td>
        <td style='padding: 5px'>$data[jml_ku]</td>
        <td style='padding: 5px'>$data[baru_sl]</td>
        <td style='padding: 5px'>$data[baru_ku]</td>
        <td style='padding: 5px'>$data[pk_sl]</td>
        <td style='padding: 5px'>$data[pk_ku]</td>
        <td style='padding: 5px'>$data[mutarif_sl]</td>
        <td style='padding: 5px'>$data[mutarif_ku]</td>
        <td style='padding: 5px'>$data[putus_sl1]</td>
        <td style='padding: 5px'>$data[putus_ku1]</td>
    </tr>
	</table>
	</div>
	<hr />
	<form role='form' action='' method='get'>
    <input type='hidden' name='id' value='$data[id]' />
    <div class='col-lg-8'>
        &nbsp;
    </div>
    <div class='col-lg-2'>
    <button type='submit' name='del' class='btn btn-danger btn-block' onclick=\"return confirm('Dengan ini data akan dihapus, dan tidak dapat dikembalikan lagi. Yakin?')\"><span class='glyphicon glyphicon-trash'></span>&nbsp;Hapus</button><br />
    </form>
    </div>
    <div class='col-lg-2'>
    <a href='./act.php?id=$data[id]&show=' class='btn btn-warning btn-block' target='_blank'><span class='glyphicon glyphicon-print'></span>&nbsp;Print view</a>
    </div>
    ";}
	}
    else 
    	echo "<table class='table table-striped table-hover table-responsive vtext'><tr><td align='center'>
        <h1><span class='text-muted glyphicon glyphicon glyphicon-book'></span></h1><h3>Data Belum Dipilih!</h3>
      <sub>Silahkan pilih datanya dulu dengan mengisi form diatas.</sub><br /><br /></td></tr></table>";
	?>
    <div class="bwh">
		<p><strong><?php echo "$appnam <i>$ver</i>"; ?></strong> <b>-</b> <i>PDAM &amp; POLINDRA &#169; <?php echo $begin . (($begin != $now) ? '-' . $now : ''); ?></i></p>
	</div>
    </div>
</body>
</html>