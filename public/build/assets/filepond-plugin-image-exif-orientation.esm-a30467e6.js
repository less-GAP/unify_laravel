/*!
 * FilePondPluginImageExifOrientation 1.0.11
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */const E=t=>/^image\/jpeg/.test(t.type),o={JPEG:65496,APP1:65505,EXIF:1165519206,TIFF:18761,Orientation:274,Unknown:65280},i=(t,n,s=!1)=>t.getUint16(n,s),w=(t,n,s=!1)=>t.getUint32(n,s),B=t=>new Promise((n,s)=>{const d=new FileReader;d.onload=function(a){const e=new DataView(a.target.result);if(i(e,0)!==o.JPEG){n(-1);return}const c=e.byteLength;let A=2;for(;A<c;){const r=i(e,A);if(A+=2,r===o.APP1){if(w(e,A+=2)!==o.EXIF)break;const f=i(e,A+=6)===o.TIFF;A+=w(e,A+4,f);const I=i(e,A,f);A+=2;for(let l=0;l<I;l++)if(i(e,A+l*12,f)===o.Orientation){n(i(e,A+l*12+8,f));return}}else{if((r&o.Unknown)!==o.Unknown)break;A+=i(e,A)}}n(-1)},d.readAsArrayBuffer(t.slice(0,64*1024))}),p=(()=>typeof window<"u"&&typeof window.document<"u")(),P=()=>p,O="data:image/jpg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QA6RXhpZgAATU0AKgAAAAgAAwESAAMAAAABAAYAAAEoAAMAAAABAAIAAAITAAMAAAABAAEAAAAAAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wAALCAABAAIBASIA/8QAJgABAAAAAAAAAAAAAAAAAAAAAxABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAAPwBH/9k=";let u;const g=P()?new Image:{};g.onload=()=>u=g.naturalWidth>g.naturalHeight;g.src=O;const m=()=>u,x=({addFilter:t,utils:n})=>{const{Type:s,isFile:d}=n;return t("DID_LOAD_ITEM",(a,{query:e})=>new Promise((c,A)=>{const r=a.file;if(!d(r)||!E(r)||!e("GET_ALLOW_IMAGE_EXIF_ORIENTATION")||!m())return c(a);B(r).then(f=>{a.setMetadata("exif",{orientation:f}),c(a)})})),{options:{allowImageExifOrientation:[!0,s.BOOLEAN]}}},h=typeof window<"u"&&typeof window.document<"u";h&&document.dispatchEvent(new CustomEvent("FilePond:pluginloaded",{detail:x}));export{x as default};
