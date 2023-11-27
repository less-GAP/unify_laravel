import{e as _,f as m,r as n,p as c,h as o,x,A as f,o as g,a as t,l as p,k as V}from"./index.a683e63c.js";import{v as N}from"./validateMessages.02f7ed2e.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const T={__name:"AuthConfig",emits:["success","cancel"],setup(q,{emit:d}){const v=["login_google","login_google_id","login_google_secret"],s=_(!1);_(null);const e=m({}),h=m({validateTrigger:"submit","label-align":"top",model:e,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":N});(function(){s.value=!0,f.get("config",{params:{names:v}}).then(l=>{Object.assign(e,l.data)}).catch(l=>{}).finally(s.value=!1)})();const b=async function(){s.value=!0,f.post("config",e).then(l=>{d("success",l)}).catch(l=>{}).finally(s.value=!1)};return(l,a)=>{const C=n("a-switch"),u=n("a-form-item"),r=n("a-input"),k=n("a-button"),y=n("a-space"),w=n("a-form");return g(),c(w,x({autocomplete:"off"},h,{onFinish:b}),{default:o(()=>[t(u,{name:"login_google",label:"Google login",rules:[{required:!0}]},{default:o(()=>[t(C,{checkedValue:"active",unCheckedValue:"inactive",checked:e.login_google,"onUpdate:checked":a[0]||(a[0]=i=>e.login_google=i)},null,8,["checked"])]),_:1}),e.login_google=="active"?(g(),c(u,{key:0,name:"login_google_id",label:"Client Id",rules:[{required:!0}]},{default:o(()=>[t(r,{showCount:!0,autocomplete:"off",value:e.login_google_id,"onUpdate:value":a[1]||(a[1]=i=>e.login_google_id=i)},null,8,["value"])]),_:1})):p("",!0),e.login_google=="active"?(g(),c(u,{key:1,name:"login_google_secret",label:"Client Secret",rules:[{required:!0}]},{default:o(()=>[t(r,{showCount:!0,autocomplete:"off",value:e.login_google_secret,"onUpdate:value":a[2]||(a[2]=i=>e.login_google_secret=i)},null,8,["value"])]),_:1})):p("",!0),t(u,null,{default:o(()=>[t(y,null,{default:o(()=>[t(k,{loading:s.value,type:"primary","html-type":"submit"},{default:o(()=>[V("Submit")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},16)}}};export{T as default};
