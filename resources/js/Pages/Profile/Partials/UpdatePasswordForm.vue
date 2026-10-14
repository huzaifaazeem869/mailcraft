<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const password = ref('');
const password_confirmation = ref('');
const code = ref('');
const isCodeSent = ref(false);
const isSending = ref(false);
const isUpdating = ref(false);
const message = ref('');
const errorMessage = ref('');
const debugCode = ref('');

const sendVerificationCode = async () => {
    isSending.value = true;
    errorMessage.value = '';
    message.value = '';

    try {
        const res = await window.axios.post(route('password.sendCode'));
        if (res.data.success) {
            isCodeSent.value = true;
            message.value = res.data.message;
            if (res.data.debug_code) {
                debugCode.value = res.data.debug_code;
            }
        }
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Failed to send verification code.';
    } finally {
        isSending.value = false;
    }
};

const updatePassword = async () => {
    isUpdating.value = true;
    errorMessage.value = '';
    message.value = '';

    try {
        const res = await window.axios.put(route('password.update'), {
            code: code.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });

        if (res.data.success) {
            message.value = res.data.message;
            password.value = '';
            password_confirmation.value = '';
            code.value = '';
            isCodeSent.value = false;
            debugCode.value = '';
        }
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Failed to update password.';
    } finally {
        isUpdating.value = false;
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-slate-900">
                Update Password
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                To keep your account secure, verify with a 6-digit email code before setting a new password. No current password required.
            </p>
        </header>

        <div v-if="message" class="mt-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-200">
            {{ message }}
        </div>

        <div v-if="errorMessage" class="mt-4 p-3 rounded-lg bg-red-50 text-red-600 text-xs font-medium border border-red-200">
            {{ errorMessage }}
        </div>

        <!-- Debug Code Helper -->
        <div v-if="debugCode" class="mt-3 p-2 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs rounded font-mono">
            <strong>Security Code:</strong> {{ debugCode }}
        </div>

        <div v-if="!isCodeSent" class="mt-6">
            <button
                type="button"
                @click="sendVerificationCode"
                :disabled="isSending"
                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50"
            >
                {{ isSending ? 'Sending Code...' : 'Send Verification Code to Email' }}
            </button>
        </div>

        <form v-else @submit.prevent="updatePassword" class="mt-6 space-y-4">
            <div>
                <InputLabel for="code" value="6-Digit Verification Code" />
                <TextInput
                    id="code"
                    v-model="code"
                    type="text"
                    maxlength="6"
                    placeholder="123456"
                    class="mt-1 block w-full tracking-widest font-mono text-center text-lg"
                    required
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />
                <TextInput
                    id="password"
                    v-model="password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm New Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
            </div>

            <div class="flex items-center space-x-3 pt-2">
                <PrimaryButton :disabled="isUpdating" class="bg-indigo-600 hover:bg-indigo-700">
                    {{ isUpdating ? 'Updating...' : 'Update Password' }}
                </PrimaryButton>

                <button
                    type="button"
                    @click="sendVerificationCode"
                    :disabled="isSending"
                    class="text-xs text-indigo-600 hover:underline"
                >
                    Resend Code
                </button>
            </div>
        </form>
    </section>
</template>
