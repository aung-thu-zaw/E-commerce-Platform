<script setup>
import InputError from "@/Components/Form/InputError.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import FormButton from "@/Components/Form/FormButton.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { inject, ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const updatePassword = () => {
  form.put(route("password.update"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset("password", "password_confirmation");
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset("current_password");
        currentPasswordInput.value.focus();
      }
    },
  });
};

const swal = inject("$swal");

const successAlert = () => {
  swal({
    icon: "success",
    title: "Password updated successfully",
  });
};
</script>

<template>
  <section class="">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

      <p class="mt-1 text-sm text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <!-- Current Password Input -->
      <div>
        <InputLabel for="current_password" value="Current Password" />

        <TextInput
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.current_password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />

        <InputError :message="form.errors.current_password" class="mt-2" />
      </div>

      <!-- New Password Input -->
      <div>
        <InputLabel for="password" value="New Password" />

        <TextInput
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />

        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <!-- Confirm Password Input -->
      <div>
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />

        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>

      <!-- Submit Button -->
      <div class="flex items-center gap-4">
        <FormButton :disabled="form.processing"> Save </FormButton>

        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.recentlySuccessful">
            {{ successAlert() }}
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>