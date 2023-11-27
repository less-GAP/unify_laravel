import{a as i,$ as d,u as f,a7 as u,Z as c,A as m}from"./index.a683e63c.js";var p={icon:{tag:"svg",attrs:{viewBox:"64 64 896 896",focusable:"false"},children:[{tag:"path",attrs:{d:"M168 504.2c1-43.7 10-86.1 26.9-126 17.3-41 42.1-77.7 73.7-109.4S337 212.3 378 195c42.4-17.9 87.4-27 133.9-27s91.5 9.1 133.8 27A341.5 341.5 0 01755 268.8c9.9 9.9 19.2 20.4 27.8 31.4l-60.2 47a8 8 0 003 14.1l175.7 43c5 1.2 9.9-2.6 9.9-7.7l.8-180.9c0-6.7-7.7-10.5-12.9-6.3l-56.4 44.1C765.8 155.1 646.2 92 511.8 92 282.7 92 96.3 275.6 92 503.8a8 8 0 008 8.2h60c4.4 0 7.9-3.5 8-7.8zm756 7.8h-60c-4.4 0-7.9 3.5-8 7.8-1 43.7-10 86.1-26.9 126-17.3 41-42.1 77.8-73.7 109.4A342.45 342.45 0 01512.1 856a342.24 342.24 0 01-243.2-100.8c-9.9-9.9-19.2-20.4-27.8-31.4l60.2-47a8 8 0 00-3-14.1l-175.7-43c-5-1.2-9.9 2.6-9.9 7.7l-.7 181c0 6.7 7.7 10.5 12.9 6.3l56.4-44.1C258.2 868.9 377.8 932 512.2 932c229.2 0 415.5-183.7 419.8-411.8a8 8 0 00-8-8.2z"}}]},name:"sync",theme:"outlined"};const g=p;function o(t){for(var e=1;e<arguments.length;e++){var s=arguments[e]!=null?Object(arguments[e]):{},n=Object.keys(s);typeof Object.getOwnPropertySymbols=="function"&&(n=n.concat(Object.getOwnPropertySymbols(s).filter(function(a){return Object.getOwnPropertyDescriptor(s,a).enumerable}))),n.forEach(function(a){h(t,a,s[a])})}return t}function h(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var r=function(e,s){var n=o({},e,s.attrs);return i(d,o({},n,{icon:g}),null)};r.displayName="SyncOutlined";r.inheritAttrs=!1;const y=r,v=f(),l=function(t,e){return m.put("task/"+t,e)},b=function(){return[{value:0,label:"Waiting",color:"orange"},{value:1,label:"Working",color:"blue"},{value:2,label:"Completed / Need Review",color:"yellow"},{value:3,label:"Done",color:"green"}]},O=function(t){const e=b();return t===null?e[0]:e.find(s=>s.value===t)},S=function(t,e=null){l(t,{deleted:1,deleted_by:v.user.id,deleted_at:u().format("YYYY-MM-DD HH:mm:ss")}).then(s=>{e&&e(),c.success({message:"Review this task in Archives!"})})},w=function(t,e=null){l(t,{is_completed:1,task_process:2,completed_at:u().format("YYYY-MM-DD HH:mm:ss")}).then(s=>{e&&e(),c.success({message:"You have completed this task!"})})},Y=function(t,e,s=null){l(t,{is_completed:e?1:0,task_process:e?3:0}).then(n=>{s&&s(),c.success({message:e?"You reviewed & accepted this task!":"You reviewed & rejected this task! Assignees will rework!"})})},_=function(t,e=null,s=!1){l(t,{is_completed:0,task_process:s?0:1,completed_at:null}).then(n=>{e&&e(),c.success({message:"You change this task to Working status!"})})};export{y as S,w as c,S as d,O as g,Y as r,_ as w};