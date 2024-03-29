<script setup>
import FromTheSameStore from "@/Components/FromTheSameStore.vue";
import AskQuestionSection from "@/Components/Sections/AskQuestionSection.vue";
import ProductReviewSection from "@/Components/Sections/ProductReviewSection.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  productQuestions: Object,
  productReviews: Object,
  paginateProductReviews: Object,
  productsFromShop: Object,
  product: Object,
  productReviewsAvg: String,
});
</script>

<template>
  <section class="bg-gray-100 py-10">
    <div class="container max-w-screen-xl mx-auto px-4">
      <div class="flex flex-wrap -mx-2">
        <div class="lg:w-3/4 px-2">
          <div class="border mb-10">
            <div class="border border-gray-200 shadow-sm rounded bg-white">
              <ul
                class="flex flex-wrap -mb-px text-sm font-medium text-center"
                id="myTab"
                data-tabs-toggle="#myTabContent"
                role="tablist"
              >
                <li class="mr-2" role="presentation">
                  <Link
                    :href="route('products.show', product.slug)"
                    :data="{ tab: 'description' }"
                    preserve-scroll
                    class="inline-flex p-4 rounded-t-lg active group text-slate-700"
                    :class="{
                      'text-blue-600 border-b-2 border-blue-600':
                        $page.props.ziggy.query.tab === 'description' ||
                        !$page.props.ziggy.query.tab,
                    }"
                  >
                    <i class="fa-solid fa-scroll mr-2 text-sm"></i>
                    {{ __("DESCRIPTION") }}
                  </Link>
                </li>
                <li class="mr-2" role="presentation">
                  <Link
                    :href="route('products.show', product.slug)"
                    :data="{ tab: 'delievery-and-services' }"
                    preserve-scroll
                    class="inline-flex p-4 rounded-t-lg active group text-slate-700"
                    :class="{
                      'text-blue-600 border-b-2 border-blue-600':
                        $page.props.ziggy.query.tab ===
                        'delievery-and-services',
                    }"
                  >
                    <i class="fa-solid fa-truck mr-2 text-sm"></i>

                    {{ __("DELIVERY_AND_SERVICES") }}
                  </Link>
                </li>
                <li class="mr-2" role="presentation">
                  <Link
                    :href="route('products.show', product.slug)"
                    :data="{ tab: 'available-payments' }"
                    preserve-scroll
                    class="inline-flex p-4 rounded-t-lg active group text-slate-700"
                    :class="{
                      'text-blue-600 border-b-2 border-blue-600':
                        $page.props.ziggy.query.tab === 'available-payments',
                    }"
                  >
                    <i class="fa-solid fa-money-bill mr-2 text-sm"></i>

                    {{ __("AVAILABLE_PAYMENTS") }}
                  </Link>
                </li>
                <li class="mr-2" role="presentation">
                  <Link
                    :href="route('products.show', product.slug)"
                    :data="{ tab: 'product-reviews' }"
                    preserve-scroll
                    class="inline-flex p-4 rounded-t-lg active group text-slate-700"
                    :class="{
                      'text-blue-600 border-b-2 border-blue-600':
                        $page.props.ziggy.query.tab === 'product-reviews',
                    }"
                  >
                    <i class="fa-solid fa-star mr-2 text-sm"></i>

                    {{ __("PRODUCT_REVIEWS") }}
                  </Link>
                </li>
              </ul>
            </div>
            <div id="myTabContent" class="">
              <div
                class="w-full border border-gray-200 shadow-sm border-t-0 p-5 bg-white"
              >
                <!-- Product Description -->
                <div
                  v-if="
                    $page.props.ziggy.query.tab === 'description' ||
                    !$page.props.ziggy.query.tab
                  "
                >
                  <p
                    v-html="product.description"
                    class="text-sm text-gray-600 font-medium dark:text-gray-400"
                  ></p>
                </div>

                <!-- Service -->
                <div
                  v-else-if="
                    $page.props.ziggy.query.tab === 'delievery-and-services'
                  "
                >
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Services
                  </p>
                </div>

                <!-- Payments -->
                <div
                  v-else-if="
                    $page.props.ziggy.query.tab === 'available-payments'
                  "
                >
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Wavepay
                  </p>
                </div>

                <!-- Product Reviews -->
                <div
                  v-else-if="$page.props.ziggy.query.tab === 'product-reviews'"
                >
                  <ProductReviewSection
                    :product="product"
                    :productReviews="productReviews"
                    :paginateProductReviews="paginateProductReviews"
                    :productReviewsAvg="productReviewsAvg"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- About Of Product Questions Section  -->
          <section>
            <AskQuestionSection
              :product="product"
              :productQuestions="productQuestions"
            />
          </section>
        </div>

        <!-- Products From The Same Shop -->
        <FromTheSameStore :productsFromShop="productsFromShop" />
      </div>
    </div>
  </section>
</template>
