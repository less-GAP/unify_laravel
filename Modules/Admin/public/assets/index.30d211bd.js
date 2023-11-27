import{r as u,p as _,h as o,I as y,o as c,a as n,m as l,a0 as A,N,O as C,H as f,X as B,l as D,k as d,q as L,c as g,L as O,b as x,A as r,i as h,Y as k,Z as m}from"./index.a683e63c.js";import{D as S}from"./DataTable.1047e308.js";import{F as T}from"./FormOutlined.646d2a07.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const V={key:0,class:"flex items-center"},w=x("div",{class:"h-2.5 w-2.5 rounded-full bg-red-500 mr-2"},null,-1),I={key:1,class:"flex items-center"},F=x("div",{class:"h-2.5 w-2.5 rounded-full bg-green-500 mr-2"},null,-1),z={__name:"index",setup(P){const v={api:e=>r.get("customer-group/list",{params:e}),addAction:e=>{h.push("/customer-group/0")},itemActions:[{label:"",class:"font-medium text-blue-600 dark:text-blue-500 hover:underline",icon:k,key:"edit",action(e,t){h.push("/customer-group/"+e.id)}},{label:"",class:"font-medium text-red-600 dark:text-red-500 hover:underline",icon:k,key:"delete",action(e,t){r.delete("customer-group/"+e.id).then(i=>{m[i.data.code==0?"error":"success"]({message:"Notification",description:i.data.message})}).finally(()=>{t()})}}],columns:[{title:"Group name",key:"name"},{title:"Status",key:"status"}],selectionActions:[{title:"Active",action(e){return r.post("customer-group/activeList",{items:e,status:"A"}).then(t=>{m[t.data.code==0?"error":"success"]({message:"Notification",description:t.data.message})})}},{title:"Inactive",action(e){return r.post("customer-group/activeList",{items:e,status:"D"}).then(t=>{m[t.data.code==0?"error":"success"]({message:"Notification",description:t.data.message})})}}]};return(e,t)=>{const i=u("SectionTitleLineWithButton"),p=u("a-button"),b=u("a-popconfirm");return c(),_(y,null,{default:o(()=>[n(O,null,{default:o(()=>[n(i,{icon:l(A),title:"User Management",main:""},null,8,["icon"]),n(l(S),N(C(v)),{"cellAction[delete]":o(({item:a,actionMethod:s})=>[n(b,{title:"Are you sure delete this user?","ok-text":"Yes","cancel-text":"No",onConfirm:s},{default:o(()=>[a.role!=="admin"?(c(),_(p,{key:0,type:"text",danger:"",icon:f(l(B)),label:"",outline:!0},null,8,["icon"])):D("",!0)]),_:2},1032,["onConfirm"])]),"cellAction[edit]":o(({item:a,actionMethod:s})=>[n(p,{type:"text",icon:f(l(T)),label:"",outline:!0,onClick:s},null,8,["icon","onClick"])]),"cell[full_name]":o(({item:a,column:s})=>[d(L(a.name),1)]),"cell[status]":o(({item:a,column:s})=>[a.status=="D"?(c(),g("div",V,[w,d(" Inactive ")])):(c(),g("div",I,[F,d(" Active ")]))]),_:1},16)]),_:1})]),_:1})}}};export{z as default};
