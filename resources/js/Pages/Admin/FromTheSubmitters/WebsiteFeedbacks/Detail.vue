<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/WebsiteFeedbackBreadcrumb.vue";
import TotalRatingStars from "@/Components/RatingStars/TotalRatingStars.vue";
import GoBackButton from "@/Components/Buttons/GoBackButton.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  queryStringParams: Array,
  websiteFeedback: Object,
});
</script>

<template>
  <AdminDashboardLayout>
    <Head :title="__('WEBSITE_FEEDBACK_DETAILS')" />

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
                >{{ websiteFeedback.email }}</span
              >
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
            href="admin.website-feedbacks.index"
            :queryStringParams="queryStringParams"
          />
        </div>
      </div>

      <div class="p-5 border shadow-md rounded-sm my-5">
        <div v-if="websiteFeedback" class="my-3">
          <div
            class="w-full text-sm text-left text-gray-500 border overflow-hidden shadow rounded-md my-5"
          >
            <div>
              <div
                class="bg-white border-b py-3 dark:bg-gray-900 flex items-center"
              >
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Email
                </span>
                <span class="w-1/2 block">
                  {{ websiteFeedback.email }}
                </span>
              </div>
              <div class="border-b py-3 bg-gray-50 flex items-center">
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Rating
                </span>
                <span class="w-1/2 block">
                  <TotalRatingStars :rating="websiteFeedback.rating" />
                </span>
              </div>
              <div
                class="bg-white border-b py-3 dark:bg-gray-900 flex items-center"
              >
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Date
                </span>
                <span class="w-1/2 block pr-5">
                  {{ websiteFeedback.created_at }}
                </span>
              </div>
              <div class="border-b py-3 bg-gray-50 flex items-start">
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Feedback
                </span>
                <span class="w-1/2 block">
                  {{ websiteFeedback.feedback_text }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminDashboardLayout>
</template>
