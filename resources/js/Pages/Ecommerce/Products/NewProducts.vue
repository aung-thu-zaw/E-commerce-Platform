<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProductCard from "@/Components/Cards/Products/ProductCardGrid.vue";
import { usePage, router, Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
  newProducts: Object,
});

// Define Variables
const isLoading = ref(false);
const products = ref(props.newProducts.data);
const url = usePage().url;

// Handle Load More Button
const loadMoreProduct = () => {
  if (props.newProducts.next_page_url === null) {
    return;
  }

  isLoading.value = true;

  router.get(
    props.newProducts.next_page_url,
    {},
    {
      preserveState: true,
      preserveScroll: true,
      only: ["newProducts"],
      onSuccess: () => {
        isLoading.value = false;
        products.value = [...products.value, ...props.newProducts.data];
        window.history.replaceState({}, "", url);
      },
    }
  );
};
</script>


<template>
  <AppLayout>
    <Head :title="__('ALL_NEW_PRODUCTS')" />

    <section class="container mx-auto mt-40 py-10">
      <!-- Title -->
      <div
        class="relative w-full h-[150px] mb-10 shadow-md rounded-md overflow-hidden border"
      >
        <img
          src="https://e0.pxfuel.com/wallpapers/265/501/desktop-wallpaper-blurred-blue-purple-retina-background.jpg"
          class="w-full h-full object-cover"
        />
        <div
          class="absolute top-[50%] right-[50%] translate-x-[50%] -translate-y-[50%] text-2xl font-semibold text-white"
        >
          <p class="text-center uppercase">{{ __("NEW_PRODUCTS") }}</p>
        </div>
      </div>

      <!-- Products -->
      <div
        v-if="products"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
      >
        <div v-for="product in products" :key="product.id">
          <ProductCard :product="product" />
        </div>
      </div>
      <div v-else>
        <p class="text-center text-xl font-bold text-red-600 animate-bounce">
          {{ __("NO_PRODUCT_FOUND") }}!
        </p>
      </div>

      <div
        v-if="props.newProducts.next_page_url != null"
        class="my-5 flex items-center justify-center"
      >
        <!-- Loading Animation  -->
        <img
          v-if="isLoading"
          src="../../../assets/images/loading.gif"
          class="w-14 h-14"
          alt=""
        />

        <!-- Load More Button  -->
        <button
          v-else
          @click="loadMoreProduct"
          class="border-2 border-slate-500 text-slate-600 rounded-sm px-5 py-2 shadow-md font-bold hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all"
        >
          {{ __("LOAD_MORE_PRODUCTS") }}
        </button>
      </div>
      <p v-else class="my-5 text-slate-600 text-center">
        {{ __("YOU_HAVE_REACH_THE_END_OF_THE_PAGE") }}
      </p>
    </section>
  </AppLayout>
</template>


