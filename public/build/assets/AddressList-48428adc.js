import{o,c as a,h as t,t as r,F as n,i as c,b as d}from"./app-632bf8b8.js";const l={class:"w-screen mx-auto"},i={class:"text-4xl font-bold text-center py-8"},u={class:"text-xl font-bold text-center py-8"},x={class:"w-80 mx-auto flex flex-col space-y-2"},h=["href"],_=t("br",null,null,-1),f={class:"font-bold"},p={__name:"AddressList",props:{addresses:{type:Array,required:!0},zone:{type:Object,required:!0},date:{type:String,required:!0}},setup(e){return(y,m)=>(o(),a("div",l,[t("h1",i,"Список на "+r(e.date),1),t("h1",u,r(e.zone.name),1),t("div",x,[(o(!0),a(n,null,c(e.addresses,s=>(o(),a("a",{key:s,href:"/generated/deliveries/"+e.zone.id+"-"+e.date+"/"+s.id,class:"bg-blue-600 rounded-xl py-3 px-4 text-white"},[d(r(s.address)+" ",1),_,t("span",f," ("+r(s.livecargo_deliveries_count)+" шт.) ",1)],8,h))),128))])]))}};export{p as default};
