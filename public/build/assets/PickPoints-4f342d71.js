import{o as s,c as o,a as c,w as i,u as p,Z as m,b as r,t as a,h as e,F as l,i as u,g as _}from"./app-632bf8b8.js";import{_ as d}from"./LabelText-36465a88.js";import{_ as f}from"./AuthenticatedLayout-6b95fe5a.js";import"./AppButton-ffc821ac.js";import"./index-81635579.js";import"./index-4cd942bc.js";import"./Modal-1349d4bc.js";import"./useAxios-d55f63a8.js";import"./TextInput-7865c9b5.js";import"./dayjs.min-356b711c.js";import"./SelectInput-944e57c4.js";import"./CheckboxInput-967d96fa.js";const h=e("title",null,"Адреса забора",-1),v={class:"panel panel_p-lg"},y={class:"divide-y"},k={class:"leading-none flex pb-1"},g={class:"w-12"},x={class:"w-8"},B={class:""},b={class:"ml-auto"},L={__name:"PickPoints",props:{zone:{type:Object,required:!0},addresses:{type:Array,required:!0}},setup(n){return(w,N)=>(s(),o(l,null,[c(p(m),null,{default:i(()=>[h]),_:1}),c(f,null,{header:i(()=>[r(a(n.zone.name),1)]),default:i(()=>[e("div",v,[e("div",y,[(s(!0),o(l,null,u(n.addresses,t=>(s(),o("div",{key:t.id,class:"flex items-center py-4 first:pt-0 last:pb-0 gap-6"},[e("div",k,[e("div",g,"#"+a(t.id),1),e("div",x,a(t.sort),1),e("div",B,a(t.address),1)]),e("div",b,[t.wb_pickpoint_exist?(s(),_(d,{key:0,theme:"success"},{default:i(()=>[r(" WB адрес найден ")]),_:1})):(s(),_(d,{key:1,theme:"danger"},{default:i(()=>[r("WB адрес не найден")]),_:1}))])]))),128))])])]),_:1})],64))}};export{L as default};