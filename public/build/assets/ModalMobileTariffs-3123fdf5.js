import{l as f,x as h,D as p,o as t,g as r,a as y,w as d,y as w,h as a,c as v,F as _,i as b,n as k,b as B,t as S,z as g,G as E,S as T}from"./app-632bf8b8.js";import{_ as x}from"./AppButton-ffc821ac.js";const C={class:"mobile-modal"},D={class:"mobile-purchases-filters"},L=a("div",{class:"mobile-purchases-filters__hr"},null,-1),z={__name:"ModalMobileTariffs",props:{show:{type:Boolean,default:!1},closeable:{type:Boolean,default:!0},currentSection:{type:String,default:"all"},sections:{type:Array,default:[]}},emits:["close","open"],setup(s,{emit:u}){const o=s,n=u;f(()=>o.show,()=>{o.show?(m(),document.body.style.overflow="hidden"):document.body.style.overflow=null});const l=e=>{o.closeable&&n("close",e)},m=()=>{n("open")},c=e=>{e.key==="Escape"&&o.show&&l()};return h(()=>document.addEventListener("keydown",c)),p(()=>{document.removeEventListener("keydown",c),document.body.style.overflow=null}),(e,M)=>(t(),r(T,{to:"body"},[y(E,{"leave-active-class":"duration-200"},{default:d(()=>[w(a("div",C,[a("div",D,[L,(t(!0),v(_,null,b(s.sections,i=>(t(),r(x,{theme:"normal",class:k({btn_selected:s.currentSection=="processing"}),onClick:N=>l(i)},{default:d(()=>[B(S(e.$t(i+"Title")),1)]),_:2},1032,["class","onClick"]))),256))])],512),[[g,s.show]])]),_:1})]))}};export{z as _};
