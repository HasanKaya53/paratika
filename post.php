<?php
/**
 *
 *
 * Paratika sanal pos (hpp) kurulumu için gerekli ayarları barınıdır.
 *
 * @category   Sanal Pos Kurulumu
 * @author     Hasan Kaya <info@hasankayaa.com>
 * @copyright  2021 Buhusoft
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01

 */


require_once("ParatikaConf.class.php");
/**
 * Paratika Sanal Pos Kurulumu
 */
class ParatikaOdemeYap extends ParatikConfing
{


  private $merchantPaymentID;
  private $customerId;

  /* Alıcı Bilgileri ZORUNLU*/
  public $aliciMail;
  public $aliciAdSoyad;
  public $aliciTelefon;

  /* Faturalandırma ve adresler */
  public $musteriAdres;
  public $musteriSehir;
  public $musteriUlke = "Türkiye";
  public $musteriPostaKodu;
  public $musteriTelefon;


  /* Nesne Bilgileri ZORUNLU*/
  public $urun;

  function __construct()
  {
    $this->merchantPaymentID = 'MPID-'.strtotime(date("H:i:s"));
    $this->customerId = 'CUS-'.strtotime(date("H:i:s"));
  }

 function createToken(){
    $sendData = array(
      "ACTION"=> "SESSIONTOKEN",
      "SESSIONTYPE"=>"PAYMENTSESSION",

      "RETURNURL"=>self::returnURL,
      "MERCHANT"=>self::merchant,
      "MERCHANTUSER"=>self::merchantUser,
      "MERCHANTPASSWORD"=>self::merchantPass,
      "CURRENCY"=>self::paraBirimi,

      "ORDERITEMS"=>urlencode("[".$this->urun."]"),
      "MERCHANTPAYMENTID"=>$this->merchantPaymentID,
      "AMOUNT"=> $this->tutar,
      "CUSTOMER"=>$this->customerId,
      "CUSTOMEREMAIL"=>$this->aliciMail,
      "CUSTOMERNAME"=>$this->aliciAdSoyad,
      "CUSTOMERPHONE"=>$this->aliciTelefon,
      "CUSTOMERIP"=> $this->getIPAddress(),

      "BILLTOADDRESSLINE"=>self::firmaAdresi,
      "BILLTOCITY"=>self::firmaSehir,
      "BILLTOCOUNTRY"=>self::firmaUlke,
      "BILLTOPOSTALCODE"=>self::firmaPostaKodu,
      "BILLTOPHONE"=>self::firmaTelefon,

      "SHIPTOADDRESSLINE"=> $this->musteriAdres,
      "SHIPTOCITY"=>$this->musteriSehir,
      "SHIPTOCOUNTRY"=> $this->musteriUlke,
      "SHIPTOPOSTALCODE"=>$this->musteriPostaKodu,
      "SHIPTOPHONE"=> $this->musteriTelefon
    );




    $postdata = http_build_query($sendData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true); //POST Metodu kullanarak verileri gönder
    curl_setopt($ch, CURLOPT_HEADER, false); //Serverdan gelen Header bilgilerini önemseme.
    curl_setopt($ch, CURLOPT_URL, "https://entegrasyon.paratika.com.tr/paratika/api/v2"); //Bağlanacağı URL
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); //POST verilerinin querystring hali. Gönderime hazır!
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Transfer sonuçlarını return et. Onları kullanacağım!
    curl_setopt($ch, CURLOPT_TIMEOUT, 20); //20 saniyede işini bitiremezsen timeout ol.
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $data = curl_exec($ch);
    curl_close($ch);

    $returnValue =  (array)json_decode($data);


    $sessionToken = $returnValue["sessionToken"];
    $respCode = $returnValue["responseCode"];

    if ($respCode != "00") {
       return $returnValue["responseMsg"]." ".$returnValue["errorCode"]." ".$returnValue["errorMsg"]." ".$returnValue["violatorParam"];
    }
    else return $sessionToken;

  }
  function getIPAddress() {
    if      (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    elseif  (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else    $ip_address = $_SERVER['REMOTE_ADDR'];

    return $ip_address;

  }

  function odemeYonlendir($token){
    $url = "https://entegrasyon.paratika.com.tr/payment/".$token;
    header("Location: $url");
  }
}



$paratika = new ParatikaOdemeYap;
$paratika->aliciMail    = "info@hasankayaa.com";
$paratika->aliciAdSoyad = "Hasan Kaya";
$paratika->aliciTelefon = "00000000000";
$paratika->musteriAdres = "Müşteri Adres";
$paratika->musteriSehir = "Müşteri Şehir";
$paratika->musteriUlke = "Türkiye";
$paratika->musteriPostaKodu = "34440";
$paratika->musteriTelefon = "05355555555";


//tek ürün gönderimi
$urun = array(
  "code"=>12345,
  "name"=>"trkargom ödeme",
  "quantity"=>1,
  "description"=>"Deneme urunu",
  "amount"=>10
);

$paratika->urun = json_encode($urun);

$paratika->tutar = 10;
$token = $paratika->createToken();
$paratika->odemeYonlendir($token);

//çoklu ürün gönderimi
// $urun = array(
//   "code"=>12345,
//   "name"=>"trkargom ödeme",
//   "quantity"=>1,
//   "description"=>"Deneme urunu",
//   "amount"=>10
// );
// $urun2 = array(
//   "code"=>12345,
//   "name"=>"trkargom ödeme",
//   "quantity"=>1,
//   "description"=>"Deneme urunu",
//   "amount"=>10
// );
//
// $paratika->urun = json_encode($urun).json_encode($urun2);




 ?>
