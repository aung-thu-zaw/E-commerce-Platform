<script setup>
import SellerDashboardLayout from "@/Layouts/SellerDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/ShopReviewBreadcrumb.vue";
import TotalRatingStars from "@/Components/RatingStars/TotalRatingStars.vue";
import PendingStatus from "@/Components/Status/PendingStatus.vue";
import PublishedStatus from "@/Components/Status/PublishedStatus.vue";
import UnpublishedStatus from "@/Components/Status/UnpublishedStatus.vue";
import GoBackButton from "@/Components/Buttons/GoBackButton.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  queryStringParams: Array,
  shopReview: Object,
});
</script>

<template>
  <SellerDashboardLayout>
    <Head :title="__('SHOP_REVIEW_DETAILS')" />

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
                {{ shopReview.shop.shop_name }}
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
            href="seller.shop-reviews.index"
            :queryStringParams="queryStringParams"
          />
        </div>
      </div>

      <div class="p-5 border shadow-md rounded-sm my-5">
        <div v-if="shopReview" class="my-3">
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
                  Shop Name
                </span>
                <span class="w-1/2 block">
                  {{ shopReview.shop.shop_name }}
                </span>
              </div>
              <div class="border-b py-3 bg-gray-50 flex items-center">
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Reviewer Name
                </span>
                <span class="w-1/2 block capitalize">
                  {{ shopReview.user.name }}
                </span>
              </div>

              <div
                class="bg-white border-b py-3 dark:bg-gray-900 flex items-center"
              >
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Reviewer Email
                </span>
                <span class="w-1/2 block pr-5">
                  {{ shopReview.user.email }}
                </span>
              </div>
              <div class="border-b py-3 bg-gray-50 flex items-center">
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Review Date
                </span>
                <span class="w-1/2 block capitalize">
                  {{ shopReview.created_at }}
                </span>
              </div>
              <div
                class="bg-white border-b py-3 dark:bg-gray-900 flex items-center"
              >
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Rating
                </span>
                <span class="w-1/2 block pr-5">
                  <TotalRatingStars :rating="shopReview.rating" />
                </span>
              </div>
              <div class="border-b py-3 bg-gray-50 flex items-center">
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Status
                </span>
                <span class="w-1/2 block capitalize">
                  <PendingStatus v-if="shopReview.status === 'pending'">
                    {{ shopReview.status }}
                  </PendingStatus>
                  <PublishedStatus v-if="shopReview.status === 'published'">
                    {{ shopReview.status }}
                  </PublishedStatus>
                  <UnpublishedStatus v-if="shopReview.status === 'unpublished'">
                    {{ shopReview.status }}
                  </UnpublishedStatus>
                </span>
              </div>

              <div
                class="bg-white border-b py-3 dark:bg-gray-900 flex items-start"
              >
                <span
                  class="px-10 w-1/2 font-medium text-gray-900 whitespace-nowrap"
                >
                  Review Text
                </span>
                <span class="w-1/2 block pr-5">
                  {{ shopReview.review_text }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </SellerDashboardLayout>
</template>
