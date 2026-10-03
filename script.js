const $=(s,c=document)=>c.querySelector(s),$$=(s,c=document)=>[...c.querySelectorAll(s)];
/* ---------- i18n: key = English text, value = [FR, AR]. Missing key => English fallback ---------- */
const T={
"Home":["Accueil","الرئيسية"],"Story":["Histoire","القصة"],"Career":["Carrière","المسيرة"],"Honours":["Palmarès","الإنجازات"],"Journal":["Journal","المجلة"],"Gallery":["Galerie","المعرض"],"Get in touch":["Contact","تواصل"],
"GET IN TOUCH ↗":["CONTACT ↗","تواصل ↗"],"Navigate":["Naviguer","التنقل"],"Connect":["Réseaux","تابعنا"],
"TUNISIAN FOOTBALL COACH · ANALYST":["ENTRAÎNEUR TUNISIEN · ANALYSTE","مدرب كرة قدم تونسي · محلل"],
"THE":["L'","عقل"],"FOOTBALL":["ESPRIT","كرة"],"MIND.":["FOOT.","القدم."],
"A football journey built across Tunisia, Saudi Arabia, Egypt, Qatar and Oman — coaching, analysis and a constant attention to the details of the game.":["Un parcours football bâti entre la Tunisie, l'Arabie saoudite, l'Égypte, le Qatar et Oman — coaching, analyse et attention constante aux détails du jeu.","مسيرة كروية بين تونس والسعودية ومصر وقطر وعُمان — تدريب وتحليل واهتمام دائم بتفاصيل اللعبة."],
"DISCOVER THE JOURNEY ↗":["DÉCOUVRIR LE PARCOURS ↗","اكتشف المسيرة ↗"],"CAREER CHAPTERS":["CHAPITRES DE CARRIÈRE","محطات المسيرة"],
"Years across professional football":["Années dans le football professionnel","سنة في كرة القدم الاحترافية"],"Documented career chapters":["Étapes de carrière documentées","محطة مسيرة موثقة"],"Football countries / markets":["Pays / marchés du football","دول / أسواق كروية"],"Coach · analyst · sports writer":["Entraîneur · analyste · journaliste sportif","مدرب · محلل · كاتب رياضي"],
"01 / THE STORY":["01 / L'HISTOIRE","01 / القصة"],"02 / THE JOURNEY":["02 / LE PARCOURS","02 / الرحلة"],"03 / IN MOTION":["03 / EN MOUVEMENT","03 / بالصورة والحركة"],
"FROM THE<br><em>PITCH</em> TO<br>THE MIND.":["DU<br><em>TERRAIN</em><br>À L'ESPRIT.","من<br><em>الملعب</em><br>إلى العقل."],
"Coach, analyst and sports writer — with a career shaped by different football cultures.":["Entraîneur, analyste et journaliste sportif — une carrière façonnée par différentes cultures du football.","مدرب ومحلل وكاتب رياضي — مسيرة صقلتها ثقافات كروية مختلفة."],
"Published biographical material describes Bayrem Mokhtari as a Tunisian professional football coach whose coaching path moved from Tunisia into Saudi Arabia, Egypt, Qatar and Oman, alongside work in football analysis and media.":["Les sources biographiques publiées présentent Bayrem Mokhtari comme un entraîneur professionnel tunisien dont le parcours l'a mené de la Tunisie à l'Arabie saoudite, l'Égypte, le Qatar et Oman, en parallèle d'un travail d'analyse et de média.","تصف المصادر المنشورة بيرم المختاري كمدرب كرة قدم محترف تونسي انتقل مشواره من تونس إلى السعودية ومصر وقطر وعُمان، إلى جانب عمله في التحليل الكروي والإعلام."],
"READ THE STORY ↗":["LIRE L'HISTOIRE ↗","اقرأ القصة ↗"],"EXPLORE CAREER ↗":["VOIR LA CARRIÈRE ↗","استكشف المسيرة ↗"],
"EVERY<br>CLUB.<br><em>ONE PATH.</em>":["CHAQUE<br>CLUB.<br><em>UN SEUL CAP.</em>","كل<br>نادٍ.<br><em>مسار واحد.</em>"],
"From Olympique du Kef to Saudi, Qatar, Oman and Egypt.":["De l'Olympique du Kef à l'Arabie saoudite, au Qatar, à Oman et à l'Égypte.","من أولمبيك الكاف إلى السعودية وقطر وعُمان ومصر."],
"Explore every documented chapter, the role listed for each period, the club identity and links to available external sources.":["Parcourez chaque étape documentée, le rôle occupé, l'identité du club et les liens vers les sources externes disponibles.","استعرض كل محطة موثقة والدور في كل فترة وهوية النادي وروابط المصادر الخارجية المتاحة."],
"THE GAME LIVES IN THE<br><em>DETAILS BEFORE KICK-OFF.</em>":["LE JEU SE JOUE DANS<br><em>LES DÉTAILS AVANT LE COUP D'ENVOI.</em>","اللعبة تُحسم في<br><em>التفاصيل قبل صافرة البداية.</em>"],
"FOOTBALL · PREPARATION · ADAPTATION":["FOOTBALL · PRÉPARATION · ADAPTATION","كرة القدم · الإعداد · التأقلم"],
"On the football side.":["Côté football.","من عالم كرة القدم."],
"Local video supplied with the project. The same video can also be reached through the supplied Facebook share link.":["Vidéo locale fournie avec le projet, également accessible via le lien Facebook.","فيديو محلي مرفق بالمشروع، ويمكن مشاهدته أيضًا عبر رابط فيسبوك."],
"OPEN FACEBOOK ↗":["OUVRIR FACEBOOK ↗","افتح فيسبوك ↗"],"Bayrem TV.":["Bayrem TV.","بيرم TV."],
"Videos, football commentary, analysis and published content.":["Vidéos, commentaires de football, analyses et contenus publiés.","فيديوهات وتعليقات كروية وتحليلات ومحتوى منشور."],"WATCH CHANNEL ↗":["VOIR LA CHAÎNE ↗","شاهد القناة ↗"],
"THE STORY IS STILL BEING WRITTEN.":["L'HISTOIRE CONTINUE DE S'ÉCRIRE.","القصة ما زالت تُكتب."],"Coach Bayrem Mokhtari · Football · Analysis · Media":["Coach Bayrem Mokhtari · Football · Analyse · Médias","المدرب بيرم المختاري · كرة القدم · تحليل · إعلام"],
"06 / GET IN TOUCH":["06 / CONTACT","06 / تواصل"],"LET'S <em>TALK.</em>":["PARLONS-<em>EN.</em>","لنتحدث<em>.</em>"],
"For coaching, football analysis, media, speaking or professional enquiries.":["Pour le coaching, l'analyse football, les médias, les conférences ou toute demande professionnelle.","للتدريب والتحليل الكروي والإعلام والمحاضرات والاستفسارات المهنية."],
"Professional enquiries and direct contact.":["Demandes professionnelles et contact direct.","للاستفسارات المهنية والتواصل المباشر."],"Football, analysis and daily updates.":["Football, analyses et actualités quotidiennes.","كرة القدم والتحليل وآخر المستجدات."],"Video archive and published content.":["Archive vidéo et contenus publiés.","أرشيف الفيديو والمحتوى المنشور."],"The Facebook video link supplied for this website.":["Le lien vidéo Facebook fourni pour ce site.","رابط فيديو فيسبوك الخاص بهذا الموقع."],
"FOLLOW":["SUIVRE","تابع"],"MESSAGE":["MESSAGE","رسالة"],"SEND MESSAGE ↗":["ENVOYER ↗","إرسال الرسالة ↗"]
};
Object.assign(T,window.BM_T||{});
const PH={"Your name":["Votre nom","اسمك"],"Your email":["Votre e-mail","بريدك الإلكتروني"],"Your message":["Votre message","رسالتك"]};
const IDX={fr:0,ar:1};
const PAGE_TITLES={
 "Home — Bayrem Mokhtari":["Accueil — Bayrem Mokhtari","الرئيسية — بيرم مختاري"],
 "The Story — Bayrem Mokhtari":["L'histoire — Bayrem Mokhtari","القصة — بيرم مختاري"],
 "Career — Bayrem Mokhtari":["Carrière — Bayrem Mokhtari","المسيرة — بيرم مختاري"],
 "Honours — Bayrem Mokhtari":["Palmarès — Bayrem Mokhtari","الإنجازات — بيرم مختاري"],
 "Journal — Bayrem Mokhtari":["Journal — Bayrem Mokhtari","المجلة — بيرم مختاري"],
 "Gallery — Bayrem Mokhtari":["Galerie — Bayrem Mokhtari","المعرض — بيرم مختاري"],
 "Get in touch — Bayrem Mokhtari":["Contact — Bayrem Mokhtari","تواصل — بيرم مختاري"],
 "Sources & Credits — Bayrem Mokhtari":["Sources et crédits — Bayrem Mokhtari","المصادر والشكر — بيرم مختاري"]
};
const PAGE_DESCRIPTION=["Coach Bayrem Mokhtari — entraîneur de football, analyste et journaliste sportif.","بيرم مختاري — مدرب كرة قدم ومحلل وكاتب رياضي."];
function setLang(l){
 if(!["en","fr","ar"].includes(l)) l="en";
 try{localStorage.setItem("bm-lang",l)}catch(e){}
 const idx={fr:0,ar:1};
 document.documentElement.lang=l;
 document.documentElement.dir=l==="ar"?"rtl":"ltr";
 const titleKey=Object.keys(PAGE_TITLES).find(key=>document.title===key||document.title===PAGE_TITLES[key][0]||document.title===PAGE_TITLES[key][1]);
 if(titleKey) document.title=l==="en"?titleKey:PAGE_TITLES[titleKey][idx[l]];
 const description=document.querySelector('meta[name="description"]');
 if(description&&l!=="en") description.setAttribute("content",PAGE_DESCRIPTION[idx[l]]);
 else if(description) description.setAttribute("content","Coach Bayrem Mokhtari — football coach, analyst and sports writer.");
 // Translate every element whose complete original HTML is present in the dictionary.
 // Snapshot originals once so switching EN/FR/AR never translates an already translated value.
 const nodes=[...document.body.querySelectorAll("*")];
 nodes.forEach(el=>{
   if(el.closest("script,style,.lang-switch")) return;
   if(el.__bmOriginalHTML===undefined) el.__bmOriginalHTML=el.innerHTML.trim();
   const key=el.__bmOriginalHTML;
   if(Object.prototype.hasOwnProperty.call(T,key)){
     const value=l==="en"?key:T[key][idx[l]];
     if(value!==undefined) el.innerHTML=value;
   } else if(!el.children.length){
     const textKey=el.__bmOriginalHTML.replace(/\s+/g," ").trim();
     if(Object.prototype.hasOwnProperty.call(T,textKey)){
       const value=l==="en"?textKey:T[textKey][idx[l]];
       if(value!==undefined) el.textContent=value;
     }
   }
 });
 // Translate form placeholders and common accessible labels without losing their English source.
 const attrs=["placeholder","aria-label","title","alt","data-cap"];
 document.querySelectorAll("input,textarea,button,a,img,iframe,[aria-label],[title],[data-cap]").forEach(el=>attrs.forEach(a=>{
   if(!el.hasAttribute(a)) return;
   const prop="__bmOriginal_"+a;
   if(el[prop]===undefined) el[prop]=el.getAttribute(a);
   const key=el[prop];
   if(a==="placeholder" && PH[key]) el.setAttribute(a,l==="en"?key:PH[key][idx[l]]);
   else if(Object.prototype.hasOwnProperty.call(T,key)) el.setAttribute(a,l==="en"?key:T[key][idx[l]]);
 }));
 document.querySelectorAll("[data-lang-btn]").forEach(b=>b.classList.toggle("active",b.dataset.langBtn===l));
}
/* ---------- init ---------- */
function init(){
 let saved="en";try{saved=localStorage.getItem("bm-lang")||"en"}catch(e){}
 if(saved!=="en")setLang(saved);else $$("[data-lang-btn=en]").forEach(b=>b.classList.add("active"));
 $$("[data-lang-btn]").forEach(b=>b.addEventListener("click",()=>setLang(b.dataset.langBtn)));
 const menu=$(".menu-btn"),mob=$(".mobile-nav");
 const close=()=>{mob?.classList.remove("open");menu?.classList.remove("open");document.body.style.overflow=""};
 menu?.addEventListener("click",()=>{const o=mob.classList.toggle("open");menu.classList.toggle("open",o);document.body.style.overflow=o?"hidden":""});
 $$(".mobile-nav a").forEach(a=>a.addEventListener("click",close));
 addEventListener("keydown",e=>{if(e.key==="Escape"){close();closeLightbox()}});
 /* Lottie */
 if(window.lottie&&window.BM_LOTTIE)$$("[data-lottie]").forEach(el=>lottie.loadAnimation({container:el,renderer:"svg",loop:true,autoplay:true,animationData:BM_LOTTIE[el.dataset.lottie]}));
 const loader=$(".page-loader");if(loader)setTimeout(()=>loader.classList.add("hide"),900);
 const glow=$(".cursor-glow");if(glow&&matchMedia("(hover:hover)").matches)addEventListener("pointermove",e=>{glow.style.left=e.clientX+"px";glow.style.top=e.clientY+"px"});
 /* reveal + stat counters */
 const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add("in");$$("[data-count]",e.target).forEach(count);io.unobserve(e.target)}}),{threshold:.08});
 $$(".reveal").forEach(e=>io.observe(e));$$(".stats").forEach(e=>io.observe(e));
 /* header state, parallax, back-to-top */
 const hd=$(".site-header"),hm=$(".hero-media img"),top=$(".backtop");
 const onS=()=>{hd?.classList.toggle("solid",scrollY>40);top?.classList.toggle("show",scrollY>700);if(hm&&scrollY<innerHeight)hm.style.transform=`translateY(${scrollY*.12}px) scale(1.06)`};
 addEventListener("scroll",onS,{passive:true});onS();
 top?.addEventListener("click",()=>scrollTo({top:0,behavior:"smooth"}));
 $$(".lightbox-trigger").forEach(img=>img.addEventListener("click",()=>openLightbox(img.src,img.alt)));
 $$(".filter").forEach(btn=>btn.addEventListener("click",()=>{$$(".filter").forEach(b=>b.classList.remove("active"));btn.classList.add("active");const f=btn.dataset.filter;$$(".media-grid>[data-type]").forEach(el=>el.style.display=(f==="all"||el.dataset.type===f)?"":"none")}));
 /* contact form -> mailto */
 $("#cform")?.addEventListener("submit",e=>{e.preventDefault();const f=e.target;location.href=`mailto:elitesport.tn@gmail.com?subject=${encodeURIComponent("Website — "+f.s.value+" — "+f.n.value)}&body=${encodeURIComponent(f.m.value+"\n\n"+f.n.value+" <"+f.e.value+">")}`});
}
function count(el){const n=+el.dataset.count,suf=el.dataset.suffix||"",pad=el.dataset.count.length>1&&el.dataset.count[0]==="0",t0=performance.now();
 (function f(t){const p=Math.min(1,(t-t0)/1200),v=Math.round(n*(1-Math.pow(1-p,3)));el.textContent=(pad?String(v).padStart(2,"0"):v)+suf;if(p<1)requestAnimationFrame(f)})(t0)}
function openLightbox(src,alt){const b=$(".lightbox");if(!b)return;$("img",b).src=src;$("img",b).alt=alt||"";b.classList.add("open")}
function closeLightbox(){$(".lightbox")?.classList.remove("open")}
document.addEventListener("DOMContentLoaded",init);

/* generic filters (journal + gallery) */
document.addEventListener("DOMContentLoaded",()=>{$$(".filters").forEach(g=>{const sec=g.closest("section");$$(".filter",g).forEach(b=>b.addEventListener("click",()=>{$$(".filter",g).forEach(x=>x.classList.remove("active"));b.classList.add("active");const f=b.dataset.filter;$$("[data-cat]",sec).forEach(el=>el.classList.toggle("hide",!(f==="all"||el.dataset.cat===f)))}))})});
/* lightbox with caption + prev/next */
let LB=[],LI=0;
function openLightbox(src){const box=$(".lightbox");if(!box)return;LB=$$(".lightbox-trigger").filter(i=>!i.closest(".hide"));LI=Math.max(0,LB.findIndex(i=>i.src===src));showLB();box.classList.add("open")}
function showLB(){const box=$(".lightbox"),i=LB[LI];if(!i)return;$("img",box).src=i.src;$("img",box).alt=i.alt;let c=$(".lb-cap",box);if(!c){c=document.createElement("p");c.className="lb-cap";box.appendChild(c);["prev","next"].forEach(d=>{const b=document.createElement("button");b.className="lb-"+d;b.textContent=d==="prev"?"‹":"›";b.setAttribute("aria-label",d);b.onclick=e=>{e.stopPropagation();LI=(LI+(d==="next"?1:-1)+LB.length)%LB.length;showLB()};box.appendChild(b)})}c.textContent=(LI+1)+" / "+LB.length+" · "+(i.dataset.cap||i.alt)}
addEventListener("keydown",e=>{if(!$(".lightbox.open"))return;if(e.key==="ArrowRight"){LI=(LI+1)%LB.length;showLB()}if(e.key==="ArrowLeft"){LI=(LI-1+LB.length)%LB.length;showLB()}});
