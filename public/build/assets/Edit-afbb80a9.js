import{Q as V,a2 as g,e as y,l as A,o as p,c as f,a as o,u as l,Z as E,w as i,b as t,h as e,t as v,y as w,z as C,j as P,F as $,g as x}from"./app-632bf8b8.js";import{g as T,h as F,i as I,j as K}from"./Modal-1349d4bc.js";import{_ as R}from"./AuthenticatedLayout-6b95fe5a.js";import S from"./UpdateProfileInformationModal-c44605f3.js";import{b as n,_ as d}from"./AppButton-ffc821ac.js";import U from"./DeleteUserModal-5e1cce99.js";import q from"./UpdatePasswordModal-2cae6765.js";import z from"./UpdateTelegramPaymentModal-02ba6a3f.js";import{_ as j}from"./CheckboxInput-967d96fa.js";import{u as L}from"./telegramAuth-3bc65f01.js";import{u as O}from"./useAxios-d55f63a8.js";import"./index-4cd942bc.js";import"./index-81635579.js";import"./TextInput-7865c9b5.js";import"./dayjs.min-356b711c.js";import"./SelectInput-944e57c4.js";import"./LabelText-36465a88.js";const Q={class:"space-y-4"},Z={class:"panel panel_p-lg"},G={class:"profile-block"},H={class:"profile-block__icon"},J={class:"profile-block__info"},M={class:"profile-block__title"},W=e("div",{class:"profile-block__desc"}," Вы можете изменить свое имя и E-mail. После подтверждения вы сможете входить под новой почтой. ",-1),X={class:"profile-block__action"},Y={class:"panel panel_p-lg"},ee={class:"profile-block"},oe={class:"profile-block__icon"},se=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Поменять пароль"),e("div",{class:"profile-block__desc"},"Поменять данные для входа.")],-1),le={class:"profile-block__action"},ie={class:"panel panel_p-lg"},te={class:"profile-block"},ce={class:"profile-block__icon"},ae=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Подключить телеграм"),e("div",{class:"profile-block__desc"}," Подключить телеграм аккаунт для получения оповещений ")],-1),_e={class:"profile-block__action"},ne={class:"flex justify-end"},de={key:0,class:"p-4 pl-10"},re=e("div",{class:"mb-2"},"Оповещения",-1),pe={class:"panel panel_p-lg"},fe={class:"profile-block"},ve={class:"profile-block__icon"},ue=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Телеграм для оплаты"),e("div",{class:"profile-block__desc"}," Добавить данные для оплаты через телеграм ")],-1),he={class:"profile-block__action"},ke={class:"panel panel_p-lg"},me={class:"profile-block"},be={class:"profile-block__icon"},ge=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Апи ключ"),e("div",{class:"profile-block__desc"},"Ключ для использования апи эндпоинтов")],-1),ye={class:"profile-block__action flex gap-4 items-center"},we={class:"p-1 px-2 bg-slate-200 rounded-md"},Ce={class:"panel panel_p-lg"},$e={class:"profile-block"},xe={class:"profile-block__icon"},je=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Реферральная программа"),e("div",{class:"profile-block__desc"}," Пригласи пользователя и получи 5% от всех его операций за следующие 3 месяца ")],-1),Be={class:"profile-block__action flex gap-4 items-center"},De={key:0,class:"p-1 px-2 bg-slate-200 rounded-md"},Ne={class:"panel panel_p-lg"},Ve={class:"profile-block profile-block_theme-danger"},Ae={class:"profile-block__icon"},Ee=e("div",{class:"profile-block__info"},[e("div",{class:"profile-block__title"},"Удалить аккаунт"),e("div",{class:"profile-block__desc"}," Как только ваша учетная запись будет удалена, все ее ресурсы и данные будут удалены безвозвратно, включая финансы. Прежде чем удалить свою учетную запись, пожалуйста, проверьте всю информацию, которую вы хотите сохранить. ")],-1),Pe={class:"profile-block__action"},We={__name:"Edit",props:{notifications:{type:Object,required:!0}},setup(B){const D=B,c=V().props.auth.user,u=g(c.api_key),h=g(c.ref_code),k=O(),m=y();L(m,route("telegram.connect"));const b=()=>{k.post(route("profile.generate-api-key"),{user_id:c.id}).then(r=>{u.value=r.data.api_key})},N=()=>{k.post(route("profile.generate-ref-code")).then(r=>{h.value=r.data.ref_code})},a=y({"wallet.all":!1,"purchase.all":!1,...D.notifications});return A(a,()=>{k.post(route("profile.notifications.update"),{notifications:a.value}).then(r=>{console.log(r)})},{deep:!0}),(r,s)=>(p(),f($,null,[o(l(E),{title:"Profile"}),o(R,null,{header:i(()=>[t(" Профиль ")]),default:i(()=>[e("div",Q,[e("div",Z,[e("div",G,[e("div",H,[o(n,{icon:"user"})]),e("div",J,[e("div",M,v(l(c).email),1),W]),e("div",X,[o(d,{theme:"outline",onClick:s[0]||(s[0]=_=>l(T).open())},{default:i(()=>[t(" Изменить информацию ")]),_:1})])]),o(S)]),e("div",Y,[e("div",ee,[e("div",oe,[o(n,{icon:"lock"})]),se,e("div",le,[o(d,{theme:"outline",onClick:s[1]||(s[1]=_=>l(F).open())},{default:i(()=>[t(" Поменять пароль ")]),_:1})])]),o(q)]),e("div",ie,[e("div",te,[e("div",ce,[o(n,{icon:"user"})]),ae,e("div",_e,[w(e("div",ne,[e("div",{ref_key:"telegram",ref:m,id:"tg-auth-widget",style:{}},null,512)],512),[[C,l(c).telegram_id==0]]),w(e("div",null," Ваш Chat ID: "+v(l(c).telegram_id),513),[[C,l(c).telegram_id!=0]])])]),l(c).telegram_id!=0?(p(),f("div",de,[re,o(j,{value:a.value["wallet.all"],checked:a.value["wallet.all"],"onUpdate:checked":s[2]||(s[2]=_=>a.value["wallet.all"]=_)},{default:i(()=>[t(" По кошелькам ")]),_:1},8,["value","checked"]),o(j,{value:a.value["purchase.all"],checked:a.value["purchase.all"],"onUpdate:checked":s[3]||(s[3]=_=>a.value["purchase.all"]=_)},{default:i(()=>[t(" По выкупам ")]),_:1},8,["value","checked"])])):P("",!0)]),e("div",pe,[e("div",fe,[e("div",ve,[o(n,{icon:"users"})]),ue,e("div",he,[o(d,{theme:"outline",onClick:s[4]||(s[4]=_=>l(I).open())},{default:i(()=>[t(" Изменить данные ")]),_:1})])]),o(z)]),e("div",ke,[e("div",me,[e("div",be,[o(n,{icon:"setting"})]),ge,e("div",ye,[u.value?(p(),f($,{key:0},[e("div",we,v(u.value),1),o(d,{theme:"outline",icon:"refresh",onClick:b})],64)):(p(),x(d,{key:1,theme:"outline",icon:"refresh",onClick:b},{default:i(()=>[t(" Сгенерировать ")]),_:1}))])])]),e("div",Ce,[e("div",$e,[e("div",xe,[o(n,{icon:"users"})]),je,e("div",Be,[h.value?(p(),f("div",De," https://app.mpb.top/login?ref="+v(h.value),1)):(p(),x(d,{key:1,theme:"outline",icon:"refresh",onClick:N},{default:i(()=>[t(" Сгенерировать ")]),_:1}))])])]),e("div",Ne,[e("div",Ve,[e("div",Ae,[o(n,{icon:"delete"})]),Ee,e("div",Pe,[o(d,{theme:"oldanger",onClick:s[5]||(s[5]=_=>l(K).open())},{default:i(()=>[t(" Удалить аккаунт ")]),_:1})])]),o(U)])])]),_:1})],64))}};export{We as default};