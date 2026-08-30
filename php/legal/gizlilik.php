<?php
$PAGE_TITLE = 'Gizlilik Politikası — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>Gizlilik Politikası</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <p><?= e($c['title']) ?> ("Atölye RA") olarak gizliliğinize önem veriyoruz. Bu politika, atolyera.com'u
  kullanırken hangi verileri, neden ve nasıl işlediğimizi açıklar.</p>

  <h2>Topladığımız Veriler</h2>
  <ul>
    <li><strong>Sipariş bilgileri:</strong> ad soyad, e-posta, telefon, teslimat adresi.</li>
    <li><strong>İşlem bilgileri:</strong> sipariş içeriği, tutar, tarih.</li>
    <li><strong>Teknik veriler:</strong> temel oturum çerezi (sepetin çalışması için).</li>
  </ul>

  <h2>Kullanım Amaçları</h2>
  <ul>
    <li>Siparişinizi hazırlamak, teslim etmek ve sizinle iletişim kurmak.</li>
    <li>Yasal yükümlülükleri (fatura, muhasebe) yerine getirmek.</li>
    <li>Talep ve şikâyetlerinizi yönetmek.</li>
  </ul>

  <h2>Paylaşım</h2>
  <p>Verileriniz yalnızca siparişin ifası için gerekli olan taraflarla (ör. kargo firması, ödeme/muhasebe
  hizmet sağlayıcıları) ve yasal olarak zorunlu hâllerde yetkili kurumlarla paylaşılır. Verileriniz
  pazarlama amacıyla üçüncü kişilere satılmaz.</p>

  <h2>Saklama ve Güvenlik</h2>
  <p>Veriler, ilgili mevzuatın öngördüğü süreler boyunca saklanır ve yetkisiz erişime karşı makul teknik
  ve idari tedbirlerle korunur.</p>

  <h2>Haklarınız</h2>
  <p>KVKK kapsamındaki haklarınız için <a href="<?= url('/yasal/kvkk') ?>">KVKK Aydınlatma Metni</a>'ne
  bakabilir, taleplerinizi <?= e($c['email']) ?> adresine iletebilirsiniz.</p>

  <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--ghost btn--sm">← Koleksiyona dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
