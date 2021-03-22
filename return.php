<?php
    header('Content-type: text/html; charset=utf-8');
?>
<html>
<head>
<title>Odeme Sonuc Sayfasi</title>
</head>
<body>
	<?php
        if(empty($_POST) ) {
          // return.php urlsini direkt olarak yazarsak veya sanal posumuzdan cevap gelmezse müşteriye gözükecek cevap.
            echo "post yok ..";
        }
        else {
          // post.php 'de token oluşturduktan sonra, tokeni veritabanımıza kaydedip, sonrasında post ile gelen tokeni sistemde aratarak güncelleyebiliriz.

          // sanal posumuzdan dönen değerler. Müşteriye gösterilmemesi gerekiyor.
          foreach ($_POST as $key => $value){
            echo $key . ": " . $value . "</br>";
         }


         $responseCode = $_POST["responseCode"]; //durum kodu
         $responseMsg = $_POST["responseMsg"];   //ödeme mesajı

         //ödeme 00 ve Approved ise (yani ödenmişse)
         if ($responseCode == 00 && $responseMsg == "Approved") {
              // burda yapmanız gereken sessionToken  ödeme sonucunu alıp veritabanınızda bulmak ve güncellemektir.
              $sessionToken = $_POST["sessionToken"];
         }else{
           // ödemenin başarsız olduğunu kullanıcıya belirttirk.
           echo "Ödeme Yapılmadı. Hata Mesajı: ".$responseMsg." Hata Kodu:".$responseCode." Sorun devam ederse sistem yöneticinizle iletişime geçin.";
           die();
         }



      }

	?>
</body>
</html>
