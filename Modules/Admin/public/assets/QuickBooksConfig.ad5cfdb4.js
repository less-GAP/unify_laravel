import{e as r,f as _,r as s,p as y,h as a,x as q,A as m,o as h,a as t,k as C}from"./index.a683e63c.js";import{v as x}from"./validateMessages.02f7ed2e.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const K={__name:"QuickBooksConfig",emits:["success","cancel"],setup(S,{emit:f}){const p=["quickbooks_client_id","quickbooks_client_secret"],n=r(!1);r(null);const e=_({}),d=_({validateTrigger:"submit","label-align":"top",model:e,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":x});(function(){n.value=!0,m.get("config",{params:{names:p}}).then(o=>{Object.assign(e,o.data)}).catch(o=>{}).finally(n.value=!1)})();const b=async function(){n.value=!0,m.post("config",e).then(o=>{f("success",o)}).catch(o=>{}).finally(n.value=!1)};return(o,l)=>{const u=s("a-input"),c=s("a-form-item"),k=s("a-button"),g=s("a-space"),v=s("a-form");return h(),y(v,q({autocomplete:"off"},d,{onFinish:b}),{default:a(()=>[t(c,{name:"quickbooks_client_id",label:"Site Key"},{default:a(()=>[t(u,{autocomplete:"off",value:e.quickbooks_client_id,"onUpdate:value":l[0]||(l[0]=i=>e.quickbooks_client_id=i),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),t(c,{name:"quickbooks_client_secret",label:"Secret Key"},{default:a(()=>[t(u,{autocomplete:"off",value:e.quickbooks_client_secret,"onUpdate:value":l[1]||(l[1]=i=>e.quickbooks_client_secret=i),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),t(c,null,{default:a(()=>[t(g,null,{default:a(()=>[t(k,{loading:n.value,type:"primary","html-type":"submit"},{default:a(()=>[C("Submit")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},16)}}};export{K as default};