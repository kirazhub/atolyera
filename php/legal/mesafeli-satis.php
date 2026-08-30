<?php
$PAGE_TITLE = 'Mesafeli Satış Sözleşmesi — Atölye RA';
require __DIR__ . '/../partials/header.php';
$c = config('company');
?>
<div class="legal">
  <h1>Mesafeli Satış Sözleşmesi</h1>
  <p class="updated">Son güncelleme: <?= date('d.m.Y') ?></p>

  <h2>1. Taraflar</h2>
  <p><strong>SATICI:</strong><br>
    Ünvan: <?= e($c['title']) ?><br>
    Adres: <?= e($c['address']) ?><br>
    Vergi Dairesi / No: <?= e($c['tax_office']) ?> / <?= e($c['tax_no']) ?><br>
    <?php if ($c['mersis']): ?>MERSİS No: <?= e($c['mersis']) ?><br><?php endif; ?>
    E-posta: <?= e($c['email']) ?><?php if ($c['phone']): ?> · Telefon: <?= e($c['phone']) ?><?php endif; ?><br>
    Web: atolyera.com
  </p>
  <p><strong>ALICI:</strong> Sipariş formunda belirtilen ad, adres ve iletişim bilgilerine sahip müşteri.</p>

  <h2>2. Konu</h2>
  <p>İşbu sözleşmenin konusu, ALICI'nın SATICI'ya ait <strong>atolyera.com</strong> internet sitesinden
  elektronik ortamda siparişini verdiği, aşağıda nitelikleri ve satış fiyatı belirtilen ürünün satışı ve
  teslimi ile ilgili olarak 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler
  Yönetmeliği hükümleri gereğince tarafların hak ve yükümlülüklerinin belirlenmesidir.</p>

  <h2>3. Sözleşme Konusu Ürün ve Ödeme Bilgileri</h2>
  <p>Ürün(ler)in cinsi, türü, adedi, satış bedeli ve ödeme şekli, sipariş anında ALICI'ya sunulan
  sepet ve sipariş özetinde ve sipariş onay e-postasında belirtildiği gibidir. Tüm fiyatlara KDV dahildir.
  Kargo ücreti sipariş özetinde ayrıca gösterilir; aksi belirtilmedikçe teslimat ücretsizdir.</p>

  <h2>4. Genel Hükümler</h2>
  <ul>
    <li>ALICI, ürünün temel nitelikleri, satış fiyatı ve ödeme şekli ile teslimata ilişkin ön bilgileri
    okuyup bilgi sahibi olduğunu ve elektronik ortamda gerekli teyidi verdiğini kabul eder.</li>
    <li>Ürün, ALICI'nın sipariş formunda bildirdiği adrese, yasal 30 (otuz) günlük süreyi aşmamak
    kaydıyla teslim edilir.</li>
    <li>Her parça elde işlendiğinden, ürün görsellerinde renk ve dokuda küçük farklılıklar olabilir.</li>
  </ul>

  <h2>5. Cayma Hakkı</h2>
  <p>ALICI, sözleşme konusu ürünün kendisine tesliminden itibaren <strong>14 (on dört) gün</strong> içinde
  hiçbir gerekçe göstermeksizin ve cezai şart ödemeksizin cayma hakkına sahiptir. Cayma bildirimi
  <?= e($c['email']) ?> adresine yapılır. Ürün, kullanılmamış, etiketleri sökülmemiş ve yeniden satılabilir
  durumda iade edilmelidir. İade onaylandığında ödeme, 14 gün içinde ALICI'ya iade edilir.
  Detaylar için <a href="<?= url('/yasal/iade-cayma') ?>">İptal &amp; İade</a> sayfasına bakınız.</p>
  <p>Kişiye özel üretilen veya ısmarlama hazırlanan ürünlerde, mevzuat gereği cayma hakkı istisnaları
  uygulanabilir; bu durum sipariş öncesi ALICI'ya bildirilir.</p>

  <h2>6. Uyuşmazlıklar</h2>
  <p>İşbu sözleşmeden doğabilecek uyuşmazlıklarda, Ticaret Bakanlığı'nca ilan edilen parasal sınırlar
  dahilinde ALICI'nın yerleşim yerindeki Tüketici Hakem Heyetleri ile Tüketici Mahkemeleri yetkilidir.</p>

  <h2>7. Yürürlük</h2>
  <p>ALICI, siparişi elektronik ortamda onayladığında işbu sözleşmenin tüm koşullarını kabul etmiş sayılır.</p>

  <p style="margin-top:30px;"><a href="<?= url('/odeme') ?>" class="btn btn--ghost btn--sm">← Ödemeye dön</a></p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
