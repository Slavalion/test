import{f as C,e as D,o as d,g as E,w as l,h as e,t as u,u as p,a,b as i,n as w,c,F as L,i as F,y as M,z as O,O as Q,d as j,Q as W,a5 as X,Z as ee}from"./app-632bf8b8.js";import{d as v}from"./dayjs.min-356b711c.js";import{u as G}from"./useAxios-d55f63a8.js";import{m as z}from"./Modal-1349d4bc.js";import{w as T,a as Y}from"./wbReviews-debf3e9c.js";import{c as te,f as se,a as q,b as ae,_ as le}from"./AuthenticatedLayout-6b95fe5a.js";import{_ as y,b as H}from"./AppButton-ffc821ac.js";import{E as oe}from"./EmptyState-d0b40849.js";import{_ as J}from"./TextInput-7865c9b5.js";import{A as K,T as g}from"./TableTh-6fdd4a53.js";import{_ as Z}from"./SelectInput-944e57c4.js";import{_ as b}from"./LabelText-36465a88.js";import{_ as ne}from"./ProgressBar-77013aff.js";import"./index-4cd942bc.js";import"./index-81635579.js";import"./CheckboxInput-967d96fa.js";const ie={class:"flex items-center"},de=e("div",null,"Укажите необходимое количество лайков и дизлайков к каждому отзыву.",-1),re={class:"ml-auto flex gap-3"},ce={class:"mt-6 flex gap-4"},ue={class:"ml-auto flex gap-2"},_e={key:0,class:"empty-state"},me=e("div",{class:"empty-state__image"},[e("img",{src:"/images/Search.svg",alt:"Нет отзывов",width:"250",height:"200"})],-1),pe=e("div",{class:"empty-state__info"},[e("div",{class:"empty-state__title"},"Нет отзывов"),e("ul",{class:"empty-state__list"},[e("li",null,"Отзывы для данного товара не найдены")])],-1),fe=[me,pe],ve={key:1},he={class:"align-top"},ke={class:"flex"},xe={class:"w-[256px]"},ye={class:"font-bold mb-2"},ge={key:0},be={key:1},we={class:"flex gap-2 mb-2"},Ve=e("path",{id:"Star 5",d:"M18.1534 1.43986C18.8365 -0.20273 21.1635 -0.202727 21.8466 1.43986L25.879 11.1347C26.167 11.8272 26.8182 12.3003 27.5658 12.3602L38.0322 13.1993C39.8055 13.3415 40.5245 15.5545 39.1734 16.7118L31.1992 23.5427C30.6296 24.0306 30.3808 24.7961 30.5549 25.5256L32.9911 35.7391C33.4039 37.4695 31.5214 38.8372 30.0032 37.9099L21.0425 32.4368C20.4025 32.0458 19.5975 32.0458 18.9575 32.4368L9.9968 37.9099C8.4786 38.8372 6.5961 37.4695 7.00887 35.7391L9.44515 25.5256C9.61916 24.7961 9.37042 24.0306 8.80084 23.5427L0.826553 16.7118C-0.52452 15.5545 0.194535 13.3415 1.96784 13.1993L12.4342 12.3602C13.1818 12.3003 13.833 11.8272 14.121 11.1347L18.1534 1.43986Z",fill:"#E7EAF0"},null,-1),$e=[Ve],Ce={class:"w-[420px]"},De={class:"flex"},Le={class:"w-[160px]"},Ue={class:"pl-8 w-[236px]"},Re={class:"flex items-cente relative mb-3"},Se={class:"flex items-center pr-3"},ze=e("span",{class:"w-[64px]"},"Лайки",-1),Fe={class:"absolute top-0 left-0 w-full h-full bg-white opacity-80"},Te={class:"flex items-center relative"},Ye={class:"flex items-center pr-3"},Be=e("span",{class:"w-[64px]"},"Дизлайки",-1),Ae={class:"absolute top-0 left-0 w-full h-full bg-white opacity-80"},I=8,Ee={__name:"ReviewReactionSlide",setup(U){const R=G(),{loading:B}=R,h=[1,2,3,4,5],_=C(()=>Y.value.filter(o=>o.total_likes>0||o.total_dislikes>0)),A=C(()=>_.value.reduce((o,t)=>o+t.total_likes,0)),V=C(()=>_.value.reduce((o,t)=>o+t.total_dislikes,0)),k=C(()=>_.value.reduce((o,t)=>o+t.total_likes*I+t.total_dislikes*I,0)),r=()=>{const o=_.value.map(t=>{const s=t.total_likes>0?"like":"dislike";return{id:t.id,period:t.period.key,type:s,total:s=="like"?t.total_likes:t.total_dislikes}});R.post(route("review-reactions.store"),{product_code:T.value.id,reactions:o}).then(t=>{z.close(),Q.reload(),j().success("Задачи успешно добавлены!")}).catch(t=>{j().error("Что-то пошло не так!")})},n=()=>{},S=[{key:"date",name:"По дате"},{key:"valuation",name:"По оценке"},{key:"rank",name:"По полезности"}],x=D(""),$=D({key:"rank",name:"По полезности"}),m=D("asc"),N=C(()=>{let o=Y.value;return $.value.key=="valuation"&&(o=o.sort((t,s)=>t.productValuation<s.productValuation?m.value=="asc"?-1:1:t.productValuation>s.productValuation?m.value=="asc"?1:-1:0)),$.value.key=="date"&&(o=o.sort((t,s)=>{if(v(t.createdDate).isSame(v(s.createdDate)))return 0;if(v(t.createdDate).isBefore(v(s.createdDate)))return m.value=="asc"?-1:1;if(v(t.createdDate).isAfter(v(s.createdDate)))return m.value=="asc"?1:-1})),$.value.key=="rank"&&(o=o.sort((t,s)=>t.rank<s.rank?m.value=="asc"?1:-1:t.rank>s.rank?m.value=="asc"?-1:1:0)),x.value==""?o:o.filter(t=>!!t.text.toLowerCase().match(x.value.toLowerCase()))}),P=o=>{m.value=o};return(o,t)=>(d(),E(ae,{"header-class":"modal__header_noborder modal__header_pbsm","body-class":"modal__body_nopadding-top","wrapper-class":"modal__wrapper_slide-review",show:p(z).show,onClose:t[4]||(t[4]=s=>p(z).close()),onOpen:n},{header:l(()=>[e("span",null,'Отзывы к "'+u(p(T).name)+'"',1)]),caption:l(()=>[e("div",ie,[de,e("div",re,[a(b,{theme:"info"},{default:l(()=>[i("Цена: 8₽/штука")]),_:1}),a(b,null,{default:l(()=>[i("Лайков: "+u(A.value),1)]),_:1}),a(b,null,{default:l(()=>[i("Дизлайков: "+u(V.value),1)]),_:1}),a(b,null,{default:l(()=>[i("К оплате: "+u(p(te).format(k.value)),1)]),_:1})])]),e("div",ce,[e("div",null,[a(J,{modelValue:x.value,"onUpdate:modelValue":t[0]||(t[0]=s=>x.value=s),size:"md",icon:"search"},null,8,["modelValue"])]),e("div",ue,[a(Z,{modelValue:$.value,"onUpdate:modelValue":t[1]||(t[1]=s=>$.value=s),items:S,size:"md"},null,8,["modelValue"]),a(y,{theme:"normal",icon:"sort",class:w({btn_selected:m.value=="asc","rotate-180":!0}),onClick:t[2]||(t[2]=s=>P("asc"))},null,8,["class"]),a(y,{theme:"normal",icon:"sort",class:w({btn_selected:m.value=="desc"}),onClick:t[3]||(t[3]=s=>P("desc"))},null,8,["class"])])])]),actions:l(()=>[a(y,{size:"lg",disabled:_.value.length==0||p(B),onClick:r},{default:l(()=>[i(" Добавить ")]),_:1},8,["disabled"])]),default:l(()=>[N.value.length==0?(d(),c("div",_e,fe)):(d(),c("div",ve,[a(K,null,{head:l(()=>[e("tr",null,[a(g,{class:"text-left"},{default:l(()=>[i("Отзыв")]),_:1}),a(g,{class:"text-left"},{default:l(()=>[i("Период добавления")]),_:1})])]),default:l(()=>[(d(!0),c(L,null,F(N.value,s=>(d(),c("tr",{key:s.id,class:"main-table__tr"},[e("td",he,[e("div",ke,[e("div",xe,[e("div",ye,[s.wbUserDetails.name?(d(),c("span",ge,u(s.wbUserDetails.name),1)):(d(),c("span",be," Имя не указано "))]),e("div",we,[(d(),c(L,null,F(h,f=>e("svg",{key:f,class:w([{"rating-input__star_active":s.productValuation>=f},"rating-input__star w-4 h-4"]),width:"40",height:"39",viewBox:"0 0 40 39",fill:"none",xmlns:"http://www.w3.org/2000/svg"},$e,2)),64))]),e("div",null,u(p(v)(s.createdDate).locale("ru").format("DD/MM/YYYY HH:mm")),1)]),e("div",Ce,u(s.text),1)])]),e("td",null,[e("div",De,[e("div",Le,[a(Z,{modelValue:s.period,"onUpdate:modelValue":f=>s.period=f,items:p(se),size:"md"},null,8,["modelValue","onUpdate:modelValue","items"])]),e("div",Ue,[e("div",Re,[e("div",Se,[a(H,{icon:"like",class:"w-4 h-4 mr-2"}),ze]),a(q,{modelValue:s.total_likes,"onUpdate:modelValue":f=>s.total_likes=f,size:"md"},null,8,["modelValue","onUpdate:modelValue"]),M(e("div",Fe,null,512),[[O,s.total_dislikes>0]])]),e("div",Te,[e("div",Ye,[a(H,{icon:"dislike",class:"w-4 h-4 mr-2"}),Be]),a(q,{modelValue:s.total_dislikes,"onUpdate:modelValue":f=>s.total_dislikes=f,size:"md"},null,8,["modelValue","onUpdate:modelValue"]),M(e("div",Ae,null,512),[[O,s.total_likes>0]])])])])])]))),128))]),_:1})]))]),_:1},8,["show"]))}},Ne=e("title",null,"Реакции на отзывы",-1),Pe={class:"panel mb-6"},Me={class:"flex gap-1.5"},Oe={class:"ml-auto flex gap-3"},je={key:0,class:"panel panel_product"},qe={class:"text-xs text-gray-400"},He={key:0},Ze={class:"flex gap-1 flex-wrap leading-none text-gray-300"},Ie={class:"pr-6"},Qe={class:"w-[234px]"},Ge={class:"pl-6 flex justify-end"},Je={key:1,class:"panel flex flex-col grow"},Ke=e("div",{class:"header-5 mb-1.5"},"Реакций на отзывы пока нет",-1),ft={__name:"ReviewReactions",props:{reviewReactions:{type:Array,required:!0},section:{type:String,required:!0}},setup(U){const R=U;W();const B=G(),h=D(R.section),_=D(""),A=()=>{B.post(route("review-reactions.search"),{product_code:_.value}).then(k=>{T.value=k.data.product,Y.value=k.data.reviews.map(r=>({id:r.id,wbUserDetails:{name:r.wbUserDetails.name},productValuation:r.productValuation,rank:r.rank,createdDate:r.createdDate,text:r.text,period:{key:3,name:"3 часа"},total_likes:0,total_dislikes:0})),z.open()})},V=k=>{h.value=k,Q.reload({only:["reviewReactions"],data:{section:h.value}})};return X(()=>{T.value=[],Y.value=[]}),(k,r)=>(d(),c(L,null,[a(p(ee),null,{default:l(()=>[Ne]),_:1}),a(le,null,{header:l(()=>[i("Реакции на отзывы")]),default:l(()=>[e("div",Pe,[e("div",Me,[a(y,{theme:"normal",class:w({btn_selected:h.value=="process"}),onClick:r[0]||(r[0]=n=>V("process"))},{default:l(()=>[i(" В процессе ")]),_:1},8,["class"]),a(y,{theme:"normal",class:w({btn_selected:h.value=="done"}),onClick:r[1]||(r[1]=n=>V("done"))},{default:l(()=>[i(" Выполненные ")]),_:1},8,["class"]),a(y,{theme:"normal",class:w({btn_selected:h.value=="all"}),onClick:r[2]||(r[2]=n=>V("all"))},{default:l(()=>[i(" Все ")]),_:1},8,["class"]),e("div",Oe,[a(J,{modelValue:_.value,"onUpdate:modelValue":r[3]||(r[3]=n=>_.value=n),size:"md",placeholder:"Артикул товара"},null,8,["modelValue"]),a(y,{size:"md",disabled:_.value=="",onClick:A},{default:l(()=>[i(" Добавить ")]),_:1},8,["disabled"])])])]),U.reviewReactions.length?(d(),c("div",je,[a(K,null,{head:l(()=>[e("tr",null,[a(g,{class:"text-left"},{default:l(()=>[i("Товар")]),_:1}),a(g,{class:"text-left"},{default:l(()=>[i("Артикул")]),_:1}),a(g,{class:"text-left"},{default:l(()=>[i("Лайки/Дизлайки")]),_:1}),a(g,{class:"text-left"})])]),default:l(()=>[(d(!0),c(L,null,F(U.reviewReactions,n=>{var S;return d(),c("tr",{key:n.id,class:"main-table__tr"},[e("td",null,[e("div",null,u((S=n.product)==null?void 0:S.name),1),e("div",qe," ID"+u(n.id)+" от "+u(p(v)(n.created_at).format("D/M/YYYY h:m")),1),(d(),c("div",He,[i(" Process: "),e("div",Ze,[(d(!0),c(L,null,F(n.review_reactions,x=>(d(),c("span",{key:x.task_id},u(x.task_id)+", ",1))),128))])]))]),e("td",null,[e("div",Ie,u(n.product_id),1)]),e("td",null,[e("div",Qe,[e("div",null,u(n.progress)+" из "+u(n.total),1),a(ne,{progress:n.progress/n.total*100},null,8,["progress"])])]),e("td",null,[e("div",Ge,[n.status==1?(d(),E(b,{key:0,theme:"info"},{default:l(()=>[i("В процессе")]),_:1})):(d(),E(b,{key:1,theme:"success"},{default:l(()=>[i("Завершено")]),_:1}))])])])}),128))]),_:1})])):(d(),c("div",Je,[a(oe,{class:"grow"},{default:l(()=>[Ke]),_:1})])),a(Ee)]),_:1})],64))}};export{ft as default};
