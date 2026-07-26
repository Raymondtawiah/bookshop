<!-- Feedback Widget -->
<button class="feedback-fab" id="feedbackFab" aria-label="Rate your experience">
    <svg class="icon-star" viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
        <path d="M12 2.5l2.95 6.34 6.99.7-5.25 4.75 1.53 6.91L12 17.77l-6.22 3.43 1.53-6.91L2.06 9.54l6.99-.7L12 2.5z"/>
    </svg>
    <svg class="icon-close" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" style="display:none;">
        <path d="M6 6l12 12M18 6L6 18"/>
    </svg>
</button>
<div class="overlay" id="overlay"></div>

<div class="rate-card" id="rateCard">
    <button class="close-btn" id="closeBtn" aria-label="Close">✕</button>

    <div class="rate-form" id="rateForm">
        <h2>How was your experience?</h2>
        <p>Your feedback helps us improve.</p>

        <div class="emoji-row" id="emojiRow">
            <button class="emoji-option" data-value="1" aria-label="Very unhappy">😡</button>
            <button class="emoji-option" data-value="2" aria-label="Unhappy">🙁</button>
            <button class="emoji-option" data-value="3" aria-label="Neutral">😐</button>
            <button class="emoji-option" data-value="4" aria-label="Happy">🙂</button>
            <button class="emoji-option" data-value="5" aria-label="Very happy">😍</button>
        </div>

        <div class="stars" id="starRow">
            <span class="star" data-value="1">★</span>
            <span class="star" data-value="2">★</span>
            <span class="star" data-value="3">★</span>
            <span class="star" data-value="4">★</span>
            <span class="star" data-value="5">★</span>
        </div>

        <input type="email" id="feedbackEmail" placeholder="Your email (optional)" class="w-full min-h-[44px] border mb-3 rounded-xl px-4 py-2 text-sm border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none" style="min-height:44px;">

        <textarea id="feedbackText" placeholder="Tell us a bit more (optional)"></textarea>

        <button class="submit-btn" id="submitBtn" disabled>
            <span id="submitBtnContent">Submit Feedback</span>
        </button>
    </div>

    <div class="thank-you" id="thankYou">
        <div class="thank-you-icon">✅</div>
        <h3>Thanks for your feedback!</h3>
        <p>We appreciate you taking the time to rate us.</p>
    </div>
</div>

<style>
  .feedback-fab {
    position: fixed;
    bottom: 26px;
    right: 26px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #6366f1, #22d3ee);
    color: #fff;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 10px 25px -6px rgba(99,102,241,0.55);
    transition: transform 0.25s cubic-bezier(.4,1.6,.5,1), box-shadow 0.25s ease;
    z-index: 20;
  }
  .feedback-fab:hover { transform: scale(1.08); }
  .feedback-fab.active { transform: scale(1.08); }

  .overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 17, 23, 0.35);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    z-index: 10;
    backdrop-filter: blur(2px);
  }
  .overlay.show { opacity: 1; visibility: visible; }

  .rate-card {
    width: 100%;
    max-width: 360px;
    background: #ffffff;
    border-radius: 16px;
    padding: 24px 20px;
    box-shadow: 0 20px 50px rgba(20, 24, 38, 0.20);
    text-align: center;
    position: fixed;
    bottom: 96px;
    right: 26px;
    z-index: 20;
    opacity: 0;
    visibility: hidden;
    transform: translateY(16px) scale(0.95);
    transform-origin: bottom right;
    transition: opacity 0.3s cubic-bezier(.4,1.6,.5,1),
                transform 0.3s cubic-bezier(.4,1.6,.5,1),
                visibility 0.3s ease;
  }
  .rate-card.show { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

  .close-btn {
    position: absolute;
    top: 14px;
    right: 16px;
    background: none;
    border: none;
    font-size: 18px;
    color: #aeb3c2;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s ease;
  }
  .close-btn:hover { color: #6366f1; }

  .rate-card h2 { margin: 0 0 6px; font-size: 20px; font-weight: 700; color: #1a1c26; }
  .rate-card p { margin: 0 0 22px; font-size: 13.5px; color: #8b93a7; }

  .emoji-row { display: flex; justify-content: space-between; margin-bottom: 22px; }
  .emoji-option {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 30px;
    padding: 8px;
    border-radius: 50%;
    filter: grayscale(1) opacity(0.45);
    transform: scale(0.92);
    transition: transform 0.25s cubic-bezier(.4,1.6,.5,1), filter 0.25s ease, background 0.25s ease;
  }
  .emoji-option:hover { transform: scale(1.12); filter: grayscale(0.4) opacity(0.8); }
  .emoji-option.selected { filter: grayscale(0) opacity(1); transform: scale(1.25); background: #eef0fb; }

  .stars { display: flex; justify-content: center; gap: 6px; margin-bottom: 22px; direction: ltr; }
  .star { font-size: 30px; cursor: pointer; color: #d9dce5; transition: transform 0.2s cubic-bezier(.4,1.6,.5,1), color 0.2s ease; }
  .star:hover, .star.hovered { transform: scale(1.15); }
  .star.active { color: #fbbf24; }

  textarea {
    width: 100%;
    min-height: 70px;
    border: 1px solid #e2e5ed;
    border-radius: 12px;
    padding: 12px 14px;
    font-family: inherit;
    font-size: 13.5px;
    color: #333744;
    resize: none;
    margin-bottom: 18px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  textarea:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }

  .submit-btn { width: 100%; padding: 13px; border: none; border-radius: 999px; background: linear-gradient(135deg, #6366f1, #22d3ee); color: #fff; font-size: 14.5px; font-weight: 600; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease; box-shadow: 0 8px 20px -6px rgba(99,102,241,0.6); display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
  .submit-btn:hover { transform: translateY(-2px); }
  .submit-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
  .submit-btn.loading { opacity: 0.85; }
  #submitBtnContent { display: inline-flex; align-items: center; gap: 8px; }
  .submit-btn-spinner { width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite; vertical-align: middle; display: none; }
  .submit-btn.loading .submit-btn-spinner { display: inline-block; }
  @keyframes spin { to { transform: rotate(360deg); } }

  .thank-you { display: none; flex-direction: column; align-items: center; gap: 10px; animation: pop-in 0.4s cubic-bezier(.4,1.6,.5,1); }
  .thank-you.show { display: flex; }
  .rate-form.hide { display: none; }
  .thank-you-icon { width: 56px; height: 56px; border-radius: 50%; background: #eafff3; display: flex; align-items: center; justify-content: center; font-size: 26px; }
  .thank-you h3 { margin: 0; font-size: 17px; color: #1a1c26; }
  .thank-you p { margin: 0; font-size: 13px; color: #8b93a7; }
  @keyframes pop-in {
    0% { opacity: 0; transform: scale(0.85); }
    100% { opacity: 1; transform: scale(1); }
  }

  @media (max-width: 480px) {
    .rate-card { left: 16px; right: 16px; bottom: 90px; max-width: none; }
  }
</style>

<script>
(function() {
    const fab = document.getElementById('feedbackFab');
    const overlay = document.getElementById('overlay');
    const rateCard = document.getElementById('rateCard');
    const closeBtn = document.getElementById('closeBtn');
    const iconStar = fab.querySelector('.icon-star');
    const iconClose = fab.querySelector('.icon-close');

    function openCard() {
        rateCard.classList.add('show');
        overlay.classList.add('show');
        fab.classList.add('active');
        iconStar.style.display = 'none';
        iconClose.style.display = 'block';
    }

    function closeCard() {
        rateCard.classList.remove('show');
        overlay.classList.remove('show');
        fab.classList.remove('active');
        iconStar.style.display = 'block';
        iconClose.style.display = 'none';
    }

    if (fab) {
        fab.addEventListener('click', () => {
            rateCard.classList.contains('show') ? closeCard() : openCard();
        });
    }

    if (overlay) overlay.addEventListener('click', closeCard);
    if (closeBtn) closeBtn.addEventListener('click', closeCard);

    const emojiButtons = document.querySelectorAll('.emoji-option');
    const stars = document.querySelectorAll('.star');
    const submitBtn = document.getElementById('submitBtn');
    const rateForm = document.getElementById('rateForm');
    const thankYou = document.getElementById('thankYou');
    const feedbackText = document.getElementById('feedbackText');
    const feedbackEmail = document.getElementById('feedbackEmail');

    let selectedRating = 0;

    function setRating(value) {
        selectedRating = value;

        emojiButtons.forEach(btn => {
            btn.classList.toggle('selected', Number(btn.dataset.value) === value);
        });

        stars.forEach(star => {
            star.classList.toggle('active', Number(star.dataset.value) <= value);
        });

        submitBtn.disabled = false;
    }

    emojiButtons.forEach(btn => {
        btn.addEventListener('click', () => setRating(Number(btn.dataset.value)));
    });

    stars.forEach(star => {
        star.addEventListener('click', () => setRating(Number(star.dataset.value)));
        star.addEventListener('mouseenter', () => {
            const hoverValue = Number(star.dataset.value);
            stars.forEach(s => {
                s.classList.toggle('hovered', Number(s.dataset.value) <= hoverValue);
            });
        });
        star.addEventListener('mouseleave', () => {
            stars.forEach(s => s.classList.remove('hovered'));
        });
    });

    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            if (!selectedRating) return;

            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            try {
                const response = await fetch('/feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        rating: selectedRating,
                        comment: feedbackText ? feedbackText.value : '',
                        email: feedbackEmail ? feedbackEmail.value : '',
                    })
                });

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ? await response.json() : {};

                if (response.ok && data.success) {
                    if (rateForm) rateForm.classList.add('hide');
                    if (thankYou) thankYou.classList.add('show');

                    setTimeout(() => {
                        closeCard();
                        setTimeout(() => {
                            if (rateForm) rateForm.classList.remove('hide');
                            if (thankYou) thankYou.classList.remove('show');
                            if (feedbackText) feedbackText.value = '';
                            if (feedbackEmail) feedbackEmail.value = '';
                            setRating(0);
                        }, 300);
                    }, 1600);
                } else {
                    const message = data.message || 'Something went wrong. Please try again.';
                    alert(message);
                }
            } catch (e) {
                alert('Something went wrong. Please try again.');
            } finally {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    }
})();
</script>
