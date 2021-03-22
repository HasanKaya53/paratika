<?php
/**
 * Paratika firma ayarları,
 * Öncelikle paratika panelinizden api user tanımlaması yapmanız gerekmektedir.
 * oluşturduğunuz api userın mail adresi $merchantUser, şifresi $merchantPass'dır.
 * returnURL değişkeni, projede paylaştığım return.php dosyası içindir.
 * return.php de ödeme yapıldıktan sonra, başarılı-başarısız sonuçları döndürmek için kullanırız.
 * verilerinizi ilgili şekilde giriniz
 */

class ParatikConfing
{
  /* Satıcı bilgileri */
  public const merchant        = "000000"; //Merchant ID niz.
  public const merchantUser    = "xxxx@gmail.com"; //merchantMail
  public const merchantPass    = "merchantPass"; //merchant Pass
  public const returnURL       = "localhost:8080/return.php";
  public const paraBirimi      = "TRY";
  /* Satıcı Adres */
  public const firmaAdresi     = "deneme adresi";
  public const firmaSehir      = "istanbul";
  public const firmaPostaKodu  = "34440";
  public const firmaUlke       = "turkiye";
  public const firmaTelefon    = "12345678";
}



 ?>
