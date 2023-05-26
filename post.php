<?php
/**
 *
 *
 * Paratika sanal pos (hpp) ve Direct 3D Pos kurulumu için gerekli ayarları barınıdır.
 *
 * @category   Sanal Pos Kurulumu
 * @author     Hasan Kaya <hasankayaaa53@gmail.com>
 * @copyright  2021 Buhusoft
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01

 */


require_once("ParatikaConf.class.php");
/**
 * Paratika Sanal Pos Kurulumu
 */




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
  "name"=>"ödeme",
  "quantity"=>1,
  "description"=>"Deneme urunu",
  "amount"=>10
);




$paratika->urun = json_encode($urun);

$paratika->tutar = 10;
$req = json_decode($paratika->createToken(),true);

if($req["Status"] == true) {
    $token = $req["token"];
    $url = $req["url"];
    $formUrl = $req["formUrl"];
    $apiType = $req["ApiType"];
}else{

    echo json_encode($req);
    die;
}




if($apiType == "3D_PAY_HOSTING"){
    $paratika->odemeYonlendir($token);
}else{?>



    <form action="<?php echo $formUrl; ?>" method="post">
        <input type="text" name="cardOwner" placeholder="Card Owner" maxlength="32" value="h kaya" />
        <input type="text" name="pan" placeholder="PAN" maxlength="19" value="4546711234567894" />
        <select name="expiryMonth">
            <option value="12">February</option>
        </select>
        <select name="expiryYear">
            <option value="2026">2026</option>
        </select>
        <input type="password" name="cvv" placeholder="CVV" maxlength="4" value="000" />
        <input type="checkbox" name="saveCard" />
        <input type="text" name="cardName" placeholder="Card Name" value="test test"/>
        <input type="text" name="cardCutoffDay" placeholder="Card Cutoff Day" value="1"/>
        <input type="text" name="installmentCount" placeholder="Installment Count" value="5"/>
        <input type="hidden" name="points" />
        <input type="submit" value="Submit" />
    </form>



<?php } ?>
