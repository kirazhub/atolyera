/* ATÖLYE RA — küçük etkileşimler */

// Mobil menü aç/kapa
const menuBtn = document.getElementById('menuBtn');
const navLinks = document.getElementById('navLinks');

if (menuBtn) {
  menuBtn.addEventListener('click', () => {
    navLinks.classList.toggle('is-open');
  });
}

// Menü linkine tıklayınca kapansın (mobil)
navLinks.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => navLinks.classList.remove('is-open'));
});

// Kaydırınca bölümler yumuşakça belirsin
const revealTargets = document.querySelectorAll(
  '.manifesto, .ethos__block, .section-head, .section-sub, .interlude, .piece, .heritage, .faq, .contact'
);
revealTargets.forEach(el => el.classList.add('reveal'));

const io = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-in');
      io.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

revealTargets.forEach(el => io.observe(el));
