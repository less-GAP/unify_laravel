import{u as x,v as A,i as D,r,p as N,h as e,I as j,o as S,a as o,m as p,x as T,k as n,q as c,L as B,b as L}from"./index.a683e63c.js";import{D as U}from"./DataTable.1047e308.js";import{U as C}from"./UseEloquentRouter.7e988003.js";import{U as R}from"./UseDataTable.106f51b0.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const V=L("h2",null,"Logs",-1),f="logs",I={__name:"index",setup(q){const{fetchListApi:m}=C(f);x();const d=[],h=[],g=[{title:"Date time",key:"created_at",width:180},{title:"Module",key:"subject_type",width:100},{title:"Name",key:"name_log",width:300},{title:"Work Description",key:"description",width:""},{title:"By user",key:"causer",width:120},{title:"Event",key:"event",width:100}],i=(t,_)=>{if(t&&t.full_name){if(_)return t.full_name;{const s=t.full_name.split(" "),l=s[s.length-1];if(l.length>0)return l.charAt(0)}}return"N/A"},b=t=>t?t.split("\\").pop():"",w=R(m,{columns:g,showSelection:!1,globalSearch:!1,showSort:!1,listActions:h,itemActions:d});let u=()=>{};A(D.currentRoute,t=>{t.path==="/"+f&&u()});function y({reload:t}){u=t}return(t,_)=>{const s=r("a-avatar"),l=r("a-tooltip"),k=r("a-avatar-group"),v=r("router-view");return S(),N(j,null,{default:e(()=>[o(B,null,{default:e(()=>[o(p(U),T(p(w),{onRegister:y}),{header:e(()=>[V]),"cell[subject_id]":e(({item:a})=>[n(c(a.subject_id),1)]),"cell[causer]":e(({item:a})=>[o(k,null,{default:e(()=>[o(l,{title:i(a.causer,!0),placement:"top"},{default:e(()=>[o(s,{style:{"background-color":"#87d068"}},{default:e(()=>[n(c(i(a.causer)),1)]),_:2},1024)]),_:2},1032,["title"])]),_:2},1024)]),"cell[subject_type]":e(({item:a})=>[n(c(b(a.subject_type)),1)]),_:1},16),o(v)]),_:1})]),_:1})}}};export{I as default};
