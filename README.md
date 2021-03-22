# paratika
Paratika Sanal Pos Kurulumu

PHP ile paratika sanal pos kurmak için gerekli adımları takip edin.
1)ParatikaConf.class.php de gerekli parametreleri giriniz.
2)Post.php dosyasında scriptin kullanımına ilişkin bir örnek bulacaksınız. Lütfen adımları takip edin.


Çoklu Ürün Göndermek:

//çoklu ürün gönderimi
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


Sistem, çoklu ürün gönderimi dahil tamamen en basit şekilde kodlanmıştır. Bir sorunla karşılaşmanız durumunda iletişime geçebilirsiniz. 
