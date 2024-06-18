import{y as k,z as h,o as a,c as n,h as e,t as d,m as v,r as w,T as V,a as l,u as t,Z as $,w as _,b as x,B as S,a4 as U,G as B,j as T,F as y,i as q}from"./app-632bf8b8.js";import{a as L}from"./AppButton-ffc821ac.js";import{_ as f}from"./TextInput-b7202784.js";import{_ as N}from"./AuthenticatedLayout-6b95fe5a.js";import"./index-81635579.js";import"./index-4cd942bc.js";import"./Modal-1349d4bc.js";import"./useAxios-d55f63a8.js";import"./TextInput-7865c9b5.js";import"./dayjs.min-356b711c.js";import"./SelectInput-944e57c4.js";import"./LabelText-36465a88.js";import"./CheckboxInput-967d96fa.js";const E={class:"text-sm text-red-600"},u={__name:"InputError",props:{message:{type:String}},setup(i){return(m,p)=>k((a(),n("div",null,[e("p",E,d(i.message),1)],512)),[[h,i.message]])}},C={class:"block font-medium text-sm text-gray-700"},D={key:0},F={key:1},c={__name:"InputLabel",props:{value:{type:String}},setup(i){return(m,p)=>(a(),n("label",C,[i.value?(a(),n("span",D,d(i.value),1)):(a(),n("span",F,[v(m.$slots,"default")]))]))}},I={},M={class:"inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"};function P(i,m){return a(),n("button",M,[v(i.$slots,"default")])}const j=L(I,[["render",P]]),z={class:"py-12"},A={class:"max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6"},G={class:"bg-white shadow-sm sm:rounded-lg p-6 text-gray-900"},Z=e("option",{value:"3",selected:""},"3 часа",-1),H=e("option",{value:"12",selected:""},"12 часов",-1),J=e("option",{value:"24",selected:""},"1 день",-1),K=e("option",{value:"168",selected:""},"1 неделя",-1),O=e("option",{value:"336",selected:""},"2 недели",-1),Q=[Z,H,J,K,O],R={class:"flex items-center gap-4"},W={key:0,class:"text-sm text-gray-600"},X={class:"flex flex-col gap-2"},Y={class:"text-xl font-medium"},ee={class:"text-xl font-medium"},se={key:0,class:"text-xl font-medium text-lime-600"},te={key:1,class:"text-xl font-medium text-amber-500"},ge={__name:"Liker",props:{phpVersion:{type:String,required:!0},result:{type:String,default:""},tasks:{type:Array,default:()=>[]}},setup(i){const p=w({tasks:i.tasks,query:"",error:""}),o=V({link:"",count:"",product_id:"",period:"",keywords:""});return window.Echo.channel("tasks").listen("TaskProgressUpdate",g=>{const r=g.task;let s=p.tasks.find(b=>b.id==r.id);s.progress=r.progress}),(g,r)=>(a(),n(y,null,[l(t($),{title:"Liker"}),l(N,null,{header:_(()=>[x(' Добавить задачу "Лайкер" ')]),default:_(()=>[e("div",z,[e("div",A,[e("div",G,[e("form",{onSubmit:r[5]||(r[5]=S(s=>t(o).post(g.route("liker.do")),["prevent"])),class:"mt-6 space-y-6"},[e("div",null,[l(c,{for:"link",value:"Ссылка"}),l(f,{id:"link",type:"text",class:"mt-1 block w-full",modelValue:t(o).link,"onUpdate:modelValue":r[0]||(r[0]=s=>t(o).link=s),required:"",autofocus:"",autocomplete:"link"},null,8,["modelValue"]),l(u,{class:"mt-2",message:t(o).errors.link},null,8,["message"])]),e("div",null,[l(c,{for:"count",value:"Количество лайков"}),l(f,{id:"count",type:"text",class:"mt-1 block w-full",modelValue:t(o).count,"onUpdate:modelValue":r[1]||(r[1]=s=>t(o).count=s),required:""},null,8,["modelValue"]),l(u,{class:"mt-2",message:t(o).errors.count},null,8,["message"])]),e("div",null,[l(c,{for:"product_id",value:"Артикул"}),l(f,{id:"product_id",type:"text",class:"mt-1 block w-full",modelValue:t(o).product_id,"onUpdate:modelValue":r[2]||(r[2]=s=>t(o).product_id=s)},null,8,["modelValue"]),l(u,{class:"mt-2",message:t(o).errors.product_id},null,8,["message"])]),e("div",null,[l(c,{for:"keywords",value:"Ключевой запрос"}),l(f,{id:"keywords",type:"text",class:"mt-1 block w-full",modelValue:t(o).keywords,"onUpdate:modelValue":r[3]||(r[3]=s=>t(o).keywords=s)},null,8,["modelValue"]),l(u,{class:"mt-2",message:t(o).errors.keywords},null,8,["message"])]),e("div",null,[l(c,{for:"period",value:"Период"}),k(e("select",{class:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full",ref:"select",id:"period","onUpdate:modelValue":r[4]||(r[4]=s=>t(o).period=s)},Q,512),[[U,t(o).period]]),l(u,{class:"mt-2",message:t(o).errors.period},null,8,["message"])]),e("div",R,[l(j,{disabled:t(o).processing},{default:_(()=>[x("Отправить")]),_:1},8,["disabled"]),l(B,{"enter-from-class":"opacity-0","leave-to-class":"opacity-0",class:"transition ease-in-out"},{default:_(()=>[t(o).recentlySuccessful?(a(),n("p",W,d(i.result),1)):T("",!0)]),_:1})])],32)]),(a(!0),n(y,null,q(p.tasks,s=>(a(),n("div",{key:s,class:"bg-white shadow-sm sm:rounded-lg p-6 text-gray-900"},[e("div",X,[e("span",Y,d(s.link),1),e("span",ee,d(s.progress)+" / "+d(s.count),1),s.progress==s.count?(a(),n("span",se,"Завершено")):(a(),n("span",te,"В процессе"))])]))),128))])])]),_:1})],64))}};export{ge as default};
