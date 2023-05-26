# paratika
<h1> Paratika Sanal Pos Kurulumu </h1>


<b> <ul>

<li>ParatikaConf.class.php => Proje ayarlarının bulunduğu dosya.</li>
<li>post.php => paratika servislerinden ödeme almak için oluşturulmuş örnek dosya</li>
<li>return.php => paratika servislerinden ödeme sonucu dönülecek sayfa</li>
<li>sonucSorgula.php => paratika ödeme sonucu sorgulamak için oluşturulmuş örnek dosya</li>



</ul>  </b>


<h2> Ortak Ödeme Sayfası İle Ödeme Almak İçin Gerekli Adımlar </h2>
<ul><li>ParatikaConf.class.php Dosyası İçersinde bulunan paratikaType sabitini 3D_PAY_HOSTING olarak değiştirin.</li></ul>


<h2> Kart bilgilerinin sizin tarafınızdan Alındığı Ödeme yöntemi için (3D Direct) </h2>
<ul> <li>ParatikaConf.class.php Dosyası İçersinde bulunan paratikaType sabitini 3D_PAY_DIRECT olarak değiştirin.</li> </ul>



<h2> Kullanım </h2>

<p> Öncelikle ParatikaConf.class.php dosyasını require edin.  </p>
<p> ParatikaConf.class.php dosyasında gerekli ayarlamaları yapın.</p>


```php
$paratika = new ParatikaOdemeYap;
$paratika->aliciMail    = "info@hasankayaa.com";
$paratika->aliciAdSoyad = "Hasan Kaya";
$paratika->aliciTelefon = "00000000000";
$paratika->musteriAdres = "Müşteri Adres";
$paratika->musteriSehir = "Müşteri Şehir";
$paratika->musteriUlke = "Türkiye";
$paratika->musteriPostaKodu = "34440";
$paratika->musteriTelefon = "05355555555";

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




```


<h3>Ortak Ödeme Sayfası İle Ödeme Alma</h3>


```php

$paratika->odemeYonlendir($token);

```




<h3> 3D Direct İle Ödeme Alma  </h3>

```php

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

```


<h2> Diğer Servisler </h2>

<h3> Ödeme Sorgulama </h3>

```php

//oluşturduğunuz token bilgisini göndererek ödeme sonucunu kontrol edebilirsiniz.

$paratika = new ParatikaOdemeYap;
echo $paratika->odemeSorgula("AT7WZYQYGAVL4PUXGKZ2VFJMYMEYA6JSAR42IA7RAWIPODX7");




```









<h2> Notlar </h2>


<h3> Çoklu Ürün Göndermek İçin </h3>

```php



$urun = array(
  "code"=>12345,
  "name"=>"trkargom ödeme",
  "quantity"=>1,
  "description"=>"Deneme urunu",
  "amount"=>10
);
$urun2 = array(
  "code"=>12345,
  "name"=>"trkargom ödeme",
  "quantity"=>1,
  "description"=>"Deneme urunu",
  "amount"=>10
);

$paratika->urun = json_encode($urun).json_encode($urun2);


```

<b>Sistem, çoklu ürün gönderimi dahil tamamen en basit şekilde kodlanmıştır. Bir sorunla karşılaşmanız durumunda iletişime geçebilirsiniz. </b>
