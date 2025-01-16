import { ScrollMenu } from './modules.min.js';
load(() => {
    getQueries(".splashscreen .contents").classList.remove("none");
    getQueries(".splashscreen .loader").classList.add("none");
    getQueries(".splashscreen").classList.add("loaded");
    getQueries(".smooth-scroll, a[href^='#'], *[data-href^='#']").scrollSmooth();
});