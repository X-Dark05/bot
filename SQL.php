<?php
class menu{
	private $objMysql;
	public function __construct($package){
		$this->objMysql = mysqli_connect("127.0.0.1","root","","androidNative",3306);
		if($this->objMysql){
			$this->getAllMenu($package);
		}else{
			echo '{"status":"0","info":"Connection error"}';
		}
	}
	private function getInformation($package){
		$select	= mysqli_query($this->objMysql,"select colorDefault,colorPressed,color3D,package.name as serverName,if(images.name is null,'-',images.name) as backgroundURL,backgroundType,backgroundCard,iconCard,colorTextHint,colorText,if(logoInternal is NULL,'-',logoInternal) as logoInternal,if(logoEksternal is NULL,'-',logoEksternal) as logoEksternal,versionCode,created,serverUrl,updateUrl,registerStatus from package inner join color using(idColor) left join images using(idImages) where packageName='$package'");
		$data = mysqli_fetch_assoc($select);
		return $data;
	}
	private function getSettings(){
		$response = array();
		$select	= mysqli_query($this->objMysql,"select name,url from settings");
		while($row = mysqli_fetch_assoc($select)){
			$response[$row['name']] = $row['url'];
		}
		return $response;
	}
	private function getCallCenter($package){
		$response = array();
		$select	= mysqli_query($this->objMysql,"select icon,title,content from callCenter inner join package using(idPackage) where packageName='$package'");
		while($row = mysqli_fetch_assoc($select)){
			$helper = array();
			$helper['icon'] = $row['icon'];
			$helper['title'] = $row['title'];
			$helper['content'] = $row['content'];
			array_push($response,$helper);
		}
		return $response;
	}
	private function getMenu($package){
		$response = array();
		$response['menu'] = array();
		$response['sidebar'] = array();
		$response['multifinance'] = array();
		$response['tv'] = array();
		$response['pasca'] = array();
		$response['umroh'] = array();
		$response['mlm'] = array();
		$response['mitra'] = array();
		$response['saldo_okesip'] = array();
		$response['asuransi'] = array();
		
		$select = mysqli_query($this->objMysql,"select groupMenu.name as groupMenu,gomTitle,gomDescription,systemMenu.name as systemMenu,if(images.name is null,'-',images.name) as images,if(images.type is null,'-',images.type) as imagesType,if(input is null,'-',input) as input,menu.position as position,title,if(description is null,'',description) as description,if(descriptionUpdate is null,'',descriptionUpdate) as descriptionUpdate from menu inner join package using(idPackage) inner join groupMenu using(idGroupMenu) left join groupOnMenu using(idGroupOnMenu) inner join systemMenu using(idSystemMenu) left join images ON menu.idImages=images.idImages where packageName='$package' and status='ON' order by groupOnMenu.position asc, menu.position asc");
		while($row	= mysqli_fetch_assoc($select)){
			$helper	= array();
			$helper['systemMenu'] = $row['systemMenu'];
			$helper['images'] = $row['images'];
			$helper['imagesType'] = $row['imagesType'];
			$helper['input'] = $row['input'];
			$helper['position'] = $row['position'];
			$helper['title'] = $row['title'];
			$helper['description'] = $row['description'];
			$helper['gomTitle'] = $row['gomTitle'];
			$helper['gomDescription'] = $row['gomDescription'];
			$helper['descriptionUpdate'] = $row['descriptionUpdate'];
			
			array_push($response[$row['groupMenu']],$helper);
		}
		return $response;
	}
	private function getAds($package){
		$response = array();
		$response['top'] = array();
		$response['bottom'] = array();
		$select = mysqli_query($this->objMysql,"select url, link, gravity from ads inner join package using(idPackage) where packageName='$package'");
		while($row = mysqli_fetch_assoc($select)){
			if($row['gravity']=="top"){
				array_push($response[$row['gravity']],$row['url']);	
			}else{
				array_push($response[$row['gravity']],$row);
			}
		}
		return $response;
	}
	private function getAllMenu($package){
		$information = $this->getInformation($package);
		$menu = $this->getMenu($package);
		$ads = $this->getAds($package);
		$response = array();
		$response['theme'] = array();
		$response['theme']['ColorDefault'] = $information['colorDefault'];
		$response['theme']['ColorPressed'] = $information['colorPressed'];
		$response['theme']['Color3D'] = $information['color3D'];
		$response['theme']['BackgroundURL'] = $information['backgroundURL'];
		$response['theme']['BackgroundCard'] = $information['backgroundCard'];
		$response['theme']['BackgroundType'] = $information['backgroundType'];
		$response['theme']['IconCard'] = $information['iconCard'];
		$response['theme']['ColorTextHint'] = $information['colorTextHint'];
		$response['theme']['ColorText'] = $information['colorText'];
		if($package=='com.guava.manis.mobile.payment.laskar.muda'){
			$response['theme']['PrintInformation'] = 'Bersama Kita Bisa|Kita Bisa Karena Bersama||';
		}else if($package=='com.mobile.payment.kios.digital'){
			$response['theme']['PrintInformation'] = file_get_contents('printKios.txt');
		}else if($package=='com.kios.digital'){
			$response['theme']['PrintInformation'] = file_get_contents('printKios.txt');
		}else{
			$response['theme']['PrintInformation'] = file_get_contents('printInformation.txt');
		}
		$response['server'] = array();
		$response['server']['name'] = $information['serverName'];
		$response['server']['created'] = $information['created'];
		$response['server']['serverUrl'] = $information['serverUrl'];
		$response['server']['updateUrl'] = $information['updateUrl'];
		$response['server']['kecamatanUrl'] = 'http://202.158.48.173/androidNative/php/address_kecamatan.php';
		$response['server']['kelurahanUrl'] = 'http://202.158.48.173/androidNative/php/address_kelurahan.php';
		$response['server']['versionCode'] = $information['versionCode'];
		$response['server']['logoInternal'] = $information['logoInternal'];
		$response['server']['logoEksternal'] = $information['logoEksternal'];
		$response['server']['registerStatus'] = $information['registerStatus'];
		//$response['server']['developer'] = 'https://facebook.com/rachmat.setiawan.37625/';
		$response['server']['developer'] = '';
		$response['CallCenter'] = $this->getCallCenter($package); 
		$response['settings'] = $this->getSettings();
		$response['menu'] = $menu['menu'];
		$response['sidebar'] = $menu['sidebar'];
		$response['multifinance'] = $menu['multifinance'];
		$response['pasca'] = $menu['pasca'];
		$response['tv'] = $menu['tv'];
		$response['saldo_okesip'] = $menu['saldo_okesip'];
		$response['asuransi'] = $menu['asuransi'];
		$response['umroh'] = $menu['umroh'];
		$response['mlm'] = $menu['mlm'];
		$response['mitra'] = $menu['mitra'];
		$response['ads'] = $ads['top'];
		$response['ads2'] = $ads['bottom'];
		echo json_encode($response);
		mysqli_close($this->objMysql);
	}
}
