<template>
    <v-container class="fill-height" fluid>
        <v-row class="fill-height" align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card class="elevation-12 login-card">
                    <v-toolbar color="primary" dark>
                        <v-toolbar-title>登入頁</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field class="centered-field" label="用戶名" type="text"></v-text-field>
                            <v-text-field class="centered-field" label="密碼" type="password"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary">登入</v-btn>
                    </v-card-actions>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="goToMemberPage">還沒有會員?</v-btn>
                        <v-btn @click="resetPassword">忘記密碼?</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    methods: {
        goToMemberPage() {
            // 設定要跳轉到的會員頁面網址
            window.location.href = '/register';
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