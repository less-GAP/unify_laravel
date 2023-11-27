import{_ as W}from"./no_image_available.915c4da6.js";import{d as G,u as H,e as m,a6 as Y,r as i,o as f,c as A,a as e,h as t,b as p,m as u,ab as L,ae as $,aM as J,k,p as D,l as U,W as X,F as R,A as Q,t as Z,Y as ee,v as te,i as ae,I as oe,L as le,x as ne,H as j,X as se,q as M}from"./index.a683e63c.js";import{D as ie}from"./DataTable.1047e308.js";import{U as ue}from"./UseEloquentRouter.7e988003.js";import{U as re}from"./UseDataTable.106f51b0.js";/* empty css              */import{c as ce}from"./jodit-vue.05e762d7.js";import{I as de}from"./InputUploadGetPath.6754ab8e.js";import{j as pe}from"./jodit.min.b5f3759b.js";import{F as me}from"./FormOutlined.646d2a07.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const fe={class:"p-3 bg-gray-200"},_e={class:"flex"},ve=p("span",{class:"ml-1 text-stone-500"},"Back",-1),he={class:"flex"},ge=p("span",{class:"ml-1 text-stone-500"},"Back",-1),be={class:"flex"},we=p("span",{class:"ml-1 text-white"},"Save",-1),ye={class:"mx-4"},ke="product",xe=G({__name:"Detail",props:{value:{type:Object,default:{}}},emits:["close"],setup(V,{emit:x}){const c=V;H();const v=m(!1),_=m("1");m(!1);const h=m(!1),g=m(),o=m({status:"A"});c.value.id>0?o.value=c.value:o.value={status:"A"},Y(()=>{o.value=c.value});const C=()=>{g.value.validate().then(()=>{Q.post(ke,Z(o.value)).then(s=>{s.data.code==1&&(g.value.resetFields(),x("close"))})}).catch(s=>{})},b=function(){x("close")};return(s,a)=>{const d=i("a-button"),P=i("a-space"),w=i("a-radio-button"),z=i("a-radio-group"),n=i("a-form-item"),r=i("a-input"),S=i("a-col"),I=i("a-textarea"),B=i("a-row"),F=i("a-tab-pane"),T=i("a-tabs"),E=i("a-form"),O=i("a-modal");return f(),A(R,null,[e(E,{layout:"vertical",ref_key:"formRef",ref:g,model:o.value,onFinish:C,class:"w-full"},{default:t(()=>[p("div",fe,[e(d,{class:"!hidden md:!inline-block",type:"link",onClick:b},{icon:t(()=>[p("div",_e,[e(u(L),{path:u($),class:"w-4 text-stone-500"},null,8,["path"]),ve])]),_:1}),e(d,{class:"!inline-flex items-center justify-center md:!hidden !w-8 !h-8 !p-0",type:"link",onClick:b},{icon:t(()=>[p("div",he,[e(u(L),{path:u($),class:"w-4 text-stone-500"},null,8,["path"]),ge])]),_:1}),e(P,{class:"float-right"},{default:t(()=>[e(d,{onClick:a[0]||(a[0]=l=>C()),type:"primary"},{default:t(()=>[p("div",be,[e(u(L),{path:u(J),class:"w-4 text-white"},null,8,["path"]),we])]),_:1})]),_:1})]),p("div",ye,[e(T,{activeKey:_.value,"onUpdate:activeKey":a[11]||(a[11]=l=>_.value=l)},{default:t(()=>[e(F,{key:"1",tab:"General"},{default:t(()=>[e(B,{gutter:20},{default:t(()=>[e(S,{span:24},{default:t(()=>[e(n,{label:"Status",name:"status",rules:[{required:!0,message:"Select a status!"}]},{default:t(()=>[e(z,{value:o.value.status,"onUpdate:value":a[1]||(a[1]=l=>o.value.status=l),"button-style":"solid",size:"middle"},{default:t(()=>[e(w,{value:"A"},{default:t(()=>[k("Active")]),_:1}),e(w,{value:"D"},{default:t(()=>[k("Deactive")]),_:1})]),_:1},8,["value"])]),_:1}),e(n,{label:"Name",name:"name",rules:[{required:!0,message:"Please input !"}]},{default:t(()=>[e(r,{value:o.value.name,"onUpdate:value":a[2]||(a[2]=l=>o.value.name=l),placeholder:"Content..."},null,8,["value"])]),_:1})]),_:1}),e(S,{span:8},{default:t(()=>[e(n,{label:"Weight",name:"weight"},{default:t(()=>[e(r,{value:o.value.weight,"onUpdate:value":a[3]||(a[3]=l=>o.value.weight=l),placeholder:"Weight..."},null,8,["value"])]),_:1})]),_:1}),e(S,{span:24},{default:t(()=>[e(n,{label:"Short description"},{default:t(()=>[e(I,{value:o.value.short_description,"onUpdate:value":a[4]||(a[4]=l=>o.value.short_description=l),placeholder:"Content...",rows:4},null,8,["value"])]),_:1}),e(n,{label:"Description",name:"description"},{default:t(()=>[v.value?U("",!0):(f(),D(u(ce),{key:0,style:{height:"40vh"},modelValue:o.value.description,"onUpdate:modelValue":a[5]||(a[5]=l=>o.value.description=l),config:{iframe:!0,height:"40vh",iframeStyle:"html{margin:0;padding:0;min-height: 100%;}body{box-sizing:border-box;font-family:roboto;font-size:16px;line-height:1.6;padding:10px;margin:0;background:transparent;color:#000;position:relative;z-index:2;user-select:auto;margin:0px;overflow:auto;outline:none;}table{width:100%;border:none;border-collapse:collapse;empty-cells: show;max-width: 80%;}th,td{padding: 2px 5px;border:1px solid #ccc;-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text}p{margin-top:0;}.jodit_editor .jodit_iframe_wrapper{display: block;clear: both;user-select: none;position: relative;}.jodit_editor .jodit_iframe_wrapper:after {position:absolute;content:'';z-index:1;top:0;left:0;right: 0;bottom: 0;cursor: pointer;display: block;background: rgba(0, 0, 0, 0);} .jodit_disabled{user-select: none;-o-user-select: none;-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;-ms-user-select: none}",toolbarButtonSize:"large",buttons:[...u(pe.exports.Jodit).defaultOptions.buttons,{name:"<svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 512 512'><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M500.3 7.3C507.7 13.3 512 22.4 512 32V176c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48V71L352 90.2V208c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48V64c0-15.3 10.8-28.4 25.7-31.4l160-32c9.4-1.9 19.1 .6 26.6 6.6zM74.7 304l11.8-17.8c5.9-8.9 15.9-14.2 26.6-14.2h61.7c10.7 0 20.7 5.3 26.6 14.2L213.3 304H240c26.5 0 48 21.5 48 48V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V352c0-26.5 21.5-48 48-48H74.7zM192 408a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM478.7 278.3L440.3 368H496c6.7 0 12.6 4.1 15 10.4s.6 13.3-4.4 17.7l-128 112c-5.6 4.9-13.9 5.3-19.9 .9s-8.2-12.4-5.3-19.2L391.7 400H336c-6.7 0-12.6-4.1-15-10.4s-.6-13.3 4.4-17.7l128-112c5.6-4.9 13.9-5.3 19.9-.9s8.2 12.4 5.3 19.2zm-339-59.2c-6.5 6.5-17 6.5-23 0L19.9 119.2c-28-29-26.5-76.9 5-103.9c27-23.5 68.4-19 93.4 6.5l10 10.5 9.5-10.5c25-25.5 65.9-30 93.9-6.5c31 27 32.5 74.9 4.5 103.9l-96.4 99.9z'/></svg>",tooltip:"Select Image",exec:l=>{h.value=!0,s.onSelectImage=function(q){q.forEach(function(N){h.value=!1;const K="<img width=100% title="+N.title+" src="+N.file_url+" />";l.s.insertHTML(K)})}}}]}},null,8,["modelValue","config"]))]),_:1})]),_:1})]),_:1})]),_:1}),e(F,{key:"2",tab:"Additional Infomation"},{default:t(()=>[e(B,{gutter:20},{default:t(()=>[e(S,{span:24},{default:t(()=>[e(n,{style:{width:"100%"},label:"Image"},{default:t(()=>[e(u(de),{autocomplete:"off",value:o.value.image,"onUpdate:value":a[6]||(a[6]=l=>o.value.image=l)},null,8,["value"])]),_:1}),e(n,{label:"Link SEO"},{default:t(()=>[e(r,{value:o.value.slug,"onUpdate:value":a[7]||(a[7]=l=>o.value.slug=l),placeholder:"Content..."},null,8,["value"])]),_:1}),e(n,{label:"Title"},{default:t(()=>[e(r,{value:o.value.title,"onUpdate:value":a[8]||(a[8]=l=>o.value.title=l),placeholder:"Content..."},null,8,["value"])]),_:1}),e(n,{label:"Meta description"},{default:t(()=>[e(I,{value:o.value.meta_description,"onUpdate:value":a[9]||(a[9]=l=>o.value.meta_description=l),placeholder:"Content...",rows:4},null,8,["value"])]),_:1}),e(n,{label:"Meta keyword"},{default:t(()=>[e(I,{value:o.value.meta_keyword,"onUpdate:value":a[10]||(a[10]=l=>o.value.meta_keyword=l),placeholder:"Content...",rows:4},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1}),U("",!0)]),_:1},8,["activeKey"])])]),_:1},8,["model"]),e(O,{"append-to-body":"",open:h.value,"onUpdate:open":a[13]||(a[13]=l=>h.value=l),style:{"z-index":"99999",top:"2vh",height:"98vh"},height:"96vh",width:"90vw",title:"Select file"},{footer:t(()=>[]),default:t(()=>[e(u(X),{multiple:!0,onClose:a[12]||(a[12]=l=>h.value=!1),onSelect:s.onSelectImage},null,8,["onSelect"])]),_:1},8,["open"])],64)}}});const Ce=["src","alt"],Se={key:1,class:"w-20 h-auto float-left",src:W},y="product",$e={__name:"index",setup(V){const{fetchListApi:x}=ue(y),c=H(),v=m(!1),_=m({}),g=re(x,{columns:[{title:"Image",key:"image",width:100},{title:"Name",key:"name"},{title:"Slug",key:"slug"},{title:"Inventory",key:"inventory",class:"text-center",width:100},{title:"Status",key:"status",width:100},{title:"Created at",key:"created_at",width:130}],showSelection:!0,showSort:!1,showReload:!0,addAction:{action:s=>{_.value={},v.value=!0},ifShow:c.hasPermission("Product.create")},listActions:[],itemActions:[{ifShow:c.hasPermission("Product.update"),label:"Edit",key:"edit",action:s=>{_.value=s,v.value=!0}},{ifShow:c.hasPermission("Product.delete"),label:"",class:"font-medium text-red-600 dark:text-red-500 hover:underline",icon:ee,key:"delete",action(s,a){Api.delete(y+"/"+s.id).then(d=>{notification[d.data.code==0?"error":"success"]({message:"Notification",description:d.data.message})}).finally(()=>{a()})}}],selectionActions:[{ifShow:c.hasPermission("Product.update"),title:"Active",action(s){return Api.post(y+"/activeList",{items:s,status:"A"}).then(a=>{notification[a.data.code==0?"error":"success"]({message:"Notification",description:a.data.message})})}},{ifShow:c.hasPermission("Product.update"),title:"Deactive",action(s){return Api.post(y+"/activeList",{items:s,status:"D"}).then(a=>{notification[a.data.code==0?"error":"success"]({message:"Notification",description:a.data.message})})}}]});let o=()=>{};te(ae.currentRoute,s=>{s.path==="/"+y&&o()});function C({reload:s}){o=s}const b=()=>{v.value=!1,o()};return(s,a)=>{const d=i("a-button"),P=i("a-popconfirm"),w=i("a-tag"),z=i("a-drawer");return f(),A(R,null,[e(oe,null,{default:t(()=>[e(le,null,{default:t(()=>[e(u(ie),ne(u(g),{onRegister:C}),{"cellAction[delete]":t(({item:n,actionMethod:r})=>[e(P,{title:"Do you want delete this?","ok-text":"Yes","cancel-text":"No",onConfirm:r},{default:t(()=>[e(d,{type:"text",danger:"",icon:j(u(se)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cellAction[edit]":t(({item:n,actionMethod:r})=>[e(d,{type:"text",icon:j(u(me)),label:"",outline:!0,onClick:r},null,8,["icon","onClick"])]),"cell[id]":t(({item:n,column:r})=>[p("div",null,M(n.id),1)]),"cell[image]":t(({item:n,column:r})=>[n.image_url?(f(),A("img",{key:0,class:"w-20 h-auto float-left",src:n.image_url,alt:n.name},null,8,Ce)):(f(),A("img",Se))]),"cell[slug]":t(({item:n,column:r})=>[k(" /"+M(n.slug),1)]),"cell[status]":t(({item:n,column:r})=>[n.status=="A"?(f(),D(w,{key:0,color:"green"},{default:t(()=>[k("Active")]),_:1})):U("",!0),n.status=="D"?(f(),D(w,{key:1,color:"green"},{default:t(()=>[k("Inactive")]),_:1})):U("",!0)]),_:1},16)]),_:1})]),_:1}),e(z,{closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:b,open:v.value,width:"80vw",maskClosable:!1},{default:t(()=>{var n;return[(f(),D(xe,{value:_.value,onClose:b,key:(n=_.value.id)!=null?n:0},null,8,["value"]))]}),_:1},8,["open"])],64)}}};export{$e as default};