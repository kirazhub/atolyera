<?php
$PAGE_TITLE = 'İptal, İade ve Cayma Hakkı — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>İptal, İade ve Cayma Hakkı</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <h2>Cayma Hakkı Süresi</h2>
  <p>Ürünü teslim aldığınız tarihten itibaren <strong>14 (on dört) gün</strong> içinde herhangi bir gerekçe
  göstermeksizin siparişinizden cayabilirsiniz.</p>

  <h2>Nasıl İade Ederim?</h2>
  <ul>
    <li><?= e($c['email']) ?> adresine sipariş numaranız ile "iade" talebinizi iletin.</li>
    <li>Size iade/gönderim yönergelerini ileteceğiz.</li>
    <li>Ürünü, kullanılmamış, yıkanmamış, etiketleri sökülmemiş ve orijinal ambalajında gönderin.</li>
  </ul>

  <h2>İade Koşulları</h2>
  <ul>
    <li>Ürün yeniden satılabilir durumda olmalıdır. Kullanım, koku, leke veya hasar durumunda iade kabul edilmeyebilir.</li>
    <li>Kişiye özel / ısmarlama üretilen parçalarda mevzuat gereği cayma hakkı istisnaları uygulanabilir; bu husus sipariş öncesi bildirilir.</li>
  </ul>

  <h2>Geri Ödeme</h2>
  <p>İade edilen ürün tarafımıza ulaşıp incelendikten sonra, uygun bulunması hâlinde ödemeniz
  <strong>14 gün</strong> içinde, ödemeyi yaptığınız yöntemle iade edilir.</p>

  <h2>Sipariş İptali</h2>
  <p>Kargoya verilmeden önce siparişinizi <?= e($c['email']) ?> adresinden iptal ettirebilirsiniz.
  Ödeme alındıysa tam iade yapılır.</p>

  <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--ghost btn--sm">← Koleksiyona dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
