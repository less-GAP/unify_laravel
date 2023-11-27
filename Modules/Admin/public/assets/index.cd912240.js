import{_ as z}from"./no_image_available.915c4da6.js";import{d as G,u as L,e as _,r as n,o as g,c as A,a as e,h as a,b as d,m as r,ab as U,ae as F,aM as Y,k as D,W as H,F as T,A as C,t as M,Y as W,Z as N,v as X,i as Z,I as J,L as Q,x as ee,H as R,X as te,q as ae,p as $,l as E}from"./index.a683e63c.js";import{D as le}from"./DataTable.1047e308.js";import{U as oe}from"./UseEloquentRouter.7e988003.js";import{U as se}from"./UseDataTable.106f51b0.js";/* empty css              */import{I as ne}from"./InputUploadGetPath.6754ab8e.js";import{F as ie}from"./FormOutlined.646d2a07.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const ue={class:"p-3 bg-gray-200"},re={class:"flex"},ce=d("span",{class:"ml-1 text-stone-500"},"Back",-1),de={class:"flex"},pe=d("span",{class:"ml-1 text-stone-500"},"Back",-1),me={class:"flex"},_e=d("span",{class:"ml-1 text-white"},"Save",-1),fe={class:"mx-4"},ve="supplier",he=G({__name:"Detail",props:{value:{type:Object,default:{}}},emits:["close"],setup(q,{emit:w}){const p=q;L(),_(!1);const f=_("1");_(!1);const m=_(!1),x=_(),l=_({status:"A"});p.value.id>0?l.value=p.value:l.value={status:"A"};const y=()=>{x.value.validate().then(()=>{C.post(ve,M(l.value)).then(v=>{v.data.code==1&&(x.value.resetFields(),w("close"))})}).catch(v=>{})},S=function(){w("close")};return(v,t)=>{const i=n("a-button"),h=n("a-space"),c=n("a-form-item"),b=n("a-radio-button"),P=n("a-radio-group"),s=n("a-input"),u=n("a-col"),B=n("a-textarea"),I=n("a-row"),K=n("a-tab-pane"),V=n("a-tabs"),O=n("a-form"),j=n("a-modal");return g(),A(T,null,[e(O,{layout:"vertical",ref_key:"formRef",ref:x,model:l.value,onFinish:y,class:"w-full"},{default:a(()=>[d("div",ue,[e(i,{class:"!hidden md:!inline-block",type:"link",onClick:S},{icon:a(()=>[d("div",re,[e(r(U),{path:r(F),class:"w-4 text-stone-500"},null,8,["path"]),ce])]),_:1}),e(i,{class:"!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0",type:"link",onClick:S},{icon:a(()=>[d("div",de,[e(r(U),{path:r(F),class:"w-4 text-stone-500"},null,8,["path"]),pe])]),_:1}),e(h,{class:"float-right"},{default:a(()=>[e(i,{onClick:t[0]||(t[0]=o=>y()),type:"primary"},{default:a(()=>[d("div",me,[e(r(U),{path:r(Y),class:"w-4 text-white"},null,8,["path"]),_e])]),_:1})]),_:1})]),d("div",fe,[e(V,{activeKey:f.value,"onUpdate:activeKey":t[8]||(t[8]=o=>f.value=o)},{default:a(()=>[e(K,{key:"1",tab:"General"},{default:a(()=>[e(I,{gutter:20},{default:a(()=>[e(u,{span:24},{default:a(()=>[e(c,{style:{width:"100%"},label:"Logo"},{default:a(()=>[e(r(ne),{autocomplete:"off",value:l.value.logo,"onUpdate:value":t[1]||(t[1]=o=>l.value.logo=o)},null,8,["value"])]),_:1}),e(c,{label:"Status",name:"status",rules:[{required:!0,message:"Select a status!"}]},{default:a(()=>[e(P,{value:l.value.status,"onUpdate:value":t[2]||(t[2]=o=>l.value.status=o),"button-style":"solid",size:"middle"},{default:a(()=>[e(b,{value:"A"},{default:a(()=>[D("Active")]),_:1}),e(b,{value:"D"},{default:a(()=>[D("Deactive")]),_:1})]),_:1},8,["value"])]),_:1}),e(c,{label:"Name",name:"name",rules:[{required:!0,message:"Please input !"}]},{default:a(()=>[e(s,{value:l.value.name,"onUpdate:value":t[3]||(t[3]=o=>l.value.name=o),placeholder:"Content..."},null,8,["value"])]),_:1})]),_:1}),e(u,{span:24},{default:a(()=>[e(c,{label:"Phone",name:"phone",rules:[{required:!0,message:"Please input!"}]},{default:a(()=>[e(s,{value:l.value.phone,"onUpdate:value":t[4]||(t[4]=o=>l.value.phone=o),placeholder:"Phone..."},null,8,["value"])]),_:1})]),_:1}),e(u,{span:24},{default:a(()=>[e(c,{label:"Email",name:"email",rules:[{required:!0,message:"Please input!"}]},{default:a(()=>[e(s,{value:l.value.email,"onUpdate:value":t[5]||(t[5]=o=>l.value.email=o),placeholder:"Email..."},null,8,["value"])]),_:1})]),_:1}),e(u,{span:24},{default:a(()=>[e(c,{label:"Address",name:"address",rules:[{required:!0,message:"Please input!"}]},{default:a(()=>[e(B,{value:l.value.address,"onUpdate:value":t[6]||(t[6]=o=>l.value.address=o),placeholder:"Address...",rows:2},null,8,["value"])]),_:1})]),_:1}),e(u,{span:24},{default:a(()=>[e(c,{label:"Description"},{default:a(()=>[e(B,{value:l.value.description,"onUpdate:value":t[7]||(t[7]=o=>l.value.description=o),placeholder:"Description...",rows:4},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["activeKey"])])]),_:1},8,["model"]),e(j,{"append-to-body":"",open:m.value,"onUpdate:open":t[10]||(t[10]=o=>m.value=o),style:{"z-index":"99999",top:"2vh",height:"98vh"},height:"96vh",width:"90vw",title:"Select file"},{footer:a(()=>[]),default:a(()=>[e(r(H),{multiple:!0,onClose:t[9]||(t[9]=o=>m.value=!1),onSelect:v.onSelectImage},null,8,["onSelect"])]),_:1},8,["open"])],64)}}});const ge=["src","alt"],ye={key:1,class:"w-20 h-auto float-left",src:z},k="supplier",Ue={__name:"index",setup(q){const{fetchListApi:w}=oe(k),p=L(),f=_(!1),m=_({}),l=se(w,{columns:[{title:"Logo",key:"logo",width:80},{title:"Name",key:"name"},{title:"Phone",key:"phone"},{title:"Email",key:"email"},{title:"Address",key:"address"},{title:"Status",key:"status",width:100},{title:"Created at",key:"created_at",width:130}],showSelection:!0,showSort:!1,showReload:!0,addAction:{action:t=>{m.value={},f.value=!0},ifShow:p.hasPermission("Supplier.create")},listActions:[],itemActions:[{ifShow:p.hasPermission("Supplier.update"),label:"Edit",key:"edit",action:t=>{m.value=t,f.value=!0}},{ifShow:p.hasPermission("Supplier.delete"),label:"",class:"font-medium text-red-600 dark:text-red-500 hover:underline",icon:W,key:"delete",action(t,i){C.delete(k+"/"+t.id).then(h=>{N[h.data.code==0?"error":"success"]({message:"Notification",description:h.data.message})}).finally(()=>{i()})}}],selectionActions:[{ifShow:p.hasPermission("Supplier.update"),title:"Active",action(t){return C.post(k+"/activeList",{items:t,status:"A"}).then(i=>{N[i.data.code==0?"error":"success"]({message:"Notification",description:i.data.message})})}},{ifShow:p.hasPermission("Supplier.update"),title:"Deactive",action(t){return C.post(k+"/activeList",{items:t,status:"D"}).then(i=>{N[i.data.code==0?"error":"success"]({message:"Notification",description:i.data.message})})}}]});let y=()=>{};X(Z.currentRoute,t=>{t.path==="/"+k&&y()});function S({reload:t}){y=t}const v=()=>{f.value=!1,y()};return(t,i)=>{const h=n("a-button"),c=n("a-popconfirm"),b=n("a-tag"),P=n("a-drawer");return g(),A(T,null,[e(J,null,{default:a(()=>[e(Q,null,{default:a(()=>[e(r(le),ee(r(l),{onRegister:S}),{"cellAction[delete]":a(({item:s,actionMethod:u})=>[e(c,{title:"Do you want delete this?","ok-text":"Yes","cancel-text":"No",onConfirm:u},{default:a(()=>[e(h,{type:"text",danger:"",icon:R(r(te)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cellAction[edit]":a(({item:s,actionMethod:u})=>[e(h,{type:"text",icon:R(r(ie)),label:"",outline:!0,onClick:u},null,8,["icon","onClick"])]),"cell[id]":a(({item:s,column:u})=>[d("div",null,ae(s.id),1)]),"cell[logo]":a(({item:s,column:u})=>[s.image_url?(g(),A("img",{key:0,class:"w-20 h-auto float-left",src:s.image_url,alt:s.name},null,8,ge)):(g(),A("img",ye))]),"cell[status]":a(({item:s,column:u})=>[s.status=="A"?(g(),$(b,{key:0,color:"green"},{default:a(()=>[D("Active")]),_:1})):E("",!0),s.status=="D"?(g(),$(b,{key:1,color:"green"},{default:a(()=>[D("Deactive")]),_:1})):E("",!0)]),_:1},16)]),_:1})]),_:1}),e(P,{closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:v,open:f.value,width:"80vw",maskClosable:!1},{default:a(()=>{var s;return[(g(),$(he,{value:m.value,onClose:v,key:(s=m.value.id)!=null?s:0},null,8,["value"]))]}),_:1},8,["open"])],64)}}};export{Ue as default};
