import{_ as pe,M as he,u as ne,e as C,v as fe,r as k,o as t,c as f,K as Y,N as U,O as Z,b as i,a as d,h as a,p as u,y as ye,l as p,m as n,F as S,n as O,k as b,q as g,R as _e,P as le,Q as se,S as I,x as K,al as ve,C as ge,t as ke,E as me,G as be,A as we,i as R,I as xe,L as Ce,ab as j,am as Ae,an as Se,ao as Pe,ap as $e,H as De,X as Te,aq as Ue,ac as Ye,ad as Oe,a7 as q}from"./index.a683e63c.js";import{a as je,b as ze}from"./Patient.e2eb7f50.js";import{U as Ee}from"./UseEloquentRouter.7e988003.js";import{U as Me}from"./UseDataTable.106f51b0.js";import"./Detail.vue_vue_type_style_index_0_lang.203a6c28.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";import"./RemoteSelect.5cb10285.js";const Fe=o=>(me("data-v-fecee43a"),o=o(),be(),o),Ne={id:"table-list",class:"flex flex-col sm:rounded-lg"},Re=["loading"],Be=Fe(()=>i("span",null,null,-1)),Ie={class:"flex-1 w-full my-5 overflow-auto bg-white border border-gray-200 rounded-lg shadow scroll-smooth"},Le={class:"w-full text-xs table-auto"},He={class:"font-semibold text-gray-400 uppercase bg-gray-50"},Ke={key:0,scope:"col",width:"40",class:"px-1 py-2 text-center whitespace-nowrap"},Ve={class:"w-full font-medium text-gray-900"},qe=["width"],Ge={class:"font-semibold text-left"},We={key:1,width:"120",scope:"col",class:"px-1 py-2"},Je={class:"divide-y divide-gray-100"},Qe={key:0,width:"40",class:"p-1 text-center whitespace-nowrap"},Xe=["for"],Ze=["id","value"],et=["data-label"],tt={key:0,class:"flex"},at=["src"],lt={key:1,width:"120",class:"p-1 whitespace-nowrap"},st={class:"flex flex-nowrap whitespace-nowrap"},ot={key:0},nt={key:1},it={__name:"DataTablePatient",props:{tableConfig:{type:Object,default:{}},pagination:{type:Object,default:{page:1,total:0,perPage:20}},versionKey:{type:String,default:null},showSizeChanger:{type:Boolean,default:!0},showReload:{type:Boolean,default:!0},showSelection:{type:Boolean,default:!0},showSort:{type:[Boolean,Array],default:!1},params:{type:Object,default:{}},sort:{type:String,default:"-id"},filter:{type:Object,default:{}},columns:Array,selectionActions:{type:Array,default:[]},itemActions:{type:Array,default:[]},listActions:{type:Array,default:[]},api:Function,addAction:Object},emits:["register"],setup(o,{emit:G}){const h=o,W=he(),z=ne(),P={item_key:"id",rowSelect:!0,globalSearch:!0,...h.tableConfig},w=C({}),m=C({search:"",...h.filter}),L=C(h.sort);function y(){let s={};for(let r in m.value)s["filter["+r+"]"]=m.value[r];return s}const ee=function(){return we.get("user/all")},D=C([]),T=C([]),J=C([{value:"waiting",label:"Waiting"},{value:"eligibility_check",label:"Eligibility Check"},{value:"active",label:"Active"},{value:"inactive",label:"Inactive"},{value:"decease",label:"Decease"},{value:"trashed",label:"Trashed"}]);async function _(s=!1){var V;if(h.api){s&&(x.value=!0);try{D.value=await je(),T.value=await ee().then(v=>v.data),T.value.map(v=>{v.value=v.id,v.label=v.full_name});var r=await ze().then(v=>v.data);r=JSON.parse(r.data);const A=await h.api({perPage:h.pagination.perPage,sort:L.value,page:h.pagination.page,...h.params,...y()});w.value=A.data,h.pagination.total=(V=A.data)!=null&&V.total?A.data.total:0;const Q=w.value.data.map(async v=>{if(v.unify_task_status!==null){const $=r.find(F=>F.value==v.unify_task_status);v.unify_task_status=$}v.assignees=[],v.tasks!==null&&v.tasks.forEach($=>{$.assignees!==null&&$.assignees.forEach(F=>{v.assignees.findIndex(X=>X.id==F.id)==-1&&v.assignees.push(F)})})});await Promise.all(Q)}catch(A){console.error("Error reloading data:",A)}finally{E.value=!1,x.value=!1}}}G("register",{reload:_,filter:m});const x=C(!1),E=C(!1),M=C([]);function l(){E.value?M.value=ke((w==null?void 0:w.value.data)||[]):M.value=[]}return fe(()=>W.versions[h.versionKey],()=>{_()}),_(!0),(s,r)=>{var te,ae;const V=k("a-input-search"),A=k("a-select"),Q=k("a-select-option"),v=k("a-space"),$=k("a-button"),F=k("a-skeleton"),X=k("a-tag"),ie=k("a-tooltip"),re=k("a-avatar-group"),ue=k("a-empty"),ce=k("a-pagination");return t(),f("div",Ne,[Y(s.$slots,"header",U(Z({tableConfig:P,filter:m.value,reload:_})),void 0,!0),i("div",{loading:x.value,class:"flex items-center justify-between p-2 bg-white border border-gray-200 rounded-xl"},[d(v,null,{default:a(()=>[P.globalSearch?(t(),u(V,{key:0,value:m.value.search,"onUpdate:value":r[0]||(r[0]=e=>m.value.search=e),"allow-clear":"",style:{"max-width":"380px"},placeholder:"Search by name, number, phone",loading:x.value,onSearch:_,onKeyup:ye(_,["enter"])},null,8,["value","loading","onKeyup"])):p("",!0),n(z).hasPermission("patient.filter.doctor")?(t(),u(A,{key:1,allowClear:!0,value:m.value.doctor_id,"onUpdate:value":r[1]||(r[1]=e=>m.value.doctor_id=e),"show-search":"",style:{width:"200px"},placeholder:"Filter by doctor",options:D.value,onChange:_},null,8,["value","options"])):p("",!0),n(z).hasPermission("patient.filter.seller")?(t(),u(A,{key:2,allowClear:!0,value:m.value.sale_user,"onUpdate:value":r[2]||(r[2]=e=>m.value.sale_user=e),"show-search":"",style:{width:"200px"},placeholder:"Filter by creator",options:T.value,onChange:_},null,8,["value","options"])):p("",!0),n(z).hasPermission("patient.filter.status")?(t(),u(A,{key:3,allowClear:!0,value:m.value.status,"onUpdate:value":r[3]||(r[3]=e=>m.value.status=e),"show-search":"",style:{width:"200px"},placeholder:"Filter by status",options:J.value,onChange:_},null,8,["value","options"])):p("",!0),Y(s.$slots,"sort",U(Z({tableConfig:P,sort:o.sort,filter:m.value,reload:_})),()=>[o.showSort?(t(),u(A,{key:0,allowClear:!0,value:L.value,"onUpdate:value":r[4]||(r[4]=e=>L.value=e),style:{width:"140px"},placeholder:"Order by...",onChange:_},{default:a(()=>[(t(!0),f(S,null,O(o.showSort,e=>(t(),u(Q,{key:e.value,value:e.value},{default:a(()=>[b(g(e.label),1)]),_:2},1032,["value"]))),128))]),_:1},8,["value"])):p("",!0)],!0)]),_:3}),Be,d(v,null,{default:a(()=>[o.showReload?(t(),u($,{key:0},{icon:a(()=>[d(n(_e),{onClick:_})]),_:1})):p("",!0),(t(!0),f(S,null,O(o.listActions,e=>(t(),u($,{type:"primary",onClick:()=>{if(e.permission)e.action(_);else return!1}},{default:a(()=>[b(g(e.label),1)]),_:2},1032,["onClick"]))),256)),o.addAction&&o.addAction.ifShow?(t(),u($,{key:1,type:"primary",onClick:r[5]||(r[5]=()=>{o.addAction.action(_)})},{default:a(()=>[b("Add")]),_:1})):p("",!0)]),_:1})],8,Re),i("div",Ie,[x.value||!w.value.data?(t(),u(F,{key:0,active:"",class:"p-10"})):Y(s.$slots,"table",U(K({key:1},{tableConfig:P,tableData:w.value,columns:o.columns,selectionActions:o.selectionActions,reload:_})),()=>[i("table",Le,[i("thead",He,[i("tr",null,[o.showSelection?(t(),f("th",Ke,[i("label",Ve,[le(i("input",{"onUpdate:modelValue":r[6]||(r[6]=e=>E.value=e),value:!0,type:"checkbox",class:"w-4 h-4 text-blue-600 rounded focus:ring-blue-500",onChange:l},null,544),[[se,E.value]])])])):p("",!0),(t(!0),f(S,null,O(o.columns,e=>(t(),f("th",{scope:"col",width:e.width?e.width:"auto",class:"px-1 py-2 whitespace-nowrap"},[i("div",Ge,g(s.__(e.title)),1)],8,qe))),256)),o.itemActions.length?(t(),f("th",We,g(s.__("Action")),1)):p("",!0)])]),i("tbody",Je,[(t(!0),f(S,null,O(w.value.data,(e,N)=>(t(),f("tr",{key:e[P.item_key],class:I({"border-b":N%2===0})},[o.showSelection?(t(),f("td",Qe,[i("label",{for:"checkbox-table-search-"+e[P.item_key],class:"w-full font-medium text-gray-900"},[le(i("input",{id:e[P.item_key],"onUpdate:modelValue":r[7]||(r[7]=c=>M.value=c),value:e,type:"checkbox",class:"w-4 h-4 text-blue-600 rounded focus:ring-blue-500"},null,8,Ze),[[se,M.value]])],8,Xe)])):p("",!0),(t(!0),f(S,null,O(o.columns,c=>(t(),f("td",{"data-label":c.title,class:I("p-1 "+(c.class?c.class:""))},[e.render?(t(),f(S,{key:0},[b(g(e.render()),1)],64)):c.key=="unify_task_status"?Y(s.$slots,"default",U(K({key:1},{item:e,column:c,index:N})),()=>[e.unify_task_status!==null?(t(),u(X,{key:0,style:ve("background-color: "+e.unify_task_status.background_color+"; color: "+e.unify_task_status.color+"; border-color: currentColor")},{default:a(()=>[b(g(e.unify_task_status.label),1)]),_:2},1032,["style"])):p("",!0)],!0):c.key=="assigned"?Y(s.$slots,"default",U(K({key:2},{item:e,column:c,index:N})),()=>[e.assignees?(t(),f("div",tt,[(t(!0),f(S,null,O(e.assignees,(H,de)=>(t(),f("div",{key:de,class:"item-assignee"},[d(re,null,{default:a(()=>[d(ie,{title:H.full_name,placement:"top"},{default:a(()=>[i("img",{class:"relative w-8 h-8 border border-white rounded-full -me-1 hover:z-10",src:H==null?void 0:H.profile_photo_url,alt:"user photo"},null,8,at)]),_:2},1032,["title"])]),_:2},1024)]))),128))])):p("",!0)],!0):Y(s.$slots,"cell["+c.key+"]",U(K({key:3},{item:e,column:c,index:N})),()=>[b(g(s.$style.format[c.key]?s.$style.format[c.key](e[c.key]):e[c.key]),1)],!0)],10,et))),256)),o.itemActions.length?(t(),f("td",lt,[i("div",st,[(t(!0),f(S,null,O(o.itemActions,c=>(t(),f(S,null,[c.ifShow?Y(s.$slots,"cellAction["+c.key+"]",U(K({key:0},{item:e,itemAction:c,actionMethod(){c.action(e,_)}})),()=>[d($,{class:I(c.class||"font-medium text-blue-600 dark:text-blue-500 hover:underline"),type:"link",onClick:H=>c.action(e,_)},{default:a(()=>[b(g(c.label),1)]),_:2},1032,["class","onClick"])],!0):p("",!0)],64))),256))])])):p("",!0)],2))),128))])])],!0),((te=w.value.data)==null?void 0:te.length)===0&&o.pagination.total===0?(t(),u(ue,{key:2,class:"my-10",description:!1})):p("",!0)]),w.value.data&&((ae=o.pagination)==null?void 0:ae.total)?(t(),u(ce,{key:0,current:o.pagination.page,"onUpdate:current":r[8]||(r[8]=e=>o.pagination.page=e),pageSize:o.pagination.perPage,"onUpdate:pageSize":r[9]||(r[9]=e=>o.pagination.perPage=e),style:{height:"40px"},class:"pt-2","show-size-changer":o.showSizeChanger,total:o.pagination.total,onChange:_},{itemRender:a(({type:e,originalElement:N})=>[e==="prev"?(t(),f("a",ot,"Previous")):e==="next"?(t(),f("a",nt,"Next")):(t(),u(ge(N),{key:2}))]),_:1},8,["current","pageSize","show-size-changer","total"])):p("",!0)])}}},rt=pe(it,[["__scopeId","data-v-fecee43a"]]),ut=i("h2",null,"Patient List",-1),ct={class:"flex justify-center"},dt={class:"flex flex-row items-center"},pt={class:"pl-1"},ht=["onClick"],ft={class:"flex"},yt=i("strong",null,"Tel:",-1),_t={class:"block max-w-[380px] truncate"},vt=i("br",null,null,-1),gt={class:"text-[11px] text-gray-400"},kt=i("div",{class:"pt-1 leading-none"},"Active",-1),mt={class:"pb-1 leading-none"},B="patient",bt="patients",wt="MM-DD-YYYY",oe="YYYY-MM-DD",Tt={__name:"index",setup(o){const{fetchListApi:G}=Ee(B),h=ne();var W={};const z=C(!1);C({});const P=y=>y.dob?q(y.dob,oe).format(wt):"-",w=y=>y.dob?"("+q().diff(q(y.dob,oe),"year")+" years old)":"-",m=Me(G,{versionKey:bt,showSelection:!1,showReload:!0,showSort:[{label:"Latest",value:"-id"},{label:"Oldest",value:"id"},{label:"Name Alphabet",value:"name"},{label:"Last Updated",value:"-updated_at"}],columns:[{title:"",key:"status",width:30},{title:"Number",key:"unify_number",width:70},{title:"Patient",key:"full_name",width:""},{title:"STATUS",key:"unify_status",width:100},{title:"Task Status",key:"unify_task_status",width:125},{title:"DOB",key:"dob",width:100},{title:"Doctor",key:"doctor_id",width:200},{title:"INFO",key:"info",width:400},{title:"CREATED AT",key:"created_at",width:130},{title:"Assigned",key:"assigned",width:120}],addAction:{action:y=>{R.push(B+"/edit/0")},ifShow:h.hasPermission("Patient.create")},listActions:[],itemActions:[{ifShow:h.hasPermission("Patient.update"),label:"Edit",key:"edit",show:y=>!0,action:y=>{R.push(B+"/edit/"+y.id)}},{ifShow:h.hasPermission("Patient.approveRunning")&&W.unify_process<2,label:"Approve Patient",key:"editProcess",action:y=>{R.replace(B+"/process/"+y.id)}},{ifShow:!0,label:"Task",key:"addTask",show:y=>!0,action:y=>{R.replace(B+"/task/"+y.id)}},{ifShow:!0,label:"History",key:"history",show:y=>!0,action:y=>{R.replace(B+"/history/"+y.id)}}]}),L=()=>{z.value=!1};return(y,ee)=>{const D=k("a-button"),T=k("a-tooltip"),J=k("a-popconfirm"),_=k("a-image"),x=k("a-tag"),E=k("router-view"),M=k("a-drawer");return t(),f(S,null,[d(xe,null,{default:a(()=>[d(Ce,null,{default:a(()=>[d(n(rt),U(Z(n(m))),{header:a(()=>[ut]),"cellAction[edit]":a(({item:l,actionMethod:s})=>[n(h).hasPermission("Patient.update")?(t(),u(T,{key:0,title:"Edit",class:"mr-1"},{default:a(()=>[d(D,{class:I(["justify-center !flex !p-1 !h-auto",n(h).hasPermission("patient.edit")?"":"!bg-gray-300 opacity-50"]),onClick:s},{default:a(()=>[d(n(j),{path:n(Ae),class:"w-4 !fill-blue-200"},null,8,["path"])]),_:2},1032,["class","onClick"])]),_:2},1024)):p("",!0)]),"cellAction[editProcess]":a(({item:l,actionMethod:s})=>[d(T,{title:"Approve Patient",class:"mr-1"},{default:a(()=>[d(D,{class:"justify-center !flex !p-1 !h-auto",onClick:s},{default:a(()=>[d(n(j),{path:n(Se),class:"w-4"},null,8,["path"])]),_:2},1032,["onClick"])]),_:2},1024)]),"cellAction[addTask]":a(({item:l,actionMethod:s})=>[n(h).hasPermission("Task.list")?(t(),u(T,{key:0,title:"View Task",class:"mr-1"},{default:a(()=>[d(D,{class:I(["justify-center !flex !p-1 !h-auto",n(h).hasPermission("task.list")?"":"!bg-gray-300 opacity-50"]),disabled:!n(h).hasPermission("task.list"),onClick:s},{default:a(()=>[d(n(j),{path:n(Pe),class:"w-4"},null,8,["path"])]),_:2},1032,["disabled","class","onClick"])]),_:2},1024)):p("",!0)]),"cellAction[history]":a(({item:l,actionMethod:s})=>[n(h).hasPermission("Patient.history")?(t(),u(T,{key:0,title:"View History"},{default:a(()=>[d(D,{class:I(["justify-center !flex !p-1 !h-auto",n(h).hasPermission("patient.history")?"":"!bg-gray-300 opacity-50"]),onClick:s},{default:a(()=>[d(n(j),{path:n($e),class:"w-4"},null,8,["path"])]),_:2},1032,["class","onClick"])]),_:2},1024)):p("",!0)]),"cellAction[delete]":a(({item:l,actionMethod:s})=>[d(J,{title:"Do you want to delete?","ok-text":"Yes","cancel-text":"No",onConfirm:s},{default:a(()=>[d(D,{type:"text",danger:"",icon:De(n(Te)),label:"",outline:!0},null,8,["icon"])]),_:2},1032,["onConfirm"])]),"cell[id]":a(({item:l,column:s})=>[i("div",null,g(l.id),1)]),"cell[status]":a(({item:l,column:s})=>[i("div",ct,[l.need_improve===0&&l.unify_status!==1?(t(),u(n(j),{key:0,path:n(Ue),class:"flex-none text-red-600 !mr-0"},null,8,["path"])):p("",!0)])]),"cell[full_name]":a(({item:l,column:s})=>[i("div",dt,[l.gender===0?(t(),u(n(j),{key:0,path:n(Ye),class:"flex-none !text-blue-600"},null,8,["path"])):p("",!0),l.gender===1?(t(),u(n(j),{key:1,path:n(Oe),class:"flex-none text-pink-600"},null,8,["path"])):p("",!0),i("span",pt,[i("a",{onClick:r=>n(h).hasPermission("Patient.view")?n(R).push("patient/view/"+l.id):"javascript:void(0)",class:"text-blue-700 underline cursor-pointer"},g(l.full_name),9,ht)])])]),"cell[image]":a(({item:l,column:s})=>[d(_,{height:"50px",class:"w-20 h-auto",src:l.image_url,alt:l.name},null,8,["src","alt"])]),"cell[info]":a(({item:l,column:s})=>[i("div",ft,[i("div",null,[yt,b(" "+g(l.phone),1)])]),i("div",_t,g(l.street)+", "+g(l.city)+", "+g(l.state)+", "+g(l.zip),1)]),"cell[dob]":a(({item:l,column:s})=>[i("small",null,g(P(l)),1),vt,i("span",gt,g(w(l)),1)]),"cell[doctor_id]":a(({item:l,column:s})=>[i("small",null,g(l.doctor?l.doctor.full_name:""),1)]),"cell[unify_status]":a(({item:l,column:s})=>[l.unify_deleted===1?(t(),u(x,{key:0,color:"gray"},{default:a(()=>[b("Trashed")]),_:1})):l.unify_process===0?(t(),u(x,{key:1,color:"yellow"},{default:a(()=>[b("Waiting")]),_:1})):l.unify_process===1?(t(),u(x,{key:2,color:"orange"},{default:a(()=>[b("Eligibility Check ")]),_:1})):l.unify_status===1?(t(),u(x,{key:3,color:"green"},{default:a(()=>[kt,i("div",mt,[i("small",null,"("+g(n(q)(l.unify_active,"YYYY-MM-DD HH:mm:ss").format("HH:mm MM-DD-YYYY"))+")",1)])]),_:2},1024)):l.unify_status===2?(t(),u(x,{key:4,color:"gray"},{default:a(()=>[b(" Inactive ")]),_:1})):l.unify_status===3?(t(),u(x,{key:5,color:"gray"},{default:a(()=>[b(" Decease ")]),_:1})):p("",!0)]),_:1},16),d(E)]),_:1})]),_:1}),d(M,{closable:!1,style:{position:"relative",display:"flex","flex-direction":"column",height:"100vh"},onClose:L,open:z.value,width:"90vw"},null,8,["open"])],64)}}};export{Tt as default};
