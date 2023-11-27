import{e as m,f as b,r as d,p as U,h as l,x as A,A as c,o as x,a as e,k as s}from"./index.a683e63c.js";import{v as I}from"./validateMessages.02f7ed2e.js";import"https://cdn.jsdelivr.net/npm/surrealdb.js";const K={__name:"MarketingConfig",emits:["success","cancel"],setup(B,{emit:v}){const g=["twilio_account_sid","twilio_auth_token","twilio_auth_key","twilio_auth_secret","twilio_auth_flow_id","twilio_phone_number","sendgrid_api_key","sendgrid_from_email","sendgrid_from_name","sendbird_app_id","sendbird_api_token"],p=m("1"),r=m(!1);m(null);const a=b({}),w=b({validateTrigger:"submit","label-align":"top",model:a,labelCol:{span:24},wrapperCol:{span:24},"validate-messages":I});(function(){r.value=!0,c.get("config",{params:{names:g}}).then(u=>{Object.assign(a,u.data)}).catch(u=>{}).finally(r.value=!1)})();const y=async function(){r.value=!0,c.post("config",a).then(u=>{v("success",u)}).catch(u=>{}).finally(r.value=!1)};return(u,t)=>{const _=d("a-Divider"),i=d("a-input"),n=d("a-form-item"),f=d("a-tab-pane"),k=d("a-tabs"),S=d("a-button"),h=d("a-space"),T=d("a-form");return x(),U(T,A({autocomplete:"off"},w,{onFinish:y}),{default:l(()=>[e(k,{activeKey:p.value,"onUpdate:activeKey":t[11]||(t[11]=o=>p.value=o),type:"card"},{default:l(()=>[e(f,{key:"1",tab:"Twilio"},{default:l(()=>[e(_,{class:"!font-bold !text-blue-700",dashed:"",orientation:"left","orientation-margin":"0",plain:""},{default:l(()=>[s("Twilio")]),_:1}),e(n,{name:"twilio_account_sid",label:"Twilio Account SID"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_account_sid,"onUpdate:value":t[0]||(t[0]=o=>a.twilio_account_sid=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"twilio_auth_token",label:"Twilio Auth Token"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_auth_token,"onUpdate:value":t[1]||(t[1]=o=>a.twilio_auth_token=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"twilio_auth_token",label:"Twilio API key"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_auth_key,"onUpdate:value":t[2]||(t[2]=o=>a.twilio_auth_key=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"twilio_auth_token",label:"Twilio API Secret"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_auth_secret,"onUpdate:value":t[3]||(t[3]=o=>a.twilio_auth_secret=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"twilio_auth_token",label:"Twilio Stuido Flow ID"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_auth_flow_id,"onUpdate:value":t[4]||(t[4]=o=>a.twilio_auth_flow_id=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"twilio_phone_number",label:"Twilio Phone Number"},{default:l(()=>[e(i,{autocomplete:"off",value:a.twilio_phone_number,"onUpdate:value":t[5]||(t[5]=o=>a.twilio_phone_number=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1})]),_:1}),e(f,{key:"2",tab:"SendGrid"},{default:l(()=>[e(_,{class:"!font-bold !text-blue-700",dashed:"",orientation:"left","orientation-margin":"0",plain:""},{default:l(()=>[s("SendGrid")]),_:1}),e(n,{name:"sendgrid_api_key",label:"SendGrid API Key"},{default:l(()=>[e(i,{autocomplete:"off",value:a.sendgrid_api_key,"onUpdate:value":t[6]||(t[6]=o=>a.sendgrid_api_key=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"sendgrid_from_email",label:"SendGrid From Email"},{default:l(()=>[e(i,{autocomplete:"off",value:a.sendgrid_from_email,"onUpdate:value":t[7]||(t[7]=o=>a.sendgrid_from_email=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"sendgrid_from_name",label:"SendGrid From Name"},{default:l(()=>[e(i,{autocomplete:"off",value:a.sendgrid_from_name,"onUpdate:value":t[8]||(t[8]=o=>a.sendgrid_from_name=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1})]),_:1}),e(f,{key:"3",tab:"SendBird"},{default:l(()=>[e(_,{class:"!font-bold !text-blue-700",dashed:"",orientation:"left","orientation-margin":"0",plain:""},{default:l(()=>[s("SendBird")]),_:1}),e(n,{name:"sendbird_app_id",label:"SendBird App ID"},{default:l(()=>[e(i,{autocomplete:"off",value:a.sendbird_app_id,"onUpdate:value":t[9]||(t[9]=o=>a.sendbird_app_id=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1}),e(n,{name:"sendbird_api_token",label:"SendBird API Token"},{default:l(()=>[e(i,{autocomplete:"off",value:a.sendbird_api_token,"onUpdate:value":t[10]||(t[10]=o=>a.sendbird_api_token=o),class:"rounded border-gray-300"},null,8,["value"])]),_:1})]),_:1})]),_:1},8,["activeKey"]),e(n,null,{default:l(()=>[e(h,null,{default:l(()=>[e(S,{loading:r.value,type:"primary","html-type":"submit"},{default:l(()=>[s("Submit")]),_:1},8,["loading"])]),_:1})]),_:1})]),_:1},16)}}};export{K as default};
