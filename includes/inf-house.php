<?php
 $d = get_post_meta(get_the_ID(), 'house_info', true);
if (!is_array($d) || (empty($d['title']) && empty($d['desc']))) return;

 $title     = $d['title'] ?? '';
 $gold_text = $d['gold_text'] ?? '';
 $desc      = $d['desc'] ?? '';
 $image     = $d['image'] ?? '';
 $gallery   = $d['gallery'] ?? [];
 $features  = $d['features'] ?? [];
 $cta_text  = $d['cta_text'] ?? '';
 $cta_link  = $d['cta_link'] ?? '';
  $advantages = $d['advantages'] ?? [];
 $all_imgs  = $image ? array_merge([$image], $gallery) : $gallery;
?>

<section class="c-house-info reveal">
  <div class="container">
    <div class="c-house-info__grid">

      <?php if (!empty($all_imgs)) : ?>
      <div class="c-house-info__images">
        <?php if (!empty($image)) : ?>
        <div class="c-house-info__main-img">
          <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
        </div>
        <?php endif; ?>

        <?php if (!empty($gallery)) : ?>
        <div class="c-house-info__gallery-wrap">
          <div class="hi-gal-track">
            <?php foreach ($gallery as $gi => $gurl) : ?>
            <div class="c-house-info__gallery-item">
              <img src="<?php echo esc_url($gurl); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
          </div>
          <button class="hi-gal-prev" type="button">&#8249;</button>
          <button class="hi-gal-next" type="button">&#8250;</button>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <div class="c-house-info__content">
        <?php if ($title || $gold_text) : ?>
        <h2 class="c-house-info__title">
          <?php echo esc_html($title); ?><?php if ($title && $gold_text) : ?><?php endif; ?>
          <?php if ($gold_text) : ?><em><?php echo esc_html($gold_text); ?></em><?php endif; ?>
        </h2>
        <?php endif; ?>

        <?php if (!empty($d['highlight'])) : ?>
<div class="c-house-info__highlight">
  <?php echo wp_kses_post($d['highlight']); ?>
</div>
<?php endif; ?>

        <?php if ($desc) : ?>
        <p class="c-house-info__desc"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>

        <?php if (!empty($advantages)) : ?>
<div class="c-house-info__advantages">
  <?php foreach ($advantages as $adv) : ?>
  <span class="c-house-info__adv"><?php echo esc_html($adv); ?></span>
  <?php endforeach; ?>
</div>
<?php endif; ?>

        <?php if (!empty($features)) : ?>
        <ul class="c-house-info__features">
          <?php foreach ($features as $f) : ?>
          <li class="c-house-info__feat">
            <?php if (!empty($f['icon'])) : ?><i class="<?php echo esc_attr($f['icon']); ?>"></i><?php endif; ?>
            <span><?php echo esc_html($f['text']); ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if ($cta_text && $cta_link) : ?>
        <a href="<?php echo esc_url($cta_link); ?>" class="btn btn--gold"><?php echo esc_html($cta_text); ?></a>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<?php if (!empty($all_imgs)) : ?>
<div class="hi-lightbox" id="hi-lightbox">
  <button class="hi-lightbox__close" id="hi-lb-close">&times;</button>
  <button class="hi-lightbox__prev" id="hi-lb-prev">&#8249;</button>
  <button class="hi-lightbox__next" id="hi-lb-next">&#8250;</button>
  <div class="hi-lightbox__img-wrap">
    <img src="" alt="" id="hi-lb-img">
  </div>
  <div class="hi-lightbox__counter" id="hi-lb-counter"></div>
</div>

<style>
/* ── ГАЛЕРЕЯ (inline, чтобы точно работало) ── */
.c-house-info__gallery-wrap{position:relative}
.hi-gal-track{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;scrollbar-width:none;-ms-overflow-style:none}
.hi-gal-track::-webkit-scrollbar{display:none}
.c-house-info__gallery-item{flex:0 0 calc((100% - 24px) / 3);overflow:hidden;cursor:pointer;line-height:0;scroll-snap-align:start}
.c-house-info__gallery-item img{width:100%;height:140px;object-fit:cover;display:block;opacity:.7;transition:transform .6s cubic-bezier(.22,1,.36,1),opacity .3s}
.c-house-info__gallery-item:hover img{transform:scale(1.05);opacity:1}
.hi-gal-prev,.hi-gal-next{position:absolute;top:50%;transform:translateY(-50%);z-index:10;width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:rgba(10,22,40,.85);border:1px solid rgba(200,169,110,.3);color:#c8a96e;font-size:18px;cursor:pointer;transition:background .3s,border-color .3s;border-radius:0!important}
.hi-gal-prev:hover,.hi-gal-next:hover{background:rgba(200,169,110,.2);border-color:#c8a96e;color:#fff}
.hi-gal-prev{left:4px}
.hi-gal-next{right:4px}

/* ── ЛАЙТБОКС ── */
.hi-lightbox{position:fixed!important;inset:0;z-index:9999;background:rgba(5,10,20,.92);display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:opacity .3s,visibility .3s}
.hi-lightbox.is-open{opacity:1;visibility:visible}
.hi-lightbox__img-wrap{position:relative;max-width:90vw;max-height:85vh;display:flex;align-items:center;justify-content:center;line-height:0}
.hi-lightbox__img-wrap img{max-width:90vw;max-height:85vh;object-fit:contain}
.hi-lightbox__close{position:absolute;top:20px;right:24px;z-index:10;width:44px;height:44px;display:grid;place-items:center;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:#fff;font-size:28px;cursor:pointer;transition:background .3s,border-color .3s;line-height:1;border-radius:0!important}
.hi-lightbox__close:hover{background:rgba(200,169,110,.15);border-color:#c8a96e}
.hi-lightbox__prev,.hi-lightbox__next{position:absolute;top:50%;transform:translateY(-50%);z-index:10;width:52px;height:52px;display:grid;place-items:center;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:#fff;font-size:32px;cursor:pointer;transition:background .3s,border-color .3s;line-height:1;border-radius:0!important}
.hi-lightbox__prev:hover,.hi-lightbox__next:hover{background:rgba(200,169,110,.15);border-color:#c8a96e}
.hi-lightbox__prev{left:20px}
.hi-lightbox__next{right:20px}
.hi-lightbox__counter{position:absolute;bottom:24px;left:50%;transform:translateX(-50%);font-size:13px;color:rgba(255,255,255,.5);letter-spacing:.05em}

@media(max-width:768px){
  .c-house-info__gallery-item{flex:0 0 calc((100% - 12px) / 2)}
  .hi-gal-prev,.hi-gal-next{width:30px;height:30px;font-size:12px}
  .hi-lightbox__prev,.hi-lightbox__next{width:40px;height:40px;font-size:24px}
}
</style>

<script>
(function() {
  'use strict';
  if (window.__houseInfoReady) return;
  window.__houseInfoReady = true;

  var lb = document.getElementById('hi-lightbox');
  if (!lb) return;
  var lbImg = document.getElementById('hi-lb-img');
  var lbCnt = document.getElementById('hi-lb-counter');
  var btnClose = document.getElementById('hi-lb-close');
  var btnPrev = document.getElementById('hi-lb-prev');
  var btnNext = document.getElementById('hi-lb-next');
  var mainImg = document.querySelector('.c-house-info__main-img img');
  var mainWrap = document.querySelector('.c-house-info__main-img');

  /* Все URL: главное + галерея */
  var imgs = [];
  if (mainImg) {
    var s = mainImg.getAttribute('src');
    if (s) imgs.push(s);
  }
  document.querySelectorAll('.c-house-info__gallery-item img').forEach(function(img) {
    var s = img.getAttribute('src');
    if (s) imgs.push(s);
  });
  if (!imgs.length) return;
  var cur = 0;

  function showLB() {
    if (!imgs[cur]) return;
    lbImg.src = imgs[cur];
    lbCnt.textContent = (cur + 1) + ' / ' + imgs.length;
  }

  function openLB() {
    /* найти текущее главное изображение в массиве */
    if (mainImg) {
      var idx = imgs.indexOf(mainImg.getAttribute('src'));
      cur = idx >= 0 ? idx : 0;
    }
    showLB();
    lb.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeLB() {
    lb.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function goPrev() { cur = (cur - 1 + imgs.length) % imgs.length; showLB(); }
  function goNext() { cur = (cur + 1) % imgs.length; showLB(); }

  /* Клик по лайтбоксу */
  lb.addEventListener('click', function(e) {
    if (e.target === btnClose || btnClose.contains(e.target)) { closeLB(); return; }
    if (e.target === btnPrev || btnPrev.contains(e.target)) { goPrev(); return; }
    if (e.target === btnNext || btnNext.contains(e.target)) { goNext(); return; }
    if (e.target === lb || e.target.classList.contains('hi-lightbox__img-wrap')) { closeLB(); }
  });

  document.addEventListener('keydown', function(e) {
    if (!lb.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeLB();
    if (e.key === 'ArrowLeft') goPrev();
    if (e.key === 'ArrowRight') goNext();
  });

  /* Лайтбокс — ТОЛЬКО по клику на большое изображение */
  if (mainWrap) {
    mainWrap.style.cursor = 'pointer';
    mainWrap.addEventListener('click', openLB);
  }

  /* Миниатюры — меняют главное изображение */
  document.querySelectorAll('.c-house-info__gallery-item').forEach(function(el) {
    el.addEventListener('click', function() {
      var imgEl = el.querySelector('img');
      if (!imgEl || !mainImg) return;
      mainImg.src = imgEl.getAttribute('src');
    });
  });

  /* Навигация галереи */
  var track = document.querySelector('.hi-gal-track');
  var gPrev = document.querySelector('.hi-gal-prev');
  var gNext = document.querySelector('.hi-gal-next');
  if (track && gPrev && gNext) {
    var item = track.querySelector('.c-house-info__gallery-item');
    var sw = item ? item.offsetWidth + 12 : 200;
    gPrev.addEventListener('click', function() { track.scrollBy({ left: -sw, behavior: 'smooth' }); });
    gNext.addEventListener('click', function() { track.scrollBy({ left: sw, behavior: 'smooth' }); });
  }
})();
</script>
<?php endif; ?>