<?php
	// error_reporting(0);
	session_start();
	include_once "library/inc.connection.php";
	include_once "library/inc.library.php";
	$aksi 	= $_GET['aksi'];
	$menu	= $_GET['menu'];
	
	switch($aksi){
		case 'autoCom';
			switch ($menu){
				case 'returBeli';
					$page 	= $_GET['page']; // get the requested page
					$limit 	= $_GET['rows']; // get how many rows we want to have into the grid
					$sidx 	= $_GET['sidx']; // get index row - i.e. user click to sort
					$sord 	= $_GET['sord']; // get the direction
					$searchTerm = $_GET['searchTerm'];

					if(!$sidx) $sidx =1;
					if ($searchTerm=="") {
						$searchTerm="%";
					} else {
						$searchTerm = "%" . $searchTerm . "%";
					}

					$result = mysql_query("SELECT count(*) as COUNT 
											FROM(
												SELECT
													kd_bahan AS kode,
													nm_bahan AS nama,
													harga_bahan AS harga
												FROM
													bahanbaku
												UNION
													SELECT
														kd_barang AS kode,
														nm_barang AS nama,
														harga_beli AS harga
													FROM
														barang
												)tbretur
											where nama like '$searchTerm'");
					$row 	= mysql_fetch_array($result,MYSQL_ASSOC);
					$count 	= $row['count'];

					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start 	= $limit*$page - $limit; // do not put $limit*($page - 1)
					if($total_pages!=0) {
						//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
						$SQL = "SELECT * FROM
								(
								SELECT
									kd_bahan AS kode,
									nm_bahan AS nama,
									harga_bahan AS harga
								FROM
									bahanbaku
								UNION
									SELECT
										kd_barang AS kode,
										nm_barang AS nama,
										harga_beli AS harga
									FROM
										barang
								)tbretur
								where nama LIKE '$searchTerm'
								ORDER BY
									'$sidx' '$sord'
								LIMIT $start,$limit";
					}else {
						//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord";
						$SQL = "SELECT * FROM
								(
								SELECT
									kd_bahan AS kode,
									nm_bahan AS nama,
									harga_bahan AS harga
								FROM
									bahanbaku
								UNION
									SELECT
										kd_barang AS kode,
										nm_barang AS nama,
										harga_beli AS harga
									FROM
										barang
								)tbretur
								where nama LIKE '$searchTerm'
								ORDER BY
									'$sidx' '$sord'";
					}
					//var_dump($SQL);exit();
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
					//var_dump($result);exit();
					// new $response;
					// $response->page = $page;
					// $response->total = $total_pages;
					// $response->records = $count;
					$i=0;
					$response=array();
					// while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
					while($row = mysql_fetch_assoc($result)) {
						$response[] = array(
								'kode'=>$row['kode'],
								'nama'=>$row['nama'],
								'harga'=>format_angka($row['harga']),
						);
						// $response->rows[$i]['kode']=$row['kode'];
						// $response->rows[$i]['nama']=$row['nama'];
						// format_angka($response->rows[$i]['harga']=$row['harga']);
						// $i++;
					}        
					echo json_encode($response);
				break;				

				case 'transJasa';
					$page 	= $_GET['page']; // get the requested page
					$limit 	= $_GET['rows']; // get how many rows we want to have into the grid
					$sidx 	= $_GET['sidx']; // get index row - i.e. user click to sort
					$sord 	= $_GET['sord']; // get the direction
					$searchTerm = $_GET['searchTerm'];

					if(!$sidx) $sidx =1;
					if ($searchTerm=="") {
						$searchTerm="%";
					} else {
						$searchTerm = "%" . $searchTerm . "%";
					}

					$result = mysql_query("SELECT COUNT(*) AS count FROM jasa WHERE nama_jasa like '$searchTerm'");
					$row 	= mysql_fetch_array($result,MYSQL_ASSOC);
					$count 	= $row['count'];

					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start 	= $limit*$page - $limit; // do not put $limit*($page - 1)
					if($total_pages!=0) {
						//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
						$SQL = "SELECT
									*
								FROM
									jasa j,
									bahanbaku b
								WHERE
									j.kd_bahan = b.kd_bahan
								AND j.nama_jasa LIKE '$searchTerm'
								ORDER BY
									'$sidx' '$sord'
								LIMIT $start,$limit";
					}else {
						//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord";
						$SQL = "SELECT
									*
								FROM
									jasa j,
									bahanbaku b
								WHERE
									j.kd_bahan = b.kd_bahan
								AND j.nama_jasa LIKE '$searchTerm'
								ORDER BY
									'$sidx' '$sord'";
					}
					//var_dump($SQL);exit();
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
					//var_dump($result);exit();

					// $response->page = $page;
					// $response->total = $total_pages;
					// $response->records = $count;

					// $i=0;
					// while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
					// $rows=array();
					while($row = mysql_fetch_assoc($result)) {
						$rows[]= array(
							'kd_jasa'     =>$row['kd_jasa'],
							'nama_jasa'   =>$row['nama_jasa'],
							'kd_bahan'    =>$row['kd_bahan'],
							'nm_bahan'    =>$row['nm_bahan'],
							'harga_jasa'  =>format_angka($row['harga_jasa']),
							'harga_bahan' =>$row['harga_bahan'],
							'diskon'      =>$row['diskon'],
							'ppn'         =>$row['ppn'],
							'diskon'      =>$row['diskon']
						);

						/*$response->rows[$i]['kd_jasa']=$row['kd_jasa'];
						$response->rows[$i]['nama_jasa']=$row['nama_jasa'];
						$response->rows[$i]['kd_bahan']=$row['kd_bahan'];
						$response->rows[$i]['nm_bahan']=$row['nm_bahan'];
						format_angka($response->rows[$i]['harga_jasa']=$row['harga_jasa']);
						$response->rows[$i]['harga_bahan']=$row['harga_bahan'];
						$response->rows[$i]['diskon']=$row['diskon'];
						$response->rows[$i]['ppn']=$row['ppn'];
						$response->rows[$i]['diskon']=$row['diskon'];
						$i++;*/
					}     

					$response=array(
						'page'    =>$page,
						'total'   =>$total_pages,
						'records' =>$count,
						'rows'    =>$rows
					);
   
					echo json_encode($response);

				break;				
				
				case 'transBeli';
					$page 	= $_GET['page']; // get the requested page
					$limit 	= $_GET['rows']; // get how many rows we want to have into the grid
					$sidx 	= $_GET['sidx']; // get index row - i.e. user click to sort
					$sord 	= $_GET['sord']; // get the direction
					$searchTerm = $_GET['searchTerm'];

					if(!$sidx) $sidx =1;
					if ($searchTerm=="") {
						$searchTerm="%";
					} else {
						$searchTerm = "%" . $searchTerm . "%";
					}

					//$result = mysql_query("SELECT COUNT(*) AS count FROM barang WHERE nm_barang like '$searchTerm'");
					$result = mysql_query("SELECT count(*) as COUNT 
											FROM(
												SELECT
													kd_bahan AS kode,
													nm_bahan AS nama,
													harga_bahan AS harga
												FROM
													bahanbaku
												UNION
													SELECT
														kd_barang AS kode,
														nm_barang AS nama,
														harga_beli AS harga
													FROM
														barang
												)tbretur
											where nama like '$searchTerm'");
					$row 	= mysql_fetch_array($result,MYSQL_ASSOC);
					$count 	= $row['count'];

					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start 	= $limit*$page - $limit; // do not put $limit*($page - 1)
					if($total_pages!=0) 
						$SQL = "SELECT * FROM
							(
							SELECT
								kd_bahan AS kode,
								nm_bahan AS nama,
								harga_bahan AS harga
							FROM
								bahanbaku
							UNION
								SELECT
									kd_barang AS kode,
									nm_barang AS nama,
									harga_beli AS harga
								FROM
									barang
							)tbretur
							where nama LIKE '$searchTerm'
							ORDER BY
								'$sidx' '$sord'
							LIMIT $start,$limit";

								//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
					else 
						$SQL = "SELECT * FROM
								(
								SELECT
									kd_bahan AS kode,
									nm_bahan AS nama,
									harga_bahan AS harga
								FROM
									bahanbaku
								UNION
									SELECT
										kd_barang AS kode,
										nm_barang AS nama,
										harga_beli AS harga
									FROM
										barang
								)tbretur
								where nama LIKE '$searchTerm'
								ORDER BY
									'$sidx' '$sord'";
						//$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord";
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

					$response->page = $page;
					$response->total = $total_pages;
					$response->records = $count;
					$i=0;
					while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
						$response->rows[$i]['kode']=$row['kode'];
						$response->rows[$i]['nama']=$row['nama'];
						$response->rows[$i]['harga']=$row['harga'];
						$i++;
					}        
					echo json_encode($response);

				break;				
				
				case 'transJual';
					$page 	= $_GET['page']; // get the requested page
					$limit 	= $_GET['rows']; // get how many rows we want to have into the grid
					$sidx 	= $_GET['sidx']; // get index row - i.e. user click to sort
					$sord 	= $_GET['sord']; // get the direction
					$searchTerm = $_GET['searchTerm'];

					if(!$sidx) $sidx =1;
					if ($searchTerm=="") {
						$searchTerm="%";
					} else {
						$searchTerm = "%" . $searchTerm . "%";
					}

					$result = mysql_query("SELECT COUNT(*) AS count FROM barang WHERE nm_barang like '$searchTerm'");
					$row 	= mysql_fetch_array($result,MYSQL_ASSOC);
					$count 	= $row['count'];

					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start 	= $limit*$page - $limit; // do not put $limit*($page - 1)
					if($total_pages!=0) 
						$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
					else 
						$SQL = "SELECT * FROM barang WHERE nm_barang like '$searchTerm'  ORDER BY $sidx $sord";
					$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

					$response->page = $page;
					$response->total = $total_pages;
					$response->records = $count;
					$i=0;
					while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
						$response->rows[$i]['kd_barang']=$row['kd_barang'];
						$response->rows[$i]['nm_barang']=$row['nm_barang'];
						$response->rows[$i]['harga_jual']=$row['harga_jual'];
						$i++;
					}        
					echo json_encode($response);

				break;
			}
		break;
		
		case 'cek':
			switch($menu){
				case 'transJasa':
					if(isset($_GET['jml']) and $_GET['jml']!=''){
						$sql 	= "select jml_bahan from bahanbaku where kd_bahan ='$_GET[kd_bhn]'";	
						//var_dump($sql);exit();
						$exe	= mysql_query($sql);
						$res	= mysql_fetch_assoc($exe);
						$stok	= $res['jml_bahan'];
						$jml	= $_GET['jml'];
						//var_dump($stok);exit();
						
						if($stok==0){ // stok ksong
							echo '{"status":"stok kosong"}';
						}else{ // stok ada
							if($jml>$stok){ // melebihi stok
								echo '{"status":"max '.$stok.'"}';
							}else{ // stok  mencukupi
								echo '{"status":""}';
							}
						}
					}
				break;
				
				case 'addJasa':
					if(isset($_GET['jasaAwal'])){ //edit
						$sql = "select * from jasa where nama_jasa ='$_GET[jasa]' and not(nama_jasa ='$_GET[jasaAwal]')";
					}else{
						$sql = "select * from jasa where nama_jasa ='$_GET[jasa]'";
					}
					//var_dump($sql);exit();
					$exe = mysql_query($sql);
					//var_dump($exe);exit();
					$jum = mysql_num_rows($exe);
						
					if($jum==0){ //tidak ditemukan
						echo '{"status":"tersedia"}';
					}else{ //ditemukan
						echo '{"status":"terpakai"}';
					}
				break;	
			}
		break;
		
		case 'delRow':
			switch($menu){
				//transaksi : jasa 
				case 'transJasa':
					//if(isset($_GET['id'])){
						$sql	= "select jml_bahan from tmp_jasa where  id='$_GET[id]'";
						//var_dump($sql);exit();
						$exe	= mysql_query($sql);
						$res	= mysql_fetch_assoc($exe);
						$jml	= $res['jml_bahan'];
						
						if($exe){
							$sql2	= "delete from tmp_jasa where id='$_GET[id]'";
							//var_dump($sql);exit();
							$exe2	=mysql_query($sql2)or die();
							if($exe2){
								$sql3	= "update bahanbaku set jml_bahan = jml_bahan + $jml where  kd_bahan='$_GET[kd_bhn]'";
								//var_dump($sql3);exit();
								$exe3	= mysql_query($sql3);
								//var_dump($exe3);exit();
								if($exe3){
									echo '{"status":""}';	
								}else{
									echo '{"status":"gagal_mengupdate_bahanbaku"}';
								}
							}else{
								echo '{"status":"gagal_menghapus_tmpJasa"}';
							}
						}
					//}
				break;
			}
		break;
		
		case 'loadRow':
			switch($menu){
				//transaksi : jasa 
				case 'transJasa':
					$sql2	= "select 
									tj.*, 
									j.*, 
									bb.kd_bahan,
									bb.jml_bahan as stok,
									bb.nm_bahan,
									bb.harga_bahan
									
								from 
									jasa j,
									tmp_jasa tj,
									bahanbaku bb
								where 
									tj.kd_bahan = bb.kd_bahan and 
									j.kd_jasa	= tj.kd_jasa and 
									bb.kd_bahan	= j.kd_bahan and 
									tj.userid	= '$_SESSION[SES_LOGIN]'";
					//var_dump($sql2);exit();
					$exe2	= mysql_query($sql2);
					$dataArr = array();
					while($res2	= mysql_fetch_assoc($exe2)){
						$hrgkotor= $res2['harga_jasa'] +($res2['harga_bahan']  * $res2['jml_bahan']  );
						$hrgppn 	= $hrgkotor * $res2['ppn']/ 100;
						$hrgdiskon	= $hrgkotor * $res2['diskon']/ 100;
						$subtotal= $harga_kotor + $hrgppn - $hrgdiskon;
						$dataArr[]=
							array(
								'id_tmp'=>$res2['id'],
								'kd_jasa'=>$res2['kd_jasa'],
								'nama_jasa'=>$res2['nama_jasa'],
								'harga_jasa'=>$res2['harga_jasa'],
								'kd_bahan'=>$res2['kd_bahan'],
								'stok'=>$res2['stok'],
								'jml_bahan'=>$res2['jml_bahan'],
								'nama_bahan'=>$res2['nm_bahan'],
								'ppn'=>$res2['ppn'],
								'diskon'=>$res2['diskon'],
								'harga_bahan'=>$res2['harga_bahan'],
								'sub_total'=>$subtotal
								
								
						);
					}
					if($exe2){ //jalan query
						if($dataArr!=NULL){ //ada data
							echo json_encode($dataArr);
							//var_dump($dataArr);exit();
						}else{
							echo '{"status":"tmp_jasa_kosong"}';	
						}
					}else{
						echo '{"status":"ambil_tmp_jasa_gagal"}';	
					}
				break;
			}
		break;

		case 'addRow':
			switch($menu){
				//transaksi : jasa 
				case 'transJasa':
					if(isset($_GET['kd_jasa'],$_GET['kd_bahan'],$_GET['jml'])){
						$sql	= "insert into tmp_jasa set kd_jasa 	= '$_GET[kd_jasa]',
															kd_bahan	= '$_GET[kd_bahan]',
															jml_bahan	= '$_GET[jml]',
															userid		= '$_SESSION[SES_LOGIN]'";
						//var_dump($sql);exit();
						$exe	= mysql_query($sql);
						//var_dump($exe);exit();
						if($exe){
							$sql2	= "update bahanbaku set jml_bahan = jml_bahan - '$_GET[jml]' where kd_bahan = '$_GET[kd_bahan]'";
							$exe2	= mysql_query($sql2);
							if($exe2){
								echo '{"status":""}';
							}else{
								echo '{"status":"gagal_mengurangi_stok"}';
							}
						}else{
							echo '{"status":"gagal_menambahkan_jasa"}';	
						}
					}//end of query tmp_jasa berjalan
				break;
			}
		break;
		
		case 'combo':
			switch($menu){
				//master : jasa - add
				case 'addJasa':
					if(isset($_GET['idBahan'])){
						$sql	= "select * from bahanbaku where kd_bahan='$_GET[idBahan]'";
						//var_dump($sql);exit();
						$exe	= mysql_query($sql) or die();
						$res 	= mysql_fetch_assoc($exe);
						if($exe){
							echo '{"status":"sukses","harga_bahan":"'.$res['harga_bahan'].'"}';
						}else{
							echo '{"status":"gagal"}';
						}
					}
				break;
				case 'transJasa':
					if(isset($_GET['idJasa'])){
						$sql	= "	select * 
									from bahanbaku b, jasa j 
									where 
										j.kd_jasa 	= '$_GET[idJasa]' and 
										j.kd_bahan	= b.kd_bahan 
										";
						//var_dump($sql);exit();
						$exe	= mysql_query($sql) or die();
						$res 	= mysql_fetch_assoc($exe);
						if($exe){
							echo '{
									"status":"sukses",
									"nm_bahan":"'.$res['nm_bahan'].'",
									"kd_bahan":"'.$res['kd_bahan'].'"
									}';
						}else{
							echo '{"status":"gagal"}';
						}
					}
				break;
				
				case 'transJasa2':
					if(isset($_GET['menu']) and $_GET['menu']=='transJasa2'){
						$sql	= "	select kd_jasa,nama_jasa as nm_jasa
									from jasa 
									where kd_jasa not in
									(		select kd_jasa
											from tmp_jasa 
											where 
												userid 	= '$_SESSION[SES_LOGIN]' 
									)";
						//var_dump($sql);exit();
						$exe	= mysql_query($sql) or die();
						$dataArr= array();
						
						while($res 	= mysql_fetch_assoc($exe)){
							$dataArr[]=$res;
						}
						if(!$exe){
							echo '{"status":"gagl_kueri_ambil_jasa"}';
						}else{
							if($dataArr!=NULL){
								//echo '{"status":"sukses",'.json_encode($dataArr).'}';
								echo json_encode($dataArr);
								/*echo '{
										"status":"sukses",
										"nm_bahan":"'.$res['nama_jasa'].'",
										"kd_bahan":"'.$res['kd_bahan'].'"
										}';*/
							}else{
								echo '{"status":"kosong"}';
							}
						}
					}
				break;
			}
		break;
	}
?>