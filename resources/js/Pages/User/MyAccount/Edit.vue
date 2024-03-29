<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
});
</script>

<template>
  <Head :title="__('MY_ACCOUNT')" />

  <AppLayout>
    <div
      class="container mx-auto mt-48 mb-10 flex flex-col items-center min-h-[500px] w-full p-5"
    >
      <!-- Title -->
      <h1 class="font-bold text-2xl text-slate-600 uppercase mb-5 self-start">
        <i class="fa-solid fa-user"></i>
        {{ __("MY_ACCOUNT") }}
      </h1>
      <div class="w-full flex flex-col items-start mx-auto">
        <ul
          class="mb-5 flex list-none flex-col flex-wrap items-center justify-between w-full border-b-2 pl-0 md:flex-row"
          role="tablist"
        >
          <!-- Edit User Information Tag -->
          <li class="flex-grow basis-0 text-center h-full" role="presentation">
            <Link
              :href="route('my-account.edit')"
              :data="{ tab: 'edit-profile' }"
              class="block border-transparent px-7 pt-4 pb-3.5 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100"
              :class="{
                'bg-neutral-200':
                  $page.props.ziggy.query.tab === 'edit-profile' ||
                  !$page.props.ziggy.query.tab,
              }"
            >
              <i class="fa-solid fa-address-card mr-2 text-sm"></i>
              {{ __("EDIT_PROFILE") }}
            </Link>
          </li>

          <!-- Change Passwords Tag -->
          <li class="flex-grow basis-0 text-center h-full" role="presentation">
            <Link
              :href="route('my-account.edit')"
              :data="{ tab: 'change-password' }"
              class="block border-transparent px-7 pt-4 pb-3.5 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100"
              :class="{
                'bg-neutral-200':
                  $page.props.ziggy.query.tab === 'change-password' ||
                  !$page.props.ziggy.query.tab,
              }"
            >
              <i class="fa-solid fa-key mr-2 text-sm"></i>
              {{ __("CHANGE_PASSWORD") }}
            </Link>
          </li>

          <!-- Delete Account Tag -->
          <li class="flex-grow basis-0 text-center h-full" role="presentation">
            <Link
              :href="route('my-account.edit')"
              :data="{ tab: 'delete-account' }"
              class="block border-transparent px-7 pt-4 pb-3.5 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100"
              :class="{
                'bg-neutral-200':
                  $page.props.ziggy.query.tab === 'delete-account' ||
                  !$page.props.ziggy.query.tab,
              }"
            >
              <i class="fa-solid fa-trash mr-2 text-sm"></i>
              {{ __("DELETE_ACCOUNT") }}
            </Link>
          </li>
        </ul>

        <div class="mb-6 w-full min-h-[250px]">
          <div class="w-full">
            <!-- Edit Profile Information -->
            <div v-if="$page.props.ziggy.query.tab === 'edit-profile'">
              <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
              />
            </div>
            <!-- Change Passwords -->
            <div v-else-if="$page.props.ziggy.query.tab === 'change-password'">
              <UpdatePasswordForm />
            </div>
            <!-- Delete Account -->
            <div v-else-if="$page.props.ziggy.query.tab === 'delete-account'">
              <DeleteUserForm />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
