import{e as f,f as u,r as n,p as x,h as o,x as C,A as m,o as F,a as e,k as p}from"./index.a683e63c.js";import{v as D}from"./validateMessages.02f7ed2e.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const T={__name:"FacebookConfig",emits:["success","cancel"],setup(N,{emit:_}){const d=["facebook_id","facebook_secret"],s=f(!1);f(null);const a=u({}),b=u({validateTrigger:"submit","label-align":"top",model:a,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":D});(function(){s.value=!0,m.get("config",{params:{names:d}}).then(t=>{Object.assign(a,t.data)}).catch(t=>{}).finally(s.value=!1)})();const g=async function(){s.value=!0,m.post("config",a).then(t=>{_("success",t)}).catch(t=>{}).finally(s.value=!1)};return(t,l)=>{const v=n("a-Divider"),i=n("a-input"),c=n("a-form-item"),k=n("a-button"),h=n("a-space"),y=n("a-form");return F(),x(y,C({autocomplete:"off"},b,{onFinish:g}),{default:o(()=>[e(v,{class:"!font-bold !text-blue-700",dashed:"",orientation:"left","orientation-margin":"0",plain:""},{default:o(()=>[p("Facebook")]),_:1}),e(c,{name:"facebook_id",label:"Facebook ID"},{default:o(()=>[e(i,{autocomplete:"off",value:a.facebook_id,"onUpdate:value":l[0]||(l[0]=r=>a.facebook_id=r),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(c,{name:"facebook_secret",label:"Facebook Secret"},{default:o(()=>[e(i,{autocomplete:"off",value:a.facebook_secret,"onUpdate:value":l[1]||(l[1]=r=>a.facebook_secret=r),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(c,null,{default:o(()=>[e(h,null,{default:o(()=>[e(k,{loading:s.value,type:"primary","html-type":"submit"},{default:o(()=>[p("Submit")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},16)}}};export{T as default};
