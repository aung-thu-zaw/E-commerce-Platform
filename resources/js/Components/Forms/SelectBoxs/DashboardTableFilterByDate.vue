<script setup>
import InputLabel from "@/Components/Forms/Fields/InputLabel.vue";
import Datepicker from "vue3-datepicker";
import { useFormatFunctions } from "@/Composables/useFormatFunctions";
import { router, usePage } from "@inertiajs/vue3";
import { watch, ref } from "vue";

const props = defineProps({
  to: {
    type: String,
    required: true,
  },
});

const isFilterBoxOpened = ref(false);

const createdFrom = ref(
  usePage().props.ziggy.query.created_from
    ? new Date(usePage().props.ziggy.query.created_from)
    : ""
);
const createdUntil = ref(
  usePage().props.ziggy.query.created_until
    ? new Date(usePage().props.ziggy.query.created_until)
    : ""
);

const deletedFrom = ref(
  usePage().props.ziggy.query.deleted_from
    ? new Date(usePage().props.ziggy.query.deleted_from)
    : ""
);
const deletedUntil = ref(
  usePage().props.ziggy.query.deleted_until
    ? new Date(usePage().props.ziggy.query.deleted_until)
    : ""
);

const { formatDate } = useFormatFunctions();

const filteredByCreatedFrom = () => {
  router.get(
    route(props.to),
    {
      search: usePage().props.ziggy.query?.search,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: usePage().props.ziggy.query?.sort,
      direction: usePage().props.ziggy.query?.direction,
      created_from: formatDate(createdFrom.value),
      created_until: usePage().props.ziggy.query?.created_until,
      deleted_from: usePage().props.ziggy.query?.deleted_from,
      deleted_until: usePage().props.ziggy.query?.deleted_until,
    },
    {
      replace: true,
      preserveState: true,
      onSuccess: () => {
        isFilterBoxOpened.value = true;
      },
    }
  );
};

const filteredByCreatedUntil = () => {
  router.get(
    route(props.to),
    {
      search: usePage().props.ziggy.query?.search,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: usePage().props.ziggy.query?.sort,
      direction: usePage().props.ziggy.query?.direction,
      created_from: usePage().props.ziggy.query?.created_from,
      created_until: formatDate(createdUntil.value),
      deleted_from: usePage().props.ziggy.query?.deleted_from,
      deleted_until: usePage().props.ziggy.query?.deleted_until,
    },
    {
      replace: true,
      preserveState: true,
      onSuccess: () => {
        isFilterBoxOpened.value = true;
      },
    }
  );
};

const filteredByDeletedFrom = () => {
  router.get(
    route(props.to),
    {
      search: usePage().props.ziggy.query?.search,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: usePage().props.ziggy.query?.sort,
      direction: usePage().props.ziggy.query?.direction,
      created_from: usePage().props.ziggy.query?.created_from,
      created_until: usePage().props.ziggy.query?.created_until,
      deleted_from: formatDate(deletedFrom.value),
      deleted_until: usePage().props.ziggy.query?.deleted_until,
    },
    {
      replace: true,
      preserveState: true,
      onSuccess: () => {
        isFilterBoxOpened.value = true;
      },
    }
  );
};

const filteredByDeletedUntil = () => {
  router.get(
    route(props.to),
    {
      search: usePage().props.ziggy.query?.search,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: usePage().props.ziggy.query?.sort,
      direction: usePage().props.ziggy.query?.direction,
      created_from: usePage().props.ziggy.query?.created_from,
      created_until: usePage().props.ziggy.query?.created_until,
      deleted_from: usePage().props.ziggy.query?.deleted_from,
      deleted_until: formatDate(deletedUntil.value),
    },
    {
      replace: true,
      preserveState: true,
      onSuccess: () => {
        isFilterBoxOpened.value = true;
      },
    }
  );
};

const resetFiltered = () => {
  createdFrom.value = "";
  createdUntil.value = "";
  deletedUntil.value = "";
  deletedFrom.value = "";
  router.get(
    route(props.to),
    {
      search: usePage().props.ziggy.query?.search,
      per_page: usePage().props.ziggy.query?.per_page,
      sort: usePage().props.ziggy.query?.sort,
      direction: usePage().props.ziggy.query?.direction,
    },
    {
      replace: true,
      preserveState: true,
      onSuccess: () => (isFilterBoxOpened.value = false),
    }
  );
};

watch(
  () => createdFrom.value,
  () => {
    if (createdFrom.value === "") {
      resetFiltered();
    } else {
      filteredByCreatedFrom();
    }
  }
);

watch(
  () => createdUntil.value,
  () => {
    if (createdUntil.value === "") {
      resetFiltered();
    } else {
      filteredByCreatedUntil();
    }
  }
);

watch(
  () => deletedFrom.value,
  () => {
    if (deletedFrom.value === "") {
      resetFiltered();
    } else {
      filteredByDeletedFrom();
    }
  }
);

watch(
  () => deletedUntil.value,
  () => {
    if (deletedUntil.value === "") {
      resetFiltered();
    } else {
      filteredByDeletedUntil();
    }
  }
);
</script>


<template>
  <button
    @click="isFilterBoxOpened = !isFilterBoxOpened"
    class="relative text-sm border px-5 py-3 rounded-md ml-5 border-slate-300 hover:bg-gray-50 text-gray-600 hover:text-gray-700 focus:ring-2 focus:ring-blue-300 focus:border-blue-400"
  >
    <span class="">
      <i class="fa-solid fa-filter"></i>
    </span>
    <!-- <span
      v-show="true"
      class="absolute top-1 right-2 font-bold text-[.5rem] text-white bg-red-500 w-3.5 h-3.5 rounded-full flex items-center justify-center"
    >
      5
    </span> -->
  </button>

  <div
    v-if="isFilterBoxOpened"
    class="w-[390px] border border-gray-300 shadow-lg absolute bg-white top-[19rem] md:top-[21rem] right-4 md:right-[3.8rem] z-30 px-5 py-4 rounded-md"
  >
    <div class="flex items-center justify-between mb-5">
      <h4 class="font-bold text-slate-600 text-md">{{ __("Filters") }}</h4>
      <span
        @click="isFilterBoxOpened = false"
        class="text-lg text-gray-500 hover:text-red-600 cursor-pointer"
      >
        <i class="fa-solid fa-circle-xmark"></i>
      </span>
    </div>

    <div class="w-full mb-5">
      <div>
        <InputLabel label="Created From" />

        <Datepicker
          id="created-from"
          class="block w-full p-4 font-semibold text-sm text-gray-800 border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all rounded-md"
          v-model="createdFrom"
          :placeholder="__('Select Date')"
        />
      </div>
    </div>
    <div class="w-full mb-5">
      <div>
        <InputLabel label="Created Until" />

        <Datepicker
          id="created-until"
          class="block w-full p-4 font-semibold text-sm text-gray-800 border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all rounded-md"
          v-model="createdUntil"
          :placeholder="__('Select Date')"
        />
      </div>
    </div>

    <div v-show="$page.component.endsWith('Trash')">
      <div class="w-full mb-5">
        <div>
          <InputLabel label="Deleted From" />

          <Datepicker
            id="deleted-from"
            class="block w-full p-4 font-semibold text-sm text-gray-800 border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all rounded-md"
            v-model="deletedFrom"
            :placeholder="__('Select Date')"
          />
        </div>
      </div>
      <div class="w-full mb-5">
        <div>
          <InputLabel label="Deleted Until" />

          <Datepicker
            id="deleted-until"
            class="block w-full p-4 font-semibold text-sm text-gray-800 border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all rounded-md"
            v-model="deletedUntil"
            :placeholder="__('Select Date')"
          />
        </div>
      </div>
    </div>

    <div class="w-full flex items-center">
      <button
        @click="resetFiltered"
        class="text-xs font-semibold px-3 ml-auto py-2 text-white bg-red-600 rounded-[4px] hover:bg-red-700 transition-all"
      >
        {{ __("Reset Filters") }}
      </button>
    </div>
  </div>
</template>
