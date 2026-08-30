<?php
$PAGE_TITLE = 'Çerez Politikası — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>Çerez Politikası</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <p>atolyera.com, temel işlevlerin çalışması için sınırlı sayıda çerez kullanır.</p>

  <h2>Kullandığımız Çerezler</h2>
  <table>
    <tr><th>Çerez</th><th>Amaç</th><th>Tür</th></tr>
    <tr><td>PHPSESSID</td><td>Oturum ve sepetin çalışması</td><td>Zorunlu (oturum)</td></tr>
  </table>

  <p>Bu çerez, sepetinizi ve oturumunuzu sürdürmek için gereklidir; tarayıcınızı kapattığınızda
  sona erer. Pazarlama veya üçüncü taraf takip çerezleri kullanmıyoruz.</p>

  <h2>Çerezleri Yönetme</h2>
  <p>Tarayıcı ayarlarınızdan çerezleri silebilir veya engelleyebilirsiniz; ancak zorunlu çerez
  engellendiğinde sepet ve sipariş işlevleri çalışmayabilir.</p>

  <p>Sorularınız için: <a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a></p>
  <p style="margin-top:30px;"><a href="<?= url('/urunler') ?>" class="btn btn--ghost btn--sm">← Koleksiyona dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
