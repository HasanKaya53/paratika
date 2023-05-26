<?php
/**
 * Paratika firma ayarları,
 * Öncelikle paratika panelinizden api user tanımlaması yapmanız gerekmektedir.
 * oluşturduğunuz api userın mail adresi $merchantUser, şifresi $merchantPass'dır.
 * returnURL değişkeni, projede paylaştığım return.php dosyası içindir.
 * return.php de ödeme yapıldıktan sonra, başarılı-başarısız sonuçları döndürmek için kullanırız.
 * verilerinizi ilgili şekilde giriniz
 *
 * paratikaType => 3D_PAY_HOSTING veya 3D_PAY_DIRECT.
 * 3D_PAY_HOSTING => Ortak Ödeme sayfasından ödeme almak için kullanılır.
 * 3D_PAY_DIRECT => Kart bilgilerini formdan almak için kullanılır.
 *
 *
 * apiType => test veya canlı (servislere bağlanmak için.)
 */

class ParatikConfing
{
  /* Satıcı bilgileri */

  public const merchant        = ""; //Merchant ID niz.
  public const merchantUser    = ""; //merchantMail
  public const merchantPass    = ""; //merchant Pass
  public const returnURL       = "http://localhost/paratika/paratika/return.php";
  public const paraBirimi      = "TRY";
  /* Satıcı Adres */
  public const firmaAdresi     = "deneme adresi";
  public const firmaSehir      = "istanbul";
  public const firmaPostaKodu  = "34440";
  public const firmaUlke       = "turkiye";
  public const firmaTelefon    = "12345678";

  public const apiUrl = "https://vpos.paratika.com.tr/paratika/api/v2";
  public const apiTestUrl = "https://entegrasyon.paratika.com.tr/paratika/api/v2";

  public const apiTest3DUrl = "https://entegrasyon.paratika.com.tr/paratika/api/v2/post/sale3d";
  public const api3DUrl = "https://entegrasyon.paratika.com.tr/paratika/api/v2/post/sale3d";
  //test, canli
  public const apiType = "test";


  //3D_PAY_HOSTING, 3D_PAY_DIRECT
  public const paratikaType = "3D_PAY_DIRECT";
}


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


  public $sendUrl  ="";
  public $formUrl = "";


  /* Nesne Bilgileri ZORUNLU*/
  public $urun;

  function __construct()
  {
    $this->merchantPaymentID = 'MPID-'.strtotime(date("H:i:s"));
    $this->customerId = 'CUS-'.strtotime(date("H:i:s"));
  }

  function CreatePost($sendData){


    if(self::apiType == "canli"){
      $this->sendUrl = self::apiUrl;
      $this->formUrl = self::apiTest3DUrl;
    }else{
      $this->sendUrl = self::apiTestUrl;
      $this->formUrl = self::apiTest3DUrl;
    }


    $postdata = http_build_query($sendData);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true); //POST Metodu kullanarak verileri gönder
    curl_setopt($ch, CURLOPT_HEADER, false); //Serverdan gelen Header bilgilerini önemseme.
    curl_setopt($ch, CURLOPT_URL, $this->sendUrl); //Bağlanacağı URL
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); //POST verilerinin querystring hali. Gönderime hazır!
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Transfer sonuçlarını return et. Onları kullanacağım!
    curl_setopt($ch, CURLOPT_TIMEOUT, 20); //20 saniyede işini bitiremezsen timeout ol.
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
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




    $data = $this->CreatePost($sendData);

    $returnValue =  (array)json_decode($data);
    $respCode = $returnValue["responseCode"];

    if ($respCode != "00") {
      return json_encode(array("Status"=>false,"message"=>$returnValue["responseMsg"]." ".$returnValue["errorCode"]." ".$returnValue["errorMsg"]." ".$returnValue["violatorParam"]));
    }


    $sessionToken = $returnValue["sessionToken"];
    return json_encode(array("Status"=>true,"token"=>$sessionToken,"url"=>$this->sendUrl."/".$sessionToken,"ApiType"=>self::paratikaType,"formUrl"=>$this->formUrl."/".$sessionToken));

  }
  function getIPAddress() {
    if      (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    elseif  (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else    $ip_address = $_SERVER['REMOTE_ADDR'];

    return $ip_address;

  }

  function odemeSorgula($sessionID){


    $sendData = array(
        "ACTION"=> "QUERYTRANSACTION",
        "MERCHANT"=>self::merchant,
        "MERCHANTUSER"=>self::merchantUser,
        "MERCHANTPASSWORD"=>self::merchantPass,
        "PGTRANID"=>$sessionID
    );
    $data = $this->CreatePost($sendData);
    echo $data;
  }



  function odemeYonlendir($token){
    $url = "https://entegrasyon.paratika.com.tr/payment/".$token;
    header("Location: $url");
  }
}


 ?>
