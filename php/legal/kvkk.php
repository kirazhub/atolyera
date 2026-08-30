<?php
$PAGE_TITLE = 'KVKK Aydınlatma Metni — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>KVKK Aydınlatma Metni</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla
  <?= e($c['title']) ?> tarafından kişisel verilerinizin işlenmesine ilişkin olarak sizi bilgilendiririz.</p>

  <h2>Veri Sorumlusu</h2>
  <p>Ünvan: <?= e($c['title']) ?><br>
     Adres: <?= e($c['address']) ?><br>
     E-posta: <?= e($c['email']) ?><?php if ($c['phone']): ?> · Telefon: <?= e($c['phone']) ?><?php endif; ?></p>

  <h2>İşlenen Kişisel Veriler</h2>
  <p>Kimlik (ad soyad), iletişim (e-posta, telefon, adres) ve işlem (sipariş, ödeme) verileri.</p>

  <h2>İşleme Amaçları ve Hukuki Sebep</h2>
  <ul>
    <li>Sözleşmenin kurulması ve ifası (siparişin hazırlanması, teslimi) — KVKK m.5/2-c.</li>
    <li>Hukuki yükümlülüklerin yerine getirilmesi (fatura, muhasebe) — KVKK m.5/2-ç.</li>
    <li>Talep/şikâyet yönetimi ve meşru menfaat — KVKK m.5/2-f.</li>
  </ul>

  <h2>Aktarım</h2>
  <p>Verileriniz; kargo, ödeme ve muhasebe hizmet sağlayıcıları ile yalnızca amaçla sınırlı olarak ve
  yasal olarak yetkili kamu kurum/kuruluşlarıyla mevzuat gereği paylaşılabilir.</p>

  <h2>Haklarınız (KVKK m.11)</h2>
  <p>Kişisel verilerinizin işlenip işlenmediğini öğrenme, düzeltilmesini/silinmesini isteme, işlemenin
  sınırlandırılmasını talep etme ve kanunda sayılan diğer haklarınızı <?= e($c['email']) ?> adresine
  başvurarak kullanabilirsiniz. Başvurularınız en geç 30 gün içinde sonuçlandırılır.</p>

  <p style="margin-top:30px;"><a href="<?= url('/odeme') ?>" class="btn btn--ghost btn--sm">← Ödemeye dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
