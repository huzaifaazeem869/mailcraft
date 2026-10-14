<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const step = ref(1); // 1 = Details, 2 = Verify OTP Code
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const code = ref('');

const isSubmitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const debugCode = ref('');

// Step 1: Send 6-digit OTP code to email
const sendCode = async () => {
    isSubmitting.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
        const res = await window.axios.post(route('register.sendCode'), {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });

        if (res.data.success) {
            step.value = 2;
            successMessage.value = res.data.message;
            if (res.data.debug_code) {
                debugCode.value = res.data.debug_code;
            }
        }
    } catch (err) {
        if (err.response?.data?.errors) {
            const firstErr = Object.values(err.response.data.errors)[0];
            errorMessage.value = Array.isArray(firstErr) ? firstErr[0] : firstErr;
        } else {
            errorMessage.value = err.response?.data?.message || 'Failed to send verification code. Please check your details.';
        }
    } finally {
        isSubmitting.value = false;
    }
};

// Step 2: Verify code and register
const verifyAndRegister = async () => {
    isSubmitting.value = true;
    errorMessage.value = '';

    try {
        const res = await window.axios.post(route('register.verify'), {
            email: email.value,
            code: code.value,
        });

        if (res.data.success) {
            window.location.href = res.data.redirect || '/';
        }
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Invalid verification code. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign Up Free - MailCraft" />

        <div class="mb-6 text-center">
            <h2 class="text-2xl font-extrabold text-slate-900">
                {{ step === 1 ? 'Create an Account' : 'Verify Your Email' }}
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                {{ step === 1 ? 'Start creating and organizing your email templates' : `We sent a 6-digit verification code to ${email}` }}
            </p>
        </div>

        <!-- Notification Alerts -->
        <div v-if="successMessage" class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-200">
            {{ successMessage }}
        </div>

        <div v-if="errorMessage" class="mb-4 p-3 rounded-lg bg-red-50 text-red-600 text-xs font-medium border border-red-200">
            {{ errorMessage }}
        </div>

        <!-- Debug Code Helper (for local testing) -->
        <div v-if="debugCode" class="mb-4 p-2.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs rounded-lg font-mono text-center">
            <strong>Security Code:</strong> {{ debugCode }}
        </div>

        <!-- STEP 1: Registration Form -->
        <div v-if="step === 1">
            <!-- Google OAuth Button -->
            <div class="mb-5">
                <a
                    href="/auth/google"
                    class="w-full flex items-center justify-center space-x-2.5 py-2.5 px-4 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 shadow-sm transition active:scale-98"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                    </svg>
                    <span>Sign up with Google</span>
                </a>
            </div>

            <div class="relative my-5">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-2 text-slate-400">Or register with email</span>
                </div>
            </div>

            <form @submit.prevent="sendCode" class="space-y-4">
                <div>
                    <InputLabel for="name" value="Full Name" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                </div>

                <div>
                    <InputLabel for="email" value="Email Address" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="email"
                        required
                        autocomplete="username"
                    />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="password"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm Password" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700"
                        :disabled="isSubmitting"
                    >
                        {{ isSubmitting ? 'Sending Verification Code...' : 'Continue with Email' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>

        <!-- STEP 2: OTP Verification Form -->
        <div v-else>
            <form @submit.prevent="verifyAndRegister" class="space-y-4">
                <div>
                    <InputLabel for="code" value="6-Digit Verification Code" />
                    <TextInput
                        id="code"
                        type="text"
                        maxlength="6"
                        placeholder="123456"
                        class="mt-1 block w-full tracking-widest font-mono text-center text-xl py-2.5"
                        v-model="code"
                        required
                        autofocus
                    />
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700"
                        :disabled="isSubmitting"
                    >
                        {{ isSubmitting ? 'Verifying...' : 'Verify Code & Create Account' }}
                    </PrimaryButton>
                </div>

                <div class="flex items-center justify-between pt-3 text-xs">
                    <button
                        type="button"
                        @click="step = 1"
                        class="text-slate-500 hover:text-slate-800 underline"
                    >
                        ← Back to form
                    </button>

                    <button
                        type="button"
                        @click="sendCode"
                        :disabled="isSubmitting"
                        class="text-indigo-600 font-semibold hover:underline"
                    >
                        Resend Code
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center text-xs text-slate-600 border-t border-slate-100 pt-4">
            Already have an account?
            <Link
                :href="route('login')"
                class="font-bold text-indigo-600 hover:text-indigo-500 ml-1 underline"
            >
                Sign In
            </Link>
        </div>
    </GuestLayout>
</template>
