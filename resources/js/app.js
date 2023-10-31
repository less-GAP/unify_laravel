import "./bootstrap";
import "../css/app.css";
import "@protonemedia/laravel-splade/dist/style.css";
import {
    createPinia
} from "pinia";
import Antd from 'ant-design-vue';
import {
    createApp
} from "vue/dist/vue.esm-bundler.js";
import {
    renderSpladeApp,
    SpladePlugin
} from "@protonemedia/laravel-splade";
const pinia = createPinia();
import FrontendNewPatientForm from './components/FrontendNewPatientForm.vue';
const el = document.getElementById("app");
const app = createApp({
        render: renderSpladeApp({
            el
        }),
    }).use(SpladePlugin, {
        'max_keep_alive': 50,
        'transform_anchors': true,
        'progress_bar': {
            delay: 250,
            color: "#4B5563",
            css: true,
            spinner: true,
        }
    }).use(pinia).use(Antd)
    .component('FrontendNewPatientForm', FrontendNewPatientForm)
    .mount(el)
