import{j as n}from"./jodit.min.b5f3759b.js";import{o as r,c as u}from"./index.a683e63c.js";const a=(t,o)=>{const e=t.__vccOpts||t;for(const[s,i]of o)e[s]=i;return e},l={name:"JoditEditor",props:{modelValue:{type:String,required:!0},buttons:{type:Array,default:null},extraButtons:{type:Array,default:null},config:{type:Object,default:()=>({})},plugins:{type:Array,default:()=>[]}},emits:["update:modelValue"],data:()=>({editor:null}),computed:{editorConfig(){const t={...this.config};return this.buttons&&(t.buttons=this.buttons,t.buttonsMD=this.buttons,t.buttonsSM=this.buttons,t.buttonsXS=this.buttons),this.extraButtons&&(t.extraButtons=this.extraButtons),t}},watch:{modelValue(t){this.editor.value!==t&&(this.editor.value=t)}},mounted(){this.plugins.length&&this.plugins.forEach(t=>{n.exports.Jodit.plugins.add(t.name,t.callback)}),this.editor=n.exports.Jodit.make(this.$el,this.editorConfig),this.editor.value=this.modelValue,this.editor.events.on("change",t=>this.$emit("update:modelValue",t))},beforeUnmount(){this.editor.destruct()}};function d(t,o,e,s,i,h){return r(),u("textarea")}const m=a(l,[["render",d]]);export{m as c};
