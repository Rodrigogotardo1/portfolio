// ===== NAVIGATION & MOBILE MENU =====
const navbar = document.getElementById('navbar');
const menuBtn = document.getElementById('menu-btn');
const navLinks = document.getElementById('nav-links');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.style.padding = '0.5rem 0';
    navbar.style.background = 'rgba(8, 12, 20, 0.95)';
  } else {
    navbar.style.padding = '1rem 0';
    navbar.style.background = 'rgba(8, 12, 20, 0.85)';
  }
});

menuBtn?.addEventListener('click', () => {
  navLinks.classList.toggle('open');
  menuBtn.textContent = navLinks.classList.contains('open') ? '✕' : '☰';
});

// ===== SCROLL REVEAL =====
const observerOptions = { threshold: 0.1 };
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, observerOptions);

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ===== MOUSE FOLLOW EFFECT & GLOW =====
const follower = document.querySelector('.cursor-follower');

document.addEventListener('mousemove', (e) => {
  const x = e.clientX;
  const y = e.clientY;
  
  // Follower movement (Sutil)
  if (follower) follower.style.transform = `translate3d(${x - 20}px, ${y - 20}px, 0)`;

  // Update Global Glow Effect (Tailwind Style)
  document.documentElement.style.setProperty('--mouse-x', `${x}px`);
  document.documentElement.style.setProperty('--mouse-y', `${y}px`);

  // Blob parallax effect (Hero background)
  const bx = (x / window.innerWidth) * 20;
  const by = (y / window.innerHeight) * 20;
  const blob1 = document.querySelector('.blob1');
  const blob2 = document.querySelector('.blob2');
  if (blob1) blob1.style.transform = `translate(${bx}px, ${by}px)`;
  if (blob2) blob2.style.transform = `translate(${-bx}px, ${-by}px)`;
});

// ===== LANGUAGE TOGGLE SYSTEM =====
const translations = {
  'pt-BR': {
    'nav-home': 'Início',
    'nav-about': 'Sobre',
    'nav-skills': 'Habilidades',
    'nav-projects': 'Projetos',
    'nav-contact': 'Contato',
    'hero-welcome': 'Bem-vindo ao meu mundo digital',
    'hero-role': 'Coordenador de TI & Especialista em Infraestrutura',
    'hero-desc': 'Gestão de redes, segurança cibernética e otimização de processos corporativos.',
    'btn-contact': 'Contate-me',
    'btn-projects': 'Ver Projetos',
    'scroll': 'Role para baixo',
    'tag-about': 'Sobre Mim',
    'about-title': 'Conheça minha trajetória',
    'about-subtitle': 'Quem é Rodrigo?',
    'about-p1': 'Baseado em Londrina, PR, sou <strong>Coordenador de TI na Consultomaq</strong>, focado em infraestrutura resiliente, segurança cibernética e otimização de processos.',
    'about-p2': 'Atuo na gestão de redes Cisco, servidores Dell, Active Directory e Azure, além de implantar metodologias de governança e gestão de pessoas para eficiência operacional.',
    'stat-years': 'Anos Exp.',
    'stat-projects': 'Foco Redes',
    'about-edu': 'Educação & Especialidades',
    'about-p3': 'MBA em Cybersecurity em andamento e formação sólida em desenvolvimento de sistemas.',
    'edu-1': 'MBA em Cybersecurity e Cybercrimes (Anhanguera)',
    'edu-2': 'Análise e Desenvolvimento de Sistemas (Unopar)',
    'edu-3': 'Azure, Active Directory, Cisco e Zabbix',
    'tag-skills': 'Expertise',
    'skills-title': 'Habilidades Técnicas',
    'skill-frontend': 'Frontend',
    'skill-backend': 'Backend & Arquitetura',
    'skill-cyber': 'Cibersegurança',
    'skill-infra': 'Infra & Nuvem',
    'tag-projects': 'Portfólio',
    'projects-title': 'Projetos em Destaque',
    'proj1-title': 'Sistema de Gestão Amplus',
    'proj1-desc': 'Plataforma completa para gestão de documentos e controle de usuários com arquitetura MVC.',
    'proj2-title': 'Limpeza Automática TI',
    'proj2-desc': 'Ferramenta para otimização de máquinas e limpeza de arquivos temporários corporativos.',
    'proj3-title': 'Auth Guard System',
    'proj3-desc': 'Microsserviço de autenticação robusto focado em segurança e alta disponibilidade.',
    'tag-contact': 'Contato',
    'contact-title': 'Vamos conversar?',
    'contact-subtitle': 'Informações',
    'label-name': 'Nome',
    'label-email': 'E-mail',
    'label-message': 'Mensagem',
    'btn-send': 'Enviar Mensagem',
    'footer-made': 'Feito com ❤️ por',
    'footer-role': 'Coordenador de TI | Cybersecurity | Londrina, PR',
    'sending': 'Enviando...',
    'sending-msg': 'Enviando mensagem...',
    'success-msg': '✅ Mensagem enviada! Entrarei em contato em breve.',
    'error-msg': '❌ Falha ao enviar. Por favor, envie diretamente para: dev.rodrigo.dev@gmail.com'
  },
  'en': {
    'nav-home': 'Home',
    'nav-about': 'About',
    'nav-skills': 'Skills',
    'nav-projects': 'Projects',
    'nav-contact': 'Contact',
    'hero-welcome': 'Welcome to my digital world',
    'hero-role': 'IT Coordinator & Infrastructure Specialist',
    'hero-desc': 'Network management, cybersecurity, and corporate process optimization.',
    'btn-contact': 'Contact Me',
    'btn-projects': 'View Projects',
    'scroll': 'Scroll down',
    'tag-about': 'About Me',
    'about-title': 'Discover my journey',
    'about-subtitle': 'Who is Rodrigo?',
    'about-p1': 'Based in Londrina, PR, I am an <strong>IT Coordinator at Consultomaq</strong>, focused on resilient infrastructure, cybersecurity, and operational optimization.',
    'about-p2': 'I manage Cisco networks, Dell servers, Active Directory, and Azure, while implementing governance methodologies and team management for operational efficiency.',
    'stat-years': 'Years Exp.',
    'stat-projects': 'Network Focus',
    'about-edu': 'Education & Specialties',
    'about-p3': 'Currently pursuing an MBA in Cybersecurity with a solid background in systems development.',
    'edu-1': 'MBA in Cybersecurity and Cybercrimes (Anhanguera)',
    'edu-2': 'Systems Analysis and Development (Unopar)',
    'edu-3': 'Azure, Active Directory, Cisco, and Zabbix',
    'tag-skills': 'Expertise',
    'skills-title': 'Technical Skills',
    'skill-frontend': 'Frontend',
    'skill-backend': 'Backend & Architecture',
    'skill-cyber': 'Cybersecurity',
    'skill-infra': 'Infra & Cloud',
    'tag-projects': 'Portfolio',
    'projects-title': 'Featured Projects',
    'proj1-title': 'Amplus Management System',
    'proj1-desc': 'Complete platform for document management and user control with MVC architecture.',
    'proj2-title': 'Automatic IT Cleanup',
    'proj2-desc': 'Tool for machine optimization and corporate temporary file cleanup.',
    'proj3-title': 'Auth Guard System',
    'proj3-desc': 'Robust authentication microservice focused on security and high availability.',
    'tag-contact': 'Contact',
    'contact-title': 'Let\'s talk?',
    'contact-subtitle': 'Information',
    'label-name': 'Name',
    'label-email': 'Email',
    'label-message': 'Message',
    'btn-send': 'Send Message',
    'footer-made': 'Made with ❤️ by',
    'footer-role': 'IT Coordinator | Cybersecurity | Londrina, PR',
    'sending': 'Sending...',
    'sending-msg': 'Sending message...',
    'success-msg': '✅ Message sent! I\'ll get back to you soon.',
    'error-msg': '❌ Failed to send. Please email directly: dev.rodrigo.dev@gmail.com'
  }
};

let currentLang = 'pt-BR';
const langToggle = document.getElementById('lang-toggle');
const langText = document.getElementById('lang-text');

function setLanguage(lang) {
  currentLang = lang;
  langText.textContent = lang === 'pt-BR' ? 'EN' : 'PT';
  document.documentElement.lang = lang;

  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (translations[lang][key]) {
      el.innerHTML = translations[lang][key];
    }
  });

  // Update placeholders separately
  const nomeInput = document.getElementById('nome');
  const emailInput = document.getElementById('email');
  const msgInput = document.getElementById('mensagem');

  if (lang === 'en') {
    if (nomeInput) nomeInput.placeholder = 'Your full name';
    if (emailInput) emailInput.placeholder = 'you@email.com';
    if (msgInput) msgInput.placeholder = 'How can I help you?';
  } else {
    if (nomeInput) nomeInput.placeholder = 'Seu nome completo';
    if (emailInput) emailInput.placeholder = 'seu@email.com';
    if (msgInput) msgInput.placeholder = 'Como posso te ajudar?';
  }
}

langToggle?.addEventListener('click', () => {
  const newLang = currentLang === 'pt-BR' ? 'en' : 'pt-BR';
  setLanguage(newLang);
});

// ===== CONTACT FORM (FormSubmit) =====
document.getElementById('contato-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn      = document.getElementById('submit-btn');
  const feedback = document.getElementById('form-feedback');

  const nome     = document.getElementById('nome').value.trim();
  const email    = document.getElementById('email').value.trim();
  const mensagem = document.getElementById('mensagem').value.trim();

  btn.textContent = translations[currentLang]['sending'];
  btn.disabled = true;
  feedback.textContent = translations[currentLang]['sending-msg'];
  feedback.style.color = '#fff';

  try {
    const response = await fetch('https://formsubmit.co/ajax/dev.rodrigo.dev@gmail.com', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        name: nome,
        email: email,
        message: mensagem,
        _subject: `New Portfolio Message from ${nome}`,
        _captcha: 'false'
      })
    });

    const result = await response.json();

    if (result.success === 'true' || result.success === true) {
      feedback.style.color = 'var(--accent2)';
      feedback.textContent = translations[currentLang]['success-msg'];
      this.reset();
    } else {
      throw new Error('Server error');
    }
  } catch (err) {
    feedback.style.color = '#f87171';
    feedback.textContent = translations[currentLang]['error-msg'];
  } finally {
    btn.textContent = translations[currentLang]['btn-send'];
    btn.disabled = false;
  }
});
