#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""Atölye RA — Line Sheet / Lookbook (konsinye) HTML üretici."""
import json, os, html, urllib.request, re, sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG  = os.path.join(ROOT, 'php', 'images')
seed = json.load(open(os.path.join(ROOT, 'php', 'seed.json'), encoding='utf-8'))

# --- TCMB kuru (taze), olmazsa yedek ---
usd, eur = 48.16, 56.09
try:
    req = urllib.request.Request('https://www.tcmb.gov.tr/kurlar/today.xml',
                                 headers={'User-Agent': 'AtolyeRA/1.0'})
    xml = urllib.request.urlopen(req, timeout=8).read().decode('iso-8859-9', 'ignore')
    m = re.search(r'CurrencyCode="USD".*?<ForexSelling>([\d.]+)', xml, re.S)
    if m: usd = float(m.group(1))
    m = re.search(r'CurrencyCode="EUR".*?<ForexSelling>([\d.]+)', xml, re.S)
    if m: eur = float(m.group(1))
except Exception as e:
    print('kur çekilemedi, yedek kullanılıyor:', e, file=sys.stderr)

def fimg(name): return 'file://' + os.path.join(IMG, name)
def e(s): return html.escape(str(s or ''))
def tl(v): return f"{v:,.0f}".replace(',', '.')
def fx(v): return f"{v/usd:,.0f}".replace(',', 'X').replace('.', ',').replace('X', '.'), f"{v/eur:,.0f}".replace(',', 'X').replace('.', ',').replace('X', '.')

groups = [('baski', 'Sanat Baskı Koleksiyonu', 'İpek saten · viskon · viskon şifon · saten'),
          ('mon',   'Özel Parça', 'Saten ipek · imzalı, tek'),
          ('tilsim','Tılsım Koleksiyonu', 'TENCEL üzerine tılsım nakışı · üzerine atılır'),
          ('seri',  'Tılsım Serisi', 'TENCEL nakışlı · sınırlı, her biri bir tılsım taşır')]

CSS = """
@page { size: A4; margin: 0; }
* { margin:0; padding:0; box-sizing:border-box; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
body { font-family:'Cormorant Garamond',Georgia,serif; color:#1A1A1A; background:#F5F1EA; }
.sheet { width:210mm; min-height:297mm; padding:22mm 20mm; page-break-after:always; position:relative; background:#F5F1EA; }
.kicker { font-family:'Jost',sans-serif; font-size:10pt; letter-spacing:.28em; text-transform:uppercase; color:#5C1A1B; }
.display { font-family:'Fraunces',Georgia,serif; font-weight:300; color:#1A1A1A; }
/* Kapak */
.cover { display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; }
.cover img { width:70mm; margin-bottom:14mm; }
.cover h1 { font-family:'Fraunces',serif; font-weight:300; font-size:34pt; line-height:1.05; margin:6mm 0; }
.cover .sub { font-family:'Cormorant Garamond',serif; font-size:15pt; color:#2A2A2A; max-width:120mm; }
.cover .yr { font-family:'Jost',sans-serif; letter-spacing:.3em; font-size:11pt; margin-top:16mm; color:#5C1A1B; }
/* Manifesto */
.man h2 { font-family:'Fraunces',serif; font-weight:300; font-size:22pt; margin:0 0 6mm; }
.man p { font-size:13.5pt; line-height:1.75; color:#2A2A2A; margin:0 0 5mm; }
.terms { border-top:1px solid rgba(26,26,26,.2); margin-top:10mm; padding-top:8mm; }
.terms li { font-size:12pt; line-height:1.8; list-style:none; padding-left:12px; position:relative; color:#2A2A2A; }
.terms li:before { content:"—"; position:absolute; left:-6px; color:#5C1A1B; }
.contact { font-family:'Jost',sans-serif; font-size:10.5pt; line-height:1.9; margin-top:10mm; color:#1A1A1A; }
/* Bölüm başlığı */
.section-head { text-align:center; margin:0 0 10mm; }
.section-head h2 { font-family:'Fraunces',serif; font-weight:300; font-size:24pt; margin:3mm 0 2mm; }
.section-head .fab { font-family:'Jost',sans-serif; font-size:9.5pt; letter-spacing:.1em; color:#2A2A2A; }
/* Ürün ızgarası */
.grid { display:grid; grid-template-columns:1fr 1fr; gap:12mm 10mm; }
.item { break-inside:avoid; }
.item .pic { width:100%; height:105mm; object-fit:cover; background:#EDE7DC; display:block; }
.item .no { font-family:'Jost',sans-serif; font-size:8.5pt; letter-spacing:.2em; text-transform:uppercase; color:#5C1A1B; margin:3mm 0 1mm; }
.item .nm { font-family:'Fraunces',serif; font-size:15pt; margin:0 0 1mm; }
.item .st { font-size:11pt; line-height:1.5; color:#2A2A2A; margin:0 0 2mm; }
.item .pr { font-family:'Jost',sans-serif; font-size:10pt; color:#1A1A1A; }
.item .pr b { color:#5C1A1B; font-weight:500; }
.item .pr .alt { color:#6a6a6a; }
.foot { position:absolute; bottom:12mm; left:20mm; right:20mm; display:flex; justify-content:space-between;
        font-family:'Jost',sans-serif; font-size:8.5pt; letter-spacing:.12em; color:#5C1A1B; text-transform:uppercase; }
"""

def item_html(p):
    u, r = fx(p['price'])
    return f"""
    <div class="item">
      <img class="pic" src="{fimg(p['image'])}" alt="">
      <p class="no">{e(p['no_label'])}</p>
      <h3 class="nm">{e(p['name'])}</h3>
      <p class="st">{e((p['story'] or '')[:120])}</p>
      <p class="pr"><b>{tl(p['price'])} ₺</b> &nbsp;<span class="alt">≈ ${u} · €{r}</span><br>{e(p['material'])}</p>
    </div>"""

pages = []
# Kapak
pages.append(f"""
<section class="sheet cover">
  <img src="{fimg('logo-dark.png')}" alt="Atölye RA">
  <p class="kicker">Giyilebilir Sanat</p>
  <h1>Line Sheet<br>& Lookbook</h1>
  <p class="sub">Yağlı boya tablolardan doğan desenler, elde işlenmiş kumaşlar. İpek değil TENCEL; her parça bir imza.</p>
  <p class="yr">KOLEKSİYON 2026</p>
</section>""")

# Manifesto + konsinye şartları
pages.append(f"""
<section class="sheet man">
  <p class="kicker">Atölye RA</p>
  <h2 class="display">Aynısı bir daha doğmaz</h2>
  <p>Her Atölye RA parçası bir sanat eseridir: yağlı boya tablolardan esinlenen desenler, usta terzilerce elde işlenir. Koleksiyonumuz kimono ve sabahlık formunda, giyilebilir birer tuvaldir.</p>
  <p>Baskılı sanat koleksiyonu ipek saten, viskon, viskon şifon ve saten üzerine; tılsım serisi ise TENCEL üzerine nakışla, sınırlı sayıda hazırlanır. Her parça, taşıyıcısına özel bir tılsım barındırır.</p>
  <div class="terms">
    <p class="kicker" style="margin-bottom:4mm;">Konsinye Koşulları</p>
    <ul>
      <li>Ürünler konsinye esasıyla sunulur; satış gerçekleştikçe faturalandırılır, satılmayanlar iade edilir.</li>
      <li>Aşağıdaki fiyatlar önerilen perakende fiyatlardır (RRP, KDV dahil). Komisyon oranı karşılıklı görüşülür.</li>
      <li>Tam (özel) paketleme ile 3 iş günü içinde teslim.</li>
      <li>Her desen tektir; yeniden üretilmez.</li>
    </ul>
  </div>
  <div class="contact">
    <strong>REKA YAZILIM TURİZM TİC. VE SAN. A.Ş.</strong> — Atölye RA<br>
    art@atolyera.com · atolyera.com<br>
    Mithatpaşa Mah. Bahçeköy Cad. No:55 B17, Eyüpsultan / İstanbul
  </div>
  <div class="foot"><span>Atölye RA · Line Sheet 2026</span><span>art@atolyera.com</span></div>
</section>""")

# Ürün sayfaları (grupla, sayfada 4 ürün)
for slug, title, fab in groups:
    items = [p for p in seed if p['cat_slug'] == slug]
    for i in range(0, len(items), 4):
        chunk = items[i:i+4]
        head = f"""<div class="section-head"><p class="kicker">Koleksiyon</p><h2 class="display">{e(title)}</h2><p class="fab">{e(fab)}</p></div>""" if i == 0 else ""
        body = "".join(item_html(p) for p in chunk)
        pages.append(f"""<section class="sheet"><div style="padding-top:{'0' if head else '6mm'}">{head}<div class="grid">{body}</div></div>
        <div class="foot"><span>{e(title)}</span><span>atolyera.com · art@atolyera.com</span></div></section>""")

doc = f"""<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Fraunces:opsz,wght@9..144,300;9..144,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>{CSS}</style></head><body>{''.join(pages)}</body></html>"""

out = os.path.join(ROOT, 'atolyera-lookbook.html')
open(out, 'w', encoding='utf-8').write(doc)
print('HTML:', out, '| sayfa:', len(pages), '| USD:', usd, 'EUR:', eur)
