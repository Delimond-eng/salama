export default {
    name: "Pagination",
    template: `
    <div v-if="totalItems > 0"
      class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap justify-between mt-4">
      
      <!-- Navigation -->
      <nav class="w-full sm:w-auto mb-2 sm:mb-0">
        <ul class="flex flex-wrap sm:flex-nowrap items-center gap-1">
          <li>
            <a @click="changePage(1)" :class="navButtonClass(currentPage === 1)"
              class="flex items-center text-slate-800 justify-center px-3 py-2 rounded-md cursor-pointer">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-left" class="lucide lucide-chevrons-left stroke-1.5 h-4 w-4"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>
            </a>
          </li>
          <li>
            <a @click="changePage(currentPage - 1)" :class="navButtonClass(currentPage === 1)"
              class="flex items-center text-slate-800 justify-center px-3 py-2 rounded-md cursor-pointer">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-left" class="lucide lucide-chevron-left stroke-1.5 h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>
            </a>
          </li>
          <li v-for="page in visiblePages" :key="page">
            <a @click="changePage(page)" class="px-3 py-2 rounded-md cursor-pointer"
              :class="page === currentPage ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-darkmode-600'">
              {{ page }}
            </a>
          </li>
          <li>
            <a @click="changePage(currentPage + 1)" :class="navButtonClass(currentPage === lastPage)"
              class="flex items-center text-slate-800 justify-center px-3 py-2 rounded-md cursor-pointer">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevron-right" class="lucide lucide-chevron-right stroke-1.5 h-4 w-4"><path d="m9 18 6-6-6-6"></path></svg>
            </a>
          </li>
          <li>
            <a @click="changePage(lastPage)" :class="navButtonClass(currentPage === lastPage)"
              class="flex items-center justify-center px-3 py-2 rounded-md cursor-pointer">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="chevrons-right" class="lucide lucide-chevrons-right stroke-1.5 h-4 w-4"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>
            </a>
          </li>
        </ul>
      </nav>

      <!-- Items per page -->
      <div></div>
    </div>
  `,
    props: {
        currentPage: {
            type: Number,
            required: true,
        },
        lastPage: {
            type: Number,
            required: true,
        },
        totalItems: {
            type: Number,
            required: true,
        },
        perPage: {
            type: Number,
            default: 10,
        },
        maxVisiblePages: {
            type: Number,
            default: 5,
        },
    },
    data() {
        return {
            perPageLocal: this.perPage,
        };
    },
    computed: {
        visiblePages() {
            const pages = [];
            const half = Math.floor(this.maxVisiblePages / 2);
            let start = Math.max(1, this.currentPage - half);
            let end = start + this.maxVisiblePages - 1;

            if (end > this.lastPage) {
                end = this.lastPage;
                start = Math.max(1, end - this.maxVisiblePages + 1);
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
    },
    methods: {
        changePage(page) {
            if (
                page >= 1 &&
                page <= this.lastPage &&
                page !== this.currentPage
            ) {
                this.$emit("update:currentPage", page);
                this.$emit("page-changed", page);
            }
        },
        onPerPageChange() {
            this.$emit("per-page-changed", this.perPageLocal);
        },
        navButtonClass(disabled) {
            return disabled
                ? "opacity-50 text-slate-100 cursor-not-allowed pointer-events-none"
                : "hover:bg-gray-200 dark:hover:bg-darkmode-500 text-slate-800";
        },
    },
    watch: {
        perPage(newVal) {
            this.perPageLocal = newVal;
        },
    },
};
