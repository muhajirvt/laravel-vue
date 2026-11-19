<template>
  <span class="inline-flex items-center justify-center">
    <img v-if="imageSource" :src="imageSource" :alt="alt" :class="imageClass" />
    <span
      v-else-if="loadFailed"
      role="img"
      :aria-label="`${alt} unavailable`"
      :class="placeholderClass"
    >
      Image unavailable
    </span>
    <span v-else :class="placeholderClass" aria-live="polite">Loading...</span>
  </span>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
  src: { type: String, required: true },
  alt: { type: String, required: true },
  size: { type: String, default: "small" },
});

const emit = defineEmits(["unauthorized"]);
const imageSource = ref(null);
const loadFailed = ref(false);
const imageClass = computed(() =>
  props.size === "large"
    ? "h-32 w-32 rounded-full border-4 border-gray-100 object-cover shadow"
    : "h-10 w-10 rounded-full object-cover",
);
const placeholderClass = computed(() =>
  props.size === "large"
    ? "flex h-32 w-32 items-center justify-center rounded-full bg-gray-100 text-center text-xs text-gray-500"
    : "flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-center text-[8px] text-gray-500",
);

watch(
  () => props.src,
  (src, _, onCleanup) => {
    const controller = new AbortController();
    let objectUrl;

    imageSource.value = null;
    loadFailed.value = false;
    onCleanup(() => {
      controller.abort();
      if (objectUrl) URL.revokeObjectURL(objectUrl);
    });

    axios
      .get(src, { responseType: "blob", signal: controller.signal })
      .then(({ data }) => {
        if (controller.signal.aborted) return;
        objectUrl = URL.createObjectURL(data);
        imageSource.value = objectUrl;
      })
      .catch((error) => {
        if (controller.signal.aborted) return;
        if (error.response?.status === 401) {
          emit("unauthorized");
        } else {
          console.error("Unable to load the private user image.", error);
        }
        loadFailed.value = true;
      });
  },
  { immediate: true },
);
</script>
