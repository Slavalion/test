import{x as y,D as p,e as g,o as l,c as o,h as r,a as c,y as d,z as u,w as h,F as k,i as w,t as E,G as S}from"./app-632bf8b8.js";import{_ as x}from"./TextInput-7865c9b5.js";const z={class:"relative"},C={class:"list origin-top-left left-0",style:{display:"none"}},V=["onClick"],$={__name:"SelectInput",props:{modelValue:{type:Object,required:!0},align:{type:String,default:"right"},items:{type:Array,default:()=>[]},size:{type:String,default:"sm"},contentClasses:{type:String,default:"py-1 bg-white"},hasError:{type:Boolean,default:!1},errorMessage:{type:String,default:""}},emits:["update:modelValue"],setup(t,{emit:m}){const v=m,i=s=>{e.value&&s.key==="Escape"&&(e.value=!1)},f=function(s){e.value=!1,v("update:modelValue",s)};y(()=>document.addEventListener("keydown",i)),p(()=>document.removeEventListener("keydown",i));const e=g(!1);return(s,n)=>(l(),o("div",z,[r("div",{onClick:n[0]||(n[0]=a=>e.value=!e.value)},[c(x,{"model-value":t.modelValue.name,size:t.size,icon:"chevron-down",readonly:"",class:"text-input_select","has-error":t.hasError,"error-message":t.errorMessage,disabled:t.items.length==0},null,8,["model-value","size","has-error","error-message","disabled"])]),d(r("div",{class:"fixed inset-0 z-40",onClick:n[1]||(n[1]=a=>e.value=!1)},null,512),[[u,e.value]]),c(S,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:h(()=>[d(r("div",C,[(l(!0),o(k,null,w(t.items,a=>(l(),o("div",{onClick:_=>f(a),key:a.key,class:"list__item"},E(a.name),9,V))),128))],512),[[u,e.value]])]),_:1})]))}};export{$ as _};
