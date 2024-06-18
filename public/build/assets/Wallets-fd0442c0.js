import{e as U,o as i,c as v,h as s,t as T,a as p,w as d,b as y,j as k,u as t,y as A,z as W,n as L,O as M,g as E,m as ae,Q as F,d as se,r as G,f as K,x as P,H as V,D as Q,U as z,Z as oe,F as B,i as Y}from"./app-632bf8b8.js";import{o as b,_ as ne}from"./Modal-1349d4bc.js";import{d as D}from"./index-81635579.js";import{b as I,a as re}from"./index-4cd942bc.js";import{_ as O,b as ie}from"./AppButton-ffc821ac.js";import{_ as C}from"./LabelText-36465a88.js";import{u as Z}from"./useAxios-d55f63a8.js";import{_ as ue}from"./AuthenticatedLayout-6b95fe5a.js";import{_ as N}from"./TextInput-7865c9b5.js";import{_ as de}from"./ProgressBar-77013aff.js";import"./dayjs.min-356b711c.js";import"./SelectInput-944e57c4.js";import"./CheckboxInput-967d96fa.js";const J={yoomoney:{logo:"/images/YooMoney.svg",disabled:!1,description:'<a href="https://yoomoney.ru/page?id=523014" target="_blank">Лимиты кошельков</a><br/><a href="https://yoomoney.ru/page?id=536144&_openstat=settings%253Baccount%253Bstatus%253Bidentified#copying" target="_blank">Как пройти верификацию</a>'},qiwi:{logo:"/images/Qiwi.svg",disabled:!0}},q={0:{code:"PENDING",title:"В процессе авторизации",labelTheme:"normal"},1:{code:"READY",title:"Готов к работе",labelTheme:"normal"},2:{code:"LIMIT_REACHED",title:"Лимит исчерпан",labelTheme:"warning"},3:{value:"LOGED_OUT",title:"Разлогин",labelTheme:"warning"},4:{value:"NEED_VERIF",title:"Нужен именной кошелек",labelTheme:"warning"},isUpdateAvailable:o=>["READY","LIMIT_REACHED"].includes(o)},ce={class:"wallet__info"},pe={class:"wallet__info-login"},_e={class:"wallet__info-balance"},me={key:0},ve={key:1},fe={class:"wallet__loading-overlay"},we=s("div",{class:"lds-ring"},[s("div"),s("div"),s("div"),s("div")],-1),ge=[we],he={__name:"WalletItem",props:{wallet:{type:Object,required:!0}},setup(o){const $=o,r=Z(),{loading:_}=r,u=U(q[$.wallet.status]),f=l=>{l.is_updating=!0,r.post(route("wallets.update-balance",{wallets:[{id:l.id}]})).then(()=>{})},c=l=>{l.is_updating=!0,r.post(route("wallets.delete",{wallet:l.id})).then(()=>{M.reload()})};return(l,e)=>(i(),v("div",{class:L(["wallet",{wallet_loading:o.wallet.is_updating}])},[s("div",ce,[s("div",pe,T(o.wallet.name),1),s("div",_e," Баланс: "+T(o.wallet.balance)+" ₽ от "+T(o.wallet.updated_ts),1)]),u.value&&u.value.code!="READY"?(i(),v("div",me,[p(C,{theme:u.value.labelTheme},{default:d(()=>[y(T(u.value.title),1)]),_:1},8,["theme"])])):k("",!0),t(q).isUpdateAvailable(u.value.code)?(i(),v("div",ve,[p(O,{theme:"normal",size:"sm",icon:"refresh",disabled:t(_),onClick:e[0]||(e[0]=m=>f(o.wallet))},null,8,["disabled"])])):k("",!0),s("div",null,[p(O,{theme:"normal",size:"sm",icon:"delete",onClick:e[1]||(e[1]=m=>c(o.wallet))})]),A(s("div",fe,ge,512),[[W,o.wallet.is_updating]])],2))}},be={class:"caption"},ke={__name:"CaptionBlock",props:{icon:{type:String,default:""}},setup(o){return($,r)=>(i(),v("div",be,[o.icon!=""?(i(),E(ie,{key:0,icon:o.icon},null,8,["icon"])):k("",!0),s("span",null,[ae($.$slots,"default")])]))}},ye={class:"wallet-stepper"},$e={class:"wallet-stepper__title"},Ee=["src"],xe={__name:"AddWallet",setup(o){const $=F(),r=se(),_=Z(),u=$.props.auth.user,f=U(null),c=U(null),l=I("addWallet_walletID",null),e=I("addWallet_step",1),m=I("addWallet_loading",!1),w=I("addWallet_progress",0),X=()=>{V(()=>f.value.focus())},n=G({login:"",password:"",code:b.walletCode,errors:{}}),g=G({code:"",errors:{}}),ee=K(()=>{let a="";return Object.keys(n.errors).forEach(h=>{a+=" "+n.errors[h].join(",")}),a}),le=K(()=>e.value==1?!(n.login!=""&&n.login.indexOf("@")>-1):e.value==2?n.password=="":e.value==3?g.code=="":!1),R=["bad_pass","bad_login","bad_code"],S=function(){if(e.value==3){if(g.code=="")return;m.value=!0,_.post(route("wallets.confirm"),{code:g.code,id:l.value}).then(()=>{H()}).catch(a=>{e.value=1,w.value=0,m.value=!1,n.errors=a.response.data.errors??{}})}if(e.value==2){if(n.password=="")return;m.value=!0,_.post(route("wallets.connect"),n).then(a=>{l.value=a.data.wallet_id,j()}).catch(a=>{e.value=1,m.value=!1,n.errors=a.response.data.errors??{}})}if(e.value==1){if(n.login==""||n.login.indexOf("@")==-1){r.error("Для авторизации необходимо использовать email адрес");return}e.value=2,w.value=33.333333,V(()=>c.value.focus())}},j=()=>{window.Echo.private("user."+u.id).listen(".wallet.connect",a=>{l.value==a.id&&(console.log("Get 2 step answer"),e.value=3,w.value=66.666666,m.value=!1,R.includes(a.task_status)&&(l.value=null,e.value=null,w.value=null,m.value=null,b.close(),M.reload(),a.task_status=="bad_pass"?r.error("Не удалось привязать кошелек, неверный пароль"):a.task_status=="bad_login"?r.error("Не удалось привязать кошелек, неверный логин"):a.task_status=="bad_code"?r.error("Не удалось привязать кошелек, неверный код подтверждения"):r.error("Не удалось привязать кошелек")),window.Echo.private("user."+u.id).stopListening(".wallet.connect"))})},H=()=>{window.Echo.private("user."+u.id).listen(".wallet.connect",a=>{l.value==a.id&&(console.log("Get 3 step answer"),w.value=100,b.close(),R.includes(a.task_status)&&(a.task_status=="bad_pass"?r.error("Не удалось привязать кошелек, неверный пароль"):a.task_status=="bad_login"?r.error("Не удалось привязать кошелек, неверный логин"):a.task_status=="bad_code"?r.error("Не удалось привязать кошелек, неверный код подтверждения"):r.error("Не удалось привязать кошелек")),V(()=>{l.value=null,e.value=null,w.value=null,m.value=null,n.login="",n.password="",g.code=""}),M.reload(),window.Echo.private("user."+u.id).stopListening(".wallet.connect"))})},te=()=>{e.value==2&&l.value!=null||e.value==3&&l.value!=null||(e.value==2&&l.value==null&&(e.value=1,w.value=0),b.close())};return P(async()=>{l.value!=null&&await _.get(route("wallets.show",{id:l.value})).catch(()=>{l.value=null,b.close(),V(()=>{e.value=null,w.value=null,m.value=null})}),e.value==2&&l.value!=null&&j(),e.value==3&&l.value!=null&&H()}),Q(()=>{window.Echo.private("user."+u.id).stopListening(".wallet.connect")}),(a,h)=>(i(),E(ne,{show:t(b).show,onClose:te,onOpen:X,loading:t(m),"loading-text":"Авторизация занимает 3-5 минут"},{header:d(()=>[y("Добавление кошелька")]),caption:d(()=>[s("div",ye,[s("div",$e,"Шаг "+T(t(e))+" из 3",1),p(de,{progress:t(w)},null,8,["progress"])])]),"footer-logo":d(()=>[s("img",{src:t(J)[t(b).walletCode].logo,alt:"",class:"mr-auto"},null,8,Ee)]),actions:d(()=>[p(O,{size:"lg",onClick:S,disabled:le.value},{default:d(()=>[y("Дальше")]),_:1},8,["disabled"])]),default:d(()=>[A(p(N,{modelValue:n.login,"onUpdate:modelValue":h[0]||(h[0]=x=>n.login=x),ref_key:"loginInput",ref:f,size:"lg",label:"Логин от кошелька (email)","has-error":n.errors.login!=null,"error-message":ee.value,placeholder:"Введите email",onKeyup:z(S,["enter"])},null,8,["modelValue","has-error","error-message"]),[[W,t(e)==1]]),A(p(N,{modelValue:n.password,"onUpdate:modelValue":h[1]||(h[1]=x=>n.password=x),type:"password",ref_key:"passwordInput",ref:c,size:"lg",label:"Пароль от кошелька",onKeyup:z(S,["enter"])},null,8,["modelValue"]),[[W,t(e)==2]]),A(p(N,{modelValue:g.code,"onUpdate:modelValue":h[2]||(h[2]=x=>g.code=x),size:"lg",label:"Введите код подтверждения (придет на телефон или почту)","has-error":g.errors.code!=null,"error-message":g.errors.code,onKeyup:z(S,["enter"])},null,8,["modelValue","has-error","error-message"]),[[W,t(e)==3]]),p(ke,{icon:"shield",class:"mt-3"},{default:d(()=>[y(" Мы не храним данные от вашего кошелька ")]),_:1})]),_:1},8,["show","loading"]))}},De=s("title",null,"Кошельки",-1),Te=s("span",null,"Кошельки",-1),Le={key:0,class:"wallet-header__top"},Se={class:"wallet-header__bottom"},Ve=["src"],Ie=["innerHTML"],Ke={__name:"Wallets",props:{wallets:{type:Object,default:()=>{}}},setup(o){const $=o,r=F(),{width:_}=re();return P(()=>{window.Echo.private("user."+r.props.auth.user.id).listen(".wallet.update-balance",u=>{const f=$.wallets.yoomoney.find(c=>c.id==u.id);f&&(f.is_updating=!1,f.balance=u.balance)})}),Q(()=>{window.Echo.private("user."+r.props.auth.user.id).stopListening(".wallet.update-balance")}),(u,f)=>(i(),v(B,null,[p(t(oe),null,{default:d(()=>[De]),_:1}),p(ue,null,{header:d(()=>[Te,o.wallets.qiwi==0&&o.wallets.yoomoney==0?(i(),E(C,{key:0,theme:"warning",class:"ml-4"},{default:d(()=>[y(" Добавьте кошельки, чтобы оплачивать выкупы ")]),_:1})):k("",!0)]),default:d(()=>[s("div",{class:L(t(D)().isDesktop&&t(_)>390?"flex flex-col gap-4":"wallets-mobile-groups")},[(i(!0),v(B,null,Y(t(J),(c,l)=>(i(),v("div",{key:l,class:L(t(D)().isDesktop&&t(_)>390?"panel panel_p-lg":"wallets-mobile-group")},[s("div",{class:L(["wallet-header",{"wallet-header_disabled":c.disabled}])},[t(D)().isDesktop&&t(_)>390?k("",!0):(i(),v("div",Le,[c.disabled?(i(),E(C,{key:0,theme:"warning",class:"ml-auto"},{default:d(()=>[y(" Временно недоступен ")]),_:1})):k("",!0)])),s("div",Se,[s("img",{src:c.logo,alt:""},null,8,Ve),c.disabled&&t(D)().isDesktop&&t(_)>390?(i(),E(C,{key:0,theme:"warning",class:"ml-auto"},{default:d(()=>[y(" Временно недоступен ")]),_:1})):k("",!0),p(O,{theme:"normal",icon:"plus-circle",onClick:e=>t(b).open(l)},{default:d(()=>[y(" Добавить ")]),_:2},1032,["onClick"])])],2),c.description?(i(),v("div",{key:0,innerHTML:c.description,class:L(["mt-2 text-sm",t(D)().isDesktop&&t(_)>390?"":"wallets-mobile-description"])},null,10,Ie)):k("",!0),(i(!0),v(B,null,Y(o.wallets[l],e=>(i(),E(he,{key:e.id,wallet:e},null,8,["wallet"]))),128))],2))),128))],2),s("div",null,[p(xe)])]),_:1})],64))}};export{Ke as default};