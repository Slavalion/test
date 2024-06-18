import{d as H,r as O,e as V,f as W,o as u,g as C,w as t,b as l,h as e,a as s,t as c,u as _,c as v,F as z,i as E,O as P,Q as L,Z as Q,n as b}from"./app-632bf8b8.js";import{d as Z}from"./dayjs.min-356b711c.js";import{b as w}from"./Modal-1349d4bc.js";import{_ as h}from"./AppButton-ffc821ac.js";import{A as Y,T as f}from"./TableTh-6fdd4a53.js";import{E as G}from"./EmptyState-d0b40849.js";import{_ as g}from"./LabelText-36465a88.js";import{_ as J}from"./ProgressBar-77013aff.js";import{c as q,W as K,a as T,f as R,b as X,_ as ee}from"./AuthenticatedLayout-6b95fe5a.js";import{u as se}from"./useAxios-d55f63a8.js";import{_ as te}from"./SelectInput-944e57c4.js";import{_ as j}from"./TextInput-7865c9b5.js";import"./index-4cd942bc.js";import"./index-81635579.js";import"./CheckboxInput-967d96fa.js";const le={class:"flex items-center"},ae=e("div",null,"Укажите запрос по которому будет искаться товар",-1),oe={class:"ml-auto flex gap-3"},re={class:"flex items-center gap-1 w-80"},de={class:"product__image"},ne=["src"],ce={class:"product__info"},ie={class:"product__info-title"},ue={class:"product__info-price"},_e={class:"flex justify-center"},me={class:"flex justify-center"},pe={class:"flex items-center gap-1"},fe=e("div",{class:"w-10"},null,-1),B=5,he={__name:"ActionSearchModal",setup(k){const x=H(),m=se(),{loading:y}=m,d=O([]),n=V(""),o=()=>{let r=!0;return d.forEach(i=>{i.keywords?i.errors.keywords=null:(i.errors.keywords="Укажите запрос",r=!1)}),r},$=V(!1),F=()=>{m.post(route("search.do"),{product_code:n.value}).then(r=>{$.value=!0,d.push({product_code:"",view_time:1,period:{key:3,name:"3 часа"},quantity:1,keywords:"",product_code:r.data.product.remote_id,product:r.data.product,errors:{}})}).catch(r=>{})},I=function(){o()&&m.post(route("actions.search.store"),{actions:d,product_code:n.value}).then(()=>{P.reload(),d.splice(0,d.length),$.value=!1,w.close(),x.success("Задача успешно добавлена!")}).catch(r=>{x.error(r.response.data.message)})},M=r=>{d.splice(r,1)},N=r=>{d.push({...d[r]})},D=W(()=>d.reduce((r,i)=>r+i.quantity*B,0));return(r,i)=>(u(),C(X,{"header-class":"modal__header_noborder modal__header_pbsm","body-class":"modal__body_nopadding-top","wrapper-class":"modal__wrapper_slide-review",show:_(w).show,onClose:i[1]||(i[1]=a=>_(w).close())},{header:t(()=>[l(" Поиск по запросу ")]),caption:t(()=>[e("div",le,[ae,e("div",oe,[s(g,{theme:"info"},{default:t(()=>[l("Цена: "+c(B)+"₽/штука")]),_:1}),s(g,null,{default:t(()=>[l("Всего: "+c(d.length),1)]),_:1}),s(g,null,{default:t(()=>[l("К оплате: "+c(_(q).format(D.value)),1)]),_:1})])])]),search:t(()=>[s(j,{modelValue:n.value,"onUpdate:modelValue":i[0]||(i[0]=a=>n.value=a),placeholder:"Введите артикул","wrapper-class":"grow",size:"lg",ref:"searchInput",disabled:$.value},null,8,["modelValue","disabled"]),s(h,{size:"lg",disabled:n.value=="",onClick:F},{default:t(()=>[l(" Добавить ")]),_:1},8,["disabled"])]),actions:t(()=>[s(h,{size:"lg",disable:_(y),onClick:I},{default:t(()=>[l("Отправить")]),_:1},8,["disable"])]),default:t(()=>[s(Y,null,{head:t(()=>[e("tr",null,[s(f,{class:"text-left"},{default:t(()=>[l("Товар")]),_:1}),s(f,{class:"text-left"},{default:t(()=>[l("Ключевой запрос")]),_:1}),s(f,{class:"text-center"},{default:t(()=>[l("Время просмотра")]),_:1}),s(f,{class:"text-center"},{default:t(()=>[l("Количество")]),_:1}),s(f,{class:"text-left"},{default:t(()=>[l("Период")]),_:1})])]),default:t(()=>[(u(!0),v(z,null,E(d,(a,S)=>{var U,A;return u(),v("tr",{key:a.id,class:"main-table__tr"},[e("td",null,[e("div",re,[e("div",de,[e("img",{src:_(K).constructHostV2(a.product.remote_id)+"/images/tm/1.webp",alt:"",width:"30",height:"40"},null,8,ne)]),e("div",ce,[e("div",ie,c(a.product.name),1),e("div",ue,c(_(q).format(a.product.price)),1)])])]),e("td",null,[s(j,{modelValue:a.keywords,"onUpdate:modelValue":p=>a.keywords=p,size:"sm","has-error":((U=a.errors)==null?void 0:U.keywords)!=null,"error-message":(A=a.errors)==null?void 0:A.keywords},null,8,["modelValue","onUpdate:modelValue","has-error","error-message"])]),e("td",null,[e("div",_e,[s(T,{modelValue:a.view_time,"onUpdate:modelValue":p=>a.view_time=p,min:1},null,8,["modelValue","onUpdate:modelValue"])])]),e("td",null,[e("div",me,[s(T,{modelValue:a.quantity,"onUpdate:modelValue":p=>a.quantity=p,min:1},null,8,["modelValue","onUpdate:modelValue"])])]),e("td",null,[e("div",pe,[s(te,{modelValue:a.period,"onUpdate:modelValue":p=>a.period=p,items:_(R),size:"md"},null,8,["modelValue","onUpdate:modelValue","items"]),fe,s(h,{theme:"normal",size:"sm",icon:"copy",onClick:p=>N(S)},null,8,["onClick"]),s(h,{theme:"normal",size:"sm",icon:"delete",onClick:p=>M(S)},null,8,["onClick"])])])])}),128))]),_:1})]),_:1},8,["show"]))}},ve=e("title",null,"Поиск по запросу",-1),ye={class:"panel mb-6"},ge={class:"flex gap-1.5"},ke={class:"ml-auto flex gap-3"},xe={key:0,class:"panel panel_product"},we={class:"text-xs text-gray-400"},$e={class:"pr-6"},be={class:"w-[234px]"},Ve={class:"pl-6 flex justify-end"},Ce={key:1,class:"panel flex flex-col grow"},ze=e("div",{class:"header-5 mb-1.5"},"Задач пока нет",-1),He={__name:"ActionSearch",props:{actions:{type:Array,required:!0},section:{type:String,required:!0}},setup(k){const x=k;L();const m=V(x.section),y=d=>{m.value=d,P.reload({only:["actions"],data:{section:m.value}})};return(d,n)=>(u(),v(z,null,[s(_(Q),null,{default:t(()=>[ve]),_:1}),s(ee,null,{header:t(()=>[l("Поиск по запросу")]),default:t(()=>[e("div",ye,[e("div",ge,[s(h,{theme:"normal",class:b({btn_selected:m.value=="process"}),onClick:n[0]||(n[0]=o=>y("process"))},{default:t(()=>[l(" В процессе ")]),_:1},8,["class"]),s(h,{theme:"normal",class:b({btn_selected:m.value=="done"}),onClick:n[1]||(n[1]=o=>y("done"))},{default:t(()=>[l(" Выполненные ")]),_:1},8,["class"]),s(h,{theme:"normal",class:b({btn_selected:m.value=="all"}),onClick:n[2]||(n[2]=o=>y("all"))},{default:t(()=>[l(" Все ")]),_:1},8,["class"]),e("div",ke,[s(h,{size:"md",onClick:n[3]||(n[3]=o=>_(w).open())},{default:t(()=>[l(" Добавить ")]),_:1})])])]),k.actions.length?(u(),v("div",xe,[s(Y,null,{head:t(()=>[e("tr",null,[s(f,{class:"text-left"},{default:t(()=>[l("Товар")]),_:1}),s(f,{class:"text-left"},{default:t(()=>[l("Артикул")]),_:1}),s(f,{class:"text-left"},{default:t(()=>[l("Прогресс")]),_:1}),s(f,{class:"text-left"})])]),default:t(()=>[(u(!0),v(z,null,E(k.actions,o=>(u(),v("tr",{key:o.id,class:"main-table__tr"},[e("td",null,[e("div",null,c(o.product.name),1),e("div",we," #"+c(o.id)+" от "+c(_(Z)(o.created_at).format("D/M/YYYY h:m")),1)]),e("td",null,[e("div",$e,c(o.product_id),1)]),e("td",null,[e("div",be,[e("div",null,c(o.progress)+" из "+c(o.total),1),s(J,{progress:o.progress/o.total*100},null,8,["progress"])])]),e("td",null,[e("div",Ve,[o.status==1?(u(),C(g,{key:0,theme:"info"},{default:t(()=>[l("В процессе")]),_:1})):(u(),C(g,{key:1,theme:"success"},{default:t(()=>[l("Завершено")]),_:1}))])])]))),128))]),_:1})])):(u(),v("div",Ce,[s(G,{class:"grow"},{default:t(()=>[ze]),_:1})]))]),_:1}),s(he)],64))}};export{He as default};