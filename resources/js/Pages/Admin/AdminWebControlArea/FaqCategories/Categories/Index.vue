<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/FaqCategoryBreadcrumb.vue";
import CreateButton from "@/Components/Buttons/CreateButton.vue";
import TrashButton from "@/Components/Buttons/TrashButton.vue";
import EditButton from "@/Components/Buttons/EditButton.vue";
import DeleteButton from "@/Components/Buttons/DeleteButton.vue";
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
  faqCategories: Object,
});

// Define Variables
const swal = inject("$swal");
const permissions = ref(usePage().props.auth.user.permissions); // Permissions From HandleInertiaRequest.php

// Create New Faq Category Permission
const faqCategoryAdd = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "faq-category.add"
      )
    : false;
});

// F Category Edit Permission
const faqCategoryEdit = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "faq-category.edit"
      )
    : false;
});

// F Category Delete Permission
const faqCategoryDelete = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "faq-category.delete"
      )
    : false;
});

// F Category Trash List Permission
const faqCategoryTrashList = computed(() => {
  return permissions.value.length
    ? permissions.value.some(
        (permission) => permission.name === "faq-category.trash.list"
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
    route("admin.faq-categories.categories.index"),
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

// Handle Delete Faq Category
const handleDelete = async (faqCategory) => {
  router.delete(
    route("admin.faq-categories.categories.destroy", {
      faq_category: faqCategory,
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
};

// Handle Delete Faq Category
const handleDeleteFaqCategory = async (faqCategory) => {
  if (faqCategory.faq_sub_categories.length > 0) {
    const result = await swal({
      icon: "error",
      title: __(
        "YOU_CANT_DELETE_THIS_FAQ_CATEGORY_BECAUSE_THIS_FAQ_CATEGORY_HAVE_FAQ_SUB_CATEGORIES"
      ),
      text: __(
        "IF_YOU_CLICK_THE_DELETE_WHATEVER_BUTTON_FAQ_SUB_CATEGORIES_ASSOCIATED_WITH_THAT_FAQ_CATEGORY_WILL_BE_AUTOMATICALLY_DELETED"
      ),
      showCancelButton: true,
      confirmButtonText: __("DELETE_WHATEVER"),
      cancelButtonText: __("CANCEL"),
      confirmButtonColor: "#d52222",
      cancelButtonColor: "#626262",
      timer: 20000,
      timerProgressBar: true,
      reverseButtons: true,
    });
    if (result.isConfirmed) {
      handleDelete(faqCategory.slug);
    }
  } else {
    const result = await swal({
      icon: "question",
      title: __("ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_FAQ_CATEGORY"),
      text: __("YOU_WILL_BE_ABLE_TO_RESTORE_THIS_FAQ_CATEGORY_IN_THE_TRASH"),
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
      handleDelete(faqCategory.slug);
    }
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
    <Head :title="__('FAQ_CATEGORIES')" />

    <div class="px-4 md:px-10 mx-auto w-full py-32">
      <div class="flex items-center justify-between mb-10">
        <!-- Breadcrumb -->
        <Breadcrumb>
          <li>
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
                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400 dark:hover:text-white"
              >
                {{ __("CATEGORIES") }}
              </span>
            </div>
          </li>
        </Breadcrumb>

        <!-- Trash Button -->
        <div v-if="faqCategoryTrashList">
          <TrashButton href="admin.faq-categories.categories.trash" />
        </div>
      </div>

      <div class="mb-5 flex items-center justify-between">
        <!-- Create Faq Category Button -->
        <div v-if="faqCategoryAdd">
          <CreateButton
            href="admin.faq-categories.categories.create"
            name="ADD_FAQ_CATEGORY"
          />
        </div>

        <div class="flex items-center ml-auto">
          <!-- Search Box -->
          <DashboardSearchInputForm
            href="admin.faq-categories.categories.index"
            placeholder="SEARCH_BY_NAME"
          />

          <!-- Perpage Select Box -->
          <div class="ml-5">
            <DashboardPerPageSelectBox
              href="admin.faq-categories.categories.index"
            />
          </div>

          <!-- Filter By Date -->
          <DashboardFilterByCreatedDate
            href="admin.faq-categories.categories.index"
          />
        </div>
      </div>

      <!-- Blog Categories Table Start -->
      <TableContainer>
        <TableHeader>
          <HeaderTh @click="updateSorting('id')">
            No
            <SortingArrows :params="params" sort="id" />
          </HeaderTh>

          <HeaderTh @click="updateSorting('name')">
            {{ __("NAME") }}
            <SortingArrows :params="params" sort="name" />
          </HeaderTh>

          <HeaderTh @click="updateSorting('created_at')">
            {{ __("CREATED_DATE") }}
            <SortingArrows :params="params" sort="created_at" />
          </HeaderTh>

          <HeaderTh v-if="faqCategoryEdit || faqCategoryDelete">
            {{ __("ACTION") }}
          </HeaderTh>
        </TableHeader>

        <tbody v-if="faqCategories.data.length">
          <Tr v-for="faqCategory in faqCategories.data" :key="faqCategory.id">
            <BodyTh>
              {{ faqCategory.id }}
            </BodyTh>

            <Td>
              {{ faqCategory.name }}
            </Td>

            <Td>
              {{ faqCategory.created_at }}
            </Td>

            <Td
              v-if="faqCategoryEdit || faqCategoryDelete"
              class="flex items-center"
            >
              <!-- Edit Button -->
              <div v-if="faqCategoryEdit">
                <EditButton
                  href="admin.faq-categories.categories.edit"
                  :slug="faqCategory.slug"
                />
              </div>

              <!-- Delete Button -->
              <div v-if="faqCategoryDelete">
                <DeleteButton @click="handleDeleteFaqCategory(faqCategory)" />
              </div>
            </Td>
          </Tr>
        </tbody>
      </TableContainer>
      <!-- Blog Categories Table End -->

      <!-- No Data Row -->
      <NotAvaliableData v-if="!faqCategories.data.length" />

      <!-- Pagination -->
      <div v-if="faqCategories.data.length" class="mt-6">
        <p class="text-center text-sm text-gray-600 mb-3 font-bold">
          Showing {{ faqCategories.from }} - {{ faqCategories.to }} of
          {{ faqCategories.total }}
        </p>
        <Pagination :links="faqCategories.links" />
      </div>
    </div>
  </AdminDashboardLayout>
</template>

