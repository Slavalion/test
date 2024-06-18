import{f as S,e as $,o as d,g as N,w as l,h as e,t as u,u as _,a as s,b as i,n as b,c,F as w,i as T,O as R,d as M,Q as Z,a5 as I,Z as Q}from"./app-632bf8b8.js";import{d as h}from"./dayjs.min-356b711c.js";import{u as j}from"./useAxios-d55f63a8.js";import{r as z}from"./Modal-1349d4bc.js";import{w as F,a as Y}from"./wbReviews-debf3e9c.js";import{_ as x}from"./AppButton-ffc821ac.js";import{A as H,T as C}from"./TableTh-6fdd4a53.js";import{E as G}from"./EmptyState-d0b40849.js";import{_ as q}from"./TextInput-7865c9b5.js";import{_ as D}from"./LabelText-36465a88.js";import{_ as J}from"./ProgressBar-77013aff.js";import{c as K,f as W,e as X,a as ee,b as te,_ as ae}from"./AuthenticatedLayout-6b95fe5a.js";import{_ as E}from"./SelectInput-944e57c4.js";import"./index-4cd942bc.js";import"./index-81635579.js";import"./CheckboxInput-967d96fa.js";const se={class:"flex items-center"},le=e("div",null,"Укажите необходимое количество лайков и дизлайков к каждому отзыву.",-1),oe={class:"ml-auto flex gap-3"},ne={class:"mt-6 flex gap-4"},de={class:"ml-auto flex gap-2"},re={key:0,class:"empty-state"},ie=e("div",{class:"empty-state__image"},[e("img",{src:"/images/Search.svg",alt:"Нет отзывов",width:"250",height:"200"})],-1),ce=e("div",{class:"empty-state__info"},[e("div",{class:"empty-state__title"},"Нет отзывов"),e("ul",{class:"empty-state__list"},[e("li",null,"Отзывы для данного товара не найдены")])],-1),ue=[ie,ce],me={key:1},_e={class:"align-top"},pe={class:"flex"},fe={class:"w-[256px] pr-4"},ve={class:"font-bold mb-2"},he={key:0},ye={key:1},ke={class:"flex gap-2 mb-2"},ge=e("path",{id:"Star 5",d:"M18.1534 1.43986C18.8365 -0.20273 21.1635 -0.202727 21.8466 1.43986L25.879 11.1347C26.167 11.8272 26.8182 12.3003 27.5658 12.3602L38.0322 13.1993C39.8055 13.3415 40.5245 15.5545 39.1734 16.7118L31.1992 23.5427C30.6296 24.0306 30.3808 24.7961 30.5549 25.5256L32.9911 35.7391C33.4039 37.4695 31.5214 38.8372 30.0032 37.9099L21.0425 32.4368C20.4025 32.0458 19.5975 32.0458 18.9575 32.4368L9.9968 37.9099C8.4786 38.8372 6.5961 37.4695 7.00887 35.7391L9.44515 25.5256C9.61916 24.7961 9.37042 24.0306 8.80084 23.5427L0.826553 16.7118C-0.52452 15.5545 0.194535 13.3415 1.96784 13.1993L12.4342 12.3602C13.1818 12.3003 13.833 11.8272 14.121 11.1347L18.1534 1.43986Z",fill:"#E7EAF0"},null,-1),xe=[ge],Ce={class:"w-[420px]"},be={class:"flex"},Ve={class:"w-[100px]"},$e={class:"pl-8 w-[336px]"},we={class:"flex items-cente relative mb-3"},De=30,Ue={__name:"ReviewComplaintSlide",setup(U){const L=j(),{loading:B}=L,y=[1,2,3,4,5],p=S(()=>Y.value.filter(o=>o.total>0)),A=S(()=>p.value.reduce((o,t)=>o+t.total,0)),V=S(()=>p.value.reduce((o,t)=>o+t.total*De,0)),k=()=>{const o=p.value.map(t=>({id:t.id,period:t.period.key,type:t.type.key,total:t.total}));L.post(route("review-complaints.store"),{product_code:F.value.id,complaints:o,sortType:f.value.key,sortOrder:m.value}).then(t=>{z.close(),R.reload(),M().success("Задачи успешно добавлены!")}).catch(t=>{M().error("Что-то пошло не так!")})},r=()=>{},n=[{key:"date",name:"По дате"},{key:"valuation",name:"По оценке"},{key:"rank",name:"По полезности"}],g=$(""),f=$({key:"rank",name:"По полезности"}),m=$("asc"),O=S(()=>{let o=Y.value;return f.value.key=="valuation"&&(o=o.sort((t,a)=>t.productValuation<a.productValuation?m.value=="asc"?-1:1:t.productValuation>a.productValuation?m.value=="asc"?1:-1:0)),f.value.key=="date"&&(o=o.sort((t,a)=>{if(h(t.createdDate).isSame(h(a.createdDate)))return 0;if(h(t.createdDate).isBefore(h(a.createdDate)))return m.value=="asc"?-1:1;if(h(t.createdDate).isAfter(h(a.createdDate)))return m.value=="asc"?1:-1})),f.value.key=="rank"&&(o=o.sort((t,a)=>t.rank<a.rank?m.value=="asc"?1:-1:t.rank>a.rank?m.value=="asc"?-1:1:0)),g.value==""?o:o.filter(t=>!!t.text.toLowerCase().match(g.value.toLowerCase()))}),P=o=>{m.value=o};return(o,t)=>(d(),N(te,{"header-class":"modal__header_noborder modal__header_pbsm","body-class":"modal__body_nopadding-top","wrapper-class":"modal__wrapper_slide-review",show:_(z).show,onClose:t[4]||(t[4]=a=>_(z).close()),onOpen:r},{header:l(()=>[e("span",null,'Отзывы к "'+u(_(F).name)+'"',1)]),caption:l(()=>[e("div",se,[le,e("div",oe,[s(D,{theme:"info"},{default:l(()=>[i("Цена: 30₽/штука")]),_:1}),s(D,null,{default:l(()=>[i("Жалоб: "+u(A.value),1)]),_:1}),s(D,null,{default:l(()=>[i("К оплате: "+u(_(K).format(V.value)),1)]),_:1})])]),e("div",ne,[e("div",null,[s(q,{modelValue:g.value,"onUpdate:modelValue":t[0]||(t[0]=a=>g.value=a),size:"md",icon:"search"},null,8,["modelValue"])]),e("div",de,[s(E,{modelValue:f.value,"onUpdate:modelValue":t[1]||(t[1]=a=>f.value=a),items:n,size:"md"},null,8,["modelValue"]),s(x,{theme:"normal",icon:"sort",class:b({btn_selected:m.value=="asc","rotate-180":!0}),onClick:t[2]||(t[2]=a=>P("asc"))},null,8,["class"]),s(x,{theme:"normal",icon:"sort",class:b({btn_selected:m.value=="desc"}),onClick:t[3]||(t[3]=a=>P("desc"))},null,8,["class"])])])]),actions:l(()=>[s(x,{size:"lg",disabled:p.value.length==0||_(B),onClick:k},{default:l(()=>[i(" Добавить ")]),_:1},8,["disabled"])]),default:l(()=>[O.value.length==0?(d(),c("div",re,ue)):(d(),c("div",me,[s(H,null,{head:l(()=>[e("tr",null,[s(C,{class:"text-left"},{default:l(()=>[i("Отзыв")]),_:1}),s(C,{class:"text-left"},{default:l(()=>[i("Период добавления")]),_:1})])]),default:l(()=>[(d(!0),c(w,null,T(O.value,a=>(d(),c("tr",{key:a.id,class:"main-table__tr"},[e("td",_e,[e("div",pe,[e("div",fe,[e("div",ve,[a.wbUserDetails.name?(d(),c("span",he,u(a.wbUserDetails.name),1)):(d(),c("span",ye," Имя не указано "))]),e("div",ke,[(d(),c(w,null,T(y,v=>e("svg",{key:v,class:b([{"rating-input__star_active":a.productValuation>=v},"rating-input__star w-4 h-4"]),width:"40",height:"39",viewBox:"0 0 40 39",fill:"none",xmlns:"http://www.w3.org/2000/svg"},xe,2)),64))]),e("div",null,u(_(h)(a.createdDate).locale("ru").format("DD/MM/YYYY HH:mm")),1)]),e("div",Ce,u(a.text),1)])]),e("td",null,[e("div",be,[e("div",Ve,[s(E,{modelValue:a.period,"onUpdate:modelValue":v=>a.period=v,items:_(W),size:"md"},null,8,["modelValue","onUpdate:modelValue","items"])]),e("div",$e,[e("div",we,[s(E,{modelValue:a.type,"onUpdate:modelValue":v=>a.type=v,items:_(X),size:"md"},null,8,["modelValue","onUpdate:modelValue","items"]),s(ee,{modelValue:a.total,"onUpdate:modelValue":v=>a.total=v,size:"md"},null,8,["modelValue","onUpdate:modelValue"])])])])])]))),128))]),_:1})]))]),_:1},8,["show"]))}},Le=e("title",null,"Жалобы на отзывы",-1),Se={class:"panel mb-6"},ze={class:"flex gap-1.5"},Te={class:"ml-auto flex gap-3"},Fe={key:0,class:"panel panel_product"},Ye={class:"text-xs text-gray-400"},Be={key:0},Ae={class:"flex gap-1 flex-wrap leading-none text-gray-300"},Ee={class:"pr-6"},Ne={class:"w-[234px]"},Oe={class:"pl-6 flex justify-end"},Pe={key:1,class:"panel flex flex-col grow"},Me=e("div",{class:"header-5 mb-1.5"},"Реакций на отзывы пока нет",-1),ot={__name:"ReviewComplaints",props:{reviewComplaints:{type:Array,required:!0},section:{type:String,required:!0}},setup(U){const L=U;Z();const B=j(),y=$(L.section),p=$(""),A=()=>{B.post(route("review-reactions.search"),{product_code:p.value}).then(k=>{F.value=k.data.product,Y.value=k.data.reviews.map(r=>({id:r.id,wbUserDetails:{name:r.wbUserDetails.name},productValuation:r.productValuation,rank:r.rank,createdDate:r.createdDate,text:r.text,period:{key:3,name:"3 часа"},type:{key:"obscene_language",name:"Нецензурная лексика"},total:0})),z.open()})},V=k=>{y.value=k,R.reload({only:["reviewComplaints"],data:{section:y.value}})};return I(()=>{F.value=[],Y.value=[]}),(k,r)=>(d(),c(w,null,[s(_(Q),null,{default:l(()=>[Le]),_:1}),s(ae,null,{header:l(()=>[i("Жалобы на отзывы")]),default:l(()=>[e("div",Se,[e("div",ze,[s(x,{theme:"normal",class:b({btn_selected:y.value=="process"}),onClick:r[0]||(r[0]=n=>V("process"))},{default:l(()=>[i(" В процессе ")]),_:1},8,["class"]),s(x,{theme:"normal",class:b({btn_selected:y.value=="done"}),onClick:r[1]||(r[1]=n=>V("done"))},{default:l(()=>[i(" Выполненные ")]),_:1},8,["class"]),s(x,{theme:"normal",class:b({btn_selected:y.value=="all"}),onClick:r[2]||(r[2]=n=>V("all"))},{default:l(()=>[i(" Все ")]),_:1},8,["class"]),e("div",Te,[s(q,{modelValue:p.value,"onUpdate:modelValue":r[3]||(r[3]=n=>p.value=n),size:"md",placeholder:"Артикул товара"},null,8,["modelValue"]),s(x,{size:"md",disabled:p.value=="",onClick:A},{default:l(()=>[i(" Добавить ")]),_:1},8,["disabled"])])])]),U.reviewComplaints.length?(d(),c("div",Fe,[s(H,null,{head:l(()=>[e("tr",null,[s(C,{class:"text-left"},{default:l(()=>[i("Товар")]),_:1}),s(C,{class:"text-left"},{default:l(()=>[i("Артикул")]),_:1}),s(C,{class:"text-left"},{default:l(()=>[i("Жалобы")]),_:1}),s(C,{class:"text-left"})])]),default:l(()=>[(d(!0),c(w,null,T(U.reviewComplaints,n=>{var g;return d(),c("tr",{key:n.id,class:"main-table__tr"},[e("td",null,[e("div",null,u((g=n.product)==null?void 0:g.name),1),e("div",Ye," ID"+u(n.id)+" от "+u(_(h)(n.created_at).format("D/M/YYYY H:m")),1),(d(),c("div",Be,[i(" Process: "),e("div",Ae,[(d(!0),c(w,null,T(n.review_complaints,f=>(d(),c("span",{key:f.task_id},u(f.task_id)+", ",1))),128))])]))]),e("td",null,[e("div",Ee,u(n.product_id),1)]),e("td",null,[e("div",Ne,[e("div",null,u(n.progress)+" из "+u(n.total),1),s(J,{progress:n.progress/n.total*100},null,8,["progress"])])]),e("td",null,[e("div",Oe,[n.status==1?(d(),N(D,{key:0,theme:"info"},{default:l(()=>[i("В процессе")]),_:1})):(d(),N(D,{key:1,theme:"success"},{default:l(()=>[i("Завершено")]),_:1}))])])])}),128))]),_:1})])):(d(),c("div",Pe,[s(G,{class:"grow"},{default:l(()=>[Me]),_:1})])),s(Ue)]),_:1})],64))}};export{ot as default};
