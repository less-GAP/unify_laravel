import{e as v,f as d,i as _,r as s,p as w,h as a,A as x,o as y,b as n,a as e,m as r,ab as k,ae as C,k as O,l as T,x as A}from"./index.a683e63c.js";/* empty css              */import{c as F}from"./jodit-vue.05e762d7.js";import{j as R}from"./jodit.min.b5f3759b.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const D={class:"overflow-y-auto"},E={class:"p-3 bg-gray-200"},J={class:"flex"},L=n("span",{class:"ml-1 text-stone-500"},"Back",-1),P={class:"flex"},G=n("span",{class:"ml-1 text-stone-500"},"Back",-1),H="email/templates",te={__name:"FormEmailTemplate",props:{value:Object},emits:["success","cancel"],setup(I,{emit:K}){window.buttons=R.exports.Jodit.defaultOptions.buttons;const j="/"+H,V={required:"${label} is required!",types:{email:"${label} is not a valid email!",number:"${label} is not a valid number!"},number:{range:"${label} must be between ${min} and ${max}"}},i=v(!0);v(null);const t=d({isNew:!0,full_name:"",email_cc:[],email_bcc:[],username:"",email_content:"",email:"",role:"user",password:""}),f=_.currentRoute.value.params.id,z=d({validateTrigger:"submit","label-align":"top",model:t,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":V});f&&async function(u){i.value=!0,x.get("email-template/"+u).then(l=>{i.value=!1,Object.assign(t,l.data)})}(f);const B=async function(){i.value=!0,x.post("email-template",t).then(u=>{_.replace("/email/templates")}).catch(u=>{}).finally(i.value=!1)},m=function(){_.replace({path:j})},$=d([{group:"font-style",buttons:[]},{group:"color",buttons:[]},{group:"source",buttons:[]}]);return(u,l)=>{const p=s("a-button"),U=s("a-space"),b=s("a-input"),c=s("a-form-item"),h=s("a-select"),M=s("a-switch"),g=s("a-col"),N=s("a-row"),S=s("a-form"),q=s("a-drawer");return y(),w(q,{"body-style":"padding:0",closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:m,open:!0,width:"90vw"},{default:a(()=>[n("div",null,[n("div",D,[e(S,A({autocomplete:"off"},z,{onFinish:B}),{default:a(()=>[n("div",E,[e(p,{class:"!hidden md:!inline-block",type:"link",onClick:m},{icon:a(()=>[n("div",J,[e(r(k),{path:r(C),class:"w-4 text-stone-500"},null,8,["path"]),L])]),_:1}),e(p,{class:"!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0",type:"link",onClick:m},{icon:a(()=>[n("div",P,[e(r(k),{path:r(C),class:"w-4 text-stone-500"},null,8,["path"]),G])]),_:1}),e(U,{class:"float-right"},{default:a(()=>[e(p,{class:"mr-5",loading:i.value,type:"primary","html-type":"submit"},{default:a(()=>[O("Submit")]),_:1},8,["loading"])]),_:1})]),e(N,{style:{height:"calc(100vh - 100px)"},class:"p-5",gutter:20},{default:a(()=>[e(g,{span:8},{default:a(()=>[e(c,{class:"w-full",name:"email_title",label:"Mail title",rules:[{required:!0}]},{default:a(()=>[e(b,{autocomplete:"off",value:t.email_title,"onUpdate:value":l[0]||(l[0]=o=>t.email_title=o)},null,8,["value"])]),_:1}),e(c,{class:"w-full",name:"email_reply_to",label:"Reply to"},{default:a(()=>[e(b,{autocomplete:"off",value:t.email_reply_to,"onUpdate:value":l[1]||(l[1]=o=>t.email_reply_to=o)},null,8,["value"])]),_:1}),e(c,{class:"w-full",name:"email_cc",label:"Mail cc"},{default:a(()=>[e(h,{value:t.email_cc,"onUpdate:value":l[2]||(l[2]=o=>t.email_cc=o),mode:"tags",style:{width:"100%"},"token-separators":[","]},null,8,["value"])]),_:1}),e(c,{class:"w-full",name:"email_bcc",label:"Mail bcc"},{default:a(()=>[e(h,{value:t.email_bcc,"onUpdate:value":l[3]||(l[3]=o=>t.email_bcc=o),mode:"tags",style:{width:"100%"},"token-separators":[","]},null,8,["value"])]),_:1}),e(c,{class:"w-full",name:"status",label:"Status"},{default:a(()=>[e(M,{checkedValue:"active",unCheckedValue:"inactive",checked:t.status,"onUpdate:checked":l[4]||(l[4]=o=>t.status=o)},null,8,["checked"])]),_:1})]),_:1}),e(g,{span:16},{default:a(()=>[e(c,{class:"w-full",name:"email_title",label:"Mail Content",rules:[{required:!0}]},{default:a(()=>[i.value?T("",!0):(y(),w(r(F),{key:0,style:{height:"80vh"},modelValue:t.email_content,"onUpdate:modelValue":l[5]||(l[5]=o=>t.email_content=o),config:{buttons:$,height:"70vh",iframeStyle:"html{margin:0;padding:0;min-height: 100%;}body{box-sizing:border-box;font-family:roboto;font-size:16px;line-height:1.6;padding:10px;margin:0;background:transparent;color:#000;position:relative;z-index:2;user-select:auto;margin:0px;overflow:auto;outline:none;}table{width:100%;border:none;border-collapse:collapse;empty-cells: show;max-width: 100%;}th,td{padding: 2px 5px;border:1px solid #ccc;-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text}p{margin-top:0;}.jodit_editor .jodit_iframe_wrapper{display: block;clear: both;user-select: none;position: relative;}.jodit_editor .jodit_iframe_wrapper:after {position:absolute;content:'';z-index:1;top:0;left:0;right: 0;bottom: 0;cursor: pointer;display: block;background: rgba(0, 0, 0, 0);} .jodit_disabled{user-select: none;-o-user-select: none;-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;-ms-user-select: none}",toolbarButtonSize:"large"}},null,8,["modelValue","config"]))]),_:1})]),_:1})]),_:1})]),_:1},16)])])]),_:1})}}};export{te as default};
