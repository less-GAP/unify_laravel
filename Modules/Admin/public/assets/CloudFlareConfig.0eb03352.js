import{e as f,f as i,r as s,p as k,h as o,x as h,A as _,o as C,a,k as x}from"./index.a683e63c.js";import{v as S}from"./validateMessages.02f7ed2e.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const K={__name:"CloudFlareConfig",emits:["success","cancel"],setup(N,{emit:m}){const p=["cloudflare_site_key","cloudflare_secret_key"],l=f(!1);f(null);const e=i({}),d=i({validateTrigger:"submit","label-align":"top",model:e,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":S});(function(){l.value=!0,_.get("config",{params:{names:p}}).then(t=>{Object.assign(e,t.data)}).catch(t=>{}).finally(l.value=!1)})();const g=async function(){l.value=!0,_.post("config",e).then(t=>{m("success",t)}).catch(t=>{}).finally(l.value=!1)};return(t,n)=>{const u=s("a-input"),c=s("a-form-item"),y=s("a-button"),v=s("a-space"),b=s("a-form");return C(),k(b,h({autocomplete:"off"},d,{onFinish:g}),{default:o(()=>[a(c,{name:"cloudflare_site_key",label:"Site Key"},{default:o(()=>[a(u,{autocomplete:"off",value:e.cloudflare_site_key,"onUpdate:value":n[0]||(n[0]=r=>e.cloudflare_site_key=r),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),a(c,{name:"cloudflare_secret_key",label:"Secret Key"},{default:o(()=>[a(u,{autocomplete:"off",value:e.cloudflare_secret_key,"onUpdate:value":n[1]||(n[1]=r=>e.cloudflare_secret_key=r),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),a(c,null,{default:o(()=>[a(v,null,{default:o(()=>[a(y,{loading:l.value,type:"primary","html-type":"submit"},{default:o(()=>[x("Submit")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},16)}}};export{K as default};