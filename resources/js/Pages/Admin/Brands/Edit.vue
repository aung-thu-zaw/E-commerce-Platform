<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Breadcrumb from "@/Components/Breadcrumbs/Breadcrumb.vue";
import BreadcrumbItem from "@/Components/Breadcrumbs/BreadcrumbItem.vue";
import PreviewImage from "@/Components/Forms/Fields/PreviewImage.vue";
import InputLabel from "@/Components/Forms/Fields/InputLabel.vue";
import InputError from "@/Components/Forms/Fields/InputError.vue";
import InputField from "@/Components/Forms/Fields/InputField.vue";
import TextAreaField from "@/Components/Forms/Fields/TextAreaField.vue";
import SelectBox from "@/Components/Forms/Fields/SelectBox.vue";
import FileInput from "@/Components/Forms/Fields/FileInput.vue";
import FormButton from "@/Components/Buttons/FormButton.vue";
import InertiaLinkButton from "@/Components/Buttons/InertiaLinkButton.vue";
import { useImagePreview } from "@/Composables/useImagePreview";
import { useResourceActions } from "@/Composables/useResourceActions";
import { Head } from "@inertiajs/vue3";
import { useQueryStringParams } from "@/Composables/useQueryStringParams";

const props = defineProps({
  brand: Object,
  categories: Object,
});

const brandList = "admin.brands.index";

const { queryStringParams } = useQueryStringParams();

const { previewImage, setImagePreview } = useImagePreview(props.brand?.image);

const handleChangeImage = (file) => {
  setImagePreview(file);
  form.image = file;
};

const { form, processing, errors, editAction } = useResourceActions({
  name: props.brand?.name,
  description: props.brand?.description,
  category_id: props.brand?.category_id,
  image: props.brand?.image,
});
</script>

<template>
  <AdminDashboardLayout>
    <Head :title="__('Edit :label', { label: __('Brand') })" />
    <div class="min-h-screen py-10 font-poppins">
      <div
        class="flex flex-col items-start md:flex-row md:items-center md:justify-between mb-4 md:mb-8"
      >
        <Breadcrumb :to="brandList" icon="fa-award" label="Brands">
          <BreadcrumbItem :label="brand.name" />
          <BreadcrumbItem label="Edit" />
        </Breadcrumb>

        <div class="w-full flex items-center justify-end">
          <InertiaLinkButton :to="brandList" :data="queryStringParams">
            <i class="fa-solid fa-left-long"></i>
            {{ __("Go Back") }}
          </InertiaLinkButton>
        </div>
      </div>

      <!-- Form Start -->
      <div class="border p-10 bg-white rounded-md">
        <form
          @submit.prevent="editAction('Brand', 'admin.brands.update', brand)"
          class="space-y-4 md:space-y-6"
        >
          <PreviewImage :src="previewImage" />

          <div>
            <InputLabel :label="__('Brand Name')" required />

            <InputField
              type="text"
              name="brand-name"
              v-model="form.name"
              :placeholder="__('Enter Brand Name')"
              autofocus
              required
            />

            <InputError :message="errors?.name" />
          </div>

          <div>
            <InputLabel :label="__('Brand Description')" required />

            <TextAreaField
              name="brand-description"
              v-model="form.description"
              :placeholder="__('Enter Brand Description')"
              required
            />

            <InputError :message="errors?.description" />
          </div>

          <div>
            <InputLabel :label="__('Category')" />

            <SelectBox
              name="brand-category"
              :options="categories"
              v-model="form.category_id"
              :selected="brand.category_id"
              :placeholder="__('Select Options')"
            />

            <InputError :message="errors?.category_id" />
          </div>

          <div>
            <InputLabel :label="__('Brand Image')" />

            <FileInput
              name="brand-image"
              v-model="form.image"
              text="PNG, JPG or JPEG ( Max File Size : 1.5 MB )"
              @update:modelValue="handleChangeImage"
            />

            <InputError :message="errors?.image" />
          </div>

          <InputError :message="errors?.captcha_token" />

          <FormButton type="submit" :processing="processing">
            {{ __("Save Changes") }}
          </FormButton>
        </form>
      </div>
      <!-- Form End -->
    </div>
  </AdminDashboardLayout>
</template>
