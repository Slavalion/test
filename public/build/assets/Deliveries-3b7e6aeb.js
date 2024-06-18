import{e as V,l as B,o as s,g as h,E as R,w as c,h as e,t as o,c as d,j as m,a as p,b as _,u as w,Q as T,r as Z,d as G,x as J,O as U,D as K,Z as X,F as g,i as j,n as N}from"./app-632bf8b8.js";import{_ as S,b as Y}from"./AppButton-ffc821ac.js";import{W as A,c as ee,d as te,_ as se}from"./AuthenticatedLayout-6b95fe5a.js";import{u as I,_ as oe}from"./Modal-1349d4bc.js";import{_ as ae}from"./TextInput-7865c9b5.js";import{d as F}from"./index-81635579.js";import{a as ie}from"./index-4cd942bc.js";import{_ as de}from"./AppPagination-44a644fe.js";import{E as le}from"./EmptyState-d0b40849.js";import{_ as D}from"./LabelText-36465a88.js";import{u as H}from"./useAxios-d55f63a8.js";import{_ as re}from"./ModalMobileTariffs-3123fdf5.js";import"./dayjs.min-356b711c.js";import"./SelectInput-944e57c4.js";import"./CheckboxInput-967d96fa.js";const ne={class:"qrcode-modal__header"},ce={class:"truncate"},ue=["href"],_e={key:0},pe={key:1},me={class:"flex"},ve={key:0,class:"qrcode-modal__content"},he=["src"],fe={__name:"UpdateDeliveryModal",props:{modelValue:{type:Object,default:()=>{}}},emits:["update:modelValue"],setup(a,{emit:$}){const v=a,q=$,z=H(),r={processing:"Оформлен",sorted:"Отсортирован",pending:"В работе",on_the_way:"В пути",available_for_pick_up:"Готов к выдаче"},u=V(!!v.modelValue.is_updating_delivery);B(()=>v.modelValue,l=>{u.value=!!l.is_updating_delivery}),B(()=>v.modelValue.is_updating_delivery,l=>{u.value=!!l});const f=function(){u.value=!0,z.post(route("deliveries.update-data"),{purchase_id:v.modelValue.id}).then(l=>{q("update:modelValue",l.data.delivery)}).catch(()=>{u.value=!1})};return(l,k)=>(s(),h(oe,{show:w(I).show,loading:u.value,onClose:k[0]||(k[0]=E=>w(I).close()),"wrapper-class":"modal__wrapper_w-480"},R({header:c(()=>[_("QR-код для выдачи")]),actions:c(()=>[p(S,{size:"lg",icon:"refresh",onClick:f},{default:c(()=>[_("Обновить код")]),_:1})]),default:c(()=>[a.modelValue?(s(),d("div",ve,[e("img",{src:"/storage/"+a.modelValue.delivery_qrcode,alt:""},null,8,he),e("span",null,o(a.modelValue.delivery_pin.split("").join(" ")),1)])):m("",!0)]),_:2},[a.modelValue?{name:"caption",fn:c(()=>[e("div",ne,[e("div",ce,o(a.modelValue.product.name),1),e("div",null,[e("a",{href:"https://www.wildberries.ru/catalog/"+a.modelValue.product_id+"/detail.aspx",target:"_blank",class:"inline-link"},o(a.modelValue.product_id),9,ue)]),e("div",null,o(a.modelValue.address),1),a.modelValue.delivery_name?(s(),d("div",_e,"Имя: "+o(a.modelValue.delivery_name),1)):m("",!0),a.modelValue.delivery_phone?(s(),d("div",pe,"Телефон: "+o(a.modelValue.delivery_phone),1)):m("",!0)]),e("div",me,[p(D,null,{default:c(()=>[_(o(r[a.modelValue.delivery_status]),1)]),_:1})])]),key:"0"}:void 0]),1032,["show","loading"]))}},ke=e("title",null,"Доставки",-1),ye={key:0,class:"input-wrapper"},ge={key:1,class:"panel mb-6"},we={class:"flex gap-1.5"},be={class:"ml-auto flex gap-3"},Ve={key:0,class:"products-header products-header_deliveries"},$e=e("div",{class:"products-header__product"},"Товар",-1),xe=e("div",{class:"products-header__code"},"Артикул",-1),Se=e("div",{class:"products-header__quantity"},"Кол-во",-1),qe=e("div",{class:"products-header__gender"},"Пол",-1),ze=e("div",{class:"products-header__size"},"Размер",-1),Ce=e("div",{class:"products-header__keywords"},"Поисковой запрос",-1),De=e("div",{class:"products-header__address"},[e("span",{class:"mr-1"},"Адрес")],-1),Be=[$e,xe,Se,qe,ze,Ce,De],Ee={class:"mobile-product-card-top"},Oe={class:"mobile-product-card__image"},Ue=["href"],je=["src"],Ie={class:"mobile-product-card__info"},Ne={class:"mobile-product-card__info__top"},Ae={class:"product__code"},Fe=e("span",{class:"product__code__text"},"Код:",-1),He={class:"product__quantity"},Le={class:"mobile-product-card__info__bottom"},Me={class:"mobile-product-card-bottom"},We={key:2,class:"product-list product-list_deliveries"},Pe={class:"product__product"},Qe={class:"product__image"},Re=["href"],Te=["src"],Ze={class:"product__info"},Ge={class:"product__info-title"},Je=["href"],Ke={class:"product__info-price"},Xe={class:"product__code"},Ye={class:"product__quantity"},et={class:"product__gender"},tt={class:"product__size"},st={class:"product__keywords"},ot={class:"product__address"},at={class:"product__actions"},it=["href"],dt={key:3,class:"panel flex flex-col grow"},bt={__name:"Deliveries",props:{deliveries:{type:Array,default:()=>[]},deliveryStatus:{type:String,default:""},paginator:{type:Array,required:!0}},setup(a){const $=a,{width:v}=ie(),q=T(),z=V(""),r=Z({deliveries:$.deliveries}),u=V(!1);B(()=>$.deliveries,i=>{r.deliveries=i});const f={processing:"Оплачен",sorted:"Отсортирован",pending:"В работе",on_the_way:"В пути",available_for_pick_up:"Готов к выдаче",picked_up:"Завершен",canceled:"Отменен",all:"Все"};B(v,()=>{u.value=!(F().isDesktop&&v.value>390)});const l=V($.deliveryStatus),k=V(!1),E=i=>{l.value=i,U.reload({only:["deliveries","paginator"],data:{status:l.value,page:1}})},L=i=>{E(i),k.value=!1},O=V(0),M=i=>{O.value=i,I.open()},W=H(),P=G(),Q=i=>{W.post(route("deliveries.update-data"),{purchase_id:i.id}).then(()=>{i.is_updating_delivery=!0,P.success("Счет обновляется")})};return J(()=>{u.value=!(F().isDesktop&&v.value>390),window.Echo.private("user."+q.props.auth.user.id).listen(".dilivery.update",i=>{const n=r.deliveries.findIndex(t=>t.id==i.id);if(i.delivery_status!=l.value){U.reload();return}if(!r.deliveries[n]){U.reload();return}r.deliveries[n].is_updating_delivery=!1,r.deliveries[n].delivery_status=i.delivery_status,r.deliveries[n].delivery_qrcode=i.delivery_qrcode,r.deliveries[n].delivery_pin=i.delivery_pin})}),K(()=>{window.Echo.private("user."+q.props.auth.user.id).stopListening(".dilivery.update")}),(i,n)=>(s(),d(g,null,[p(w(X),null,{default:c(()=>[ke]),_:1}),p(se,null,{header:c(()=>[_("Доставки")]),default:c(()=>[u.value?(s(),d("div",ye,[e("div",{onClick:n[0]||(n[0]=t=>k.value=!k.value),class:"mobile-section-input"},[e("p",null,o(f[l.value]),1),p(Y,{icon:"chevron-down"})])])):(s(),d("div",ge,[e("div",we,[(s(),d(g,null,j(f,(t,y)=>p(S,{key:y,theme:"normal",onClick:b=>E(y),class:N({btn_selected:y==l.value})},{default:c(()=>[_(o(t),1)]),_:2},1032,["onClick","class"])),64)),e("div",be,[p(ae,{modelValue:z.value,"onUpdate:modelValue":n[1]||(n[1]=t=>z.value=t),size:"md",placeholder:"Адрес ПВЗ"},null,8,["modelValue"])])])])),a.deliveries.length>0?(s(),d(g,{key:2},[e("div",{class:N(u.value?"mobile-deliveries__body":"panel panel_product")},[u.value?m("",!0):(s(),d("div",Ve,Be)),u.value?(s(!0),d(g,{key:1},j(r.deliveries,(t,y)=>{var b,x,C;return s(),d("div",{key:"mobile"+y,class:"mobile-product-card"},[e("div",Ee,[e("div",Oe,[e("a",{href:"https://www.wildberries.ru/catalog/"+((b=t.product)==null?void 0:b.remote_id)+"/detail.aspx",target:"_blank",rel:"noopener noreferrer"},[e("img",{src:w(A).constructHostV2((x=t.product)==null?void 0:x.remote_id)+"/images/tm/1.webp",alt:"",width:"30",height:"40"},null,8,je)],8,Ue)]),e("div",Ie,[e("div",Ne,[e("div",Ae,[Fe,_(" "+o((C=t.product)==null?void 0:C.remote_id),1)]),e("div",He,"Кол-во: "+o(t.quantity),1)]),e("div",Le,o(t.product.name),1)])]),e("div",Me,[t.delivery_status!="not_paid"?(s(),h(D,{key:0,theme:"info",class:"whitespace-nowrap"},{default:c(()=>[_(o(f[t.delivery_status]),1)]),_:2},1024)):m("",!0)])])}),128)):(s(),d("div",We,[(s(!0),d(g,null,j(r.deliveries,(t,y)=>{var b,x;return s(),d("div",{key:t.id,class:"product"},[e("div",Pe,[e("div",Qe,[e("a",{href:"https://www.wildberries.ru/catalog/"+t.product.remote_id+"/detail.aspx",target:"_blank"},[e("img",{src:w(A).constructHostV2(t.product.remote_id)+"/images/tm/1.webp",alt:"",width:"30",height:"40"},null,8,Te)],8,Re)]),e("div",Ze,[e("div",Ge,[e("a",{href:"https://www.wildberries.ru/catalog/"+t.product.remote_id+"/detail.aspx",target:"_blank"},o(t.task_id)+" "+o(t.product.name),9,Je)]),e("div",Ke,[_(o(w(ee).format(t.product.price/100))+" ",1),t.delivery_status_updated_ts?(s(),d(g,{key:0},[_(o(t.delivery_status_updated_ts),1)],64)):m("",!0)])])]),e("div",Xe,o(t.product.remote_id),1),e("div",Ye,o(t.quantity),1),e("div",et,o(w(te)[t.gender].name),1),e("div",tt,o((b=t.size)!=null&&b.name?(x=t.size)==null?void 0:x.name:"---"),1),e("div",st,o(t.keywords),1),e("div",ot,o(t.address),1),e("div",at,[l.value=="available_for_pick_up"?(s(),h(S,{key:0,size:"sm",icon:"qr",theme:"outline",onClick:C=>M(y)},null,8,["onClick"])):m("",!0),l.value=="picked_up"?(s(),d(g,{key:1},[t.receipt?(s(),d("a",{key:1,href:t.receipt,target:"_blank",rel:"noopener noreferrer"},[p(S,{size:"sm",icon:"share",theme:"outline"})],8,it)):(s(),h(S,{key:0,size:"sm",icon:"excel-file",theme:"outline",disabled:t.is_updating_delivery,onClick:C=>Q(t)},null,8,["disabled","onClick"]))],64)):m("",!0),t.delivery_status!="not_paid"?(s(),h(D,{key:2,theme:"info",class:"whitespace-nowrap"},{default:c(()=>[_(o(f[t.delivery_status]),1)]),_:2},1024)):(s(),h(D,{key:3,theme:"danger",class:"whitespace-nowrap"},{default:c(()=>[_(" Доставлен, но не оплачен ")]),_:1}))])])}),128))]))],2),u.value?m("",!0):(s(),h(de,{key:0,links:a.paginator},null,8,["links"]))],64)):(s(),d("div",dt,[p(le,{class:"grow"},{title:c(()=>[_("Нет активных доставок")]),_:1})])),r.deliveries.length>0&&l.value=="available_for_pick_up"?(s(),h(fe,{key:4,modelValue:r.deliveries[O.value],"onUpdate:modelValue":n[2]||(n[2]=t=>r.deliveries[O.value]=t)},null,8,["modelValue"])):m("",!0),p(re,{show:k.value,currentSection:" ",sections:Object.keys(f),onClose:L,onOpen:n[3]||(n[3]=t=>i.disableInput=!0)},null,8,["show","sections"])]),_:1})],64))}};export{bt as default};
