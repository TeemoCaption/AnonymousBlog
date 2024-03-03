<template>
    <v-container class="fill-height" fluid>
        <v-row class="fill-height" align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <!-- 在v-card上方添加的進度條 -->
                <v-progress-linear v-if="loading" :indeterminate="true" color="red"></v-progress-linear>

                <v-card class="elevation-12 login-card">
                    <v-toolbar color="primary" dark>
                        <v-toolbar-title>登入頁</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <!-- 登入錯誤時顯示的提示 -->
                        <v-alert v-if="loginError" type="error" dismissible>
                            {{ errorMessage }}
                        </v-alert>

                        <v-form @submit.prevent="submitForm">
                            <v-text-field v-model="username" class="centered-field" label="用戶名" type="text"
                                autocomplete="username"></v-text-field>
                            <v-text-field v-model="password" class="centered-field" label="密碼" :type="password"
                                autocomplete="current-password"></v-text-field>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" type="submit">登入</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <!-- 條件渲染重寄驗證信的按鈕 -->
                        <v-btn v-if="!isUserVerified" @click="resendVerificationEmail">沒收到驗證信?重寄驗證信</v-btn>
                        <v-btn @click="goToMemberPage">還沒有會員?</v-btn>
                        <v-btn @click="resetPassword">忘記密碼?</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
    <v-snackbar v-model="snackbar" :timeout="3000" bottom right>
        {{ snackbarText }}
        <v-btn color="red" text @click="snackbar = false">關閉</v-btn>
    </v-snackbar>
</template>

<script>
import $ from 'jquery';

export default {
    data: () => {
        return {
            loading: true, // 初始時設定為 true，讓進度條在頁面加載時顯示

            username: '',
            password: '',

            isUserVerified: true, // 預設假設用戶已驗證

            loginError: false, // 控制登入錯誤提示的顯示狀態
            errorMessage: '', // 錯誤訊息內容

            snackbar: false, // 控制重發驗證信提示框的顯示狀態
            snackbarText: '', // 提示框中顯示的文本
        }
    },
    mounted() {
        // 假設頁面加載需要一些時間，這裡使用 setTimeout 模擬網頁加載過程
        setTimeout(() => {
            this.loading = false; // 模擬加載完成，隱藏進度條
        }, 1500); // 假設加載過程需要 1.5 秒
    },
    methods: {
        goToMemberPage() {
            // 設定要跳轉到的會員頁面網址
            window.location.href = '/register';
        },
        submitForm() {
            console.log('submitForm is called');

            // 檢查是否已輸入用戶名和密碼
            if (!this.username || !this.password) {
                this.loginError = true; // 顯示錯誤訊息
                this.errorMessage = '帳號或密碼欄位未輸入'; // 設定錯誤訊息
                return; // 提前退出函數
            }
            // 從 meta 標籤取得 CSRF 令牌
            const tokenElement = document.querySelector('meta[name="csrf-token"]');

            if (tokenElement) {
                const token = tokenElement.getAttribute('content');
                // 使用 jQuery 發送 AJAX 請求
                $.ajax({
                    url: '/loginverify', // 請求的 URL
                    method: 'POST', // 請求方法
                    contentType: 'application/json', // 傳送訊息至伺服器時內容編碼類型
                    headers: {
                        'X-CSRF-TOKEN': token, // 在請求頭中設定 CSRF 令牌
                    },
                    data: JSON.stringify({ // 將資料轉換為 JSON 字串
                        username: this.username,
                        password: this.password,
                    }),
                    success: (data) => { // 請求成功後的回呼函數
                        console.log(data);
                        // 重定向到首頁
                        window.location.href = '/';
                    },
                    error: (xhr, status, error) => {
                        this.loginError = true; // 登入失敗，設定 loginError 為 true 以顯示錯誤訊息

                        // 檢查後端是否提供了具體的錯誤訊息
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.error) {
                                // 如果存在 'error' 字段，使用該字段作為錯誤訊息
                                this.errorMessage = xhr.responseJSON.error;

                                // 檢查錯誤訊息是否指示用戶未驗證
                                if (this.errorMessage.includes('請先至信箱點擊認證信驗證按鈕進行驗證(有時可能在垃圾郵件中)')) {
                                    this.isUserVerified = false; // 設置用戶未驗證

                                }
                            } else if (xhr.responseJSON.message) {
                                // 如果存在 'message' 字段，使用該字段作為錯誤訊息
                                this.errorMessage = xhr.responseJSON.message;
                            } else {
                                // 如果後端回應中沒有 'error' 或 'message' 字段，使用預設錯誤訊息
                                this.errorMessage = '登入失敗，請檢查輸入或稍後重試';
                            }
                        } else {
                            // 如果 xhr.responseJSON 不存在，使用預設錯誤訊息
                            this.errorMessage = '登入失敗，請檢查輸入或稍後重試';
                        }
                    }
                });
            } else {
                this.errorMessage = 'CSRF token meta tag not found.'; // 設定錯誤訊息
                this.loginError = true; // 顯示錯誤提示
            }
        },
        resendVerificationEmail() {
            // 從 meta 標籤取得 CSRF 令牌
            const tokenElement = document.querySelector('meta[name="csrf-token"]');

            if (tokenElement) {
                const token = tokenElement.getAttribute('content');
                // 使用 jQuery 發送 AJAX 請求
                $.ajax({
                    url: '/resendverification', // 請求的 URL
                    method: 'POST', // 請求方法
                    contentType: 'application/json', // 傳送訊息至伺服器時內容編碼類型
                    headers: {
                        'X-CSRF-TOKEN': token, // 在請求頭中設定 CSRF 令牌
                    },
                    data: JSON.stringify({ // 將資料轉換為 JSON 字串
                        username: this.username,
                        password: this.password,
                    }),
                    contentType: 'application/json', // 設置請求的內容類型
                    success: (data) => { // 請求成功後的回呼函數
                        this.snackbarText = '驗證信已重發，請檢查您的郵箱。';
                        this.snackbar = true; // 顯示提示

                        // 設置 5 秒的延時
                        setTimeout(() => {
                            window.location.reload(); // 重新載入網頁
                        }, 5000); // 5000 毫秒 = 5 秒
                    },
                    error: (xhr) => {
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            this.snackbarText = xhr.responseJSON.error; // 從後端響應中獲取錯誤訊息
                        }
                        this.snackbar = true; // 顯示錯誤提示
                    }
                });
            }
            else {
                this.errorMessage = 'CSRF token meta tag not found.'; // 設定錯誤訊息
                this.loginError = true; // 顯示錯誤提示
            }
        },
        resetPassword() {
            // 設定要跳轉到的忘記密碼頁面網址
            window.location.href = '你的忘記密碼頁面網址';
        }
    }
}
</script>

<style lang="scss">
@import "./resources/scss/login.scss";
</style>