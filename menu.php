<?php
if(isset($_SESSION['SES_ADMIN'])){
	echo "<ul><li><a href='?page' title='Halaman Utama'>Home</a></li></ul>";
	echo "<ul><li><a href='?page=Data-User' title='Data User'>Data User</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Kategori' title='Data Kategori'>Data Kategori</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Supplier' title='Data Supplier'>Data Supplier</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Pelanggan' title='Data Pelanggan'>Data Pelanggan</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Barang' title='Data Barang'>Data Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Bahan' title='Data Bahan Baku'>Data Bahan Baku</a></li></ul>";
	echo "<ul><li><a href='?page=Data-Jasa' title='Data Jasa'>Data Jasa</a></li></ul>";
	echo "<ul><li><a href='?page=Pembelian-Barang' title='Pembelian Barang'>Pembelian Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Penjualan-Barang' title='Penjualan Barang'>Penjualan Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Transaksi-Jasa' title='Transaksi Jasa'>Transaksi Jasa</a></li></ul>";
	echo "<ul><li><a href='?page=Retur-Pembelian' title='Retur Pembelian'>Retur Pembelian</a></li></ul>";
	echo "<ul><li><a href='?page=Laporan-Stok-Barang' title='Laporan Stok Barang'>Laporan Stok Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Laporan-Data' title='Laporan Data'>Laporan Data</a></li></ul>";
	echo "<ul><li><a href='?page=Logout' title='Logout (Exit)'>Logout</a></li></ul>";
}
elseif(isset($_SESSION['SES_KASIR'])){
	echo "<ul><li><a href='?page' title='Halaman Utama'>Home</a></li></ul>";
	#echo "<ul><li><a href='?page=Pembelian-Barang' title='Pembelian Barang'>Pembelian Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Penjualan-Barang' title='Penjualan Barang'>Penjualan Barang</a></li></ul>";
	echo "<ul><li><a href='?page=Transaksi-Jasa' title='Transaksi Jasa'>Transaksi Jasa</a></li></ul>";
	echo "<li><a href='?page=Lap-Penjualan-Perperiode' title='Daftar Penjualan Periode'>Laporan Transaksi Penjualan Periode</a></li>";
	echo "<ul><li><a href='?page=Logout' title='Logout (Exit)'>Logout</a></li></ul>";
}
else {
	echo "<ul><li><a href='?page=Login' title='Login System'>Login</a></li></ul>";	
}
?>