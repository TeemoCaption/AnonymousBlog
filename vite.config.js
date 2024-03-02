// 引入 defineConfig 來幫助 vite 更好地進行類型推斷
import { defineConfig } from "vite";
// 引入 Laravel Vite 插件
import laravel from "laravel-vite-plugin";
// 引入 Vue 插件
import vue from "@vitejs/plugin-vue";
// 引入 Vuetify 插件
import VitePluginVuetify from "vite-plugin-vuetify";

// 使用 defineConfig 函數定義並導出配置
export default defineConfig({
    // CSS 相關配置
    css: {
        preprocessorOptions: {
            scss: {
                // 將額外的數據添加到每個 SCSS 文件的開頭，用於導入全局樣式
                additionalData: `@import "resources/scss/settings.scss";`,
            },
        },
    },
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    plugins: [
        // 使用 Laravel 插件，並設定其選項
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: false,
        }),
        // 啟用 Vue 插件
        vue(),
        // 啟用 Vuetify 插件，並設定其選項
        VitePluginVuetify({
            styles: {
                configFile: "resources/scss/settings.scss",
            },
        }),
    ],
    // 定義全局常量
    define: {
        "import.meta.env.BASE_URL": JSON.stringify(process.env.BASE_URL),
    },
});
