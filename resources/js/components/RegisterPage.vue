<template>
    <v-container class="fill-height" fluid>
        <v-row class="fill-height" align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <!-- 在v-card上方添加的進度條 -->
                <v-progress-linear v-if="loading" :indeterminate="true" color="white"></v-progress-linear>

                <v-card class="elevation-12 register-card">
                    <v-toolbar color="primary" dark>
                        <v-toolbar-title>註冊頁</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <!-- 為v-form添加submit事件處理器 -->
                        <v-form @submit.prevent="submitForm">
                            <v-text-field v-model="email" class="centered-field" label="信箱" type="email"
                                :rules="emailRules"></v-text-field>
                            <v-text-field v-model="username" class="centered-field" label="用戶名" type="text"
                                :rules="userNameRules" autocomplete="username"></v-text-field>
                            <v-text-field v-model="password" class="centered-field" label="密碼" :type="password"
                                :rules="passwordRules" autocomplete="current-password"></v-text-field>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="goBack">回上一頁</v-btn>
                                <v-btn color="primary" type="submit">註冊</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data: () => {
        return {
            loading: true, // 初始時設定為 true，讓進度條在頁面加載時顯示

            email: '',
            username: '',
            password: '',

            emailRules: [
                // 第一條規則：檢查輸入值是否存在。如果用戶沒有輸入任何內容（即輸入值為空），
                // 則返回一個錯誤信息提示用戶“信箱是必填的”。使用`!!value`可以將輸入值轉換為布林值，
                // 如果輸入值為空（如空字符串、null、undefined等），`!!value`的結果為`false`，
                // 因此將顯示錯誤信息。如果輸入值存在，則返回`true`，表示通過驗證。
                value => !!value || '信箱是必填的',

                // 第二條規則：使用正則表達式檢查輸入值是否符合信箱的基本格式。
                // 正則表達式`/.+@.+/`簡單地檢查輸入值中是否包含至少一個`@`字符，
                // 並且`@`字符前後都有至少一個其他字符。這是一個非常基本的檢查，
                // 不足以涵蓋所有有效的信箱格式，但可以用作基本驗證。
                // 如果輸入值不符合這個格式，則返回錯誤信息“請輸入有效的信箱格式”。
                value => /.+@.+/.test(value) || '請輸入有效的信箱格式',
            ],
            userNameRules: [
                value => !!value || '用戶名是必填的',

                // 此規則使用正則表達式來檢查用戶名是否符合特定的格式要求。
                // ^[a-zA-Z0-9]{4,12}$ 正則表達式的解釋如下：
                // ^ 表示字符串的開始。
                // [a-zA-Z0-9] 表示允許的字符範圍，包括小寫英文字母(a-z)、大寫英文字母(A-Z)和數字(0-9)。
                // {4,12} 表示前面的字符類別([a-zA-Z0-9])必須出現的次數，至少4次，最多12次。
                // $ 表示字符串的結束。
                // 如果用戶名不符合這個正則表達式的要求（即不是4到12個英文字母或數字），則會返回一個錯誤信息。
                value => /^[a-zA-Z0-9]{4,12}$/.test(value) || '用戶名必須是4到12個英文字母或數字',
            ],
            passwordRules: [
                value => (value ? value.length >= 4 && value.length <= 12 : false) || '密碼必須是4到12個字元',
                value => (value ? /[a-zA-Z]/.test(value) && /\d/.test(value) : false) || '密碼必須包含英文和數字',
            ]
        }
    }, 
    mounted() {   // 生命鉤子
        // 假設頁面加載需要一些時間，這裡使用 setTimeout 模擬網頁加載過程
        setTimeout(() => {
            this.loading = false; // 模擬加載完成，隱藏進度條
        }, 1500); // 假設加載過程需要 1.5 秒
    },
    methods: {
        goBack() {
            window.history.back();
        },
        submitForm() {
            console.log('submitForm is called');
            // 從 meta 標籤獲取 CSRF 令牌
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: '/register/save', // 請求的 URL
                method: 'POST', // 請求方法
                contentType: 'application/json', // 發送信息至服務器時內容編碼類型
                headers: {
                    'X-CSRF-TOKEN': token, // 在請求標頭中設定 CSRF 令牌
                },
                data: JSON.stringify({ // 將數據轉換為 JSON 字符串
                    email: this.email,
                    username: this.username,
                    password: this.password,
                }),
                success: function (data) { // 請求成功後的回調函數
                    console.log(data);
                    // 重定向到 /login 頁面
                    window.location.href = '/login';
                },
                error: function (xhr, status, error) { // 請求失敗後的回調函數
                    console.error('請求失敗：', error);
                }
            });
        }
    }
}
</script>

<style lang="scss">
@import "./resources/scss/register.scss";
</style>