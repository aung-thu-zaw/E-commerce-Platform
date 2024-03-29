<script setup>
import ShopReviewCard from "@/Components/Cards/Shops/ShopReviewCard.vue";
import ShopReplyCard from "@/Components/Cards/Shops/ShopReplyCard.vue";
import ShopReviewForm from "@/Components/Forms/Reviews/ShopReviewForm.vue";
import Pagination from "@/Components/Paginations/DashboardPagination.vue";
import TotalAverageRatingStarWithProgressBar from "@/Components/RatingStars/TotalAverageRatingStarWithProgressBar.vue";
import TotalReviewAvgStars from "@/Components/RatingStars/TotalReviewAvgStars.vue";
import { computed } from "vue";

const props = defineProps({
  paginateShopReviews: Object,
  shopReviews: Object,
  shopReviewsAvg: Object,
  shop: Object,
});

// Total Shop Review Avg
const reviewAvg = computed(() => parseFloat(props.shopReviewsAvg).toFixed(2));

// Filter Shop Rating Stars
const oneStarRating = computed(() => {
  const totalRatings = props.shopReviews.filter((review) => review.rating == 1);

  return ((totalRatings.length / props.shopReviews.length) * 100).toFixed(0);
});

const twoStarsRating = computed(() => {
  const totalRatings = props.shopReviews.filter((review) => review.rating == 2);
  return ((totalRatings.length / props.shopReviews.length) * 100).toFixed(0);
});

const threeStarsRating = computed(() => {
  const totalRatings = props.shopReviews.filter((review) => review.rating == 3);
  return ((totalRatings.length / props.shopReviews.length) * 100).toFixed(0);
});

const fourStarsRating = computed(() => {
  const totalRatings = props.shopReviews.filter((review) => review.rating == 4);
  return ((totalRatings.length / props.shopReviews.length) * 100).toFixed(0);
});

const fiveStarsRating = computed(() => {
  const totalRatings = props.shopReviews.filter((review) => review.rating == 5);
  return ((totalRatings.length / props.shopReviews.length) * 100).toFixed(0);
});
</script>

<template>
  <section class="container mx-auto py-10">
    <div class="flex items-start">
      <div
        v-if="shopReviews.length"
        class="border w-1/3 text-center py-5 shadow rounded-md mr-3"
      >
        <h1 class="text-lg font-bold text-slate-600 mb-5 pb-2 border-b-4">
          <i class="fa-solid fa-star"></i>
          Ratings & Reviews For Shop
        </h1>

        <div class="w-full">
          <div class="flex items-center justify-center">
            <TotalReviewAvgStars :reviewAvg="reviewAvg" />
          </div>

          <p class="text-sm text-slate-400">
            Based on {{ shopReviews.length }} customer ratings
          </p>

          <TotalAverageRatingStarWithProgressBar
            :oneStarRating="oneStarRating"
            :twoStarsRating="twoStarsRating"
            :threeStarsRating="threeStarsRating"
            :fourStarsRating="fourStarsRating"
            :fiveStarsRating="fiveStarsRating"
          />
        </div>
      </div>

      <div class="w-full border rounded-sm shadow-md">
        <div class="p-5">
          <h1 class="font-bold text-slate-600 text-xl my-3">
            {{ __("CUSTOMER_REVIEWS") }} ({{ shopReviews.length }})
          </h1>

          <div v-if="paginateShopReviews.data.length">
            <div
              v-for="paginateShopReview in paginateShopReviews.data"
              :key="paginateShopReview.id"
            >
              <div
                v-if="
                  paginateShopReview.status === 'published' ||
                  ($page.props.auth.user &&
                    $page.props.auth.user.id === paginateShopReview.user_id &&
                    paginateShopReview.status !== 'published')
                "
                class="shadow border rounded-md p-5 flex flex-col items-start my-3"
              >
                <ShopReviewCard :paginateShopReview="paginateShopReview" />
                <div v-if="paginateShopReview.reply" class="w-full">
                  <ShopReplyCard :paginateShopReview="paginateShopReview" />
                </div>
              </div>
            </div>

            <!-- Shop Reviews Pagination -->
            <div>
              <Pagination class="mt-6" :links="paginateShopReviews.links" />
            </div>
          </div>

          <!-- Reviews do not exist -->
          <div
            v-if="
              paginateShopReviews.status !== 'published' ||
              !paginateShopReviews.data.length
            "
          >
            <div class="font-bold text-center text-sm text-slate-600 my-10">
              <i class="fa-solid fa-star text-3xl mb-5 animate-bounce"></i>
              <p>{{ __("THIS_SHOP_HAS_NO_REVIEWS") }}</p>
              <p>
                {{
                  __(
                    "LET_OTHERS_KNOW_WHAT_YOU_THINK_AND_BE_THE_FIRST_TO_WRITE_A_REVIEW"
                  )
                }}
              </p>
            </div>
          </div>
        </div>

        <hr v-if="shopReviews.length" />

        <!-- Review Form -->
        <div
          v-if="$page.props.auth.user && $page.props.auth.user.id !== shop.id"
        >
          <ShopReviewForm :shop="shop" />
        </div>

        <div v-else-if="!$page.props.auth.user" class="px-5 my-5">
          <p class="font-bold text-sm text-slate-600 text-center">
            If you want to review this shop you need to login first. Here
            <Link :href="route('login')" class="text-blue-600 underline">
              {{ __("SIGN_IN") }}
            </Link>
            Or
            <Link :href="route('register')" class="text-blue-600 underline">
              {{ __("SIGN_UP") }}
            </Link>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

