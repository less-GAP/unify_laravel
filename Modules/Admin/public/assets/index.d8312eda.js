import{a as r,$ as M,u as H,e as A,i as P,A as Y,Y as I,v as R,r as C,c as p,h as a,I as U,F,o as n,L,m as _,x as X,H as O,X as G,ai as J,aj as Q,k as v,q as u,b as g,l as i,a7 as W,p as k,t as Z}from"./index.a683e63c.js";import{D as K}from"./DataTable.1047e308.js";import{U as ee}from"./UseEloquentRouter.7e988003.js";import{U as te}from"./UseDataTable.106f51b0.js";import{_ as ae,S as ne}from"./Detail.vue_vue_type_script_setup_true_lang.dcd27795.js";import{F as oe}from"./FormOutlined.646d2a07.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";import"./no_image_available.915c4da6.js";/* empty css              */import"./Patient.bb818edd.js";import"./RemoteSelect.5cb10285.js";var le={icon:{tag:"svg",attrs:{viewBox:"64 64 896 896",focusable:"false"},children:[{tag:"path",attrs:{d:"M433.1 657.7a31.8 31.8 0 0051.7 0l210.6-292c3.8-5.3 0-12.7-6.5-12.7H642c-10.2 0-19.9 4.9-25.9 13.3L459 584.3l-71.2-98.8c-6-8.3-15.6-13.3-25.9-13.3H315c-6.5 0-10.3 7.4-6.5 12.7l124.6 172.8z"}},{tag:"path",attrs:{d:"M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z"}}]},name:"check-square",theme:"outlined"};const ie=le;function T(h){for(var d=1;d<arguments.length;d++){var o=arguments[d]!=null?Object(arguments[d]):{},f=Object.keys(o);typeof Object.getOwnPropertySymbols=="function"&&(f=f.concat(Object.getOwnPropertySymbols(o).filter(function(y){return Object.getOwnPropertyDescriptor(o,y).enumerable}))),f.forEach(function(y){se(h,y,o[y])})}return h}function se(h,d,o){return d in h?Object.defineProperty(h,d,{value:o,enumerable:!0,configurable:!0,writable:!0}):h[d]=o,h}var q=function(d,o){var f=T({},d,o.attrs);return r(M,T({},f,{icon:ie}),null)};q.displayName="CheckSquareOutlined";q.inheritAttrs=!1;const re=q,ce={key:0},ue=g("br",null,null,-1),de={key:0},pe={key:1},he={key:2},fe={key:3},_e={key:4},me={key:0},ye=g("br",null,null,-1),ve={key:0},ke={key:1},be={key:2},m="order",Te={__name:"index",setup(h){const{fetchListApi:d}=ee(m),o=H(),f=A(!1),y=A({}),V=te(d,{columns:[{title:"Order ID",dataIndex:"id",key:"id",width:50,render:(t,c,l)=>(++l,l),class:"text-center"},{title:"Patient",key:"patient_id"},{title:"Shipping",key:"shipping_id",width:200,class:"text-left"},{title:"Delivery Date",key:"delivery_date",width:100},{title:"Created By",key:"created_by",width:100},{title:"Created at",key:"created_at",width:130},{title:"Status",key:"status",width:130}],showSelection:!1,showSort:!1,showReload:!0,addAction:{action:t=>{P.push(m+"/0")},ifShow:o.hasPermission("Order.create")},listActions:[],itemActions:[{ifShow:o.hasPermission("Order.menu"),label:"View",key:"view",action:t=>{P.push(m+"/view/"+t.id)}},{ifShow:o.hasPermission("Order.update"),label:"Edit",key:"edit",action:t=>{P.push(m+"/"+t.id)}},{ifShow:o.hasPermission("Order.assign"),label:"Assign",key:"assign",action:t=>{var c={order_id:t.id,shipping_id:t.shipping_id,shipper_phone:t.shipper_phone,shipper_email:t.shipper_email};z(c)}},{ifShow:o.hasPermission("Order.updateStatus"),label:"updateStatus",key:"updateStatus",confirm:!0,action:(t,c)=>{var l="confirmed";t.status=="confirmed"?l="delivering":t.status=="delivering"&&(l="delivered"),Y.post(m+"/updateStatus",{id:t.id,status:l}).then(w=>{notification[w.data.code==0?"error":"success"]({message:"Notification",description:w.data.message})}).finally(()=>{c()})}},{ifShow:o.hasPermission("Order.delete"),label:"",class:"font-medium text-red-600 dark:text-red-500 hover:underline",icon:I,key:"delete",action(t,c){Y.delete(m+"/"+t.id).then(l=>{notification[l.data.code==0?"error":"success"]({message:"Notification",description:l.data.message})}).finally(()=>{c()})}}],selectionActions:[]});let x=()=>{};R(P.currentRoute,t=>{t.path==="/"+m&&x()});function $({reload:t}){x=t}const N=()=>{f.value=!1,x()},S=A(!1),D=A({}),j=t=>{Y.post(m+"/assign",Z(t)).then(c=>{c.data.code==1&&(S.value=!1,x())})},z=t=>{D.value=t,S.value=!0};return(t,c)=>{const l=C("a-button"),w=C("a-popconfirm"),b=C("a-tag"),B=C("a-drawer"),E=C("a-modal");return n(),p(F,null,[r(U,null,{default:a(()=>[r(L,null,{default:a(()=>[r(_(K),X(_(V),{onRegister:$}),{"cellAction[delete]":a(({item:e,actionMethod:s})=>[r(w,{title:"Do you want delete this?","ok-text":"Yes","cancel-text":"No",onConfirm:s},{default:a(()=>[r(l,{type:"text",danger:"",icon:O(_(G)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cellAction[edit]":a(({item:e,actionMethod:s})=>[r(l,{type:"text",icon:O(_(oe)),label:"",outline:!0,onClick:s},null,8,["icon","onClick"])]),"cellAction[view]":a(({item:e,actionMethod:s})=>[r(l,{type:"text",icon:O(_(J)),label:"",outline:!0,onClick:s},null,8,["icon","onClick"])]),"cellAction[assign]":a(({item:e,actionMethod:s})=>[r(l,{type:"text",icon:O(_(Q)),label:"",outline:!0,onClick:s},null,8,["icon","onClick"])]),"cellAction[updateStatus]":a(({item:e,actionMethod:s})=>[r(w,{title:"Do you confirm this?","ok-text":"Yes","cancel-text":"No",onConfirm:s},{default:a(()=>[r(l,{type:"text",icon:O(_(re)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cell[type]":a(({item:e,column:s})=>[r(b,{class:"capitalize",color:e.type=="in"?"green":"red"},{default:a(()=>[v(u(e.type),1)]),_:2},1032,["color"])]),"cell[patient_id]":a(({item:e,column:s})=>[e.patient?(n(),p("div",ce,[g("span",null,[g("b",null,"#"+u(e.patient.unify_number)+" - "+u(e.patient.full_name),1)]),ue,e.phone?(n(),p("span",de,"Tel: "+u(e.phone),1)):i("",!0),e.phone?(n(),p("br",pe)):i("",!0),e.email?(n(),p("span",he,u(e.email),1)):i("",!0),e.email?(n(),p("br",fe)):i("",!0),e.street?(n(),p("span",_e,u(e.street)+", "+u(e.city)+", "+u(e.state)+", "+u(e.zip),1)):i("",!0)])):i("",!0)]),"cell[shipping_id]":a(({item:e,column:s})=>[e.shipping_id?(n(),p("div",me,[g("span",null,[g("b",null,u(e.shipper_name),1)]),ye,e.shipper_phone?(n(),p("span",ve,"Tel: "+u(e.shipper_phone),1)):i("",!0),e.shipper_phone?(n(),p("br",ke)):i("",!0),e.shipper_email?(n(),p("span",be,u(e.shipper_email),1)):i("",!0)])):i("",!0)]),"cell[delivery_date]":a(({item:e,column:s})=>[v(u(_(W)(e.delivery_date,"YYYY-MM-DD").format("MM-DD-YYYY")),1)]),"cell[status]":a(({item:e,column:s})=>[e.status=="new"?(n(),k(b,{key:0,color:"#2db7f5"},{default:a(()=>[v("New")]),_:1})):i("",!0),e.status=="confirmed"?(n(),k(b,{key:1,color:"#108ee9"},{default:a(()=>[v("Confirmed")]),_:1})):i("",!0),e.status=="delivering"?(n(),k(b,{key:2,color:"#87d068"},{default:a(()=>[v("Delivering")]),_:1})):i("",!0),e.status=="delivered"?(n(),k(b,{key:3,color:"#A8E18C"},{default:a(()=>[v("Delivered")]),_:1})):i("",!0),e.status=="done"?(n(),k(b,{key:4,color:"#869D7A"},{default:a(()=>[v("Done")]),_:1})):i("",!0)]),_:1},16)]),_:1})]),_:1}),r(B,{closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:N,open:f.value,width:"80vw",maskClosable:!1},{default:a(()=>[(n(),k(ae,{value:y.value,onClose:N,key:y.value.id},null,8,["value"]))]),_:1},8,["open"]),r(E,{"append-to-body":"",open:S.value,"onUpdate:open":c[1]||(c[1]=e=>S.value=e),zIndex:10,width:"40%",title:"Select Shipper",closable:!0,footer:null,maskClosable:!1},{default:a(()=>[(n(),k(ne,{value:D.value,onClose:c[0]||(c[0]=e=>S.value=!1),onSelect:j,key:D.value.id?D.value:0},null,8,["value"]))]),_:1},8,["open"])],64)}}};export{Te as default};