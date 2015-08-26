<?php
class mycurl {
	protected $_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
	protected $_header = array(
							'Content-Type: text/plain',
							"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5", 
							"Accept-Language: ru-ru,ru;q=0.7,en-us;q=0.5,en;q=0.3", 
							"Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7", "Keep-Alive: 300"
							);
	protected $_url;
	protected $_user;
	protected $_pass;
	protected $_followlocation;
	protected $_timeout = 60;
	protected $_maxRedirects;
	//protected $_cookieFileLocation = './cookie.txt';
	protected $_post = 0;
	protected $_postFields;
	protected $_referer = "http://www.google.com";

	protected $_session;
	protected $_webpage;
	protected $_includeHeader;
	protected $_ssl = false;
	protected $_ssl_version = 3;

	protected $_noBody;
	protected $_status;
	protected $_binaryTransfer;
	public $authentication = 1;
	public $auth_name = '';
	public $auth_pass = '';

	public function useAuth($use) {
		$this -> authentication = 0;
		if ($use == true)
			$this -> authentication = 1;
	}

	public function setName($name) {
		$this -> auth_name = $name;
	}

	public function setPass($pass) {
		$this -> auth_pass = $pass;
	}

	public function __construct($url, $user = "", $pass = "", $followlocation = true, $timeOut = 600, $maxRedirecs = 4, $binaryTransfer = false, $includeHeader = false, $noBody = false) {
		$this -> _url = $url;
		$this -> _user = $user;
		$this -> _pass = $pass;
		$this -> _followlocation = $followlocation;
		$this -> _timeout = $timeOut;
		$this -> _maxRedirects = $maxRedirecs;
		$this -> _noBody = $noBody;
		$this -> _includeHeader = $includeHeader;
		$this -> _binaryTransfer = $binaryTransfer;
		//$this -> _cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
	}

	public function setReferer($referer) {
		$this -> _referer = $referer;
	}

	public function setCookiFileLocation($path) {
		$this -> _cookieFileLocation = $path;
	}

	public function setPost($postFields) {
		$this -> _post = true;
		$this -> _postFields = $postFields;
	}

	public function setUserAgent($userAgent) {
		$this -> _useragent = $userAgent;
	}

	public function createCurl($url = 'nul') {
		if ($url != 'nul') {
			$this -> _url = $url;
		}
		$s = curl_init();
		curl_setopt($s, CURLOPT_URL, $this -> _url);
		curl_setopt($s, CURLOPT_POST, $this->_post);
		curl_setopt($s, CURLOPT_HTTPHEADER, $this->_header);
		curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
		curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
		curl_setopt($s, CURLOPT_COOKIESESSION, true);
		//curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
		//curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
		if ($this->authentication == 1) {
			curl_setopt($s, CURLOPT_USERPWD, $this->_user . ":" . $this->_pass);
		}
		if ($this->_post) {
			curl_setopt($s, CURLOPT_POST, true);
			curl_setopt($s, CURLOPT_POSTFIELDS, $this -> _postFields);
		}
		if ($this->_includeHeader)
			curl_setopt($s, CURLOPT_HEADER, false);
		if ($this->_ssl)
			curl_setopt($s, CURLOPT_SSLVERSION, $this->_ssl_version);
		curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($s, CURLOPT_SSL_VERIFYHOST, false);
		if ($this->_noBody) curl_setopt($s, CURLOPT_NOBODY, true);
		 //if($this->_binary){curl_setopt($s,CURLOPT_BINARYTRANSFER,true);}
		curl_setopt($s, CURLOPT_USERAGENT, $this -> _useragent);
		curl_setopt($s, CURLOPT_REFERER, $this -> _referer);

		$result = curl_exec($s);
		$this -> _webpage = $result;
		$this -> _status = curl_getinfo($s, CURLINFO_HTTP_CODE);
		curl_close($s);

	}

	public function getHttpStatus() {
		return $this -> _status;
	}

	public function __tostring() {
		return $this -> _webpage;
	}

}


if ( ! function_exists('api_simulation_companies')){
	function api_simulation_companies(){
		return '[{"entityID":"8","termID":"20","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0704479768-35-669636","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"400000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"8","termID":"52","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0788146626-17-512381","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"980000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"8","termID":"53","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0405921580-43-678353","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"410000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"8","termID":"94","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0912912420-66-242924","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"184000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"8","termID":"203","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0095636797-55-344306","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"970000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"12","termID":"20","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0416532704-36-229143","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"127000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"12","termID":"52","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0494312576-83-013127","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"440000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"12","termID":"53","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0006959135-43-471852","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"166000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"12","termID":"94","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0166260504-47-487206","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"320000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"12","termID":"203","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0152695388-71-211264","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"157000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"14","termID":"20","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0623253962-32-843608","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"750000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"14","termID":"52","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0058639868-74-253880","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"129000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"14","termID":"53","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0874945253-96-237506","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"222000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"14","termID":"94","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0604582530-59-494336","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"162000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"14","termID":"203","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0678350245-57-004074","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"179000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"18","termID":"20","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0405128536-78-387077","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"530000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"18","termID":"52","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0081648181-48-217313","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"760000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"18","termID":"53","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0619493689-60-331525","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"230000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"18","termID":"94","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0747440126-92-276262","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"143000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]},{"entityID":"18","termID":"203","FYFQ":"2009Q2","FY":"2009","FQ":"Q2","accessionNumber":"0688981279-66-870242","startDate":"2009-04-01","endDate":"2009-06-30","unitDesc":"USD","decimals":"-6","value":"124000000","rank":"1","dataSources":[{"dataSource":"SEC XBRL Fillings"}]}]';
	}
}