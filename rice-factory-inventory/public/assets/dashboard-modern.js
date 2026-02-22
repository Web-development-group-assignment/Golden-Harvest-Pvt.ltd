const THEME_KEY='imsTheme';
function setCookie(n,v,d=365){
    const t=new Date();
    t.setTime(t.getTime()+d*24*60*60*1e3);
    document.cookie=`${n}=${v};expires=${t.toUTCString()};path=/`;
}
function getStoredTheme(){
    const ls=localStorage.getItem(THEME_KEY);
    if(ls)return ls;
    return window.matchMedia('(prefers-color-scheme: light)').matches?'light':'dark';
}
function applyTheme(theme){
    document.body.setAttribute('data-theme',theme);
    localStorage.setItem(THEME_KEY,theme);setCookie(THEME_KEY,theme);
    const ic=document.getElementById('themeIcon');
    if(ic) ic.className=theme==='light'?'ri-moon-line':'ri-sun-line';
}
applyTheme(getStoredTheme());
document.getElementById('themeToggle')?.addEventListener('click',()=>{const next=document.body.getAttribute('data-theme')==='light'?'dark':'light';applyTheme(next)});
