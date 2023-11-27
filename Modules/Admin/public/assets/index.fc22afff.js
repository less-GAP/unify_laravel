import{_ as z}from"./no_image_available.915c4da6.js";import{d as G,u as L,e as p,r as o,o as h,c as D,a as e,h as a,b as r,m as c,ab as N,ae as B,aM as Y,k as A,W as H,F as q,A as S,t as M,Y as W,Z as P,v as X,i as Z,I as J,L as Q,x as ee,H as F,X as te,q as ae,p as T,l as R}from"./index.a683e63c.js";import{D as oe}from"./DataTable.1047e308.js";import{U as se}from"./UseEloquentRouter.7e988003.js";import{U as le}from"./UseDataTable.106f51b0.js";/* empty css              */import{I as ne}from"./InputUploadGetPath.6754ab8e.js";import{F as ie}from"./FormOutlined.646d2a07.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const ce={class:"p-3 bg-gray-200"},ue={class:"flex"},re=r("span",{class:"ml-1 text-stone-500"},"Back",-1),de={class:"flex"},me=r("span",{class:"ml-1 text-stone-500"},"Back",-1),pe={class:"flex"},_e=r("span",{class:"ml-1 text-white"},"Save",-1),fe={class:"mx-4"},ve="trademark",he=G({__name:"Detail",props:{value:{type:Object,default:{}}},emits:["close"],setup($,{emit:w}){const d=$;L(),p(!1);const _=p("1");p(!1);const m=p(!1),x=p(),l=p({status:"A"});d.value.id>0?l.value=d.value:l.value={status:"A"};const g=()=>{x.value.validate().then(()=>{S.post(ve,M(l.value)).then(f=>{f.data.code==1&&(x.value.resetFields(),w("close"))})}).catch(f=>{})},C=function(){w("close")};return(f,t)=>{const i=o("a-button"),v=o("a-space"),k=o("a-form-item"),y=o("a-radio-button"),U=o("a-radio-group"),s=o("a-input"),u=o("a-col"),I=o("a-textarea"),K=o("a-row"),V=o("a-tab-pane"),E=o("a-tabs"),O=o("a-form"),j=o("a-modal");return h(),D(q,null,[e(O,{layout:"vertical",ref_key:"formRef",ref:x,model:l.value,onFinish:g,class:"w-full"},{default:a(()=>[r("div",ce,[e(i,{class:"!hidden md:!inline-block",type:"link",onClick:C},{icon:a(()=>[r("div",ue,[e(c(N),{path:c(B),class:"w-4 text-stone-500"},null,8,["path"]),re])]),_:1}),e(i,{class:"!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0",type:"link",onClick:C},{icon:a(()=>[r("div",de,[e(c(N),{path:c(B),class:"w-4 text-stone-500"},null,8,["path"]),me])]),_:1}),e(v,{class:"float-right"},{default:a(()=>[e(i,{onClick:t[0]||(t[0]=n=>g()),type:"primary"},{default:a(()=>[r("div",pe,[e(c(N),{path:c(Y),class:"w-4 text-white"},null,8,["path"]),_e])]),_:1})]),_:1})]),r("div",fe,[e(E,{activeKey:_.value,"onUpdate:activeKey":t[5]||(t[5]=n=>_.value=n)},{default:a(()=>[e(V,{key:"1",tab:"General"},{default:a(()=>[e(K,{gutter:20},{default:a(()=>[e(u,{span:24},{default:a(()=>[e(k,{style:{width:"100%"},label:"Logo"},{default:a(()=>[e(c(ne),{autocomplete:"off",value:l.value.logo,"onUpdate:value":t[1]||(t[1]=n=>l.value.logo=n)},null,8,["value"])]),_:1}),e(k,{label:"Status",name:"status",rules:[{required:!0,message:"Select a status!"}]},{default:a(()=>[e(U,{value:l.value.status,"onUpdate:value":t[2]||(t[2]=n=>l.value.status=n),"button-style":"solid",size:"middle"},{default:a(()=>[e(y,{value:"A"},{default:a(()=>[A("Active")]),_:1}),e(y,{value:"D"},{default:a(()=>[A("Deactive")]),_:1})]),_:1},8,["value"])]),_:1}),e(k,{label:"Name",name:"name",rules:[{required:!0,message:"Please input !"}]},{default:a(()=>[e(s,{value:l.value.name,"onUpdate:value":t[3]||(t[3]=n=>l.value.name=n),placeholder:"Content..."},null,8,["value"])]),_:1})]),_:1}),e(u,{span:24},{default:a(()=>[e(k,{label:"Description"},{default:a(()=>[e(I,{value:l.value.description,"onUpdate:value":t[4]||(t[4]=n=>l.value.description=n),placeholder:"Description...",rows:4},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["activeKey"])])]),_:1},8,["model"]),e(j,{"append-to-body":"",open:m.value,"onUpdate:open":t[7]||(t[7]=n=>m.value=n),style:{"z-index":"99999",top:"2vh",height:"98vh"},height:"96vh",width:"90vw",title:"Select file"},{footer:a(()=>[]),default:a(()=>[e(c(H),{multiple:!0,onClose:t[6]||(t[6]=n=>m.value=!1),onSelect:f.onSelectImage},null,8,["onSelect"])]),_:1},8,["open"])],64)}}});const ge=["src","alt"],ke={key:1,class:"w-20 h-auto float-left",src:z},b="trademark",Ne={__name:"index",setup($){const{fetchListApi:w}=se(b),d=L(),_=p(!1),m=p({}),l=le(w,{columns:[{title:"Logo",key:"logo",width:80},{title:"Name",key:"name"},{title:"Status",key:"status",width:100},{title:"Created at",key:"created_at",width:130}],showSelection:!0,showSort:!1,showReload:!0,addAction:{action:t=>{m.value.id=0,_.value=!0},ifShow:d.hasPermission("Trademark.create")},listActions:[],itemActions:[{ifShow:d.hasPermission("Trademark.update"),label:"Edit",key:"edit",action:t=>{m.value=t,_.value=!0}},{ifShow:d.hasPermission("Trademark.delete"),label:"",class:"font-medium text-red-600 dark:text-red-500 hover:underline",icon:W,key:"delete",action(t,i){S.delete(b+"/"+t.id).then(v=>{P[v.data.code==0?"error":"success"]({message:"Notification",description:v.data.message})}).finally(()=>{i()})}}],selectionActions:[{ifShow:d.hasPermission("Trademark.update"),title:"Active",action(t){return S.post(b+"/activeList",{items:t,status:"A"}).then(i=>{P[i.data.code==0?"error":"success"]({message:"Notification",description:i.data.message})})}},{ifShow:d.hasPermission("Trademark.update"),title:"Deactive",action(t){return S.post(b+"/activeList",{items:t,status:"D"}).then(i=>{P[i.data.code==0?"error":"success"]({message:"Notification",description:i.data.message})})}}]});let g=()=>{};X(Z.currentRoute,t=>{t.path==="/"+b&&g()});function C({reload:t}){g=t}const f=()=>{_.value=!1,g()};return(t,i)=>{const v=o("a-button"),k=o("a-popconfirm"),y=o("a-tag"),U=o("a-drawer");return h(),D(q,null,[e(J,null,{default:a(()=>[e(Q,null,{default:a(()=>[e(c(oe),ee(c(l),{onRegister:C}),{"cellAction[delete]":a(({item:s,actionMethod:u})=>[e(k,{title:"Do you want delete this?","ok-text":"Yes","cancel-text":"No",onConfirm:u},{default:a(()=>[e(v,{type:"text",danger:"",icon:F(c(te)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cellAction[edit]":a(({item:s,actionMethod:u})=>[e(v,{type:"text",icon:F(c(ie)),label:"",outline:!0,onClick:u},null,8,["icon","onClick"])]),"cell[id]":a(({item:s,column:u})=>[r("div",null,ae(s.id),1)]),"cell[logo]":a(({item:s,column:u})=>[s.image_url?(h(),D("img",{key:0,class:"w-20 h-auto float-left",src:s.image_url,alt:s.name},null,8,ge)):(h(),D("img",ke))]),"cell[status]":a(({item:s,column:u})=>[s.status=="A"?(h(),T(y,{key:0,color:"green"},{default:a(()=>[A("Active")]),_:1})):R("",!0),s.status=="D"?(h(),T(y,{key:1,color:"green"},{default:a(()=>[A("Deactive")]),_:1})):R("",!0)]),_:1},16)]),_:1})]),_:1}),e(U,{closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:f,open:_.value,width:"80vw",maskClosable:!1},{default:a(()=>{var s;return[(h(),T(he,{value:m.value,onClose:f,key:(s=m.value.id)!=null?s:0},null,8,["value"]))]}),_:1},8,["open"])],64)}}};export{Ne as default};
