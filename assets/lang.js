(function(){
  // language helper
  const STORAGE_KEY = 'lang';
  // Language switching is disabled: site stays in French
  function getLang(){ return 'fr'; }
  function setLang(lang){ console.warn('[lang] setLang ignored — language switching is disabled'); /* no-op */ }

  function applyLanguage(lang){
    document.documentElement.lang = lang;

    document.querySelectorAll('[data-fr], [data-en]').forEach(el => {
      const text = el.dataset[lang];
      if(text){
        const tag = el.tagName.toUpperCase();
        if(tag === 'INPUT' || tag === 'TEXTAREA'){
          el.placeholder = text;
        } else {
          el.innerHTML = text;
        }
      }

      // attributes translations (alt, title, aria-label)
      const alt = el.dataset[lang + 'Alt'];
      if(alt) el.setAttribute('alt', alt);
      const title = el.dataset[lang + 'Title'];
      if(title) el.setAttribute('title', title);
      const aria = el.dataset[lang + 'Aria'];
      if(aria) el.setAttribute('aria-label', aria);

      // custom attribute translations (e.g., data-fr-href not supported yet)
    });

    // Update meta and Open Graph tags (use data-fr/data-en on meta elements)
    document.querySelectorAll('meta[name="description"], meta[property="og:description"], meta[property="og:title"]').forEach(m => {
      const c = m.dataset[lang];
      if(c) m.setAttribute('content', c);
    });

    // Update document title if <title> has data attributes
    const titleEl = document.querySelector('title');
    if(titleEl && titleEl.dataset && titleEl.dataset[lang]){
      document.title = titleEl.dataset[lang];
    }

    // Disable language toggle buttons and show 'FR' (site fixed to French)
    document.querySelectorAll('#langToggle, #langToggleMobile').forEach(btn => {
      btn.textContent = 'FR';
      btn.setAttribute('disabled', '');
      btn.setAttribute('aria-disabled', 'true');
      btn.setAttribute('title', 'Langue : Français (basculement désactivé)');
      btn.setAttribute('aria-label', 'Langue : Français, basculement désactivé');
      // normalize dataset so other code sees FR
      btn.dataset.fr = 'FR'; btn.dataset.en = 'FR';
    });
  }

  function toggleLanguage(){
    // Disabled on purpose — keep site in French
    console.warn('[lang] toggleLanguage called but switching is disabled');
  }

  // Auto-fill missing English translations from a small dictionary
  const TRANSLATIONS = {
    "À propos": "About",
    "Parcours": "Journey",
    "Projets": "Projects",
    "Compétences": "Skills",
    "Contact": "Contact",
    "Télécharger CV": "Download CV",
    "Voir mes projets": "View my projects",
    "Me contacter": "Contact me",
    "Voir le projet": "View project",
    "Voir la galerie": "View gallery",
    "Retour à l'accueil": "Back to home",
    "Image précédente": "Previous image",
    "Image suivante": "Next image",
    "Page d'accueil": "Home page",
    "Page classements": "Rankings Page",
    "Page vote": "Voting Page",
    "Paiement": "Payment",
    "Envoyer": "Send",
    "Nom": "Name",
    "Votre nom": "Your name",
    "Email": "Email",
    "Message": "Message",
    "Décrire votre besoin en quelques lignes": "Describe your need in a few lines",
    "Voir la galerie": "View gallery",
    "Galerie du projet": "Project gallery",
    "Description du Projet": "Project Description",
    "À propos du projet": "About the project",
    "Fonctionnalités principales": "Main Features",
    "Technologies utilisées": "Technologies used",
    "Infos rapides": "Quick Info",
    "Retour en haut": "Back to top",
    "Ouvrir le menu": "Open menu",
    "Tous": "All",
    "Automatisation": "Automation",
    "Compétences": "Skills"
  };

  function ensureTranslations(){
    document.querySelectorAll('[data-fr]').forEach(el => {
      if(!el.dataset.en){
        const fr = el.dataset.fr.trim();
        const en = TRANSLATIONS[fr] || fr;
        el.dataset.en = en;
      }
      // handle alt/title/aria attributes stored as data-fr-*
      const frAlt = el.dataset.frAlt || el.dataset.fralt || null;
      if(frAlt && !el.dataset.enAlt){
        el.dataset.enAlt = TRANSLATIONS[frAlt] || frAlt;
      }
      const frTitle = el.dataset.frTitle || el.dataset.frtitle || null;
      if(frTitle && !el.dataset.enTitle){
        el.dataset.enTitle = TRANSLATIONS[frTitle] || frTitle;
      }
      const frAria = el.dataset.frAria || el.dataset.fraria || null;
      if(frAria && !el.dataset.enAria){
        el.dataset.enAria = TRANSLATIONS[frAria] || frAria;
      }
    });

    // Also fill for elements that have standard attributes but no data-* counterparts
    document.querySelectorAll('[alt]').forEach(img => {
      if(!img.dataset.enAlt){
        const alt = img.getAttribute('alt') || '';
        if(alt) img.dataset.enAlt = TRANSLATIONS[alt] || alt;
      }
    });
    document.querySelectorAll('[title]').forEach(el => {
      if(!el.dataset.enTitle){
        const title = el.getAttribute('title') || '';
        if(title) el.dataset.enTitle = TRANSLATIONS[title] || title;
      }
    });
    document.querySelectorAll('[aria-label]').forEach(el => {
      if(!el.dataset.enAria){
        const aria = el.getAttribute('aria-label') || '';
        if(aria) el.dataset.enAria = TRANSLATIONS[aria] || aria;
      }
    });
  }

  // Initialization function (idempotent)
  let _langInitDone = false;
  function initLang(){
    if(_langInitDone) return; _langInitDone = true;

    // ensure we have English translations available before applying language
    ensureTranslations();

    const lang = getLang();
    console.log('[lang] initializing with', lang);
    applyLanguage(lang);

    // Attach toggle listeners if buttons exist (idempotent)
    ['langToggle','langToggleMobile'].forEach(id => {
      const btn = document.getElementById(id);
      if(btn && !btn.dataset.langListener){
        btn.addEventListener('click', toggleLanguage);
        btn.dataset.langListener = '1';
      }
    });

    // If page has a "thanks" div with data attributes, localize it
    const thanks = document.getElementById('thanks');
    if(thanks && thanks.dataset){
      const text = thanks.dataset[lang];
      if(text){
        thanks.textContent = text;
      }
    }

    // Apply aria/title/alt translations that were added via data-en-* during ensureTranslations
    // (ensure these attributes reflect chosen language)
    document.querySelectorAll('[data-fr-aria], [data-en-aria]').forEach(el => {
      const a = el.dataset[document.documentElement.lang + 'Aria'];
      if(a) el.setAttribute('aria-label', a);
    });
    document.querySelectorAll('[data-fr-title], [data-en-title]').forEach(el => {
      const t = el.dataset[document.documentElement.lang + 'Title'];
      if(t) el.setAttribute('title', t);
    });
    document.querySelectorAll('[data-fr-alt], [data-en-alt]').forEach(el => {
      const alt = el.dataset[document.documentElement.lang + 'Alt'];
      if(alt) el.setAttribute('alt', alt);
    });

    // remove optional flash helper class if present
    document.documentElement.classList.remove('lang-flash');
  }

  // Try to initialize synchronously (script is usually placed at end of body)
  try{ initLang(); } catch(e){ /* ignore if DOM not ready */ }

  // Also initialize when DOMContentLoaded for maximum compatibility
  document.addEventListener('DOMContentLoaded', initLang);
})();
