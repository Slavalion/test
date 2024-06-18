import{b as V}from"./index-4cd942bc.js";import{r as e,l as _,x as I,D as C,o as c,g as k,a,w as h,y as n,h as s,G as u,z as d,n as S,c as w,t as b,j as p,m as l,b as N,S as x}from"./app-632bf8b8.js";import{_ as g}from"./AppButton-ffc821ac.js";const Q=e({show:JSON.parse(localStorage.getItem("fillUpBalance"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("fillUpBalance",this.show)}}),q=e({show:JSON.parse(localStorage.getItem("purchaseSlide"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("purchaseSlide",this.show)}}),A=e({show:JSON.parse(localStorage.getItem("reviewReactionSlide"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("reviewReactionSlide",this.show)}}),H=e({show:JSON.parse(localStorage.getItem("reviewComplaintSlide"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("reviewComplaintSlide",this.show)}}),K=e({show:JSON.parse(localStorage.getItem("actionCartModal"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("actionCartModal",this.show)}}),X=e({show:JSON.parse(localStorage.getItem("actionSearchModal"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("actionSearchModal",this.show)}}),Y=e({show:JSON.parse(localStorage.getItem("purchaseGenerator"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("purchaseGenerator",this.show)}}),Z=e({show:JSON.parse(localStorage.getItem("purchaseImport"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("purchaseImport",this.show)}}),ee=e({show:JSON.parse(localStorage.getItem("pickUpImport"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("pickUpImport",this.show)}}),se=e({show:JSON.parse(localStorage.getItem("locationSelector"))===!0,close(){this.show=!1,this.storeValue()},open(){this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("locationSelector",this.show)}}),te=e({show:!1,close(){this.show=!1},open(){this.show=!0},storeValue(){localStorage.setItem("updateDeliveryData",this.show)}}),oe=e({show:V("addWallet",!1),walletCode:localStorage.getItem("addWallet_walletCode"),close(){this.show=!1,this.storeValue()},open(t){this.walletCode=t,this.show=!0,this.storeValue()},storeValue(){localStorage.setItem("addWallet_walletCode",this.walletCode)}}),ae=e({show:!1,close(){this.show=!1},open(){this.show=!0},storeValue(){localStorage.setItem("updateDeliveryData",this.show)}}),le=e({show:!1,close(){this.show=!1},open(){this.show=!0}}),ie=e({show:!1,close(){this.show=!1},open(){this.show=!0}}),re=e({show:!1,close(){this.show=!1},open(){this.show=!0}}),ce=e({show:!1,close(){this.show=!1},open(){this.show=!0}}),he=e({show:!1,close(){this.show=!1},open(t){this.show=!0},storeValue(){localStorage.setItem("addQuestion",this.show)}}),B={class:"fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50","scroll-region":""},O=s("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),D=[O],J={class:"modal__loading-overlay flex flex-col gap-4"},M=s("div",{class:"lds-ring"},[s("div"),s("div"),s("div"),s("div")],-1),T={key:0},U={class:"modal__header"},$={class:"modal__header-wrapper"},E={class:"modal__header-title"},W={class:"modal__header-close"},z={key:0,class:"modal__header-caption"},G={key:0,class:"modal__footer"},R={class:"modal__footer-actions"},ne={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0},wrapperClass:{type:String,default:""},bodyClass:{type:String,default:""},hideFooter:{type:Boolean,default:!1},loading:{type:Boolean,default:!1},loadingText:{type:String,default:""}},emits:["close","open"],setup(t,{emit:v}){const i=t,m=v;_(()=>i.show,()=>{i.show?(y(),document.body.style.overflow="hidden"):document.body.style.overflow=null});const r=()=>{i.closeable&&m("close")},y=()=>{m("open")},f=o=>{o.key==="Escape"&&i.show&&r()};return I(()=>document.addEventListener("keydown",f)),C(()=>{document.removeEventListener("keydown",f),document.body.style.overflow=null}),(o,L)=>(c(),k(x,{to:"body"},[a(u,{"leave-active-class":"duration-200"},{default:h(()=>[n(s("div",B,[a(u,{"enter-active-class":"ease-out duration-200","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:h(()=>[n(s("div",{class:"fixed inset-0 transform transition-all",onClick:r},D,512),[[d,t.show]])]),_:1}),a(u,{"enter-active-class":"ease-out duration-200","enter-from-class":"opacity-0 scale-50","enter-to-class":"opacity-100 scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-50"},{default:h(()=>[n(s("div",{class:S(["modal__wrapper transform -translate-x-2/4 -translate-y-2/4 transition-all",[t.wrapperClass]])},[n(s("div",J,[M,t.loadingText?(c(),w("div",T,b(t.loadingText),1)):p("",!0)],512),[[d,t.loading]]),s("div",U,[s("div",$,[s("div",E,[l(o.$slots,"header")]),s("div",W,[a(g,{onClick:r,icon:"close",theme:"normal"})])]),o.$slots.caption?(c(),w("div",z,[l(o.$slots,"caption")])):p("",!0)]),s("div",{class:S(["modal__body",[t.bodyClass]])},[l(o.$slots,"default")],2),t.hideFooter?p("",!0):(c(),w("div",G,[s("div",R,[l(o.$slots,"footer-logo"),a(g,{size:"lg",theme:"normal",onClick:r},{default:h(()=>[N(" Отменить ")]),_:1}),l(o.$slots,"actions")])]))],2),[[d,t.show]])]),_:3})],512),[[d,t.show]])]),_:3})]))}};export{ne as _,K as a,X as b,q as c,Z as d,ee as e,Q as f,le as g,ie as h,re as i,ce as j,he as k,se as l,A as m,ae as n,oe as o,Y as p,H as r,te as u};
