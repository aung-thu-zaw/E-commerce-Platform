<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/BannerBreadcrumb.vue";
import CreateButton from "@/Components/Buttons/CreateButton.vue";
import TrashButton from "@/Components/Buttons/TrashButton.vue";
import EditButton from "@/Components/Buttons/EditButton.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
import ActiveStatus from "@/Components/Status/ActiveStatus.vue";
import InactiveStatus from "@/Components/Status/InactiveStatus.vue";
import DashboardSearchInputForm from "@/Components/Forms/DashboardSearchInputForm.vue";
import DashboardPerPageSelectBox from "@/Components/Forms/DashboardPerPageSelectBox.vue";
import DashboardFilterByCreatedDate from "@/Components/Forms/DashboardFilterByCreatedDate.vue";
import SortingArrows from "@/Components/Table/SortingArrows.vue";
import TableContainer from "@/Components/Table/TableContainer.vue";
import TableHeader from "@/Components/Table/TableHeader.vue";
import HeaderTh from "@/Components/Table/HeaderTh.vue";
import BodyTh from "@/Components/Table/BodyTh.vue";
import Tr from "@/Components/Table/Tr.vue";
import Td from "@/Components/Table/Td.vue";
import NotAvaliableData from "@/Components/Table/NotAvaliableData.vue";
import Pagination from "@/Components/Paginations/DashboardPagination.vue";
import { __ } from "@/Services/translations-inside-setup.js";
import { inject, computed, ref, reactive } from "vue";
import { router, Head, usePage } from "@inertiajs/vue3";

// Define the props
const props = defineProps({
  productBanners: Object,
});

// Define Variables
const swal = inject("$swal");
const permissions = ref(usePage().props.auth.user.permissions); // Permissions From HandleInertiaRequest.php

// Create New Banner Permission
const bannerAdd = computed(() => {
  return permissions.value.length
    ? permissions.value.some((permission) => permission.name === "banner.add")
    : false;
});

// Banner Control Permission
const bannerControl = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "banner.control"
      )
    : false;
});

// Banner Edit Permission
const bannerEdit = computed(() => {
  return permissions.value.length
    ? permissions.value.some((permission) => permission.name === "banner.edit")
    : false;
});

// Banner Delete Permission
const bannerDelete = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "banner.delete"
      )
    : false;
});

// Banner Trash List Permission
const bannerTrashList = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "banner.trash.list"
      )
    : false;
});

// Query String Parameteres
const params = reactive({
  sort: usePage().props.ziggy.query?.sort,
  direction: usePage().props.ziggy.query?.direction,
});

// Update Sorting Table Column
const updateSorting = (sort = "id") => {
  params.sort = sort;
  params.direction = params.direction === "asc" ? "desc" : "asc";

  router.get(
    route("admin.product-banners.index"),
    {
      search: usePage().props.ziggy.query?.search,
      page: usePage().props.ziggy.query?.page,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: params.sort,
      direction: params.direction,
      created_from: usePage().props.ziggy.query?.created_from,
      created_until: usePage().props.ziggy.query?.created_until,
    },
    {
      replace: true,
      preserveState: true,
    }
  );
};

// Handle Show Product Banner
const handleShow = async (hideProductBannerId) => {
  const result = await swal({
    icon: "question",
    title: __("ARE_YOU_SURE_YOU_WANT_TO_SHOW_THIS_PRODUCT_BANNER"),
    showCancelButton: true,
    confirmButtonText: __("YES_SHOW"),
    cancelButtonText: __("CANCEL"),
    confirmButtonColor: "#2562c4",
    cancelButtonColor: "#626262",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.patch(
      route("admin.product-banners.show", {
        product_banner: hideProductBannerId,
        search: usePage().props.ziggy.query?.search,
        page: usePage().props.ziggy.query?.page,
        per_page: usePage().props.ziggy.query?.per_page,
        sort: params.sort,
        direction: params.direction,
        created_from: usePage().props.ziggy.query?.created_from,
        created_until: usePage().props.ziggy.query?.created_until,
      }),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          if (usePage().props.flash.successMessage) {
            swal({
              icon: "success",
              title: __(usePage().props.flash.successMessage),
            });
          }
          if (usePage().props.flash.errorMessage) {
            swal({
              icon: "error",
              title: __(usePage().props.flash.errorMessage),
            });
          }
        },
      }
    );
  }
};

// Handle Hide Product Banner
const handleHide = async (showProductBannerId) => {
  const result = await swal({
    icon: "question",
    title: __("ARE_YOU_SURE_YOU_WANT_TO_HIDE_THIS_PRODUCT_BANNER"),
    showCancelButton: true,
    confirmButtonText: __("YES_HIDE"),
    cancelButtonText: __("CANCEL"),
    confirmButtonColor: "#2562c4",
    cancelButtonColor: "#626262",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.patch(
      route("admin.product-banners.hide", {
        product_banner: showProductBannerId,
        search: usePage().props.ziggy.query?.search,
        page: usePage().props.ziggy.query?.page,
        per_page: usePage().props.ziggy.query?.per_page,
        sort: params.sort,
        direction: params.direction,
        created_from: usePage().props.ziggy.query?.created_from,
        created_until: usePage().props.ziggy.query?.created_until,
      }),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          if (usePage().props.flash.successMessage) {
            swal({
              icon: "success",
              title: __(usePage().props.flash.successMessage),
            });
          }
        },
      }
    );
  }
};

// Handle Delete Banner
const handleDeleteProductBanner = async (productBannerId) => {
  const result = await swal({
    icon: "question",
    title: __("ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_PRODUCT_BANNER"),
    text: __("YOU_WILL_BE_ABLE_TO_RESTORE_THIS_PRODUCT_BANNER_IN_THE_TRASH"),
    showCancelButton: true,
    confirmButtonText: __("YES_DELETE_IT"),
    cancelButtonText: __("CANCEL"),
    confirmButtonColor: "#d52222",
    cancelButtonColor: "#626262",
    timer: 20000,
    timerProgressBar: true,
    reverseButtons: true,
  });

  if (result.isConfirmed) {
    router.delete(
      route("admin.product-banners.destroy", {
        product_banner: productBannerId,
        search: usePage().props.ziggy.query?.search,
        page: usePage().props.ziggy.query?.page,
        per_page: usePage().props.ziggy.query?.per_page,
        sort: params.sort,
        direction: params.direction,
        created_from: usePage().props.ziggy.query?.created_from,
        created_until: usePage().props.ziggy.query?.created_until,
      }),
      {
        preserveScroll: true,
        onSuccess: () => {
          if (usePage().props.flash.successMessage) {
            swal({
              icon: "success",
              title: __(usePage().props.flash.successMessage),
            });
          }
        },
      }
    );
  }
};

if (usePage().props.flash.successMessage) {
  swal({
    icon: "success",
    title: __(usePage().props.flash.successMessage),
  });
}
</script>

<template>
  <AdminDashboardLayout>
    <Head :title="__('PRODUCT_BANNERS')" />

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
                {{ __("PRODUCT_BANNERS") }}
              </span>
            </div>
          </li>
        </Breadcrumb>

        <!-- Trash Button -->
        <div v-if="bannerTrashList">
          <TrashButton href="admin.product-banners.trash" />
        </div>
      </div>

      <div class="mb-5 flex items-center justify-between">
        <!-- Create Product Banner Button -->
        <div v-if="bannerAdd">
          <CreateButton
            href="admin.product-banners.create"
            name="ADD_PRODUCT_BANNER"
          />
        </div>

        <div class="flex items-center">
          <!-- Search Box -->
          <DashboardSearchInputForm
            href="admin.product-banners.index"
            placeholder="SEARCH_BY_URL"
          />

          <!-- Perpage Select Box -->
          <div class="ml-5">
            <DashboardPerPageSelectBox href="admin.product-banners.index" />
          </div>

          <!-- Filter By Date -->
          <DashboardFilterByCreatedDate href="admin.product-banners.index" />
        </div>
      </div>

      <!-- Product Banner Table Start -->
      <TableContainer>
        <TableHeader>
          <HeaderTh @click="updateSorting('id')">
            No
            <SortingArrows :params="params" sort="id" />
          </HeaderTh>

          <HeaderTh> {{ __("IMAGE") }} </HeaderTh>

          <HeaderTh @click="updateSorting('url')">
            {{ __("URL") }}
            <SortingArrows :params="params" sort="url" />
          </HeaderTh>

          <HeaderTh @click="updateSorting('status')">
            {{ __("STATUS") }}
            <SortingArrows :params="params" sort="status" />
          </HeaderTh>

          <HeaderTh @click="updateSorting('created_at')">
            {{ __("CREATED_DATE") }}
            <SortingArrows :params="params" sort="created_at" />
          </HeaderTh>

          <HeaderTh v-if="bannerEdit || bannerDelete || bannerControl">
            {{ __("ACTION") }}
          </HeaderTh>
        </TableHeader>

        <tbody v-if="productBanners.data.length">
          <Tr
            v-for="productBanner in productBanners.data"
            :key="productBanner.id"
          >
            <BodyTh>
              {{ productBanner.id }}
            </BodyTh>
            <Td>
              <img :src="productBanner.image" class="image" />
            </Td>

            <Td>
              {{ productBanner.url }}
            </Td>

            <Td>
              <ActiveStatus v-if="productBanner.status == 'show'">
                {{ productBanner.status }}
              </ActiveStatus>
              <InactiveStatus v-if="productBanner.status == 'hide'">
                {{ productBanner.status }}
              </InactiveStatus>
            </Td>

            <Td>
              {{ productBanner.created_at }}
            </Td>

            <Td
              v-if="bannerEdit || bannerDelete || bannerControl"
              class="w-[450px] flex items-center"
            >
              <!-- Show Button -->
              <button
                v-if="productBanner.status == 'hide' && bannerControl"
                @click="handleShow(productBanner.id)"
                class="text-sm px-5 py-2 border-[3px] border-green-700 font-semibold rounded-[4px] shadow-md bg-green-600 text-white transition-all hover:bg-green-700 mr-3 my-1 group"
                type="button"
              >
                <span class="group-hover:animate-pulse">
                  <i class="fa-solid fa-eye"></i>
                  {{ __("SHOW") }}
                </span>
              </button>

              <!-- Hide Button -->
              <button
                v-if="productBanner.status == 'show' && bannerControl"
                @click="handleHide(productBanner.id)"
                class="text-sm px-5 py-2 border-[3px] border-orange-700 font-semibold rounded-[4px] shadow-md bg-orange-600 text-white transition-all hover:bg-orange-700 mr-3 my-1 group"
                type="button"
              >
                <span class="group-hover:animate-pulse">
                  <i class="fa-solid fa-eye-slash"></i>
                  {{ __("HIDE") }}
                </span>
              </button>

              <!-- Edit Button -->
              <div v-if="bannerEdit">
                <EditButton
                  href="admin.product-banners.edit"
                  :id="productBanner.id"
                />
              </div>

              <!-- Delete Button -->
              <div v-if="bannerDelete">
                <DeleteButton
                  @click="handleDeleteProductBanner(productBanner.id)"
                />
              </div>
            </Td>
          </Tr>
        </tbody>
      </TableContainer>
      <!-- Product Banner Table End -->

      <!-- No Data Row -->
      <NotAvaliableData v-if="!productBanners.data.length" />

      <!-- Pagination -->
      <div v-if="productBanners.data.length" class="mt-6">
        <p class="text-center text-sm text-gray-600 mb-3 font-bold">
          Showing {{ productBanners.from }} - {{ productBanners.to }} of
          {{ productBanners.total }}
        </p>
        <Pagination :links="productBanners.links" />
      </div>
    </div>
  </AdminDashboardLayout>
</template>

