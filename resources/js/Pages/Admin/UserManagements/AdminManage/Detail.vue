<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/AdminManageBreadcrumb.vue";
import InputContainer from "@/Components/Forms/InputContainer.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import GoBackButton from "@/Components/Buttons/GoBackButton.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  queryStringParams: Array,
  user: Object,
});
</script>

<template>
  <AdminDashboardLayout>
    <Head :title="user.name + ' Details'" />

    <div class="px-4 md:px-10 mx-auto w-full py-32">
      <div class="flex items-center justify-between mb-10">
        <!-- Breadcrumb -->
        <Breadcrumb>
          <li aria-current="page">
            <div class="flex items-center">
              <svg
                aria-hidden="true"
                class="w-6 h-6 text-gray-400"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <span
                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400"
              >
                {{ user.name }}
              </span>
            </div>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <svg
                aria-hidden="true"
                class="w-6 h-6 text-gray-400"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <span
                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400"
              >
                {{ __("DETAILS") }}
              </span>
            </div>
          </li>
        </Breadcrumb>

        <!-- Go Back Button -->
        <div>
          <GoBackButton
            href="admin.admin-manage.index"
            :queryStringParams="queryStringParams"
          />
        </div>
      </div>

      <!-- Admin Detail -->
      <div class="border shadow-md p-10">
        <div class="mb-5 flex items-center justify-center">
          <img
            :src="user.avatar"
            alt=""
            class="w-48 h-48 rounded-full object-cover ring-2 ring-slate-300 mb-5"
          />
        </div>

        <div class="flex items-center justify-center mb-5">
          <h1 class="text-2xl text-slate-600 font-bold capitalize">
            Role - {{ user.role }}
          </h1>

          <span
            class="text-sm bg-sky-200 text-sky-600 font-bold rounded-full px-3 py-1 ml-2"
          >
            {{ user.roles[0].name }}
          </span>
        </div>

        <form>
          <div class="mb-6">
            <InputLabel for="name" value="Name" />

            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="user.name"
              disabled
            >
              <template v-slot:icon>
                <span>
                  <i class="fa-solid fa-user text-gray-600"></i>
                </span>
              </template>
            </TextInput>
          </div>
          <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
              <InputLabel for="email" value="Email" />

              <TextInput
                type="email"
                class="mt-1 block w-full"
                v-model="user.email"
                disabled
              >
                <template v-slot:icon>
                  <span>
                    <i class="fa-solid fa-envelope text-gray-600"></i>
                  </span>
                </template>
              </TextInput>
            </div>

            <div>
              <InputLabel for="phone" value="Phone" />

              <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="user.phone"
                disabled
              >
                <template v-slot:icon>
                  <span>
                    <i class="fa-solid fa-phone text-gray-600"></i>
                  </span>
                </template>
              </TextInput>
            </div>

            <div>
              <InputLabel for="gender" value="Gender" />

              <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="user.gender"
                disabled
              >
                <template v-slot:icon>
                  <span>
                    <i class="fa-solid fa-venus-mars text-gray-600"></i>
                  </span>
                </template>
              </TextInput>
            </div>

            <div>
              <InputLabel for="birthday" value="Birthday" />

              <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="user.birthday"
                disabled
              >
                <template v-slot:icon>
                  <span>
                    <i class="fa-solid fa-calendar-days text-gray-600"></i>
                  </span>
                </template>
              </TextInput>
            </div>
          </div>
          <div class="mb-6">
            <InputLabel for="address" value="Address" />
            <TextInput
              type="text"
              class="mt-1 block w-full"
              v-model="user.address"
              disabled
            >
              <template v-slot:icon>
                <span>
                  <i class="fa-solid fa-phone text-gray-600"></i>
                </span>
              </template>
            </TextInput>
          </div>
          <div class="mb-6">
            <InputLabel for="about" value="About" />

            <InputContainer>
              <textarea
                id="about"
                cols="30"
                rows="10"
                disabled
                class="p-2 w-full border-transparent outline-none focus:border-transparent focus:ring-0 placeholder:text-gray-400 placeholder:text-sm"
                v-model="user.about"
              ></textarea>
            </InputContainer>
          </div>
        </form>
      </div>
    </div>
  </AdminDashboardLayout>
</template>




