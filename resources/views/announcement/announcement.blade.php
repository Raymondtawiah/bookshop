<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Announcements</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  :root{
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #eef2ff;
    --ink: #0f172a;
    --ink-soft: #475569;
    --muted: #94a3b8;
    --border: #e2e8f0;
    --bg: #f1f5f9;
    --card: #ffffff;
    --like-red: #ef4444;
  }

  *{ box-sizing:border-box; margin:0; padding:0; }

  body{
    background: var(--bg);
    font-family:'Inter', sans-serif;
    color: var(--ink);
    padding-bottom: 60px;
    padding-top: 80px;
  }

  a{ color:inherit; text-decoration:none; }
  button{ font-family:inherit; cursor:pointer; border:none; background:none; }

  /* ---------------- Page header ---------------- */

  .page-header{
    background:#fff;
    border-bottom:1px solid var(--border);
    position:sticky;
    top:0;
    z-index:10;
    padding: 18px 20px;
    text-align:center;
  }

  .page-header h1{
    font-size:19px;
    font-weight:800;
  }

  .page-header p{
    font-size:13px;
    color: var(--muted);
    margin-top:3px;
  }

  /* ---------------- Feed layout ---------------- */

  .feed{
    max-width:600px;
    margin: 24px auto 0;
    padding: 0 16px;
    display:flex;
    flex-direction:column;
    gap:20px;
  }

  .post{
    background: var(--card);
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
  }

  /* ---------------- Post header ---------------- */

  .post-head{
    display:flex;
    align-items:center;
    gap:12px;
    padding: 16px 18px 14px;
  }

  .avatar{
    width:42px; height:42px;
    border-radius:50%;
    background: linear-gradient(135deg, var(--primary), #818cf8);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:14px;
    flex-shrink:0;
  }

  .post-head-info{ flex:1; min-width:0; }

  .post-head-name{
    font-size:14.5px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:6px;
  }

  .verified{
    width:14px; height:14px;
    color: var(--primary);
    flex-shrink:0;
  }

  .post-head-meta{
    font-size:12.5px;
    color: var(--muted);
    margin-top:1px;
  }

  .new-badge{
    font-size:10.5px;
    font-weight:800;
    letter-spacing:0.04em;
    text-transform:uppercase;
    background: var(--primary-light);
    color: var(--primary-dark);
    padding:4px 10px;
    border-radius:999px;
    flex-shrink:0;
  }

  /* ---------------- Post text ---------------- */

  .post-text{
    padding: 0 18px 14px;
    font-size:14.5px;
    line-height:1.55;
    color: var(--ink-soft);
  }

  .post-text strong{ color: var(--ink); }

  /* ---------------- Video block ---------------- */

  .video-wrap{
    position:relative;
    aspect-ratio: 9/16;
    max-width: 320px;
    margin: 0 auto;
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    overflow:hidden;
    border-radius: 12px;
  }

  .video-wrap video{
    position:absolute;
    inset:0;
    width:100%; height:100%;
    object-fit:contain;
    background:#000;
    z-index: 1;
  }

  .video-wrap .play-overlay,
  .video-wrap .video-badge { z-index: 3; }

  .video-wrap.playing .play-overlay,
  .video-wrap.playing .video-badge { display:none; }

  .play-overlay{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
    color:#fff;
    z-index:1;
  }

  .play-btn{
    width:58px; height:58px;
    border-radius:50%;
    background: rgba(255,255,255,0.15);
    border:1.5px solid rgba(255,255,255,0.5);
    display:flex;
    align-items:center;
    justify-content:center;
    transition: background 0.15s ease, transform 0.15s ease;
  }

  .video-wrap:hover .play-btn{ background: rgba(255,255,255,0.25); transform: scale(1.05); }

  .play-btn svg{ width:22px; height:22px; margin-left:3px; }

  .video-duration{
    font-size:12px;
    font-weight:600;
    color: rgba(255,255,255,0.85);
  }

  .video-badge{
    position:absolute;
    top:12px; left:12px;
    font-size:10.5px;
    font-weight:700;
    letter-spacing:0.04em;
    text-transform:uppercase;
    background: rgba(0,0,0,0.5);
    color:#fff;
    padding:4px 10px;
    border-radius:6px;
  }

  @keyframes doubleTapHeart {
    0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
    15% { transform: translate(-50%, -50%) scale(1.2); opacity: 1; }
    30% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
  }

  .double-tap-heart{
    position:absolute;
    top:50%;
    left:50%;
    width: 90px;
    height: 90px;
    pointer-events: none;
    z-index: 10;
    animation: doubleTapHeart 0.8s ease-out forwards;
  }

  .double-tap-heart svg{
    width: 100%;
    height: 100%;
    fill: #ef4444;
    stroke: #ef4444;
    stroke-width: 2;
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.25));
  }

  /* ---------------- Stats row ---------------- */

  .stats-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 12px 18px 4px;
    font-size:12.5px;
    color: var(--muted);
  }

  .stats-row .likes-stat{
    display:flex;
    align-items:center;
    gap:5px;
  }

  .mini-heart{
    width:16px; height:16px;
    background: var(--like-red);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    flex-shrink:0;
  }
  .mini-heart svg{ width:9px; height:9px; }

  /* ---------------- Action bar ---------------- */

  .action-bar{
    display:flex;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    margin: 10px 18px 0;
  }

  .action-btn{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    padding:11px 0;
    font-size:13.5px;
    font-weight:600;
    color: var(--ink-soft);
    border-radius:8px;
    transition: background 0.15s ease, color 0.15s ease;
  }

  .action-btn:hover{ background: var(--bg); }

  .action-btn svg{ width:18px; height:18px; }

  .action-btn.liked{ color: var(--like-red); }
  .action-btn.liked svg{ fill: var(--like-red); stroke: var(--like-red); }

  .like-pop{
    display:inline-block;
    transition: transform 0.25s cubic-bezier(.17,.89,.32,1.49);
  }
  .action-btn.pop .like-pop{ transform: scale(1.3); }

  /* ---------------- Inline Comments Section ---------------- */

  .comments-section{
    border-top: 1px solid var(--border);
    background: #fff;
    display: none;
  }

  .comments-section.open{
    display: block;
  }

  .comments-header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    border-bottom: 1px solid var(--border);
  }

  .comments-header h4{
    font-size: 14px;
    font-weight: 700;
    color: var(--ink);
  }

  .comments-header .count{
    color: var(--muted);
    font-weight: 500;
    font-size: 12.5px;
  }

  .close-comments-btn{
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-soft);
    font-size: 16px;
    transition: background 0.15s ease;
    line-height: 1;
  }

  .close-comments-btn:hover{
    background: var(--border);
  }

  .comments-body{
    padding: 12px 18px;
    max-height: 340px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  .comments-body::-webkit-scrollbar{
    width: 4px;
  }

  .comments-body::-webkit-scrollbar-thumb{
    background: var(--border);
    border-radius: 4px;
  }

  /* ---------------- Comment Item ---------------- */

  .comment-item{
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
  }

  .comment-item .avatar{
    width: 30px;
    height: 30px;
    font-size: 11px;
    flex-shrink: 0;
  }

  .comment-body{
    flex: 1;
    min-width: 0;
  }

  .comment-name{
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink);
  }

  .comment-text{
    font-size: 13px;
    color: var(--ink-soft);
    margin-top: 2px;
    line-height: 1.4;
    word-wrap: break-word;
  }

  .comment-time{
    font-size: 11px;
    color: var(--muted);
    margin-top: 3px;
  }

  .comment-actions{
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 5px;
  }

  .comment-action-btn{
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    background: none;
    border: none;
    padding: 0;
    transition: color 0.15s ease;
  }

  .comment-action-btn:hover{
    color: var(--primary);
  }

  .comment-action-btn.liked{
    color: var(--like-red);
  }

  .reply-toggle{
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: opacity 0.15s ease;
  }

  .reply-toggle:hover{
    opacity: 0.8;
    text-decoration: underline;
  }

  /* ---------------- Replies Section ---------------- */

  .replies-section{
    margin-top: 8px;
    padding-left: 40px;
    display: none;
  }

  .replies-section.open{
    display: block;
  }

  .reply-item .replies-section{
    padding-left: 28px;
  }

  .reply-item{
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
  }

  .reply-item .avatar{
    width: 26px;
    height: 26px;
    font-size: 10px;
    flex-shrink: 0;
  }

  .reply-body{
    flex: 1;
    min-width: 0;
  }

  .reply-name{
    font-size: 12px;
    font-weight: 700;
    color: var(--ink);
  }

  .reply-text{
    font-size: 12.5px;
    color: var(--ink-soft);
    margin-top: 1px;
    line-height: 1.35;
    word-wrap: break-word;
  }

  .reply-time{
    font-size: 11px;
    color: var(--muted);
    margin-top: 2px;
  }

  .reply-actions{
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 3px;
  }

  .reply-action-btn{
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    background: none;
    border: none;
    padding: 0;
  }

  .reply-action-btn:hover{
    color: var(--primary);
  }

  /* ---------------- Inline Composer ---------------- */

  .comments-footer{
    border-top: 1px solid var(--border);
    padding: 10px 18px;
    background: #fff;
  }

  .reply-mode-bar{
    display: none;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    padding: 0 2px;
  }

  .reply-mode-bar.active{
    display: flex;
  }

  .reply-mode-label{
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
  }

  .cancel-reply-btn{
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    background: none;
    border: none;
    padding: 3px 7px;
    border-radius: 6px;
    transition: color 0.15s ease, background 0.15s ease;
  }

  .cancel-reply-btn:hover{
    color: var(--ink-soft);
    background: var(--bg);
  }

  .composer-row{
    display: flex;
    align-items: flex-end;
    gap: 8px;
  }

  .composer-row .avatar{
    width: 30px;
    height: 30px;
    font-size: 11px;
    flex-shrink: 0;
  }

  .composer-input-wrap{
    flex: 1;
    display: flex;
    align-items: flex-end;
    background: var(--bg);
    border-radius: 18px;
    padding: 5px 5px 5px 14px;
    border: 1px solid transparent;
    transition: border-color 0.15s ease;
  }

  .composer-input-wrap:focus-within{
    border-color: var(--border);
  }

  .composer-textarea{
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-family: inherit;
    font-size: 13px;
    padding: 7px 0;
    color: var(--ink);
    resize: none;
    max-height: 100px;
    line-height: 1.35;
    overflow-y: auto;
  }

  .composer-textarea::placeholder{
    color: var(--muted);
  }

  .composer-textarea::-webkit-scrollbar{
    width: 3px;
  }

  .composer-textarea::-webkit-scrollbar-thumb{
    background: var(--border);
    border-radius: 3px;
  }

  .send-btn{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.15s ease, opacity 0.15s ease;
    opacity: 0.4;
    pointer-events: none;
  }

  .send-btn.active{
    opacity: 1;
    pointer-events: auto;
  }

  .send-btn:hover.active{
    background: var(--primary-dark);
  }

  .send-btn svg{
    width: 13px;
    height: 13px;
  }

  .send-btn.loading{
    opacity: 0.7;
    pointer-events: none;
  }

  .send-btn.loading svg{
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin{
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  /* ---------------- Share feedback ---------------- */

  .share-feedback{
    position:fixed;
    bottom:24px;
    left:50%;
    transform: translateX(-50%) translateY(20px);
    background: var(--ink);
    color:#fff;
    font-size:13px;
    font-weight:600;
    padding:10px 20px;
    border-radius:999px;
    opacity:0;
    transition: opacity 0.2s ease, transform 0.2s ease;
    pointer-events:none;
    z-index:50;
  }

  .share-feedback.visible{
    opacity:1;
    transform: translateX(-50%) translateY(0);
  }

  /* ---------------- Empty state ---------------- */

  .empty-state{
    text-align: center;
    padding: 32px 18px;
    color: var(--muted);
  }

  .empty-state svg{
    width: 44px;
    height: 44px;
    margin-bottom: 10px;
    opacity: 0.5;
  }

  .comment-skeleton{
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
  }

  .skeleton-avatar{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--bg);
    flex-shrink: 0;
  }

  .skeleton-body{
    flex: 1;
  }

  .skeleton-line{
    height: 10px;
    background: var(--bg);
    border-radius: 4px;
    margin-bottom: 6px;
  }

  .skeleton-line.short{
    width: 40%;
  }

  @media (max-width:480px){
    .action-btn > span:not(.like-pop) { display:none; }
  }
</style>
</head>
<body>

<x-customer-navbar />

<header class="page-header">
  <h1>Announcements</h1>
  <p>Latest updates, video walkthroughs, and news from the team</p>
</header>

<main class="feed" id="feed">

<script>
  const API_BASE = '/api';
  const CURRENT_USER = { user_name: 'You', user_initials: 'Y' };
  let activeAnnouncementId = null;
  let activeReplyCommentId = null;
  let submitting = false;

  async function fetchAnnouncements() {
    const response = await fetch(`${API_BASE}/announcements`);
    const json = await response.json();
    return json.data || [];
  }

  async function likeAnnouncement(id) {
    const response = await fetch(`/announcements/${id}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(CURRENT_USER),
    });
    const json = await response.json();
    return json;
  }

  async function postComment(announcementId, text) {
    const response = await fetch(`/announcements/${announcementId}/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ ...CURRENT_USER, text }),
    });
    const json = await response.json();
    return json;
  }

  async function fetchComments(announcementId) {
    const response = await fetch(`/announcements/${announcementId}/comments`);
    const json = await response.json();
    return json.data || [];
  }

  async function fetchReplies(announcementId, commentId) {
    const response = await fetch(`/announcements/${announcementId}/comments/${commentId}/replies`);
    const json = await response.json();
    return json.data || [];
  }

  async function likeComment(announcementId, commentId) {
    const response = await fetch(`/announcements/${announcementId}/comments/${commentId}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(CURRENT_USER),
    });
    const json = await response.json();
    return json;
  }

  async function replyComment(announcementId, commentId, text) {
    const response = await fetch(`/announcements/${announcementId}/comments/${commentId}/reply`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ ...CURRENT_USER, text }),
    });
    const json = await response.json();
    return json;
  }

  async function shareAnnouncement(id) {
    const response = await fetch(`/announcements/${id}/share`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    return response.json();
  }

  async function loadAnnouncements() {
    const announcements = await fetchAnnouncements();
    const feed = document.getElementById('feed');
    feed.innerHTML = '';

    for (const announcement of announcements) {
      const article = document.createElement('article');
      article.className = 'post';
      article.setAttribute('data-post-id', announcement.id);
      article.innerHTML = `
        <div class="post-head">
          <div class="avatar">${announcement.author_initials}</div>
          <div class="post-head-info">
            <div class="post-head-name">
              ${announcement.author_name}
              ${announcement.is_new ? '<span class="new-badge">New</span>' : ''}
            </div>
            <div class="post-head-meta">${announcement.published_at || ''}</div>
          </div>
        </div>

        <div class="post-text">
          ${announcement.message}
        </div>

        ${announcement.video_url ? `
        <div class="video-wrap" data-video-target="video${announcement.id}">
          <span class="video-badge">Video</span>
          <div class="play-overlay">
            <div class="play-btn">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </div>
            <span class="video-duration">${announcement.video_duration || ''}</span>
          </div>
          <video id="video${announcement.id}" controls playsinline muted preload="metadata">
            <source src="${encodeURI(announcement.video_url)}" type="video/mp4">
          </video>
        </div>
        ` : ''}

        <div class="stats-row">
          <span class="likes-stat">
            <span class="mini-heart"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21s-6.7-4.3-9.3-8.1C.8 10 1.6 6.4 4.6 5.1 6.9 4 9.5 4.8 11 6.6c.4.5.7.9 1 1.4.3-.5.6-.9 1-1.4 1.5-1.8 4.1-2.6 6.4-1.5 3 1.3 3.8 4.9 1.9 7.8C18.7 16.7 12 21 12 21z"/></svg></span>
            <span id="likeCount${announcement.id}">${announcement.likes_count || 0} likes</span>
          </span>
          <span id="commentCount${announcement.id}">${announcement.comments_count || 0} comments</span>
        </div>

        <div class="action-bar">
          <button class="action-btn" data-like-btn="${announcement.id}">
            <span class="like-pop">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-6.7-4.3-9.3-8.1C.8 10 1.6 6.4 4.6 5.1 6.9 4 9.5 4.8 11 6.6c.4.5.7.9 1 1.4.3-.5.6-.9 1-1.4 1.5-1.8 4.1-2.6 6.4-1.5 3 1.3 3.8 4.9 1.9 7.8C18.7 16.7 12 21 12 21z"/></svg>
            </span>
            <span>Like</span>
          </button>
          <button class="action-btn" data-comment-toggle="${announcement.id}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 01-8.9 8.4 8.6 8.6 0 01-3.6-.9L3 21l1.9-4.5a8.4 8.4 0 01-.9-3.9A8.4 8.4 0 0112.5 4a8.4 8.4 0 018.5 7.5z"/></svg>
            <span>Comment</span>
          </button>
          <button class="action-btn" data-share-btn="${announcement.id}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 10.6l6.8-3.8M8.6 13.4l6.8 3.8"/></svg>
            <span>Share</span>
          </button>
        </div>

        <div class="comments-section" data-announcement-id="${announcement.id}">
          <div class="comments-header">
            <h4>Comments <span class="comments-count">0 comments</span></h4>
            <button class="close-comments-btn" aria-label="Close comments">&times;</button>
          </div>
          <div class="comments-body"></div>
          <div class="comments-footer">
            <div class="reply-mode-bar">
              <span class="reply-mode-label"></span>
              <button class="cancel-reply-btn">Cancel</button>
            </div>
            <div class="composer-row">
              <div class="avatar">Y</div>
              <div class="composer-input-wrap">
                <textarea class="composer-textarea" placeholder="Add a comment..." rows="1"></textarea>
                <button class="send-btn" aria-label="Send">
                  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      `;

      feed.appendChild(article);
    }

    attachEventListeners();
    setupVideoInteractions();
  }

  function showHeartAnimation(container, x, y) {
    const heart = document.createElement('div');
    heart.className = 'double-tap-heart';
    heart.innerHTML = '<svg viewBox="0 0 24 24"><path d="M12 21s-6.7-4.3-9.3-8.1C.8 10 1.6 6.4 4.6 5.1 6.9 4 9.5 4.8 11 6.6c.4.5.7.9 1 1.4.3-.5.6-.9 1-1.4 1.5-1.8 4.1-2.6 6.4-1.5 3 1.3 3.8 4.9 1.9 7.8C18.7 16.7 12 21 12 21z"/></svg>';
    heart.style.left = x + 'px';
    heart.style.top = y + 'px';
    container.appendChild(heart);
    setTimeout(function () { heart.remove(); }, 800);
  }

  async function handleVideoLike(announcementId, buttonElement) {
    if (buttonElement && buttonElement.classList.contains('liked')) {
      return;
    }

    try {
      const result = await likeAnnouncement(announcementId);
      const countEl = document.getElementById('likeCount' + announcementId);
      const btn = buttonElement || document.querySelector('[data-like-btn="' + announcementId + '"]');

      if (countEl && result.likes_count !== undefined) {
        countEl.textContent = result.likes_count + ' likes';
      }
      if (btn) {
        btn.classList.add('liked');
        btn.classList.add('pop');
        setTimeout(function () { btn.classList.remove('pop'); }, 220);
      }
    } catch (e) {
      console.error('Like failed', e);
    }
  }

  function setupVideoInteractions() {
    document.querySelectorAll('.video-wrap').forEach(function (wrap) {
      var video = wrap.querySelector('video');
      if (!video) return;

      const post = wrap.closest('.post');
      const announcementId = post ? post.getAttribute('data-post-id') : null;

      let lastTapTime = 0;
      let singleTapTimer = null;
      const TAP_DELAY = 350;
      let ignoreNextClick = false;

      wrap.addEventListener('click', function (e) {
        if (ignoreNextClick) {
          ignoreNextClick = false;
          e.preventDefault();
          e.stopPropagation();
          return;
        }

        const currentTime = new Date().getTime();

        if (lastTapTime && currentTime - lastTapTime < TAP_DELAY) {
          const tapX = e.clientX || (e.changedTouches && e.changedTouches[0].clientX);
          const tapY = e.clientY || (e.changedTouches && e.changedTouches[0].clientY);

          const rect = wrap.getBoundingClientRect();
          const x = tapX - rect.left;
          const y = tapY - rect.top;

          const btn = document.querySelector('[data-like-btn="' + announcementId + '"]');
          if (btn && !btn.classList.contains('liked')) {
            showHeartAnimation(wrap, x, y);
            handleVideoLike(announcementId, btn);
          }

          clearTimeout(singleTapTimer);
          singleTapTimer = null;
          lastTapTime = 0;
          ignoreNextClick = true;
          e.preventDefault();
          return;
        }

        lastTapTime = currentTime;

        singleTapTimer = setTimeout(function () {
          singleTapTimer = null;
          lastTapTime = 0;
          if (video.paused) {
            wrap.classList.add('playing');
            video.play().catch(function () {});
          } else {
            wrap.classList.remove('playing');
            video.pause();
          }
        }, TAP_DELAY);
      });

      video.addEventListener('playing', function () {
        wrap.classList.add('playing');
      });

      video.addEventListener('pause', function () {
        wrap.classList.remove('playing');
      });

      video.addEventListener('ended', function () {
        wrap.classList.remove('playing');
      });
    });

    const videoObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const video = entry.target.querySelector('video');
          const wrap = entry.target;
          if (video) {
            wrap.classList.add('playing');
            video.play().catch(() => {});
          }
        } else {
          const video = entry.target.querySelector('video');
          const wrap = entry.target;
          if (video) {
            video.pause();
            wrap.classList.remove('playing');
          }
        }
      });
    }, { threshold: 0.5 });

    document.querySelectorAll('.video-wrap').forEach((wrap) => videoObserver.observe(wrap));
  }

  /* ===========================
     INLINE COMMENTS
     =========================== */

  function getPostCommentsSection(post) {
    return post.querySelector('.comments-section');
  }

  function getPostBody(section) {
    return section ? section.querySelector('.comments-body') : null;
  }

  function getPostTextarea(section) {
    return section ? section.querySelector('.composer-textarea') : null;
  }

  function getPostSendBtn(section) {
    return section ? section.querySelector('.send-btn') : null;
  }

  function getPostReplyModeBar(section) {
    return section ? section.querySelector('.reply-mode-bar') : null;
  }

  function getPostReplyModeLabel(section) {
    return section ? section.querySelector('.reply-mode-label') : null;
  }

  function getPostCountEl(section) {
    return section ? section.querySelector('.comments-count') : null;
  }

  function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function updateComposerState(section) {
    const textarea = getPostTextarea(section);
    const sendBtn = getPostSendBtn(section);
    if (!textarea || !sendBtn) return;
    const hasText = textarea.value.trim().length > 0;
    sendBtn.classList.toggle('active', hasText && !submitting);
  }

  function exitReplyMode(section, silent) {
    activeReplyCommentId = null;
    const label = getPostReplyModeLabel(section);
    const bar = getPostReplyModeBar(section);
    const textarea = getPostTextarea(section);
    const sendBtn = getPostSendBtn(section);

    if (label) label.textContent = '';
    if (bar) bar.classList.remove('active');
    if (textarea && !silent) {
      textarea.value = '';
      textarea.placeholder = 'Add a comment...';
      textarea.style.height = 'auto';
    }
    if (sendBtn) sendBtn.classList.remove('active');
  }

  function enterReplyMode(section, commentId, userName) {
    if (submitting) return;
    activeReplyCommentId = commentId;
    const label = getPostReplyModeLabel(section);
    const bar = getPostReplyModeBar(section);
    const textarea = getPostTextarea(section);

    if (label) label.textContent = `Replying to ${userName}`;
    if (bar) bar.classList.add('active');
    if (textarea) {
      textarea.placeholder = 'Write a reply...';
      textarea.focus();
    }
    updateComposerState(section);
  }

  async function loadPostComments(post, announcementId) {
    const section = getPostCommentsSection(post);
    if (!section) return;
    const body = getPostBody(section);
    if (!body) return;

    body.innerHTML = '<div class="comment-skeleton"><div class="skeleton-avatar"></div><div class="skeleton-body"><div class="skeleton-line"></div><div class="skeleton-line short"></div></div></div>'.repeat(3);

    const comments = await fetchComments(announcementId);
    body.innerHTML = '';

    const countEl = getPostCountEl(section);
    if (countEl) {
      countEl.textContent = comments.length + ' comment' + (comments.length === 1 ? '' : 's');
    }

    if (comments.length === 0) {
      body.innerHTML = '<div class="empty-state"><svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.5\"><path d=\"M21 11.5a8.4 8.4 0 01-8.9 8.4 8.6 8.6 0 01-3.6-.9L3 21l1.9-4.5a8.4 8.4 0 01-.9-3.9A8.4 8.4 0 0112.5 4a8.4 8.4 0 018.5 7.5z\"/></svg><p>No comments yet. Be the first!</p></div>';
      return;
    }

    for (const comment of comments) {
      const wrapper = document.createElement('div');
      wrapper.setAttribute('data-comment-id', comment.id);
      wrapper.innerHTML = renderCommentHTML(comment);
      body.appendChild(wrapper);
      attachCommentActions(wrapper, section, announcementId);
    }
  }

  function renderCommentHTML(comment) {
    const replyCount = comment.replies_count || 0;
    const likeCount = comment.likes_count || 0;
    return `
      <div class="comment-item">
        <div class="avatar">${escapeHtml(comment.user_initials)}</div>
        <div class="comment-body">
          <div class="comment-name">${escapeHtml(comment.user_name)}</div>
          <div class="comment-text">${escapeHtml(comment.text)}</div>
          <div class="comment-time">Just now</div>
          <div class="comment-actions">
            <button class="comment-action-btn comment-like-btn" data-comment-id="${comment.id}">${likeCount > 0 ? likeCount + ' likes' : 'Like'}</button>
            <button class="comment-action-btn comment-reply-btn" data-comment-id="${comment.id}" data-user-name="${escapeHtml(comment.user_name)}">Reply</button>
            ${replyCount > 0 ? `<button class="reply-toggle" data-replies-toggle="${comment.id}">${replyCount} ${replyCount === 1 ? 'reply' : 'replies'}</button>` : ''}
          </div>
          <div class="replies-section" data-replies-section="${comment.id}">
            <div data-replies-list="${comment.id}"></div>
          </div>
        </div>
      </div>
    `;
  }

  function renderReplyHTML(reply) {
    const likeCount = reply.likes_count || 0;
    return `
      <div class="reply-item">
        <div class="avatar">${escapeHtml(reply.user_initials)}</div>
        <div class="reply-body">
          <div class="reply-name">${escapeHtml(reply.user_name)}</div>
          <div class="reply-text">${escapeHtml(reply.text)}</div>
          <div class="reply-time">Just now</div>
          <div class="reply-actions">
            <button class="reply-action-btn reply-like-btn" data-comment-id="${reply.id}">${likeCount > 0 ? likeCount + ' likes' : 'Like'}</button>
            <button class="reply-action-btn reply-reply-btn" data-comment-id="${reply.id}" data-user-name="${escapeHtml(reply.user_name)}">Reply</button>
          </div>
          <div class="replies-section" data-replies-section="${reply.id}">
            <div data-replies-list="${reply.id}"></div>
          </div>
        </div>
      </div>
    `;
  }

  async function toggleReplies(section, announcementId, commentId) {
    const sectionEl = section.querySelector(`[data-replies-section="${commentId}"]`);
    if (!sectionEl) return;
    const isOpen = sectionEl.classList.contains('open');
    if (isOpen) {
      sectionEl.classList.remove('open');
      return;
    }
    const list = sectionEl.querySelector(`[data-replies-list="${commentId}"]`);
    if (list.children.length === 0) {
      const replies = await fetchReplies(announcementId, commentId);
      for (const reply of replies) {
        const div = document.createElement('div');
        div.innerHTML = renderReplyHTML(reply);
        list.appendChild(div.firstElementChild);
      }
      attachReplyActions(sectionEl, section, announcementId);
    }
    sectionEl.classList.add('open');
  }

  async function submitTopComment(section, announcementId) {
    if (submitting) return;
    const textarea = getPostTextarea(section);
    const text = textarea.value.trim();
    if (!text) return;

    submitting = true;
    const sendBtn = getPostSendBtn(section);
    if (sendBtn) sendBtn.classList.add('loading');

    try {
      const result = await postComment(announcementId, text);
      if (result && result.success) {
        const textarea = getPostTextarea(section);
        const sendBtn = getPostSendBtn(section);

        if (textarea) {
          textarea.value = '';
          textarea.style.height = 'auto';
          textarea.placeholder = 'Add a comment...';
          textarea.disabled = false;
        }
        if (sendBtn) sendBtn.classList.remove('active');

        exitReplyMode(section, true);
        updateComposerState(section);

        const body = getPostBody(section);
        if (!body) return;

        const empty = body.querySelector('.empty-state');
        if (empty) empty.remove();

        const wrapper = document.createElement('div');
        wrapper.setAttribute('data-comment-id', result.data.id);
        wrapper.innerHTML = renderCommentHTML({
          id: result.data.id,
          user_name: CURRENT_USER.user_name,
          user_initials: CURRENT_USER.user_initials,
          text: text,
          replies_count: 0,
          likes_count: 0,
        });
        body.insertBefore(wrapper, body.firstChild);
        attachCommentActions(wrapper, section, announcementId);

        const countEl = getPostCountEl(section);
        if (countEl) {
          const current = parseInt(countEl.textContent, 10) || 0;
          countEl.textContent = (current + 1) + ' comment' + (current + 1 === 1 ? '' : 's');
        }

        const postCountEl = document.getElementById('commentCount' + announcementId);
        if (postCountEl) {
          const current = parseInt(postCountEl.textContent, 10) || 0;
          postCountEl.textContent = (current + 1) + ' comments';
        }

        setTimeout(function () {
          if (textarea) textarea.focus();
        }, 120);
      }
    } catch (e) {
      console.error('Comment failed', e);
    } finally {
      submitting = false;
      if (sendBtn) sendBtn.classList.remove('loading');
      updateComposerState(section);
    }
  }

  async function submitReply(section, announcementId) {
    if (submitting || !activeReplyCommentId) return;
    const textarea = getPostTextarea(section);
    const text = textarea.value.trim();
    if (!text) return;

    submitting = true;
    const sendBtn = getPostSendBtn(section);
    if (sendBtn) sendBtn.classList.add('loading');

    try {
      const result = await replyComment(announcementId, activeReplyCommentId, text);
      if (result && result.success) {
        const textarea = getPostTextarea(section);
        textarea.value = '';
        textarea.style.height = 'auto';

        const repliesSection = section.querySelector(`[data-replies-section="${activeReplyCommentId}"]`);
        if (repliesSection) {
          const list = repliesSection.querySelector(`[data-replies-list="${activeReplyCommentId}"]`);
          if (list) {
            const div = document.createElement('div');
            div.innerHTML = renderReplyHTML({
              id: result.data.id,
              user_name: CURRENT_USER.user_name,
              user_initials: CURRENT_USER.user_initials,
              text: text,
              likes_count: 0,
            });
            list.appendChild(div.firstElementChild);
            attachReplyActions(repliesSection, section, announcementId);
          }
          repliesSection.classList.add('open');
        }

        let toggle = section.querySelector(`[data-replies-toggle="${activeReplyCommentId}"]`);
        if (!toggle && repliesSection) {
          const commentBody = repliesSection.closest('.comment-body');
          if (commentBody) {
            const actions = commentBody.querySelector('.comment-actions');
            if (actions) {
              toggle = document.createElement('button');
              toggle.className = 'reply-toggle';
              toggle.setAttribute('data-replies-toggle', activeReplyCommentId);
              toggle.textContent = '1 reply';
              toggle.addEventListener('click', function () {
                const cid = this.getAttribute('data-replies-toggle');
                toggleReplies(section, announcementId, cid);
              });
              toggle.dataset.bound = 'true';
              actions.appendChild(toggle);
            }
          }
        }
        if (toggle && repliesSection) {
          const replies = repliesSection.querySelectorAll('.reply-item');
          const count = replies.length;
          toggle.textContent = count + (count === 1 ? ' reply' : ' replies');
        }

        exitReplyMode(section, true);
        updateComposerState(section);
      }
    } catch (e) {
      console.error('Reply failed', e);
    } finally {
      submitting = false;
      if (sendBtn) sendBtn.classList.remove('loading');
      updateComposerState(section);
    }
  }

  function attachCommentActions(scope, section, announcementId) {
    scope.querySelectorAll('.comment-like-btn').forEach(function (btn) {
      if (btn.dataset.bound === 'true') return;
      btn.dataset.bound = 'true';
      btn.addEventListener('click', async function () {
        if (submitting) return;
        const commentId = this.getAttribute('data-comment-id');
        try {
          const result = await likeComment(announcementId, commentId);
          if (result.likes_count !== undefined) {
            this.textContent = result.likes_count + ' likes';
            this.classList.add('liked');
          }
        } catch (e) {
          console.error('Comment like failed', e);
        }
      });
    });

    scope.querySelectorAll('.comment-reply-btn').forEach(function (btn) {
      if (btn.dataset.bound === 'true') return;
      btn.dataset.bound = 'true';
      btn.addEventListener('click', function () {
        if (submitting) return;
        const commentId = this.getAttribute('data-comment-id');
        const userName = this.getAttribute('data-user-name') || 'this comment';
        enterReplyMode(section, commentId, userName);
      });
    });

    scope.querySelectorAll('[data-replies-toggle]').forEach(function (btn) {
      if (btn.dataset.bound === 'true') return;
      btn.dataset.bound = 'true';
      btn.addEventListener('click', function () {
        const commentId = this.getAttribute('data-replies-toggle');
        toggleReplies(section, announcementId, commentId);
      });
    });
  }

  function attachReplyActions(repliesSection, section, announcementId) {
    repliesSection.querySelectorAll('.reply-like-btn').forEach(function (btn) {
      if (btn.dataset.bound === 'true') return;
      btn.dataset.bound = 'true';
      btn.addEventListener('click', async function () {
        if (submitting) return;
        const commentId = this.getAttribute('data-comment-id');
        try {
          const result = await likeComment(announcementId, commentId);
          if (result.likes_count !== undefined) {
            this.textContent = result.likes_count + ' likes';
            this.classList.add('liked');
          }
        } catch (e) {
          console.error('Reply like failed', e);
        }
      });
    });

    repliesSection.querySelectorAll('.reply-reply-btn').forEach(function (btn) {
      if (btn.dataset.bound === 'true') return;
      btn.dataset.bound = 'true';
      btn.addEventListener('click', function () {
        if (submitting) return;
        const commentId = this.getAttribute('data-comment-id');
        const userName = this.getAttribute('data-user-name') || 'this comment';
        enterReplyMode(section, commentId, userName);
      });
    });
  }

  function attachEventListeners() {
    document.querySelectorAll('[data-like-btn]').forEach(function (btn) {
      var id = btn.getAttribute('data-like-btn');
      btn.addEventListener('click', async function () {
        await handleVideoLike(id, btn);
      });
    });

    document.querySelectorAll('[data-share-btn]').forEach(function (btn) {
      btn.addEventListener('click', async function () {
        var id = btn.getAttribute('data-share-btn');
        await shareAnnouncement(id);
        var feedback = document.getElementById('shareFeedback');
        feedback.classList.add('visible');
        setTimeout(function () { feedback.classList.remove('visible'); }, 1800);
      });
    });

    document.querySelectorAll('[data-comment-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const post = btn.closest('.post');
        if (post) openCommentsSection(post);
      });
    });
  }

  /* Composer events */
  function setupComposer() {
    document.addEventListener('input', function (e) {
      if (!e.target.classList.contains('composer-textarea')) return;
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        const section = this.closest('.comments-section');
        updateComposerState(section);
      });

      document.addEventListener('keydown', function (e) {
        if (e.target.classList.contains('composer-textarea') && e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          const section = e.target.closest('.comments-section');
          if (!section) return;
          if (activeReplyCommentId) {
            submitReply(section, section.getAttribute('data-announcement-id'));
          } else {
            submitTopComment(section, section.getAttribute('data-announcement-id'));
          }
        }
      });

      document.addEventListener('click', function (e) {
        if (e.target.closest('.send-btn')) {
          const section = e.target.closest('.comments-section');
          if (!section) return;
          if (activeReplyCommentId) {
            submitReply(section, section.getAttribute('data-announcement-id'));
          } else {
            submitTopComment(section, section.getAttribute('data-announcement-id'));
          }
        }

        if (e.target.classList.contains('cancel-reply-btn')) {
          const section = e.target.closest('.comments-section');
          if (!section) return;
          exitReplyMode(section, false);
          updateComposerState(section);
        }

        if (e.target.classList.contains('close-comments-btn')) {
          const section = e.target.closest('.comments-section');
          if (!section) return;
          section.classList.remove('open');
        }
      });
    }

  function openCommentsSection(post) {
    const section = getPostCommentsSection(post);
    if (!section) return;
    const announcementId = section.getAttribute('data-announcement-id');
    if (!announcementId) return;
    section.classList.add('open');
    activeAnnouncementId = announcementId;
    activeReplyCommentId = null;
    exitReplyMode(section, true);
    const textarea = getPostTextarea(section);
    if (textarea) {
      textarea.value = '';
      textarea.placeholder = 'Add a comment...';
      textarea.style.height = 'auto';
    }
    const sendBtn = getPostSendBtn(section);
    if (sendBtn) sendBtn.classList.remove('active');
    loadPostComments(post, announcementId);
  }

  setupComposer();
  loadAnnouncements();
</script>

</main>

<div class="share-feedback" id="shareFeedback">Link copied to clipboard</div>

</body>
</html>
