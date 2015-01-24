<?php
if($_GET) {
	switch ($_GET['page']){				
		case '' :				
			if(!file_exists ("main.php")) die ("Empty Main Page!"); 
			include "main.php";						
		break;
		case 'HalamanUtama' :				
			if(!file_exists ("main.php")) die ("Sorry Empty Page!"); 
			include "main.php";						
		break;			
		case 'Login' :				
			if(!file_exists ("login.php")) die ("Sorry Empty Page!"); 
			include "login.php";						
		break;
		case 'Login-Validasi' :				
			if(!file_exists ("login_validasi.php")) die ("Sorry Empty Page!"); 
			include "login_validasi.php";						
		break;
		case 'Logout' :				
			if(!file_exists ("login_out.php")) die ("Sorry Empty Page!"); 
			include "login_out.php";						
		break;		

		# USER LOGIN
		case 'Data-User' :				
			if(!file_exists ("user_data.php")) die ("Sorry Empty Page!"); 
			include "user_data.php";	 break;		
		case 'Add-User' :				
			if(!file_exists ("user_add.php")) die ("Sorry Empty Page!"); 
			include "user_add.php";	 break;		
		case 'Delete-User' :				
			if(!file_exists ("user_delete.php")) die ("Sorry Empty Page!"); 
			include "user_delete.php"; break;		
		case 'Edit-User' :				
			if(!file_exists ("user_edit.php")) die ("Sorry Empty Page!"); 
			include "user_edit.php"; break;	

		# USER SUPPLIER / PEMASOK
		case 'Data-Supplier' :				
			if(!file_exists ("supplier_data.php")) die ("Sorry Empty Page!"); 
			include "supplier_data.php";	 break;		
		case 'Add-Supplier' :				
			if(!file_exists ("supplier_add.php")) die ("Sorry Empty Page!"); 
			include "supplier_add.php";	 break;		
		case 'Delete-Supplier' :				
			if(!file_exists ("supplier_delete.php")) die ("Sorry Empty Page!"); 
			include "supplier_delete.php"; break;		
		case 'Edit-Supplier' :				
			if(!file_exists ("supplier_edit.php")) die ("Sorry Empty Page!"); 
			include "supplier_edit.php"; break;	
			
			# USER PELANGGAN 
		case 'Data-Pelanggan' :				
			if(!file_exists ("pelanggan_data.php")) die ("Sorry Empty Page!"); 
			include "pelanggan_data.php";	 break;		
		case 'Add-Pelanggan' :				
			if(!file_exists ("pelanggan_add.php")) die ("Sorry Empty Page!"); 
			include "pelanggan_add.php";	 break;		
		case 'Delete-Pelanggan' :				
			if(!file_exists ("pelanggan_delete.php")) die ("Sorry Empty Page!"); 
			include "pelanggan_delete.php"; break;		
		case 'Edit-Pelanggan' :				
			if(!file_exists ("pelanggan_edit.php")) die ("Sorry Empty Page!"); 
			include "pelanggan_edit.php"; break;	
			
		# DATA KATEGORI
		case 'Data-Kategori' :				
			if(!file_exists ("kategori_data.php")) die ("Sorry Empty Page!"); 
			include "kategori_data.php"; break;		
		case 'Add-Kategori' :				
			if(!file_exists ("kategori_add.php")) die ("Sorry Empty Page!"); 
			include "kategori_add.php"; break;		
		case 'Delete-Kategori' :				
			if(!file_exists ("kategori_delete.php")) die ("Sorry Empty Page!"); 
			include "kategori_delete.php"; break;		
		case 'Edit-Kategori' :				
			if(!file_exists ("kategori_edit.php")) die ("Sorry Empty Page!"); 
			include "kategori_edit.php"; break;		
		
		# DATA BARANG
		case 'Data-Barang' :				
			if(!file_exists ("barang_data.php")) die ("Sorry Empty Page!"); 
			include "barang_data.php"; break;		
		case 'Add-Barang' :				
			if(!file_exists ("barang_add.php")) die ("Sorry Empty Page!"); 
			include "barang_add.php"; break;	
		case 'Search-Barang' :				
			if(!file_exists ("cari_barang.php")) die ("Sorry Empty Page!"); 
			include "cari_barang.php"; break;
		case 'Delete-Barang' :				
			if(!file_exists ("barang_delete.php")) die ("Sorry Empty Page!"); 
			include "barang_delete.php"; break;		
		case 'Edit-Barang' :				
			if(!file_exists ("barang_edit.php")) die ("Sorry Empty Page!"); 
			include "barang_edit.php"; break;		
		
		# DATA BAHAN BAKU
		case 'Data-Bahan' :				
			if(!file_exists ("bahanbaku_data.php")) die ("Sorry Empty Page!"); 
			include "bahanbaku_data.php"; break;		
		case 'Add-Bahan' :				
			if(!file_exists ("bahanbaku_add.php")) die ("Sorry Empty Page!"); 
			include "bahanbaku_add.php"; break;	
		case 'Search-Bahan' :				
			if(!file_exists ("cari_bahanbaku.php")) die ("Sorry Empty Page!"); 
			include "cari_bahanbaku.php"; break;	
		case 'Delete-Bahan' :				
			if(!file_exists ("bahanbaku_delete.php")) die ("Sorry Empty Page!"); 
			include "bahanbaku_delete.php"; break;		
		case 'Edit-Bahan' :				
			if(!file_exists ("bahanbaku_edit.php")) die ("Sorry Empty Page!"); 
			include "bahanbaku_edit.php"; break;	
			
		#DATA JASA
		case 'Data-Jasa' :				
			if(!file_exists ("jasa_data.php")) die ("Sorry Empty Page!"); 
			include "jasa_data.php"; break;		
		case 'Add-Jasa' :				
			if(!file_exists ("jasa_add.php")) die ("Sorry Empty Page!"); 
			include "jasa_add.php"; break;
		case 'Search-Jasa' :				
			if(!file_exists ("cari_jasa.php")) die ("Sorry Empty Page!"); 
			include "cari_jasa.php"; break;
		case 'Delete-Jasa' :				
			if(!file_exists ("jasa_delete.php")) die ("Sorry Empty Page!"); 
			include "jasa_Delete.php"; break;
		case 'Edit-Jasa' :				
			if(!file_exists ("jasa_edit.php")) die ("Sorry Empty Page!"); 
			include "jasa_edit.php"; break;
			
		# DATA PEMBELIAN BARANG (BARANG MASUK)	
		case 'Pembelian-Barang' :				
			if(!file_exists ("transaksi_pembelian.php")) die ("Sorry Empty Page!"); 
			include "transaksi_pembelian.php"; break;		
			
		# DATA PENJUALAN BARANG (BARANG MASUK)	
		case 'Penjualan-Barang' :				
			if(!file_exists ("transaksi_penjualan.php")) die ("Sorry Empty Page!"); 
			include "transaksi_penjualan.php"; break;	
			
		# DATA RETUR PEMBELIAN(SUPPLIER)
		case 'Retur-Pembelian' :				
			if(!file_exists ("retur_beli.php")) die ("Sorry Empty Page!"); 
			include "retur_beli.php"; break;	
			
		# DATA TRANSAKSI JASA
		case 'Transaksi-Jasa' :				
			if(!file_exists ("transaksi_jasa.php")) die ("Sorry Empty Page!"); 
			include "transaksi_jasa.php"; break;
			
		# DATA Stok Barang
		case 'Laporan-Stok-Barang' :				
			if(!file_exists ("laporan_stok.php")) die ("Sorry Empty Page!"); 
			include "laporan_stok.php"; break;
		#case 'Search-Stok' :				
			#if(!file_exists ("cari_stok.php")) die ("Sorry Empty Page!"); 
			#include "cari_stok.php"; break;

		# MASTER DATA
		case 'Laporan-Data' :				
				echo "<ul><li><a href='?page=Daftar-Supplier' title='Daftar Supplier'>Laporan Daftar Supplier</a></li>";
				echo "<li><a href='?page=Daftar-Kategori' title='Daftar Kategori'>Laporan Daftar Kategori</a></li>";
				echo "<li><a href='?page=Daftar-Barang' title='Daftar Barang'>Laporan Daftar Barang</a></li>";
				echo "<li><a href='?page=Daftar-Jasa' title='Daftar Jasa'>Laporan Daftar Jasa</a></li>";
				echo "<li><a href='?page=Daftar-Barang-Kategori' title='Daftar Barang'>Laporan Daftar Barang Per Kategori</a></li>";
				echo "<li><a href='?page=Daftar-Jasa-Kategori' title='Daftar Jasa'>Laporan Daftar Jasa Per Kategori</a></li>";
				echo "<li><a href='?page=Daftar-Pembelian' title='Daftar Pembelian'>Laporan Transaksi Pembelian</a></li>";
				echo "<li><a href='?page=Daftar-Penjualan' title='Daftar Penjualan'>Laporan Transaksi Penjualan</a></li>";
				echo "<li><a href='?page=Daftar-Transaksi-Jasa' title='Daftar Transaksi Jasa'>Laporan Transaksi Jasa</a></li>";
				echo "<li><a href='?page=Daftar-Retur-Pembelian' title='Daftar Retur Pembelian'>Laporan Retur Pembelian</a></li>";
				echo "<li><a href='?page=Lap-Penjualan-Perperiode' title='Daftar Penjualan Periode'>Laporan Transaksi Penjualan Periode</a></li>";
				#echo "<li><a href='?page=Lap-Pembelian-Perperiode' title='Daftar Pembelian Periode'>Laporan Transaksi Pembelian Periode</a></li>";
				echo "<li><a href='?page=Lap-Omzet-Penjualan' title='Daftar Omzet Penjualan'>Laporan Omzet Penjualan</a></li>";
				echo "<li><a href='?page=Daftar-Petugas' title='Daftar Petugas'>Daftar Petugas</a></li></ul>";
		break;		
		
		# INFORMASI DAN LAPORAN
		case 'Daftar-Supplier' :				
			if(!file_exists ("daftar_supplier.php")) die ("Sorry Empty Page!"); 
			include "daftar_supplier.php"; break;		
		case 'Daftar-Kategori' :				
			if(!file_exists ("daftar_kategori.php")) die ("Sorry Empty Page!"); 
			include "daftar_kategori.php"; break;		
		case 'Daftar-Barang' :				
			if(!file_exists ("daftar_barang.php")) die ("Sorry Empty Page!"); 
			include "daftar_barang.php"; break;	
		case 'Daftar-Jasa' :				
			if(!file_exists ("daftar_jasa.php")) die ("Sorry Empty Page!"); 
			include "daftar_jasa.php"; break;	
		case 'Daftar-Barang-Kategori' :				
			if(!file_exists ("daftar_barang_kategori.php")) die ("Sorry Empty Page!"); 
			include "daftar_barang_kategori.php"; break;		
		case 'Daftar-Barang-Kategori-Show' :				
			if(!file_exists ("daftar_barang_kategori_show.php")) die ("Sorry Empty Page!"); 
			include "daftar_barang_kategori_show.php"; break;	
		case 'Daftar-Jasa-Kategori' :				
			if(!file_exists ("daftar_jasa_kategori.php")) die ("Sorry Empty Page!"); 
			include "daftar_jasa_kategori.php"; break;
		case 'Daftar-Jasa-Kategori-Show' :				
			if(!file_exists ("daftar_jasa_kategori_show.php")) die ("Sorry Empty Page!"); 
			include "daftar_jasa_kategori_show.php"; break;
		case 'Daftar-Petugas' :				
			if(!file_exists ("daftar_petugas.php")) die ("Sorry Empty Page!"); 
			include "daftar_petugas.php"; break;		
		case 'Daftar-Pembelian' :				
			if(!file_exists ("daftar_pembelian.php")) die ("Sorry Empty Page!"); 
			include "daftar_pembelian.php"; break;	
		case 'Search-Pembelian' :				
			if(!file_exists ("cari_pembelian.php")) die ("Sorry Empty Page!"); 
			include "cari_pembelian.php"; break;
		case 'Search-Penjualan' :				
			if(!file_exists ("cari_penjualan.php")) die ("Sorry Empty Page!"); 
			include "cari_penjualan.php"; break;
		case 'Search-Penjualan' :				
			if(!file_exists ("cari_jasa.php")) die ("Sorry Empty Page!"); 
			include "cari_jasa.php"; break;
		case 'Daftar-Pembelian-List' :				
			if(!file_exists ("daftar_pembelian_list.php")) die ("Sorry Empty Page!"); 
			include "daftar_pembelian_list.php"; break;		
		case 'Daftar-Penjualan' :				
			if(!file_exists ("daftar_penjualan.php")) die ("Sorry Empty Page!"); 
			include "daftar_penjualan.php"; break;		
		case 'Daftar-Penjualan-List' :				
			if(!file_exists ("daftar_penjualan_list.php")) die ("Sorry Empty Page!"); 
			include "daftar_penjualan_list.php"; break;			
		case 'Daftar-Retur-Pembelian' :				
			if(!file_exists ("daftar_returbeli.php")) die ("Sorry Empty Page!"); 
			include "daftar_returbeli.php"; break;
		case 'Daftar-Retur-Pembelian-List' :				
			if(!file_exists ("daftar_returbeli_list.php")) die ("Sorry Empty Page!"); 
			include "daftar_returbeli_list.php"; break;
		case 'Lap-Penjualan-Perperiode' :				
			if(!file_exists ("daftar_penjualan_per_periode.php")) die ("Sorry Empty Page!"); 
			include "daftar_penjualan_per_periode.php"; break;	
		#case 'Lap-Pembelian-Perperiode' :				
			#if(!file_exists ("daftar_pembelian_per_periode.php")) die ("Sorry Empty Page!"); 
			#include "daftar_pembelian_per_periode.php"; break;
		case 'Lap-Omzet-Penjualan' :				
			if(!file_exists ("daftar_omzet_penjualan.php")) die ("Sorry Empty Page!"); 
			include "daftar_omzet_penjualan.php"; break;	
		case 'Daftar-Transaksi-Jasa' :				
			if(!file_exists ("daftar_tjasa.php")) die ("Sorry Empty Page!"); 
			include "daftar_tjasa.php"; break;	
		case 'Daftar-Transaksi-Jasa-List' :				
			if(!file_exists ("daftar_tjasa_list.php")) die ("Sorry Empty Page!"); 
			include "daftar_tjasa_list.php"; break;
						
		default:
			if(!file_exists ("main.php")) die ("Empty Main Page!"); 
			include "main.php";						
		break;
	}
}
?>