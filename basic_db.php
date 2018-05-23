<?php


//define( 'DS', DIRECTORY_SEPARATOR );
include('./bootstrap.php');

############################################# OPENNING CLASS DATABASE #############################################


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//******************************************************************************
//***** CLASS DATABASE
//******************************************************************************
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
class Database {
    var $table;
    var $fields = "*";
    var $submit = "submit";
    var $doPrint;
    var $doPrint_r;
    var $where;
    var $vwhere;
    var $fwhere;
    var $kand;
    var $vand;
    var $fand;
    var $order;
    var $order_key;
    var $order_type;
	var $id;
	var $mfields = array();

	//private $baseFolder;
	private $pathToXMLFILE;


	private $client_id;
	
	private $quoteHtmlOutput;
	//private $quoteHtmlOutput;

	public static $dbId = null;

	private static $instances = array();

	private $conf = array(

		'local' => array(
			//'localhost', 'athletics', 'iyiIainU', 'athletics'
			'localhost', 'root', 'wh3r3ruf', 'athletics'
		),
		'clients' => array(
			//'localhost', 'root', 'wh3r3ruf', 'd6_clients',
			'5.9.158.12',
			'd6admin',
			'a2d9JD6s1Ab7aG',
			'd6_clients',
		)

	);

	//public function __construct($host, $username, $password, $db_name) {
	public function __construct($id) {
		$this->connect($id);		
	}

	private function connect( $id ) {
		$x = new mydbset(MACHINENAME);
		$machineName = $x->getMachineName();
		//$conf = $x->setDBSet($machineName);
		$conf = $x->setDBSet($machineName);

		$host = $conf[0];
		$username = $conf[1];
		$password = $conf[2];
		$db_name = $conf[3];
        
        $result = mysqli_connect($host,$username,$password,$db_name);
        
        if ($result->connect_error) {
            //die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
            return false;
        }
        /*
		$result = mysql_connect($host,$username,$password);
		if(!$result) {
			return false;
		}

		if(!@mysql_select_db($db_name)) {
			return false;
		}
         * 
         */
		self::$instances[$id] = $result;
	}

	public static function getInstance($id = null) {

		if(!is_null(self::$dbId)) {

			//$id = $this->dbId;
			$id = self::$dbId;

		}

		//echo self::$instances[$id];exit();
		if(!isset(self::$instances[$id]) || !self::$instances[$id]) {
			//self::$instances[$id] = new self;
			new self($id);
		}

		return self::$instances[$id];
	}

	public function db_result_to_array($result) {

		$res_array = array();
		
		for($count=0; $row = @mysqli_fetch_array($result, MYSQL_ASSOC); $count++) {
			$res_array[$count]= $row;
		}
		
		return $res_array;
	}

	function Fields($fields = "*"){
        $this->fields = $fields;
	}
	
	function FieldsAdd($mfields){
		foreach($mfields as $key => $value){
			$this->fields[$key] = $value;
		}
	}
	
	function Hidden($hidden = array()){
		$this->hidden = $hidden;
	}
	
	function Table_Name($table){
        $this->table=$table;
	}

	function Submit($submit = "submit"){
        $this->submit=$submit;
	}
	
	function Where($where, $vwhere, $fwhere = "="){
        $this->kwhere=$where;
        $this->fwhere=$fwhere;
        $this->vwhere=$vwhere;
	}

	function _And($kand, $vand, $fand = "="){
        $this->kand=$kand;
        $this->fand=$fand;
        $this->vand=$vand;
	}

	function Query($query){
        $this->query=$query;
	}
	
	function Date($field_name){
        $this->fdate=$field_name;
	}

	function OrderBy ($order_key, $order_type = "ASC"){
		if($order_key && $order_type)
			{
				$this->order = true;
				$this->order_key = $order_key;
				$this->order_type = $order_type;
			}
	}
	
	function doPrint(){
		$this->doPrint = true;
	}
	
	function doPrint_r(){
		$this->doPrint_r = true;
	}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// SELECT()
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function Select ($qtype='s') {

		//echo "Bevan";

		$conn = Database::getInstance();

		if($this->query) {
			$query = $this->query;
		} else {
			$query = "SELECT ";
		
			if(is_array($this->fields)){
				unset($this->fields[$this->submit]);
				foreach ($this->fields as $key => $row){
					$query .= $row.", ";
				}
			
				$query = substr_replace($query,"",-2,-1);
			} else {
				$query .= "*";
			}
			
			$query .= " FROM $this->table";
			if($this->kwhere) $query .= " WHERE $this->kwhere $this->fwhere '$this->vwhere'";
			if($this->kand) $query .= " AND $this->kand $this->fand '$this->vand'";
			if($this->order) $query .= " ORDER BY $this->order_key $this->order_type";
		}

		$result = @mysqli_query($conn,$query);
	
		if($this->doPrint)
			echo $query."<br />";
			
		if(!$result){
			return false;
		} else {
			if($qtype == 'c'){
				// $result = $this->db_result_to_array($result);
				$result = mysqli_num_rows($result);
				if($this->doPrint_r)
					print_r($result);
				return $result;
			}
			if($qtype == 'm'){
				$result = $this->db_result_to_array($result);
				if($this->doPrint_r)
					print_r($result);
				return $result;
			} else {
				$result = @mysqli_fetch_array($result, MYSQL_ASSOC);
				if($this->doPrint_r)
					print_r($result);
				return $result;
			}
		}
	}	

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// INSERT()
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	
	function Insert(){
		//$conn = db_connect();
		$conn = Database::getInstance();

		
		//unset($this->fields[$this->submit]);

		if($this->hidden)
		{
			foreach ($this->hidden as $key => $row)
			{
				unset($this->fields[$row]);
			}
		}
		
		$query = "INSERT INTO ".$this->table." ( ";

		foreach ($this->fields as $key => $row)
		{
			$query .= $key.", ";
		}

		//var_dump($query);

		//exit();

		if($this->fdate)
		{
			$query .= $this->fdate.", ";
		}

		$query = substr_replace($query,"",-2,-1);

		$query .= " ) VALUES ( ";

		foreach ($this->fields as $key => $row)
		{
			$query .= " '".$row."', ";
		}

		//var_dump($query);exit();

		if($this->fdate)
		{
			$query .= " NOW(), ";
		}

		$query = substr_replace($query,"",-2,-1);

		$query .= " ) ";
			
		if($this->doPrint)
			echo $query."<br />";

		

		$result = mysqli_query($conn, $query);
		if(!$result){
			return false;
		} else {
			$id = mysqli_insert_id($conn);
			return $id;
		}
	}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// UPDATE()
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function Update($id){
		$value = $this->fields[$id];
		unset($this->fields[$this->submit]);
		unset($this->fields[$id]);
		
		if($this->hidden)
		{
			foreach ($this->hidden as $key => $row)
			{
				unset($this->fields[$row]);
			}
		}
		
		//$conn = db_connect();
		$conn = Database::getInstance();
		$query = "UPDATE `".$this->table."` SET ";

		

		foreach ($this->fields as $key => $row)
		{
			$query .= $key." = '".$row."', ";
		}

		if($this->fdate)
		{
			$query .= $this->fdate." = NOW(), ";
		}

		$query = substr_replace($query,"",-2,-1);

		$query .= " WHERE ".$id." = '".$value."'  ";

				
		if($this->doPrint)
			echo $query."<br />";
			
		$result = mysqli_query($conn,$query);

		if(!$result){
            return false;
        } else {
			return true;
		}
	}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// DELETE()
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function Delete($key, $value){
		//$conn = db_connect();
		$conn = Database::getInstance();
		$query = "DELETE FROM ".$this->table." WHERE $key = '".$value."'";
			
		if($this->doPrint)
			echo $query."<br />";

		$result = @mysqli_query($conn,$query);
	
		if(!$result)
		{ return false; }
		else
		{
			return true;
		}
	}

	public function updateQuoteState ( $info ) {

		$this->Table_Name('quoteslist');

		$this->Hidden('');
		$this->Date('');

		// $this->doPrint();

		$this->Fields(
			array(
				'state' => $info['state'],
				'id' => $info['id']
			)
		);

		return $this->Update('id');
	}

	public function GetQuoteLineInfo ($quoteId) {
		$this->Table_Name('quoteslist');
		// $this->doPrint();

		$query = "SELECT * FROM quoteslist where id = ".$quoteId.";";

		$this->Query($query);
		$results = $this->Select('s');

		return $results;
	}

	public function getQuoteCount ( $type ) {

		$this->Table_Name('quoteslist');
		$this->doPrint();

		// $query = "select * from products where companyname like '%$term%' or clientemail like '%$term%' or contactperson like '%$term%';";
		$query = "SELECT * FROM quoteslist;";

		$this->Query($query);
		$results = $this->Select('c');

		return $results;
	}
	
	public function getQuoteLineCount ( $info ) {

		$this->Table_Name('quoteline');
		$this->doPrint();

		$query = "SELECT * FROM quoteline where id = " . $info['id'] . " and state = 'active';";

		$this->Query($query);
		$results = $this->Select( $info['type'] );

		return $results;
	}

	function GetSelectedTC ( $id ) {

		// var_dump($id);

		$this->Table_Name('quotetermsconditions');
		// $this->doPrint();

		// $query = "select * from products where companyname like '%$term%' or clientemail like '%$term%' or contactperson like '%$term%';";
		$query = "SELECT * FROM quotetermsconditions WHERE quoteId = $id ";

		$this->Query($query);
		$results = $this->Select('m');

		$data = array();

		foreach ($results as $row){
			$data[$row['websitetextId']] = 1;
		}
	
		return $data;
	}

	public function getProductCodeInfo ( $term ) {

		$this->Table_Name('products');

		$query = "SELECT a.* FROM products a WHERE a.productsname LIKE '%$term%' OR a.productcode LIKE '%$term%' OR a.productUnit LIKE '%$term%' OR a.productGroup LIKE '%$term%' ";

		$this->Query($query);
		$results = $this->Select('m');
	
		$data = array();
	
		foreach( $results as $row ){
			// $data[] = array(
				// 'label' => $row['productcode'] .', '. $row['productsname'] .', '. $row['productUnit'],
				// 'value' => $row['productcode'],
				// 'productcode' => $row['productcode'],
				// 'productsname' => $row['productsname'],
				// 'productunit' => $row['productUnit']
			// );
			$data[] = array(	
				'id' => $row['productcode'], 
				'text' => $row['productcode'] .', '. $row['productsname'] .', '. $row['productUnit'],
				'code' => $row['productUnit']);
		}
	
		return $data;
	}

	public function getAllProducts($term){
		$this->Table_Name('products');
		// $this->doPrint();

		// $query = "select * from products where companyname like '%$term%' or clientemail like '%$term%' or contactperson like '%$term%';";
		$query = "SELECT a.* FROM products a WHERE a.productsname LIKE '%$term%' OR a.productcode LIKE '%$term%' OR a.productUnit LIKE '%$term%';";

		$this->Query($query);
		$results = $this->Select('m');

		$stat[0] = true;
		$stat[1] = $results;
		return $stat;
	} 

	public function updateQuoteLineState ( $info ) {

		// var_dump($info);

		$this->Table_Name('quoteline');

		$this->Hidden('');
		$this->Date('');

		// $this->doPrint();

		$arr = array(
			'state' => $info['state'],
			'quotelineid' => $info['quotelineid']
		);

		// var_dump($arr);

		$this->Fields(
			$arr
		);

		return $this->Update('quotelineid');
	}

	public function pageDesc ($key) {
		$pieces = explode(DS, $key);
		$page = $pieces[count($pieces)-1];
		$info = array(
			'index.php' => 'NEWS',
			'technical.php' => 'Technical Info',
			'club.php' => 'Club Structure',
			'viewnews.php' => 'Club News Article',
			'viewAllNews.php' => 'View All News',
			'contact.php' => 'Club Contact'
		);
		return $info[$page];
	}

	public function savePage ( $info ) {
		$this->Table_Name('pagecontent');

		$this->Hidden('');
		$this->Date('');

		$this->Fields(
			array(
				'pagename' => $info['pagename'],
				'htmlcontent' => $info['htmlcontent'],
				'id' => $info['id']
			)
		);

		return $this->Update('id');
	}

	public function DeleteQuoteLineItem ($quotelineid){
	}

	public function AddQuotelineEntry ( $info ) {
		$this->Table_Name('quoteline');
		// $this->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'productsname' 		=> $info['productsname'],
				'productcode' 		=> $info['productcode'],
				'id' 				=> $info['id'],
				'state' 			=> 'active',
				'measurement' 		=> $info['productmeasurement'],
				'DateCreated' 		=> date("Y-m-d H:m:s"),
				'linenote' 			=> $info['linenote'],
				'Price' 			=> $info['price'],
				'quantity' 			=> $info['quantity'],
				'discount' 			=> 0
			)
		);

		$quotelineId = $this->Insert();

		// echo "Insert for QueueID {$info['id']} to history table completed...\n";

		return $quotelineId;
	}

	public function saveNews ( $info ) {
		$this->Table_Name('newslist');

		$this->Hidden('');
		$this->Date('');

		$this->Fields(
			array(
				'newsposition' => $info['newsposition'],
				'synopsis' => $info['synopsis'],
				'newscontent' => $info['newscontent'],
				'newsname' => $info['newsname'],
				'newsdate' => $info['newsdate'],
				'id' => $info['id']
			)
		);

		return $this->Update('id');
	}

	public function ultag ( $txt ){
		return '<ul>'.$txt.'</ul>';
	}

	public function shutter ( $info ) {

		$this->Table_Name('websitetext');
		//$this->doPrint();
		//$this->Where(array( 'clientid' => $client_id, 'name' => 'skin' ));
		//$this->Fields(array('*'));
		$query = "SELECT * FROM websitetext WHERE shortcut = 'shutter';";

		$this->Query($query);
		$result = $this->Select('m');

		$out = '';

		foreach ($result as $key => $shutterInfo) {
			$pieces = explode(';', $shutterInfo['contenttext']);
			$out .= "<li><img src='assets/img/photos/" . $pieces[0] . "'' width='".$info['width']."' height='".$info['height']."' alt='". $pieces[1] . "' /></li>";
		}

		return $this->ultag($out);
	}

	public function updateTable ( $info ) {

		//var_dump($info['id']);
		$this->Table_Name('logs');

		$this->Hidden('');
		$this->Date('');

		$this->Fields(
			array(
				'id' => $info['id'],
				'iPlayed' => $info['iPlayed'],
				'iWon' => $info['iWon'],
				'iDraw' => $info['iDraw'],
				'iLost' => $info['iLost'],
				'iFor' => $info['iFor'],
				'iAgainst' => $info['iAgainst'],
				'iGD' => $info['iGD'],
				'iPTS' => $info['iPTS']
			)
		);
		//$this->doPrint();

		return $this->Update('id');
		//echo "<br />DONE...<br >";
	}

	public function checkUpdates ( $client_id, $name ) {

		$this->Table_Name('adm_updates');
		$this->doPrint();
		//$this->Where(array( 'clientid' => $client_id, 'name' => 'skin' ));
		//$this->Fields(array('*'));

		$query = "SELECT * FROM adm_updates WHERE client_id = ".$client_id." AND `name` = '".$name."' AND `version` = 3;";

		$this->Query($query);
		$result = $this->Select('m');
		return $result;
	}

	public function topDivContent ( $fn ) {
		if ( $fn['type'] == 'top' ) {
			return $this->companyLogo() . " MATIES ATHLETICS - " . $this->pageDesc($fn['fn']);
		} elseif ( $fn['type'] == 'title' ) {
			return $this->getwebsitetext("website.title") . " - " . $this->pageDesc($fn['fn']);
		} elseif ( $fn['type'] == 'news' ) {
			return $this->companyLogo() . $fn['txt'];
		}
	}

	public function companyLogo () {
		return "<img style='height: 44px;' src='".$this->getwebsitetext('assets.photos.dir').$this->getwebsitetext('tournament.logo')."' />";
	}

	public function displayMenu  ( ) {


		$this->Table_Name('websitetext');
		$query = "SELECT * FROM websitetext WHERE `shortcut` = 'menu';";
		$this->Query($query);
		$result = $this->Select('m');
		$out = '<div class="divMenu">';
		foreach ($result as $key => $menuItem) {
			$url = explode(';', $menuItem['contenttext']);
			//$postDivId = explode('.', $url[0]);
			$out .= "<div style='width: ".$url[2]."px' id='checkoutbutton'><p><a href='".$url[0]."'>".$url[1]."</a></p></div>";
		}

		$out .= '</div>';

		return $out;
	}

	public function productGroup ( ) {

		$this->Table_Name('websitetext');
		$query = "SELECT * FROM websitetext WHERE `shortcut` = 'menu';";
		$this->Query($query);
		$result = $this->Select('m');
		$out = '<div class="divMenu">';
		foreach ($result as $key => $menuItem) {
			$url = explode(';', $menuItem['contenttext']);
			//$postDivId = explode('.', $url[0]);
			$out .= "<div style='width: ".$url[2]."px' id='checkoutbutton'><p><a href='".$url[0]."'>".$url[1]."</a></p></div>";
		}

		$out .= '</div>';

		return $out;
	}

	public function getPageId ( $name ) {
		$this->Table_Name('pagename');
		$query = "SELECT * FROM pagename WHERE PageNameText = '".$name."';";
		$this->Query($query);
		$result = $this->Select('s');
		return $result['PageNameId'];
	}

	public function getPageInfo ( $pagename ) {

		$id = $this->getPageId($pagename);
		$this->Table_Name('content');
		$query = "SELECT * FROM content WHERE ContentPage = ".$id." ORDER BY ContentOrder ASC;";
		// var_dump($query);
		// $this->DoPrint_r();

		$this->Query($query);
		$result = $this->Select('m');

		// var_dump($result);

		return $result;

	}

	public function getHeadInfo( $pagecontent ) {

		$pieces = explode(DS, $pagecontent);
		$admfilename = $pieces[count($pieces)-1];
		$admfilename_pieces = explode('_', $admfilename);
		$webpagename = $admfilename_pieces[0].'.php';
		$page = $function->getPageInfoFromFilename($webpagename);

	}

	public function getPageInfoFromFilename ( $name ) {

		$this->Table_Name('pagecontent');
		$query = "SELECT * FROM pagecontent WHERE `pagefilename` = '".$name."';";
		$this->Query($query);
		$result = $this->Select('s');
		return $result;

	}

	public function getSettingItem ( $desc, $type ) {

		$this->Table_Name('setting');
		$query = "SELECT * FROM setting WHERE `` = '".$desc."';";
		$this->Query($query);
		$result = $this->Select($type);
		return $result;

	}

	public function DeleteTermsConditions ( $quoteId ) {
		$this->Table_Name('quotetermsconditions');
		$this->doPrint_r();
		return $this->Delete('quoteId', $quoteId);
	}

	public function AddEntryQuoteTermsConditions ($info){
		// var_dump($info);
		$this->Table_Name('quotetermsconditions');
		// $this->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'quoteId' => $info['quoteId'],
				'websitetextId' => $info['websitetextId']
			)
		);

		$quotetermsconditionsId = $this->Insert();
		return $quotetermsconditionsId;
	}

	public function checkTermsConditionsForQuote ( $info ) {

		$this->Table_Name("quotetermsconditions");

		$query = "SELECT * FROM `quotetermsconditions` WHERE quoteId = " . $info['quote_id'] . ";";
		$this->Query($query);
		$result = $this->Select("m");

		return $result;

	}
	
	public function addToQueueHistory ( $info ) {
		$this->Table_Name('adm_send_queue_histories');
		//$this->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'send_queue_id' => $info['id'],
				'send_type' => $info['send_type'],
				'client_id' => $info['client_id'],
				'add_date_time' => $info['add_date_time'],
				'send_date_time' => date('Y-m-d H:i:s'),
				'html_body_content' => $info['html_body_content'],
				'email_subject' => $info['email_subject'],
				'mail_attachments' => $info['mail_attachments'],
				'embedded_images' => $info['embedded_images'],
				'additional_header_info' => $info['additional_header_info'],
				'from_email' => $info['from_email'],
				'to_email' => $info['from_email'],
				'priority' => $info['priority'],
				'country_id' => $info['country_id']
			)
		);

		$queueHistoryId = $this->Insert();

		echo "Insert for QueueID {$info['id']} to history table completed...\n";

		return $queueHistoryId;
	}

	public function getfileextension ( $filename ){
		return substr(strrchr($filename,'.'),1);
	}

	public function returnFileCat ( $extension ) {
		if ( ( $extension == "pdf" ) || ( $extension == "doc" ) || ( $extension == "docx" ) || ( $extension == "xlsx" ) || ( $extension == "xls" ) || ( $extension == "ppt" ) || ( $extension == "pptx" ) || ( $extension == "txt" ) ){
			//word/excel/powerpoint 2003/2007
			return 'document';
		} else {
			return 'image';
		}
	}

	public function addfileinfo ( $info ) {
		$this->Table_Name('files');
		//$db->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'filename' => $info['filename'],
				'filescategoryid' => $info['filescategoryid'],
				'username' => $info['username'],
			)
		);

		$fileId = $this->Insert();

		//echo "Insert for QueueID {$info['id']} to history table completed...\n";

		return $fileId;
	}

	public function AddFormInfo ( $info ) {
		// var_dump($info);
		$this->Table_Name('clientfeedbackform');
		// $this->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'clientname' => $info['name'],
				'clientsurname' => $info['surname'],
				'clientemail' => $info['email'],
				'clientmobile' => $info['mobile'],
				'clientcomment' => $info['comment']
			)
		);

		$id = $this->Insert('id');
		// var_dump($id);

		return $id;
	}

	public function addproductinfo ( $info ) {
		// var_dump($info);
		$this->Table_Name('products');
		$this->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'productsname' => $info['productName'],
				'productcode' => $info['productCode'],
				'productUnit' => $info['productUnits'],
				'productGroup' => $info['productGroup'],
				'active' => 1
				
			)
		);

		$productId = $this->Insert();

		var_dump($productId);
		
		return $productId;
	}

	public function addpricetableinfo ( $info ) {
		$this->Table_Name('pricetable');
		//$db->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'productprice_cost' => $info['productprice_cost'],
				'productprice_selling' => $info['productprice_selling'],
				'productsid' => $info['productsid'],
				'indexid' => $info['indexid']
			)
		);

		$pricetableId = $this->Insert();

		return $pricetableId;
	}

	public function addlogginginfo ( $info ) {
		$this->Table_Name('logging');
		//$db->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'action' => $info['action'],
				'type' => $info['type'],
				'username' => $info['username'],
				'loggedtime' => date('Y-m-d H:i:s')
			)
		);

		$loggingId = $this->Insert();

		return $loggingId;
	}

	public function insertNews ( $info ) {
		$this->Table_Name('newslist');
		//$db->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'newsname' => $info['newsname'],
				'newscontent' => $info['newscontent'],
				'synopsis' => $info['synopsis'],
				'newsposition' => $info['newsposition'],
			)
		);

		$queueHistoryId = $this->Insert();

		return $queueHistoryId;
	}

	public function addScheduleRelations ( $info ) {

		//var_dump($info);
		$this->Table_Name('schedule_relations');
		//$db->doPrint();
		$this->Hidden('');
		$this->Date('');
		$this->Fields(
			array(
				'player_id' => $info['person_id'],
				'schedule_id' => $info['schedule_id'],
				'type_id' => $info['type'],
				'amount' => $info['count']
			)
		);

		$id = $this->Insert();

		return $id;
	}

	public function getBaseFolder () {
		return $this->baseFolder;
	}

	public function setBaseFolder ($inputFolder) {
		$this->baseFolder = $inputFolder;
	}

	public function setFullPathToXMLFile (){
		$this->pathToXMLFILE = $this->baseFolder . DS . 'data' . DS . $this->client_id . DS . $this->finalFolderPart . DS . $this->xmlfilename;
	}

	public function getFullPathToXMLFile (){
		return $this->pathToXMLFILE;
	}

	public function dirExist($client_id) {

		$foldername = $this->baseFolder . DS . 'data' . DS . $client_id;
		if (!file_exists($foldername)) {
			return false;
		} else {
			//echo "The directory $dirname exists.";
			return true;
		}
	}

	public function getFIFOQueueBatch( $size ){


		$this->Table_Name("adm_send_queues");
		//$this->Query("SELECT * FROM adm_send_queues WHERE `send_type` = 'email' ORDER BY `id` DESC LIMIT ".$size.";");
		$this->Query("SELECT * FROM adm_send_queues ORDER BY `id` DESC LIMIT ".$size.";");
		$result = $this->Select("m");

		return $result;

	}

	public function getTeamInfo	( $info ){

		//var_dump($info);
		$this->Table_Name("logs");
		//$this->Query("SELECT * FROM `logs` WHERE team_id = ".$info['team_id'].";");
		$this->Query("SELECT * FROM `logs` WHERE team_id = ".$info['team_id']." AND league_id = ".$info['league_id'].";");
		$result = $this->Select("s");
		//var_dump($result);
		return $result;
	}

	public function getClients( ){


		$this->Table_Name("adm_send_queues");
		//$this->Query("SELECT * FROM adm_send_queues WHERE `send_type` = 'email' ORDER BY `id` DESC LIMIT ".$size.";");
		$this->Query("SELECT `name` FROM adm_clients;");
		$result = $this->Select("m");
		return $result;

	}

	public function getCountryId( $country ){


		$this->Table_Name("cmn_countries");
		$this->Query("SELECT * FROM cmn_countries WHERE `name` = '".$country."';");
		$result = $this->Select("s");

		return $result;

	}

	public function removeItemFromQueue($id) {
		$this->Table_Name('adm_send_queues');
		$this->doPrint_r();
		return $this->delete('id', $id);
	}

	public function sendVarStatus($x){
		if ( isset( $x ) ) {
			if ( $x == 1 ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function checkCurrentSendingBatchDuplication ($stack, $mynumber){
		if (!in_array($mynumber, $stack) ){
			array_push($stack, $mynumber);
			//print_r($stack);
			//echo "Sending will be done...\n";
			$send = true;
		} else {
			//echo "Sending will not be done...\n";
			$send = false;
		}
		return array('numbers' => $stack, 'send' => $send);
	}

	public function writeHTMLtoFile($file, $txt) {
		file_put_contents($file, $txt);
	}

	public function insertToXML($pos) {
		return '<layer id="sponsor" area="'.$pos['X'].','.$pos['Y'].','.$pos['W'].','.$pos['H'].'" />';
	}

	public function getwebsitetext ( $shortcut ){
		$this->Table_Name("websitetext");
		//$this->Query("SELECT * FROM adm_send_queues WHERE `send_type` = 'email' ORDER BY `id` DESC LIMIT ".$size.";");
		$this->Query("SELECT * FROM websitetext WHERE `shortcut` = '".$shortcut."';");
		$result = $this->Select("s");

		return $result['contenttext'];
	}

	function listexceptions ( $info ){
		$output = "<select name='exception' id='exception' style='width: ".$info['width']."px' >";

		if ( $info['value'] == 'yes' )
			$output .= "<option value='yes' selected>yes</option>";
		else
			$output .= "<option value='yes'>yes</option>";

		if ( $info['value'] == 'no' )
			$output .= "<option value='no' selected>no</option>";
		else
			$output .= "<option value='no'>no</option>";

		$output .= "</select>";
		return $output;
	}

	public function h1_headers ($txt){
		return "<h1 style='color: gray; font-family: verdana;'>".$txt."</h1><br />";
	}

	public function tablecaption ($headerTxt){
		$out = "<caption>";
		$out .= "<table style='margin: 0px auto;' border='0'>";
		$out .= "<tr>";
		$out .= "<td><img style='height: 150px;' src='assets/img/photos/".$this->getwebsitetext('tournament.logo')."' /></td>";
		$out .= "<td> </td>";
		$out .= "<td>".$this->h1_headers($this->getwebsitetext($headerTxt))."</td>";
		$out .= "</tr>";
		$out .= "</table>";
		$out .= "<br /><br />";
		$out .= "</caption>";
		return $out;
	}

	public function checkuser( $info ) {

		$this->Table_Name("login");
		//$this->Query("SELECT * FROM adm_send_queues WHERE `send_type` = 'email' ORDER BY `id` DESC LIMIT ".$size.";");
		//this->Query("SELECT * FROM login WHERE loginname = '".$info['loginname']. "' AND loginpassword = ".$info['loginpassword']. ";");
		$this->Query("SELECT * FROM login WHERE loginname = '".$info['loginname']. "';");
		$result = $this->Select("s");


		if ( ($result['loginname'] == $info['loginname']) && ($result['loginpassword'] == $info['loginpassword']) ){
			return true;
		} else {
			return false;
		}

		//return $result;
		//return false;
	}

	public function returnInput ( $info ) {

		$this->Table_Name($info['table']);
		$query = "SELECT `".$info['columnname']."` FROM `".$info['table']."` WHERE `".$info['compare_column']."` = '".$info['compare_value']. "';";
		
		$this->Query($query);
		$result = $this->Select("s");

		return $result[$info['columnname']];

	}

	public function pagefooter () {
		$output = "<div id='pagefooter'><div id='pagefooter-left'><b>";
		$output .= $this->getwebsitetext('app.registered');
		$output .= "</b></div><div id='pagefooter-right'><b>";
		$output .= $this->getwebsitetext('developed.by');
		$output .= "</b></div></div>";
		return $output;
	}

	public function singleColumnTable ( $info ) {

		$out = '';

		foreach ($info as $key => $item) {
			$out .= "<tr><td style='width: ".$item['width']."%'>" . $item['value'] . "</td></tr>";
		}
		return $out;
		// $pagecontent .= "<tr><td style='width: 50%'>";
		// $pagecontent .= "&nbsp;";
		// $pagecontent .= "</td>";
		// $pagecontent .= "<td style='width: 50%'>";
		// $pagecontent .= "&nbsp;";
		// $pagecontent .= "</td>";
		// $pagecontent .= "</tr>";
	}

	public function pageHTML ($info ){

		$pagecontent = '';


		
		// $pagecontent .= "<tr>";
		// $pagecontent .= "<td style='width: 50%'>";
		// $pagecontent .= "&nbsp;";
		// $pagecontent .= "</td>";
		// $pagecontent .= "<td style='width: 50%'>";
		// $pagecontent .= "&nbsp;";
		// $pagecontent .= "</td>";
		// $pagecontent .= "</tr>";

		if ( $info['page'] == 'dashboard.php' ){


			// List all the articles for editing
			$pagecontent = $this->listNews(3);

		} elseif ( $info['page'] == 'files_admin.php' ){

			$pagecontent .= "<tr>";
			$pagecontent .= "<td style='width: 50%; border: 0px solid black; text-align: center;' colspan='2'>";


			$pagecontent .= $this->displayFiles();


			$pagecontent .= "</td>";
			$pagecontent .= "</tr>";

		} elseif ( $info['page'] == 'viewtables.php' ){
			$pagecontent .= "<tr>";
			$pagecontent .= "<td style='width: 50%; border: 0px solid black; text-align: center;' colspan='2'>";



			$pagecontent .= $this->displayLeagueLogs();
			//$pagecontent .= $this->displayFixtures(array('league_id'=>2));

			// $pagecontent .= "</td>";
			// $pagecontent .= "<td style='width: 50%; border: 0px solid black; text-align: center;'>";

			$pagecontent .= "</td>";
			$pagecontent .= "</tr>";
		}

		return $pagecontent;
	}

	public function listNewsStates ($status) {
		$state = array ( 1 => 'home', 2 => 'all', 3 => 'admin' );
		return $state[$status];
	}

	public function styleAttr ($info) {
		return ' style="width: '.$info['width'].'%"';
	}

	public function displayFilesHeader () {
		return '<tr style="background-color: #A9A9A9;"><td'.$this->styleAttr(array('width'=>5)).'>ID</td><td'.$this->styleAttr(array('width'=>45)).'>Filename</td><td'.$this->styleAttr(array('width'=>20)).'>Filescategory</td><td'.$this->styleAttr(array('width'=>20)).'>Action</td></tr>';
	}

	public function displayFiles () {



		$this->Table_Name("files");

		$sql = "SELECT * FROM `files`;";


		$this->Query($sql);
		$result = $this->Select("m");

		$out = '<table border="0" style="width: 100%;">';

		$out .= $this->displayFilesHeader();

		foreach ($result as $fileslisting) {
			//$out .= '<tr><td>' . $fileslisting['id'] . '</td><td>' . $fileslisting['filename'] . '</td><td>' . $fileslisting['filescategoryid'] . '</td><td><a href="editFiles.php?id='.$fileslisting['id'].'">edit</a></td></tr>';
			$out .= '<tr><td>' . $fileslisting['id'] . '</td><td>' . $fileslisting['filename'] . '</td><td>' . $fileslisting['filescategoryid'] . '</td><td>----</td></tr>';
		}

		$out .= '</table>';

		return $out;

	}

	public function TermsAndConditions () {



		$this->Table_Name("websitetext");

		$sql = "SELECT * FROM `websitetext` where shortcut  like '%TermsConditions%';";


		$this->Query($sql);
		$result = $this->Select("m");

		return $result;

	}

	public function listNews ( $web ){

		$state = $this->listNewsStates ($web);


		$this->Table_Name("newslist");

		if ( $state == 'home' )
			$sql = "SELECT * FROM `newslist` ORDER BY `newsposition` LIMIT 5;";
		else
			$sql = "SELECT * FROM `newslist` ORDER BY `newsposition`";

		$this->Query($sql);
		$result = $this->Select("m");

		$newscount = count($result);
		$out = '';

		if ( $state == 'admin' )
			$out .= '<table style="width: 100%;"><tr></td><a href="addNewsPage.php"><img height="35px" src="./assets/img/photos/add-1.jpg" /></a></td></tr></table>';



		$out .= '<table style="width: 100%; margin-top: 20px;">';

			

		if ( $newscount > 0 ) {	

			$out .=  '<tr style="background-color: #a9a9a9;">';

			if ( $state == 'admin' ) 
				$out .=  '<td>#</td>';
			
			$out .= '<td>Name of News</td>';
			$out .= '<td>Synopsis</td>';


			if ( $state == 'all' || $state == 'home' ) 
				$out .= '<td>Text</td>';


			
			if ( $state == 'admin' ) 
					$out .= '<td>Action</td>';
			
			$out .= '<tr>';
		}

		$counter = 1;

		foreach ($result as $newslisting) {
			$out .=  '<tr>';
			if ( $state == 'admin' ) 
				$out .=  '<td>' . $newslisting['id'] . '</td>';
	
			
			if ( $state == 'all' || $state == 'home' ) 
				$out .=  '<td><a href="./viewnews.php?id='.$newslisting['id'].'">' . $newslisting['newsname'] . '</a></td>';
			else
				$out .=  '<td>' . $newslisting['newsname'] . '</td>';


			$out .=  '<td>' . $newslisting['synopsis'] . '</td>';

			$newscontent = strip_tags($newslisting['newscontent']);

			
			if ( $state == 'all' || $state == 'home' ) 
				$out .= '<td>' . $this->displayXChars(array( 'txt'=>$newscontent, 'amount'=>100)) . '</td>';


			if ( $state == 'admin' ) 
				$out .=  '<td>' . '<a href="./viewnews.php?id='.$newslisting['id'].'">[del]</a>' . ' ' . '<a href="./editnews.php?id='.$newslisting['id'].'">[edit]</a>' . '</td>';

			$out .=  '<tr>';
			$counter++;
		}


		if ( $newscount == 0 ) {
			$out .= "<tr><td>No News Available</td></tr>";
		}

		$out .= '</table>';
		

		if ( $newscount > 0 ) {

			if ( $state == 'home' ){
				$out .= '<hr />';
				$out .= '<br /><table><tr></td><a href="viewAllNews.php">View All News</a></td></tr></table>';
			}

		}



		return $out;
	}

	public function displayFixtureUpdateForm ($info){
		$out = "";
		$out .= $this->formHTML($info);
		return $out;
	}

	public function getScheduleInfo ( $info ) {


		$this->Table_Name("schedules");
		$this->Query("SELECT * FROM schedules WHERE id = ".$info['schedule_id'].";");
		$result = $this->Select("s");

		return $result;
	}

	public function displayHTMLContent ($info) {
		$this->Table_Name("pagecontent");
		$sql = "SELECT `htmlcontent` FROM `pagecontent` WHERE `pagename` = '".$info['pagename']."';";
		$this->Query($sql);
		$result = $this->Select();
		return $result;
	}

	public function datanav() {
		return "<div id='datanav'>website developed by: <a href='http://www.datanav.co.za/'>DataNav</a></div>";
	}

	public function webfooter (){
		return "<div id='footer'>".$this->datanav()."</div>";
	}

	public function displayXChars ($info) {
		return (strlen($info['txt']) > $info['amount'] ? substr($info['txt'], 0, $info['amount']-3) . '...' : substr($info['txt'], 0, $info['amount']));
		//substr($info['txt'], 0, $info['amount'])
		//return substr($info['txt'], 0, $info['amount']);
	}

	public function quickLinks ($info) {
		$this->Table_Name("newslist");
		$this->Query("SELECT * FROM `newslist` ORDER BY `newsposition` LIMIT 3;");
		$result = $this->Select("m");

		//var_dump($result);

		$out = '<div><br />';

		$counter = 1;

		foreach ($result as $newslisting) {
			$out .=  $counter .  '. <a href="./viewnews.php?id='.$newslisting['id'].'">' . $newslisting['newsname'] . '</a>' . '<br />' . $newslisting['synopsis'] . '<hr /><br />';
			$counter++;
		}

		$out .= '</div>';

		return $out;

	}

	public function setGoals ( $info ) {
		foreach ($info as $key => $value) {
			$value['type'] = 1;
			$this->addScheduleRelations($value);
		}
	}

	public function setSentOffs ( $info ) {
		foreach ($info as $key => $value) {
			$value['type'] = 3;
			$value['count'] = 1;
			$this->addScheduleRelations($value);
		}
	}

	public function setCautions ( $info ) {
		foreach ($info as $key => $value) {
			$value['type'] = 2;
			$value['count'] = 1;
			$this->addScheduleRelations($value);
		}
	}

	public function getPlayers ($info) {
		$this->Table_Name($info['table']);
		//var_dump($info);
		$sql = "SELECT * FROM `".$info['table']."` WHERE team_id = '".$info['team_id']. "';";
		//var_dump($sql);
		$this->Query($sql);
		$result = $this->Select("m");

		$players = array();

		foreach ($result as $player) {
			$players[$player['id']] = array( 'playername' => $player['playername'], 'playersurname' => $player['playersurname'] );
		}

		return $players;
	}

	public function getLeagues ($info) {
		$this->Table_Name($info['table']);
		//var_dump($info);
		$sql = "SELECT * FROM `".$info['table']."` WHERE ".$info['column']. " = '".$info['value']. "';";
		//var_dump($sql);
		$this->Query($sql);
		$result = $this->Select("m");


		$leagues = array();


		foreach ($result as $league) {
			$leagues[$league['id']] = array( 'leaguename' => $league['leaguename'] );
		}

		return $leagues;
	}

	public function goalsInfo ( $info ) {
		//SELECT * FROM `schedule_relations` WHERE type_id = 1
		$info['table'] = 'schedule_relations';
		// $info['type_id'] = 1;
		$this->Table_Name($info['table']);

		$sql = "SELECT * FROM `".$info['table']."` WHERE type_id = '".$info['type_id']. "' AND schedule_id = '".$info['schedule_id']. "';";

		$this->Query($sql);
		$result = $this->Select("m");

		return $result;
	}

	public function displayMatchTypeInfo( $info ){

		//	print_r($info);
		//$out .= $info['type_id'];
		$info['table'] = 'schedule_relations';
		// $info['type_id'] = 1;
		$this->Table_Name($info['table']);


		$sql = "SELECT `player_id`, SUM(amount) as personTotal FROM `schedule_relations` WHERE type_id = ".$info['type_id']." GROUP BY player_id ORDER BY `personTotal` DESC";

		$this->Query($sql);
		$result = $this->Select("m");


		return $this->matchTypeInfoTable($result);
	}

	public function matchTypeInfoTable( $arr ){
		//print_r($arr);
		$out = "";
		$out .= "<table style='width: 100%'>";
		$out .= "<tr><td style='width: 35%'>NAME</td><td style='width: 50%'>Team</td><td style='width: 15%'>Total</td></tr>";
		foreach ( $arr as $key => $entry) {
			$team_id = $this->returnInput(
				array(
					'table' => 'persons',
					'columnname' => 'team_id', 
					'compare_column' => 'id',
					'compare_value' => $entry['player_id'],
				)
			);
			//echo $team_id;
			//$out .= "<tr><td>".$entry['player_id']."</td><td>".$this->getName(array('table' => 'team', 'column' => 'teamname','id' => $team_id,'logtype' => 'long'))."</td><td>".$entry['personTotal']."</td></tr>";
			$out .= "<tr><td>".$this->capitalizeSentence($this->getPersonName($entry['player_id']))."</td><td>".$this->getName(array('table' => 'teams', 'column' => 'teamname','id' => $team_id,'logtype' => 'long'))."</td><td>".$entry['personTotal']."</td></tr>";
		}
		$out .= "</table>";
		return $out;
	}

	public function displayGoals ( $info ){

		$info['table'] = 'persons';

		$goalInfo = $this->goalsInfo ($info);
		$teamInfo = $this->getPlayers ($info);


		$out = "<div style='text-align: left; padding: 10px; font-size: 14px;'>";
		$out .= $this->bold(ucfirst($this->getName(array( 'table' => 'schedule_types', 'column' => 'schedule_type' , 'logtype' => 'long', 'id' => $info['type_id'])))) . "<br />";
		$out .= "<div style='padding: 0 60px; border: 0px solid black; width: 165px;'>";

		foreach ( $goalInfo as $value ){
			// var_dump( $value );
			if ( array_key_exists($value['player_id'], $teamInfo) ){
				if ( $info['type_id'] == 1 ) {
					$out .= $this->capitalizeSentence($this->getPersonName($value['player_id'])) . " - " . $value['amount'] . "<br />";
				} else {
					$out .= $this->capitalizeSentence($this->getPersonName($value['player_id'])) . "<br />";
				}
			}
		}


		$out .= "</div>";
		$out .= "</div>";
		return $out;
	}

	public function formHTML ($schedule_id){

		$gameInfo = $this->getScheduleInfo($schedule_id);

		
		$out = "<table style='width: 760px;' border='0'>";
		$out .= "<form id='myForm' action='updatefixture.php' method='post'>";
		$out .= "<tr style='font-size: 14px; font-weight: bold; background-color: #a3a3a3;'><td colspan='4'>".$this->getName(array('table'=>'leagues','logtype'=>'long','id'=>$gameInfo['league_id'],'column'=>'leaguename'))." Match -- Venue: ".$this->getName(array('table'=>'venues','logtype'=>'long','id'=>$gameInfo['venue_id'],'column'=>'venuename'))." - Date/Time: ".$gameInfo['gamedate']."/".$gameInfo['gametime']."</td></tr>";
		$out .= "<tr><td colspan='2'>&nbsp;</td><td>&nbsp;</td><td style='text-align: left'>Score</td></tr>";
		$out .= "<tr><td colspan='2'></td><td>".$this->getName(array('id'=>$gameInfo['teama'],'logtype'=>'long','table'=>'teams','column'=>'teamname'))."</td><td style='text-align: left'><input name='teamscore_".$gameInfo['teama']."' value='' style='width: 75px' /></td></tr>";
		$out .= "<tr><td colspan='2'></td><td>".$this->getName(array('id'=>$gameInfo['teamb'],'logtype'=>'long','table'=>'teams','column'=>'teamname'))."</td><td style='text-align: left'><input name='teamscore_".$gameInfo['teamb']."' value='' style='width: 75px' /></td></tr>";

		$out .= "<tr><td colspan='2' valign='top'>" . $this->listplayers( array('team_id'=>$gameInfo['teama'], 'schedule_id' => $schedule_id['schedule_id']) ) . "</td>";

		$out .= "<td colspan='2'  valign='top'>" . $this->listplayers( array('team_id'=>$gameInfo['teamb'], 'schedule_id' => $schedule_id['schedule_id']) ) . "</td></tr>";

		$out .= "<input type='hidden' name='game_id' value='".$schedule_id['schedule_id']."' />";

		$out .= "<tr><td></td><td><button id='sub'>update</button></td></tr>";
		$out .= "</form>";
		$out .= "<table>";
		$out .= "<span id='result'></span>";

		return $out;

	}

	public function displayLeagueLogs (){
		$out = "";

		$info = array( 'table' => 'leagues', 'column' => 'displayable', 'value' => 1 );

		$leagues = $this->getLeagues($info);

		foreach ( $leagues as $key => $league ){
			$out .= "<h3 style='color: #a9a9a9;'><a target='_blank' href='displayLeagueLog.php?id=".$key."' >" . $league['leaguename'] . "</a></h3>";
			$out .= $this->displayShortLog(array('league_id'=>$key,'logtype'=>'long'));
		}
		
		return $out;
	}

	public function listplayers ( $info ){
		$out = "";
		$out .= "<table style='width: 370px; border: 1px solid black;'>";
		$out .= "<caption>".$this->getName(array('id' => $info['team_id'], 'table' => 'teams', 'logtype' => 'long', 'column' => 'teamname'))."</caption>";
		$out .= "<tr><td>Name</td><td>Goals</td><td>Cause</td><td>SentOff</td></tr>";
		$out .= $this->listplayerInfo($info);
		$out .= "</table>";
		return $out;
	}

	public function goalsTextbox ($info) {
		return "<input type='text' name='goals_person_" . $info['schedule_id']."_". $info['person_id']."' id='' style='width: 50px;' />";
	}

	public function cautions_and_sentoff ($info ) {
		//return "<input type='checkbox' value='".$info['type']."_".$info['schedule_id']."_".$info['person_id']."' name='".$info['type']."_".$info['schedule_id']."_".$info['team_id']."' style='width: 50px;' />";
		return "<input type='checkbox' value='1' name='".$info['type']."_".$info['schedule_id']."_".$info['team_id']."_".$info['person_id']."' style='width: 50px;' />";
	}

	public function listplayerInfo($info){

		//print_r($info);
		$out = "";
		//$out .= "<tr><td>Name</td><td>Goals</td><td>Cause</td><td>SentOff</td></tr>";
		$tablename = 'persons';
		$this->Table_Name($tablename);

		$this->Query("SELECT * FROM $tablename WHERE team_id = '".$info['team_id']. "';");
		$result = $this->Select("m");


		//print_r($result);
		foreach ($result as $key => $person) {
			$out .= "<tr><td>".$this->capitalizeSentence( $this->getPersonName ( $person['id'] ) )."</td><td>".$this->goalsTextbox(array('person_id' => $person['id'], 'schedule_id' => $info['schedule_id']))."</td><td>".$this->cautions_and_sentoff(array( 'team_id' => $info['team_id'], 'type' => 'caution', 'person_id' => $person['id'], 'schedule_id' => $info['schedule_id'] ))."</td><td>".$this->cautions_and_sentoff(array( 'team_id' => $info['team_id'], 'type' => 'sentoff', 'person_id' => $person['id'], 'schedule_id' => $info['schedule_id'] ))."</td></tr>";
		}
		return $out;
	}

	public function listSchedulesInfo($info){


		$out = "";
		$tablename = 'schedules';
		$this->Table_Name($tablename);

		$this->Query("SELECT * FROM $tablename WHERE id = '".$info['schedule_id']. "';");
		$result = $this->Select("s");

		return $result;

	}

	public function capitalizeSentence($foo){
		$pieces = explode(' ', $foo);
		$out = '';
		foreach ($pieces as $val) {
			$out .= ucfirst(strtolower($val)) . ' ';
		}
		return trim($out);
	}

	public function saveFixture ( $info ) {

		$this->Table_Name('schedules');

		$this->Hidden('');
		$this->Date('');

		$this->Fields(
			array(
				'source' => $info['source'],
				'file_size' => $info['file_size'],
				'file_md5' => $info['file_md5'],
				'serial' => $info['serial'],
				'active' => 1,
				'update_type_id' => 1,
				'application_type_id' => 1,
				'client_id' => $info['client_id'],
				'modified' => date('Y-m-d H:i:s'),
				'id' => $info['id']
			)
		);
		//$db->doPrint();

		return $this->Update('id');

	}

	public function displayFixturesForm ($info){
		$out = "";

		$out .= "<table border='1' style='margin: 0px auto; text-align: center; width: 100%;'>";
		$out .= "<caption style='text-align: left; font-size: 14px; font-weight: bold;'>" . $this->returnInput(array('table'=>'leagues', 'compare_column'=>'id', 'compare_value'=>$info['league_id'], 'columnname'=>'leaguename')) . "</caption>";
		$out .= "<tr><td>Home</td><td>Away</td><td>Date</td><td>Time</td><td>Referee</td><td>Action</td></tr>";
		$out .= $this->displayFixtures($info);
		$out .= "</table>";
		return $out;
	}

	public function fixtureActions($info){

		if ( $info['state'] == 'fixture' )
			$out = "<a target='_blank' href='updateFixtures.php?id=".$info['id']."'><img width='20px' src='img/edit-icon.jpg' /></a>";
		else
			$out = "<a target='_blank' href='reportFixture.php?id=".$info['id']."'><img width='20px' src='img/report-icon.gif' /></a>";


		return $out;
	}

	public function displayFixtures($info){
		$tablename = 'schedules';
		$this->Table_Name($tablename);

		$this->Query("SELECT * FROM `$tablename` WHERE league_id = '".$info['league_id']. "' ORDER BY gamedate,gametime DESC;");
		$result = $this->Select("m");


		//print_r($result);


		$formattedResult = "";

		foreach ($result as $teamInfo) {
			//$formattedResult .= "<tr><td>".$this->getName(array('table'=>$tablename,'logtype'=>'long','id'=>$teamInfo['teama'],'column'=>'teamname'))."</td><td>".$this->getName(array('table'=>$tablename,'logtype'=>'long','id'=>$teamInfo['teamb'],'column'=>'teamname'))."</td><td>".$teamInfo['gamedate']."</td><td>".$teamInfo['gametime']."</td><td>".$this->getPersonName($teamInfo['referee'])."</td><td>".$this->fixtureActions($teamInfo['id'])."</td></tr>";
			$formattedResult .= "<tr><td>".$this->getName(array('table'=>'teams','logtype'=>'long','id'=>$teamInfo['teama'],'column'=>'teamname'))."</td><td>".$this->getName(array('table'=>'teams','logtype'=>'long','id'=>$teamInfo['teamb'],'column'=>'teamname'))."</td><td>".$teamInfo['gamedate']."</td><td>".$teamInfo['gametime']."</td><td>".$this->getPersonName($teamInfo['referee'])."</td><td>".$this->fixtureActions(array( 'id' => $teamInfo['id'], 'state' => $teamInfo['state']))."</td></tr>";
		}

		return $formattedResult;
	}

	public function teamManagement ( $info ){
		$out = "";
		$tablename = 'persons';
		$this->Table_Name($tablename);

		$this->Query("SELECT * FROM $tablename WHERE team_id = '".$info['team_id']. "' AND person_type_id = '".$info['person_type_id']."';");
		$result = $this->Select("s");

		return $result;

	}

	public function footerLogos(){
		$width = '175';
		$out = "<table border='0px'>";
		$out .= "<tr>";
		$out .= "<td><img src='img" . DS . $this->getwebsitetext( "slfa.logo" ) . "' width='".($width-90)."px' /></td>";
		$out .= "<td><img src='img" . DS . $this->getwebsitetext( "us.logo" )."' width='".$width."px' /></td>";
		$out .= "<td><img src='img" . DS . $this->getwebsitetext( "innovus.logo" )."' width='".$width."px' /></td>";
		$out .= "<td><img src='img" . DS . $this->getwebsitetext( "saos.logo" )."' width='".$width."px' /></td>";
		$out .= "</tr>";
		return $out;
	}

	public function getPersonName( $id ){
		$info = array(
			'table' => 'persons',
			'columnname' => 'playername',
			'compare_column' => 'id',
			'compare_value' => $id
		);
		$name = $this->returnInput ( $info );
		$info['columnname'] = 'playersurname';
		$surname = $this->returnInput ( $info );
		return $name . " " . $surname;
	}

	public function displayShortLog ($info){

		$out = "";
		if ( $info['league_id'] <= 2 ){

			$out .= "<table border='0' style='margin: 0px auto; text-align: center; width: 80%;'>";
			$out .= "<caption style='font-weight: bold;'>";
			$out .= $this->returnInput( array('table' => 'leagues', 'columnname' => 'leaguename', 'compare_value' => $info['league_id'], 'compare_column' => 'id') );
			$out .= "</caption>";
			if ( $info['logtype'] == 'short' )
				$out .= "<tr style='font-weight: bold; background-color: #a3a3a3; color: white;'><td>Team</td><td>P</td><td>W</td><td>D</td><td>L</td><td>PTS</td></tr>";
			else
				$out .= "<tr style='font-weight: bold; background-color: #a3a3a3; color: white;'><td>Team</td><td>P</td><td>W</td><td>D</td><td>L</td><td>F</td><td>A</td><td>GD</td><td>PTS</td></tr>";
			$out .= $this->displayLogTeams(array( 'league_id' => $info['league_id'], 'logtype' => $info['logtype']));
			$out .= "</table>";
		}
		return $out;
	}

	public function getallusers ( $info ){
		$out = "";
		$tablename = 'login';
		$this->Table_Name($tablename);

		$this->Query("SELECT * FROM $tablename;");
		$result = $this->Select("m");

		return $result;

	}

	public function getTD ( $str ) {
		//var_dump($str);
		if ( isset($str['type']) && $str['type'] == 'action' ) {
			$classStr = '';
			if ( isset($str['class']) ){
				$classStr = 'class=' . $str['class'] . " ";
			}
			$idStr = '';
			if ( isset($str['id']) ){
				$idStr = 'id=' . $str['id'] . " ";
			}
			$usernStr = '';
			if ( isset($str['usern']) ){
				$usernStr = 'usern=' . $str['usern'] . " ";
			}
			$passwStr = '';
			if ( isset($str['passw']) ){
				$passwStr = 'passw=' . $str['passw'] . " ";
			}
			return "<td>" . "<a " . $classStr . $idStr . $usernStr . $passwStr . "href='#'><img width='30px' src='images/".$str['img']."' /></a>" . "</td>";
		} else {
			return "<td>".$str['value']."</td>";
		}
	}

	public function getTR ( $str ) {
		//var_dump($str);
		$idStr = '';
		if ( isset($str['id']) ){
			$idStr = ' id=' . $str['id'];
		}

		return "<tr" . $idStr . ">".$str['value']."</tr>";
	}

	public function displayCustomTable ( $info ) {
		return "<table border='1' style='".$this->convertStyleToStr($info['style'])."'>".$info['data']."</table>";
	}

	private function convertStyleToStr ($style){
		$out = '';
		foreach ($style as $key => $value) {
			$out .= $key . ': ' . $value . ';';
		}
		return $out;
	}

	public function displayPlayerRatings (){

		$out = "";

		$out .= "<table border='0' style='margin: 0px auto; text-align: center; width: 80%;'>";
		$out .= "<tr style='font-weight: bold; background-color: #a3a3a3; color: white;'><td>Player Name</td><td>Team</td><td>Rating Score</td></tr>";
		$out .= $this->displayRatings();
		$out .= "</table>";

		return $out;
	}

	public function displayFixturesShort ($info){
		$out = "";
		$out .= "<table border='1' style='margin: 0px auto; text-align: center; width: 80%;'>";
		$out .= "<caption>";
		$out .= $this->returnInput( array('table' => 'leagues', 'columnname' => 'leaguename', 'compare_value' => $info['league_id'], 'compare_column' => 'id') );
		$out .= "</caption>";
		if ( $info['logtype'] == 'short' )
			$out .= "<tr><td>#</td><td>Home</td><td>Away</td><td>Date</td><td>Time</td><td>Venue</td></tr>";
		else
			$out .= "<tr><td>Team</td><td>P</td><td>W</td><td>D</td><td>L</td><td>F</td><td>A</td><td>GD</td><td>PTS</td></tr>";
		$out .= $this->getFixture(array( 'league_id' => $info['league_id'], 'logtype' => $info['logtype']));
		$out .= "</table>";
		return $out;
	}

	public function getFixture($info){
		$this->Table_Name('table');

		//$this->Query("SELECT * FROM `table` WHERE league_id = '".$info['league_id']. "' ORDER BY PTS, GD DESC;");
		$this->Query("SELECT * FROM `schedules` where `state` = 'fixture' and league_id = $info[league_id] order by gamedate, gametime desc;");
		$result = $this->Select("m");

		$formattedResult = "";

		foreach ($result as $teamInfo) {
			if ( $info['logtype'] == 'short' ) {
				$venueShort = $this->returnInput(array('table'=>'venues', 'columnname'=>'shortname','compare_column'=>'id','compare_value'=>$teamInfo['venue_id']));
				$formattedResult .= "<tr><td>$teamInfo[id]</td><td>".$this->getName(array('id'=>$teamInfo['teama'],'logtype'=>'short','table'=>'teams','column'=>'teamname'))."</td><td>".$this->getName(array('id'=>$teamInfo['teamb'],'logtype'=>'short','table'=>'teams','column'=>'teamname'))."</td><td>".$teamInfo['gamedate']."</td><td>".$teamInfo['gametime']."</td><td>".$venueShort."</td></tr>";
			} else {
				$venue = $this->returnInput(array('table'=>'venues', 'columnname'=>'venuename','compare_column'=>'id','compare_value'=>$teamInfo['venue_id']));
				$formattedResult .= "<tr><td>$teamInfo[id]</td><td>".$this->getName(array('id'=>$teamInfo['teama'],'logtype'=>'long','table'=>'teams','column'=>'teamname'))."</td><td>".$this->getName(array('id'=>$teamInfo['teamb'],'logtype'=>'long'))."</td><td>".$teamInfo['gamedate']."</td><td>".$teamInfo['gametime']."</td><td>".$venue."</td></tr>";
			}
		}

		return $formattedResult;
	}

	public function displayRatings(){
		//var_dump($info['league_id']);
		$this->Table_Name('player_ratings');

		$this->Query("SELECT * FROM `player_ratings` ORDER BY playerscore DESC;");
		$result = $this->Select("m");
		//print_r($result);
		$formattedResult = "";
		foreach ($result as $playerInfo) {
			$formattedResult .= "<tr><td>".$playerInfo['player']."</td><td>".$playerInfo['team']."</td><td>".$playerInfo['playerscore']."</td></tr>";
		}

		return $formattedResult;
	}

	public function displayLogTeams($info){
		//var_dump($info['league_id']);
		$this->Table_Name('logs');

		$this->Query("SELECT * FROM `logs` WHERE league_id = ".$info['league_id']. " ORDER BY iPTS DESC, iGD DESC;");
		//$this->Query("SELECT * FROM `logs` WHERE league_id = ".$info['league_id']. " ORDER BY iPTS DESC;");
		//$this->Query("SELECT * FROM `logs`;");
		$result = $this->Select("m");


		//print_r($result);


		$formattedResult = "";

		foreach ($result as $teamInfo) {
			if ( $info['logtype'] == 'short' ) {
				$formattedResult .= "<tr><td>".$this->getName(array('id'=>$teamInfo['team_id'],'logtype'=>'long','table'=>'teams','column'=>'teamname'))."-<b>".$this->getName(array('id'=>$teamInfo['team_id'],'logtype'=>'short','table'=>'teams','column'=>'teamname'))."<b></td><td>".$teamInfo['iPlayed']."</td><td>".$teamInfo['iWon']."</td><td>".$teamInfo['iDraw']."</td><td>".$teamInfo['iLost']."</td><td>".$teamInfo['iPTS']."</td></tr>";
			} else {
				$formattedResult .= "<tr><td>".$this->getName(array('id'=>$teamInfo['team_id'],'logtype'=>'long','table'=>'teams','column'=>'teamname'))."</td><td>".$teamInfo['iPlayed']."</td><td>".$teamInfo['iWon']."</td><td>".$teamInfo['iDraw']."</td><td>".$teamInfo['iLost']."</td><td>".$teamInfo['iFor']."</td><td>".$teamInfo['iAgainst']."</td><td>".$teamInfo['iGD']."</td><td>".$teamInfo['iPTS']."</td></tr>";
			}
		}

		return $formattedResult;
	}

	public function bold ( $txt ) {
		return "<b>" . $txt . "</b>";
	}

	public function getCurrencyInfo(){
		
		$this->Table_Name('currency');
		
		
		$query = "SELECT * FROM `currency` ORDER BY `countrydesc` ASC;";

		$this->Query($query);
		$result = $this->Select("m");
		
		return $result;

	}

	public function listinstallUnits ($width) {
		
		$this->Table_Name('websitetext');
		$query = "SELECT * FROM websitetext WHERE shortcut = 'Install_Units_List' ;";
		$this->Query($query);
		$result = $this->Select("m");
		
		$output = "<select name='installunits' id='installunits' style='width: ".$width."px' >";

		foreach ($result as $key => $installUnits) {
			$output .= "<option value='".$installUnits['id']."'>".$installUnits['contenttext']."</option>";
		}

		$output .= "</select>";
		return $output;
	}

	public function displayselectquotetypes ($info) {
		
		$this->Table_Name('websitetext');
		$query = "SELECT * FROM websitetext WHERE shortcut = 'productserviceadd';";
		
		$this->Query($query);
		$result = $this->Select("m");

		$output = "<select name='choose' id='choose' style='width: ".$info['width']."px'>";
		$output .= "<option value=''></option>";
		// keeps getting the next row until there are no more to get
		foreach ($result as $key => $productType) {
			$pieces = explode(';', $productType['contenttext']);
			$output .= "<option value='".$pieces[1]."'>".$pieces[0]."</option>";
		}
		$output .= "</select>";
		return $output;
	}

	public function shippingmethodselect($inputArray) {

		$query = "SELECT * FROM websitetext WHERE shortcut like 'ShippingMethod%';";
		
		$this->Query($query);
		$results = $this->Select("m");
	
		$output = "<select name='shippingmethodselect' id='shippingmethodselect' style='width: ".$inputArray['width']."px'>";
		$output .= "<option value=''></option>";
		// keeps getting the next row until there are no more to get
		foreach( $results as $row ){
			if ( $inputArray['value'] == $row['contenttext'] ) {
				$output .= "<option value='" . $row['contenttext'] . "' selected>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
			else {
				$output .= "<option value='".$row['contenttext']."'>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
		}
		$output .= "</select>";
		return $output;
	}

	public function getCurrencyInfoForm (){

		// Make a MySQL Connection
		$link = mysqli_connect(server_name(), getun(),  getpwd(), getdbname());
	
		// Get all the data from the "currency" table
		$query = "SELECT * FROM `currency` ORDER BY `countrydesc` ASC;";
	
		//$result = mysql_query("SELECT * FROM `currency` ORDER BY `countrydesc` ASC;") or die(mysql_error());
	
		$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
		$output = array();
		// keeps getting the next row until there are no more to get priceindexid, indexdescription, indexpriceval
		while($row = mysqli_fetch_array( $result )) {
			$output[$row['short']] = array(
				"id" => $row['id'],
				"name" => $row['countrydesc'],
				"symbol" => $row['symbol'],
				"short" => $row['short']
			);
		}
		mysqli_close($link);
		return $output;
	}

	public function currencyselect ( $info ) {
		$outputstring = "";
		$outputstring .= "<select name='".$info['selectname']."' id='".$info['selectname']."' style='width: ".$info['selectwidth']."px;'>";
	
		$data = $info['data'];
	
		foreach ($data as $key => $currency) {
	
			if ( $info['value'] == $currency['short'] )
				$outputstring .= "<option value='".$currency['short']."' selected>".$currency['name'] . " - " . $currency['symbol'] ."</option>";
			else
				$outputstring .= "<option value='".$currency['short']."'>".$currency['name'] . " - " . $currency['symbol'] ."</option>";
		}
	
		$outputstring .= "</select>";
		return $outputstring;
	}

	public function discountstructure ( $info ){

		$this->Table_Name('discounts');
		$query = 'SELECT * FROM discounts';
		$this->Query( $query );
		$result = $this->Select("m");

		$output = "<select name='discounts' id='discounts' style='width: ".$info['width']."px'>";
		$output .= "<option></option>";
		// keeps getting the next row until there are no more to get priceindexid, indexdescription, indexpriceval
		foreach ( $result as $row ){
			if ( $info['id'] == $row['id'] ){
				$output .= "<option value='$row[id]' selected>";
				$output .= $row['discountname'] . " - " . $row['discountvalue'] ;
				$output .= "</option>";
			} else {
				$output .= "<option value='$row[id]'>";
				$output .= $row['discountname'] . " - " . $row['discountvalue'] ;
				$output .= "</option>";
			}
		}
		$output .= "</select>";
		return $output;
	}

	public function mysanitize($input){
		$input = htmlspecialchars($input);
		$input = htmlentities($input); // convert symbols to html entities
		$input = addslashes($input); // server doesn't add slashes, so we will add them to escape ',",\,NULL
		return $input;
	}

	public function listvalidity ( $inputArray ) {

		$query = "SELECT * FROM websitetext WHERE shortcut like 'validity%';";

		$this->Query( $query );
		$results = $this->Select("m");

		$output = "<select name='validity' id='validity' style='width: ".$inputArray['width']."px'>";
		$output .= "<option value=''></option>";
		// keeps getting the next row until there are no more to get
		foreach( $results as $row ){
			if ( $inputArray['value'] == $row['contenttext'] ) {
				$output .= "<option value='" . $inputArray['value'] . "' selected>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
			else {
				$output .= "<option value='".$row['contenttext']."'>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
		}
		$output .= "</select>";
		return $output;
	}

	public function getClientInfoById ( $id ) {
		
		//$term = $term;
		$id = $this->mysanitize($id);
	
		$query = 'select * from clientlist where id = ' . $id;
		// var_dump($query);
		$this->Table_Name('discounts');
		$this->Query( $query );
		$result = $this->Select("m");

		$data = array();
		if ( $result ){
			foreach ( $result as $row ){
				$data[] = array(
					'label' => $row['companyname'] .', '. $row['contactperson'] .' '. $row['clientemail'] ,
					'value' => $row['companyname'],
					'contactperson' => $row['contactperson'],
					'clientemail' => $row['clientemail'],
					'client_id' => $row['id']
				);
			}
		}
		return $data[0];
	}

	public function getClientInfoByCode ( $code ) {
		
		//$term = $term;
		$id = $this->mysanitize($id);
	
		$query = 'select * from clientlist where id = ' . $id;
		// var_dump($query);
		$this->Table_Name('discounts');
		$this->Query( $query );
		$result = $this->Select("m");

		$data = array();
		if ( $result ){
			foreach ( $result as $row ){
				$data[] = array(
					'label' => $row['companyname'] .', '. $row['contactperson'] .' '. $row['clientemail'] ,
					'value' => $row['companyname'],
					'contactperson' => $row['contactperson'],
					'clientemail' => $row['clientemail'],
					'client_id' => $row['id']
				);
			}
		}
		return $data[0];
	}

	private function topMenuDisplay ( $typedisplay, $input ){
		return "<div id='topMenuDisplay'><font color='blue'>" . $typedisplay .": </font>" . $input['vendorname'] . " " . "(<a href='./editclientinfo.php' >" . $input['sysuser'] . "</a>) " .  logoutbutton()."</div>";
	}

	function pagemenu( $info ){

		$outstring = "";
		$outstring .= $this->topMenuDisplay( "Logged in", $info);
	
		$outstring .= "<div id='smoothmenu1' class='ddsmoothmenu'>";
		$outstring .= "<ul>";
	
		// Make a MySQL Connection
		$link = mysqli_connect(server_name(), getun(),  getpwd(), getdbname());
	
		$accesskey = $info['groupid'];
	
		$query = "SELECT * FROM menu WHERE parentid = 0 AND accesskey >= $accesskey ORDER BY position ASC;";
		$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
		// keeps getting the next row until there are no more to get
		while($row = mysqli_fetch_array( $result )) {
		if ( $row['displayname'] == "VIEW THIRD PARTY" ) {
			$outstring .= "<li><a href='".$row['url']."'>".$row['displayname']."</a>";
			//Sub menu items
			$outstring .= subusers( $info['loginid'] );
			$outstring .= "</li>";
		} elseif ( $row['displayname'] == "FILES" ) {
			$outstring .= "<li><a href='".$row['url']."'>".$row['displayname']."</a>";
			//Sub menu items
			$outstring .= filescategories( 1, $info['loginid'] );
			$outstring .= "</li>";
			$outstring .= "</li>";
		} else {
			$outstring .= "<li><a href='".$row['url']."'>".$row['displayname']."</a>";
			//Sub menu items
			$outstring .= submenus( $row['menuid'], $info['groupid'] );
			$outstring .= "</li>";
		}
		}
		//mysql_close();
		$outstring .= "</ul>";
		$outstring .= "<br style='clear: left' />";
		$outstring .= "</div>";
		return $outstring;
		mysqli_close();
	}

	public function getClientInfo ( $term ) {
		
		//$term = $term;
		$term = $this->mysanitize($term);
	
		$query = 'select * from clientlist where companyname like "'. $term .'%";';
		// var_dump($query);
		$this->Table_Name('discounts');
		$this->Query( $query );
		$result = $this->Select("m");

		$data = array();
		if ( $result ){
			foreach ( $result as $row ){
				$data[] = array(
					'label' => $row['companyname'] .', '. $row['contactperson'] .' '. $row['clientemail'] ,
					'value' => $row['companyname'],
					'contactperson' => $row['contactperson'],
					'clientemail' => $row['clientemail'],
					'client_id' => $row['id']
				);
			}
		}
		return $data;
	}
	
	public function listquotetypes ( $inputarray ) {
		$this->Table_Name('websitetext');
		$query = "SELECT * FROM websitetext WHERE shortcut = 'quotetype';";
		$this->Query( $query );
		$result = $this->Select("m");


		$output = "<select name='quotetypeid' id='quotetypeid' style='width: ".$inputarray['width']."px'>";
		$output .= "<option value=''></option>";
		foreach ( $result as $row ){

			if ( $inputarray['id'] == $row['id'] ) {
				$output .= "<option value='$row[id]' selected>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
			else {
				$output .= "<option value='$row[id]'>";
				$output .= $row['contenttext'];
				$output .= "</option>";
			}
		}
		$output .= "</select>";
		return $output;
	}

	public function listproducttypes ( $info ){

		$this->Table_Name($info['table']);
		$query = "SELECT * FROM `".$info['table']. "` WHERE `" . $info['column']. "` = '" . $info['value']. "';";
		$this->Query( $query );
		$result = $this->Select("m");

		$output = "<select name='choose' id='choose' style='width: ".$info['width']."px'>";
		$output .= "<option value=''></option>";
		foreach ( $result as $producttype){
			if ( !isset($info['value_check']) ){
				$output .= "<option value='".$producttype['id']."'>".$producttype['contenttext']."</option>";
			} else {
				if ( $producttype['id'] == $info['value_check']  ) {
					$output .= "<option value='".$producttype['id']."' selected>".$producttype['contenttext']."</option>";
				} else {
					$output .= "<option value='".$producttype['id']."'>".$producttype['contenttext']."</option>";
				}
			}
		}
		$output .= "</select>";

		return $output;
	}

	public function listproducttypeslike ( $info ){

		$this->Table_Name($info['table']);
		$query = "SELECT * FROM `".$info['table']. "` WHERE `" . $info['column']. "` like '%" . $info['value']. "%';";
		$this->Query( $query );
		$result = $this->Select("m");

		$output = "<select name='choose' id='choose' style='width: ".$info['width']."px'>";
		$output .= "<option value=''></option>";
		foreach ( $result as $producttype){
			if ( $info['value_check'] == $producttype['id'] ) {
				$output .= "<option value='".$producttype['id']."' selected>".$producttype['contenttext']."</option>";
			} else {
				$output .= "<option value='".$producttype['id']."'>".$producttype['contenttext']."</option>";
			}
		}
		$output .= "</select>";

		return $output;
	}

	public function getName($info){
		
		$this->Table_Name($info['table']);

		$this->Query("SELECT * FROM $info[table] WHERE id = '".$info['id']. "';");
		$result = $this->Select("s");


		if ( $info['logtype'] == 'short' ) {
			return $result['shortname'];
		} else {
			return $result[$info['column']];
		}
	}

	public function brTags($total) {
		$out = "";
		for ( $i = 1; $i < $total; $i++ ) {
			$out .= "<br />";
		}
		return $out;
	}
	
	public function displayQuoteLines($info){
		
		var_dump($info['quote_id']);
		
		$quote_id = $info['quote_id'];
		
		$sql = "SELECT * FROM quoteline WHERE id = '$quote_id' AND state = 'active';";
		
		var_dump($sql);
		
		$email = $this->returnInput(
			array (
				'table' => 'quoteslist',
				'columnname' => 'sys_user',
				'compare_column' => 'id',
				'compare_value' => $quote_id
			)
		);

		var_dump($email);
		
		
		$index = $this->returnInput(
			array (
				'table' => 'franchisordetails',
				'columnname' => 'ProductPriceIndex',
				'compare_column' => 'Email',
				'compare_value' => $email
			)
		);
		
		var_dump($index);

		$currency = $this->returnInput(
			array (
				'table' => 'quoteslist',
				'columnname' => 'currency',
				'compare_column' => 'id',
				'compare_value' => $quote_id
			)
		);

		
		$currencies = $this->getCurrencyInfo();
		
		
		$quotetypeid = $this->returnInput(
			array(
				'table' => 'quoteslist',
				'columnname' => 'quotetypeid',
				'compare_value' => $quote_id,
				'compare_column' => 'id'
			)
		);
		if ( !empty($quotetypeid) ){
			$quotetype = $this->returnInput(
				array (
					'table' => 'websitetext',
					'columnname' => 'contenttext',
					'compare_value' => $quotetypeid,
					'compare_column' => 'id'
				)
			);
		}
		
		var_dump($quotetype);
		
		$output = "<table width='100%' border='1'>";

		$tableStart = "<table width='100%' border='1'>";

		$tableHeader = "";
		exit();
	}

}

?>