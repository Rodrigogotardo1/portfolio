// ===== NAV SCROLL =====
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.style.boxShadow = window.scrollY > 40
    ? '0 4px 32px rgba(0,0,0,.5)'
    : 'none';
});

// ===== MOBILE MENU =====
const navToggle = document.getElementById('nav-toggle');
const navLinks  = document.querySelector('.nav-links');
navToggle?.addEventListener('click', () => navLinks.classList.toggle('open'));
document.querySelectorAll('.nav-links a').forEach(a =>
  a.addEventListener('click', () => navLinks.classList.remove('open'))
);

// ===== SCROLL REVEAL =====
const reveals = document.querySelectorAll(
  '.glass, .section-header, .proj-link, .hero-content, .hero-btns'
);
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      revealObserver.unobserve(e.target);
    }
  });
}, { threshold: 0.1 });

reveals.forEach(el => {
  el.classList.add('reveal');
  revealObserver.observe(el);
});

// ===== ACTIVE NAV LINK =====
const sections = document.querySelectorAll('section[id]');
const navAnchors = document.querySelectorAll('.nav-links a');
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 100) current = s.getAttribute('id');
  });
  navAnchors.forEach(a => {
    a.style.color = a.getAttribute('href') === `#${current}`
      ? 'var(--text)' : '';
  });
});

// ===== CONTACT FORM (Web3Forms) =====
const WEB3FORMS_KEY = '8ed4901b-a757-e34f-f302-bc719e4ac9d3';

document.getElementById('contato-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn      = document.getElementById('submit-btn');
  const feedback = document.getElementById('form-feedback');

  const nome     = document.getElementById('nome').value.trim();
  const email    = document.getElementById('email').value.trim();
  const mensagem = document.getElementById('mensagem').value.trim();

  btn.textContent = 'Sending...';
  btn.disabled = true;
  feedback.textContent = 'Sending message, please wait...';
  feedback.style.color = '#fff';

  try {
    const response = await fetch('https://api.web3forms.com/submit', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        access_key: WEB3FORMS_KEY,
        name: nome,
        email: email,
        message: mensagem,
        subject: `New Portfolio Message from ${nome}`
      })
    });

    const result = await response.json();

    if (result.success) {
      feedback.style.color = 'var(--accent2)';
      feedback.textContent = '✅ Message sent! I\'ll get back to you soon.';
      this.reset();
    } else {
      // Show the actual error message from Web3Forms
      throw new Error(result.message || 'Submission failed');
    }
  } catch (err) {
    console.error('Form Error:', err);
    feedback.style.color = '#f87171';
    feedback.textContent = `❌ Error: ${err.message}. Please email directly: dev.rodrigo.dev@gmail.com`;
  } finally {
    btn.textContent = 'Send Message';
    btn.disabled = false;
    // Don't clear success messages immediately
  }
});

// ===== SMOOTH CURSOR GLOW (optional easter egg) =====
const glow = document.createElement('div');
glow.style.cssText = `
  position:fixed;pointer-events:none;z-index:9999;
  width:300px;height:300px;border-radius:50%;
  background:radial-gradient(circle,rgba(108,99,255,.08),transparent 70%);
  transform:translate(-50%,-50%);transition:transform .1s;
`;
document.body.appendChild(glow);
document.addEventListener('mousemove', e => {
  glow.style.left = e.clientX + 'px';
  glow.style.top  = e.clientY + 'px';
});
