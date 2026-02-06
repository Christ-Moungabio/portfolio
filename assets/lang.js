(function(){
  const STORAGE_KEY = 'lang';
  
  function getLang(){ 
    return localStorage.getItem(STORAGE_KEY) || 'fr'; 
  }
  
  function setLang(lang){ 
    localStorage.setItem(STORAGE_KEY, lang); 
  }

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

      const alt = el.dataset[lang + 'Alt'];
      if(alt) el.setAttribute('alt', alt);
      
      const title = el.dataset[lang + 'Title'];
      if(title) el.setAttribute('title', title);
      
      const aria = el.dataset[lang + 'Aria'];
      if(aria) el.setAttribute('aria-label', aria);
    });

    document.querySelectorAll('meta[name="description"], meta[property="og:description"], meta[property="og:title"]').forEach(m => {
      const c = m.dataset[lang];
      if(c) m.setAttribute('content', c);
    });

    const titleEl = document.querySelector('title');
    if(titleEl && titleEl.dataset && titleEl.dataset[lang]){
      document.title = titleEl.dataset[lang];
    }

    document.querySelectorAll('#langToggle, #langToggleMobile').forEach(btn => {
      btn.textContent = lang.toUpperCase();
      btn.removeAttribute('disabled');
      btn.removeAttribute('aria-disabled');
      const langName = lang === 'fr' ? 'Français' : 'English';
      btn.setAttribute('title', `${langName} - Click to switch`);
      btn.setAttribute('aria-label', `Language: ${langName}, click to switch`);
    });
  }

  function toggleLanguage(){
    const currentLang = getLang();
    const newLang = currentLang === 'fr' ? 'en' : 'fr';
    setLang(newLang);
    applyLanguage(newLang);
  }

  let _langInitDone = false;
  function initLang(){
    if(_langInitDone) return; 
    _langInitDone = true;

    const lang = getLang();
    applyLanguage(lang);

    ['langToggle','langToggleMobile'].forEach(id => {
      const btn = document.getElementById(id);
      if(btn && !btn.dataset.langListener){
        btn.addEventListener('click', toggleLanguage);
        btn.dataset.langListener = '1';
      }
    });

    const thanks = document.getElementById('thanks');
    if(thanks && thanks.dataset){
      const text = thanks.dataset[lang];
      if(text){
        thanks.textContent = text;
      }
    }

    document.documentElement.classList.remove('lang-flash');
  }

  try{ initLang(); } catch(e){ }
  document.addEventListener('DOMContentLoaded', initLang);
})();
